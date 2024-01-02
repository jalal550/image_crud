@extends('backend.master')

@section('body')

    <!-- Your Blade view file -->
    @if(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif


@endsection
