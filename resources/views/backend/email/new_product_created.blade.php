

@extends('backend.master')
@component('mail::message')
    # New Product Created

    Hello,

    A new product has been created:

    - **Name:** {{ $product->name }}
    - **Price:** {{ $product->price }}
    - **Quantity:** {{ $product->quantity }}

    Thank you

@endcomponent


