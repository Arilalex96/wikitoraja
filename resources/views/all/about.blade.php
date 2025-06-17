@php
    $pagename = 'about';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('content')
    <div id="react-root" data-page="AboutPage"></div>
@endsection

@viteReactRefresh
@vite(['resources/js/app.jsx'])
