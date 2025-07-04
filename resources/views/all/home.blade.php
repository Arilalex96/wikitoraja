@php
    $pagename = 'home';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('page-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        #searchSuggestions {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 9999;
            width: 100%;
            background-color: #1e1e1e;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #444;
            border-radius: 0 0 6px 6px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #searchSuggestions::-webkit-scrollbar {
            display: none;
        }

        .suggestion-item {
            cursor: pointer;
            border-bottom: 1px solid #333;
        }

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
            background-color:rgb(107, 107, 107) !important;
        }

        .wikitoraja-name {
            color: #8B0000;
            font-weight: bold;
        }

        header.py-5.bg-light.border-bottom.mb-4 {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background-image: url('/images/Toraja.png');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;
            color: white;
            height: 65vh;
        }

        header.py-5.bg-light.border-bottom.mb-4::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); 
            z-index: 0;
        }

        header.py-5.bg-light.border-bottom.mb-4 .container {
            position: relative;
            z-index: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            header.py-5.bg-light.border-bottom.mb-4 {
                background-position: center top; 
                background-size: cover; 
                padding: 3rem 1rem; 
                height: 50vh; 
            }
        }
        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            .card.article {
                width: 100% !important;
                margin-bottom: 1rem;
            }
            .card-image-wrapper {
                height: 30vh;
            }
            .card-title {
                font-size: 1.25rem;
            }
            .card-text {
                font-size: 0.9rem;
            }
            .btn-primary {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
            }
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
                <!-- Search input moved here -->
                <div class="mt-4 position-relative" style="max-width: 600px; margin: 0 auto;">
                    <div class="input-group justify-content-center" style="max-width: 600px; margin: 0 auto;">
                        <input class="form-control" id="searchInput" type="text"
                            placeholder="Cari artikel..."
                            aria-label="Cari artikel..." aria-describedby="button-search"
                            autocomplete="off" />
                        <button class="btn btn-primary" id="button_search" type="button">Cari</button>
                    </div>
                    <div id="searchSuggestions"
                        class="bg-white border shadow-sm"
                        style="display: none; position: absolute; top: 100%; left: 0; z-index: 9999; width: 100%; background-color: rgb(82, 81, 81) !important;">
                    </div>
                </div>
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
                <!-- Removed search widget from sidebar -->
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
                    url: "{{ route('search.suggestions.with_images') }}",
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

                        $.each(data, function(index, suggestion) {
                            const $item = $('<div></div>')
                                .addClass('px-3 py-2 suggestion-item d-flex align-items-center')
                                .css({
                                    cursor: 'pointer'
                                })
                                .attr('data-index', index)
                                .on('click', function() {
                                    // Redirect to the article page when clicking on suggestion
                                    // Use slug from API response instead of slugified title
                                    window.location.href = "/articles/" + suggestion.slug;
                                });

                            if (suggestion.image_url) {
                                const $img = $('<img>')
                                    .attr('src', suggestion.image_url)
                                    .attr('alt', suggestion.title)
                                    .css({
                                        width: '40px',
                                        height: '40px',
                                        objectFit: 'cover',
                                        marginRight: '10px',
                                        borderRadius: '4px',
                                    });
                                $item.append($img);
                            }

                            const $text = $('<span></span>').text(suggestion.title);
                            $item.append($text);

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
