@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')

<div class="page-content">
<!-- 404 Error Page -->
    <div class="misc-wrapper">
        <h2 class="mb-2 mx-2">Oops! Page not found.</h2>
        <p class="mb-4 mx-2">
        @if(isset($message))
            <div class="alert alert-danger mx-2">{{ $message }}</div>
        @endif
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        <div class="mt-4">
            <img
                src="../assets/img/illustrations/girl-doing-yoga-light.png"
                alt="girl-doing-yoga-light"
                width="500"
                class="img-fluid"
                data-app-dark-img="illustrations/girl-doing-yoga-dark.png"
                data-app-light-img="illustrations/girl-doing-yoga-light.png"
            />
        </div>
</div>
<!-- /404 Error Page -->

</div>
@endsection
