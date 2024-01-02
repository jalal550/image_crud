
@extends('backend.master')
@section('title')
   Edit Products
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
                                <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <h4 class="text-success text-center">{{Session::get('message')}}</h4>
                        <div class="card shadow">
                            <div class="card-body">
                                <form action="{{ route('products.update', ['id' => $product->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                    <div class="row mb-3">
                                        <label for="" class="col-md-3">Category</label>
                                        <div class="col-md-9">
                                            <select name="category_id" class="form-control">
                                                <option value="" selected disabled>--Select--</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="" class="col-md-3">Product Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="" class="col-md-3">Price</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="" class="col-md-3">Quantity</label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" name="quantity" value="{{ $product->quantity }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-success" value="Update Product"/>
                                        </div>
                                    </div>
                                </form>

                            </div>
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


