

@extends('base')

@section('styles')

    <link rel="stylesheet" href="{{ asset('/css/clients.css') }}">

@endsection

@section('content')

    @parent();
    @yield('body')
@endsection

@section('scripts')

	<script src="{{ asset('/js/clients.js') }}"></script>

@endsection