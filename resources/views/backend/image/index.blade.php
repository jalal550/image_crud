
@extends('backend.master')
@section('title')
all images
@endsection

@section('body')


<section class="py-5">
{{--        <div class="container">--}}
{{--            <div class="row">--}}

{{--                <div class="row">--}}
{{--                    <div class=col-md-12 >--}}
{{--                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">--}}
{{--                            <ol class="breadcrumb">--}}
{{--                                <li class="breadcrumb-item"><a href="#">Product Management</a></li>--}}
{{--                                <li class="breadcrumb-item active" aria-current="page">All Products</li>--}}
{{--                            </ol>--}}
{{--                        </nav>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <h6>Image Upload with AJAX</h6>--}}
{{--                    <form id="imageUploadForm" enctype="multipart/form-data">--}}
{{--                        <div class="dropzone" id="imageDropzone"></div>--}}
{{--                        <button class="btn btn-success mt-2" type="submit">Upload</button>--}}
{{--                    </form>--}}
{{--                    <h6>Image Show</h6>--}}
{{--                    <div id="images"></div>--}}



{{--                    <div>--}}
{{--                </div>--}}
{{--                <div class="col-md-12 mx-auto">--}}
{{--                    <div class="card-body">--}}

{{--                        @if(session()->has('success'))--}}
{{--                            <div class="alert alert-success">--}}
{{--                                {{ session('success') }}--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <table class="table" id="productTable">--}}
{{--                            <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>Serial</th>--}}

{{--                                    <th>Image</th>--}}
{{--                                    <th>Uploaded On</th>--}}
{{--                                    <th style="width: 60px;">Action</th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}




{{--                            </tbody>--}}
{{--                        </table>--}}

{{--                      <div>--}}
{{--                      </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


    <div class="container">
        <h6>Image Upload with AJAX</h6>

        <!-- Dropzone Form for Image Upload -->
        <form id="imageUploadForm">
            @csrf
            <div class="dropzone" id="imageDropzone"></div>
            <button type="submit" class="btn btn-primary mt-3">Upload Images</button>
        </form>

        <div>
            <h6>Uploaded Images</h6>
            <table id="imagesTable" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Uploaded At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </section>
<!-- Edit Image Modal -->
<div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel">Edit Image</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editImageForm">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <input type="hidden" id="editImageId">
                    <div class="form-group">
                        <label for="editImageFile">Choose New Image:</label>
                        <input type="file" id="editImageFile" name="image" class="form-control-file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection
@section('script')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>

    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#imageDropzone", {
            url: "{{ url('/api/images') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            init: function() {
                var myDropzone = this;
                $('#imageUploadForm').on('submit', function(e) {
                    e.preventDefault();
                    myDropzone.processQueue();
                });
                this.on("successmultiple", function(files, response) {
                    loadImages();
                });
            }
        });

        function loadImages() {
            $('#imagesTable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Important to destroy and reinitialize the table
                ajax: {
                    url: '{{ url("/api/images-get/ajaxIndex") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
            });
        }

        // Delete image function
        function deleteImage(id) {
            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: `/api/images/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.success);
                        loadImages(); // Reload the images after deletion
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }





        function editImage(id) {
            $('#editImageId').val(id);
            $('#editImageModal').modal('show');
        }


        $('#editImageForm').submit(function(e) {
            e.preventDefault();
            let id = $('#editImageId').val();
            let formData = new FormData(this);

            $.ajax({
                url: `/api/images/${id}`,
                method: "POST", // Change method to POST for updating image
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editImageModal').modal('hide');
                    loadImages();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).ready(function() {
            loadImages();
        });
    </script>
    <script>
        {{--if ($('#productTable').length > 0) {--}}
        {{--    $('#productTable').DataTable({--}}
        {{--        'processing': true,--}}
        {{--        'order': [[0, 'desc']],--}}
        {{--        'serverSide': true,--}}
        {{--        'serverMethod': 'post',--}}
        {{--        ajax: {--}}
        {{--            url: "{{ route('images.get') }}"--}}
        {{--        },--}}
        {{--    });--}}
        {{--}--}}


    </script>
    @endsection

