

@extends('backend.master')
@section('title')
    create product
@endsection

@section('body')

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class=col-md-12 >
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Product Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <h4 class="text-success text-center">{{Session::get('message')}}</h4>
                    <div class="card shadow">
                        <div class="card-header bg-secondary text-white">Import Product form</div>
                        <div class="card-body">

                            <form action="{{ route('import.product') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mt-3">
                                    <input type="file" class="form-control" name="file" accept=".csv">
                                </div>
                               <div class="text-center">
                                   <button type="submit" class="btn btn-success mt-3 ">Import CSV</button>
                               </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mx-auto">
                    <h4 class="text-success text-center">{{Session::get('message')}}</h4>
                    <div class="card shadow">
                        <div class="card-header bg-secondary text-white">Export Product form</div>
                        <div class="card-body py-3">


                                <div class="text-center mt-3">
                                    <a href="{{ route('export.products') }}" class="btn btn-primary">Export Products</a>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

