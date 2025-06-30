@php
    $pagename = 'article preview';
    $page_title = 'Preview of '.$article->title;
@endphp

@extends('layouts.default')
@section('page-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @if(auth()->user()?->hasRole('contributor'))
        <link href="https://cdn.jsdelivr.net/gh/teddy95/starry@5/dist/starry.min.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @endif
    <style>
        .article-info{
            gap: 4%;
        }

        .article-info {
            font-style: italic;
        }

        .article-content{
            white-space: pre-wrap;
        }

        .article-tags {
            gap: 0.5%;
        }

        .article-tags {
            font-style: normal;
        }

        .article-tags .tag {
            background: gray;
            padding: 2px 10px;
            border-radius: 3px;
            font-style: normal;
            font-weight: 500;
            font-size: 0.8rem;
            color: white;
        }

        .article-rating-wrapper span {
            font-weight: 500;
        }

        .row.article-row > * {
            width: 90%;
        }

        .article-reference span {
            font-weight: 500;
        }

        .preview-label {
            letter-spacing: 1.5px;
            font-weight: 600;
        }
    </style>
@endsection
@section('content')
        <div class="preview-label bg-warning text-center py-2">Preview</div>
        <!-- Page content-->
        <div class="container mt-5">
            <div class="row mb-5">
                <!-- Blog entries-->
                <div class="col-lg-8">
                    <div class="row article-row">
                        <h4>{{ $article->title }}</h4>
                        <div class="d-flex article-info">
                            <div>Author:&nbsp; {{ $article->user_fullname }}</div>
                            <div>{{ $article->created_at }}</div>
                            <div>Rating: {{ $article->rating }}/5</div>
                        </div>
                        <div class="article-info mt-2">
                            <div>Category:&nbsp;&nbsp; {{ $article->category_name }}</div>
                        </div>
                        <div class="article-info mt-2">
                            <div class="article-tags d-flex">
                                Tag:&nbsp;&nbsp;
                                @foreach($article->tags as $tag)
                                    <span class="tag">{{ $tag['name'] }}</span>
                                @endforeach
                            </div>
                        </div>
                        <img class="my-3 article-image" src="{{ asset('/uploaded-image/article/'.$article->image) }}" alt="article image">
                        <div class="article-content mt-2">{{ $article->content }}</div>
                        <div class="article-reference mt-3">
                            <span>References:</span>
                            <ul>
                                @foreach($article->references as $reference)
                                    <li><a href="{{ $reference['link'] }}">{{ $reference['link'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <div class="card mb-4">
                        <div class="card-header">Search</div>
                        <div class="card-body">
                            <div class="search-container position-relative">
                                <div class="input-group">
                                    <input class="form-control" id="searchInput" type="text" placeholder="Cari Artikel..." aria-label="Search articles..." aria-describedby="button-search" autocomplete="off" />
                                    <button class="btn btn-primary" id="button_search" type="button">Cari!</button>
                                </div>
                                <div id="searchSuggestions" class="position-absolute w-100 bg-white border rounded-bottom shadow-sm" style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto; border-radius: 0;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Categories widget-->
                    @include('components.category_widget')
                    <!-- About Widget-->
                    <div class="card mb-4">
                        <div class="card-header">Tentang WikiToraja</div>
                        <div class="card-body">WikiToraja adalah platform kolaboratif yang didedikasikan untuk melestarikan dan berbagi warisan budaya Toraja yang kaya. Bergabunglah dengan kami dalam mendokumentasikan dan menjelajahi budaya unik ini.</div>
                    </div>
                </div>
            </div>
        </div>
@endsection


