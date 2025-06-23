@php
    $pagename = 'contact';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('content')
    <div id="react-root" data-page="ContactPage"></div>
@endsection

@viteReactRefresh
@vite(['resources/js/app.jsx'])
