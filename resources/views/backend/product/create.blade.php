

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
                            <li class="breadcrumb-item active" aria-current="page">Add Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h4 class="text-success text-center">{{Session::get('message')}}</h4>
                    <div class="card shadow">
                        <div class="card-header bg-secondary text-white">Add Product</div>
                        <div class="card-body">
                            <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <label for="" class="col-md-3">Product Name</label>
                                    <div class="col-md-9">
                                        <select name="category_id" class="form-control">
                                            <option value="" selected disabled>--Select--</option>
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-3">Product Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-3">Price</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="price">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-3">Quantity</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="quantity">
                                    </div>
                                </div>

                                <div class="row mb-3">

                                    <div class="col-md-12">
                                        <input type="submit" class="btn btn-success" value="Add New Product"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

