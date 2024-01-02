
@extends('backend.master')
@section('title')
all products
@endsection

@section('body')


<section class="py-5">
        <div class="container">
            <div class="row">

                <div class="row">
                    <div class=col-md-12 >
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Product Management</a></li>
                                <li class="breadcrumb-item active" aria-current="page">All Products</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-12 mx-auto">
                    <div class="card-body">

                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table" id="productTable">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th style="width: 60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>




                            </tbody>
                        </table>

                      <div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection
@section('script')
    <script>
        if ($('#productTable').length > 0) {
            $('#productTable').DataTable({
                'processing': true,
                'order': [[0, 'desc']],
                'serverSide': true,
                'serverMethod': 'post',
                ajax: {
                    url: "{{ route('products.ajax') }}"
                },
            });
        }

    </script>
    @endsection

