@extends('layouts.home')

@section('title', $title ?? 'Home')

@section('content')
    @include('front.collections')
    @include('front.slider')
@endsection
