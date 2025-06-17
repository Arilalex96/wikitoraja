@php
    $pagename = 'home';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('page-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .min-h-100vh {
            min-height: 100vh !important;
        }

        .article-wrapper {
            gap: 5%;
        }

        .article-wrapper>div.result-not-found {
            margin: 0 auto;
        }

        .card.article {
            width: 45%;
        }

        .card-image-wrapper {
            height: 20vh;
            overflow: hidden;
        }

        .suggestion-item:hover,
        .suggestion-item.bg-light {
            background-color: #eaeaea !important;
        }

        .wikitoraja-name {
            color: #8B0000;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <!-- Page header with logo and tagline-->
    <header class="py-5 bg-light border-bottom mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder">Selamat datang di <span class="wikitoraja-name">{{ config('app.name') }}</span></h1>
                <p class="lead mb-0">Jelajahi dan lestarikan warisan budaya Toraja yang kaya</p>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container min-h-100vh">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8 d-flex flex-column">
                @if ($search)
                    @include('components.article_search_result')
                @elseif($category)
                    @include('components.article_category')
                @else
                    @include('components.article_index')
                @endif
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Cari</div>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="position-relative flex-grow-1" style="min-width: 0;">
                                <input class="form-control" id="searchInput" type="text"
                                    placeholder="Cari artikel..."
                                    aria-label="Cari artikel..." aria-describedby="button-search"
                                    autocomplete="off" />
                                <div id="searchSuggestions"
                                    class="position-absolute bg-white border rounded-bottom shadow-sm"
                                    style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto; width: 100%; border-radius: 0;">
                                </div>
                            </div>
                            <button class="btn btn-primary" id="button_search" type="button">Cari</button>
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
@section('page-js')
    <script>
        $(document).ready(function() {
            let selectedIndex = -1;
            let suggestions = [];

            // search suggestions
            $('#searchInput').on('keyup', function(e) {
                const query = $(this).val().trim();

                if ([38, 40, 13].includes(e.which)) return;

                if (query.length === 0) {
                    $('#searchSuggestions').hide();
                    return;
                }

                $.ajax({
                    url: "{{ route('search.suggestions') }}",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        const $suggestionsBox = $('#searchSuggestions');
                        $suggestionsBox.empty();
                        suggestions = data;
                        selectedIndex = -1;

                        if (data.length === 0) {
                            $suggestionsBox.hide();
                            return;
                        }

                        $.each(data, function(index, title) {
                            const $item = $('<div></div>')
                                .addClass('px-3 py-2 suggestion-item')
                                .css({
                                    cursor: 'pointer'
                                })
                                .text(title)
                                .attr('data-index', index)
                                .on('click', function() {
                                    $('#searchInput').val(title);
                                    $suggestionsBox.hide();
                                });

                            $suggestionsBox.append($item);
                        });

                        $suggestionsBox.show();
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $('#searchSuggestions').hide();
                    }
                });
            });

            // keyboard navigation in sugestion list
            $('#searchInput').on('keydown', function(e) {
                const $items = $('#searchSuggestions .suggestion-item');

                if ($items.length === 0) return;

                if (e.which === 40) { // ↓ down
                    e.preventDefault();
                    if (selectedIndex < $items.length - 1) {
                        selectedIndex++;
                    }
                } else if (e.which === 38) { // ↑ up
                    e.preventDefault();
                    if (selectedIndex > 0) {
                        selectedIndex--;
                    }
                } else if (e.which === 13) { // Enter
                    e.preventDefault();
                    if (selectedIndex >= 0 && selectedIndex < suggestions.length) {
                        $('#searchInput').val(suggestions[selectedIndex]);
                        $('#searchSuggestions').hide();
                    }
                } else if (e.which === 27) { // Esc
                    $('#searchSuggestions').hide();
                }

                $items.removeClass('bg-light');
                if (selectedIndex >= 0) {
                    const $selectedItem = $items.eq(selectedIndex);
                    $selectedItem.addClass('bg-light');

                    $selectedItem[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            });

            // click outside to hide suggestion
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#searchInput, #searchSuggestions').length) {
                    $('#searchSuggestions').hide();
                }
            });

            $('#button_search').click(function() {
                var search = $('#searchInput').val();
                if (search === '') {
                    return false;
                }

                search = encodeURIComponent(search);

                var url = "{{ route('home', ['search' => '_search_', 'page' => 1, 'sort' => 'desc']) }}";

                url = url.replace('_search_', search);
                location.href = url;
            });

            $('#searchInput').keypress(function(e) {
                var this_elem = $(this);
                if (event.which === 13) {
                    if (this_elem.val() === '') {
                        return false;
                    }

                    $('#button_search').trigger('click');
                }
            });
        });
    </script>
@endsection
