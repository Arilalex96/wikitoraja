@php
    $pagename = 'article single view';
    $page_title = $article->title;
@endphp

@extends('layouts.default')
@section('page-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @if (auth()->user()?->hasRole('contributor'))
        <link href="https://cdn.jsdelivr.net/gh/teddy95/starry@5/dist/starry.min.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @endif
    <style>
        body, html {
            overflow-x: hidden;
        }
        .article-info {
            gap: 4%;
        }

        .article-info {
            font-style: italic;
        }

        .article-content {
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

        .row.article-row>* {
            width: 100%;
        }

        .article-reference span {
            font-weight: 500;
        }

        .comment-item {
            border-radius: 15px;
            border: 2px solid #d6dcea;
        }

        .comment-item .comment-top-bar .info .fullname {
            font-weight: 500;
        }

        .comment-item .comment-top-bar .info .date {
            font-weight: 100;
            font-size: 0.80rem;
        }

        .comment-item .comment-text {
            color: gray;
            white-space: pre-wrap;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .row.article-row>* {
                width: 100%;
            }
            .article-image {
                width: 100%;
                height: auto;
                max-width: 100%;
                margin-left: 0;
                margin-right: 0;
            }
            .article-info {
                flex-direction: column;
                gap: 1rem;
            }
            .article-tags {
                flex-wrap: wrap;
            }
            .comment-item {
                font-size: 0.9rem;
            }
            .container.mt-5 {
                padding-left: 1rem;
                padding-right: 1rem;
                max-width: 100% !important;
            }
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
    </style>
@endsection
@section('content')
    <!-- Page content-->
    <div class="container mt-5">
        <div class="row mb-5">
            <div class="row">
                <!-- Blog entries-->
<div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="row article-row">
                        <h4>{{ $article->title }}</h4>
                        <div class="d-flex article-info">
                        <div>Penulis:&nbsp; {{ $article->user_fullname }}</div>
                        <div>{{ $article->created_at }}</div>
                        <div>Rating: {{ $article->rating }}/5</div>
                    </div>
                    <div class="article-info mt-2">
                        <div>Kategori:&nbsp;&nbsp; <a
                                href="{{ route('home', ['page' => 1, 'sort' => 'desc', 'category' => $article->category_id]) }}">{{ $article->category_name }}</a>
                        </div>
                    </div>
                    <div class="article-info mt-2">
                        <div class="article-tags d-flex">
                            Tag:&nbsp;&nbsp;
                            @foreach ($article->tags as $tag)
                                <span class="tag">{{ $tag['name'] }}</span>
                            @endforeach
                        </div>
                    </div>
                    <img class="my-3 article-image" src="{{ asset('/uploaded-image/article/' . $article->image) }}"
                        alt="gambar artikel">
                    <div class="article-content mt-2">{{ $article->content }}</div>
                    <div class="article-reference mt-3">
                        <span>Referensi:</span>
                        <ul>
                            @foreach ($article->references as $reference)
                                <li><a href="{{ $reference['link'] }}">{{ $reference['link'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <hr class="mt-4 mb-2">
                    @if (auth()->user()?->hasRole('contributor'))
                        <div class="d-flex align-items-center mt-2 article-rating-wrapper">
                            <span>Nilai artikel ini:&nbsp;&nbsp;</span>
                            <div id="article_rating_input"></div>
                        </div>
                    @endif
                    <div class="comment-section mt-5">
                        <h4 class="mb-4">Komentar:</h4>
                        <div class="comment-container d-flex flex-column gap-4">
                            @if (auth()->user()?->hasRole('contributor'))
                                <div class="card comment-item p-4 create-comment">
                                    <textarea class="comment-text-input form-control" rows="5" required></textarea>
                                    <div class="d-flex justify-content-end gap-2 mt-3">
                                        <button type="button"
                                            class="btn btn-success btn-md post-create-comment px-4">Kirim</button>
                                    </div>
                                </div>
                            @endif
                            @foreach ($article->comments as $comment)
                                <div class="card comment-item p-4" data-id="{{ $comment['id'] }}">
                                    <div class="comment-top-bar d-flex justify-content-between align-items-center mb-2">
                                        <div class="info d-flex align-items-center gap-2">
                                            <div class="fullname">{{ $comment['user_fullname'] }}</div>
                                            <div class="date">{{ $comment['created_at'] }}</div>
                                        </div>
                                        @if ($comment['is_created_by_user'])
                                            <div class="control-button d-flex gap-2">
                                                <button type="button" class="btn btn-warning btn-sm edit-comment"><i
                                                        class="far fa-edit"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm delete-comment"><i
                                                        class="far fa-trash-alt"></i></button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="comment-text">{{ $comment['text'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Side widgets-->
<div class="col-lg-4 col-md-12 col-sm-12">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Pencarian</div>
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
                    <div class="card-body">WikiToraja adalah platform kolaboratif yang didedikasikan untuk melestarikan dan berbagi
                        warisan budaya Toraja yang kaya. Bergabunglah dengan kami dalam mendokumentasikan dan menjelajahi budaya unik ini.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-css')
    <style>
        /* Additional responsive styles */
        @media (max-width: 768px) {
            .container.mt-5 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .article-content {
                font-size: 1rem;
                line-height: 1.5;
            }
            .article-reference ul {
                padding-left: 1.25rem;
            }
            .comment-section {
                padding-left: 0;
                padding-right: 0;
            }
            .article-image {
                width: 100% !important;
                height: auto !important;
            }
            .article-tags {
                flex-wrap: wrap !important;
            }
            .comment-item {
                font-size: 0.9rem !important;
            }
        }
    </style>
@endsection
@section('page-js')
    @if (auth()->user()?->hasRole('contributor'))
        <script src="https://cdn.jsdelivr.net/gh/teddy95/starry@5/dist/starry.min.js" type="text/javascript"
            language="javascript"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @endif
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

        });

        @if (auth()->user()?->hasRole('contributor'))
            //rating
            var old_rating_value = parseInt({{ $article->current_user_rating }});
            var article_rating = new Starry($('#article_rating_input')[0], {
                beginWith: {{ $article->current_user_rating }} * 20,
            });

            article_rating.on('rate', function(rating) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    rating: rating,
                    article_id: {{ $article->id }},
                };

                var url = "{{ route('article_rating.edit.backend', ['article_slug' => $article->slug]) }}";

                $.ajax({
                    type: "PATCH",
                    url: url,
                    data: data,
                    success: success,
                    dataType: "json",
                    beforeSend: function() {
                        article_rating.update({
                            readOnly: true,
                            beginWith: rating * 20,
                        });
                    },
                    complete: function() {
                        article_rating.update({
                            readOnly: false,
                        });
                    }
                }).fail(function() {
                    fireToast("Failed updating data!", 'danger');
                    revertBackRating();
                });

                function success(json) {
                    if (json.success === true) {
                        fireToast('Rating updated successfully!', 'success');
                        old_rating_value = parseInt(data.rating);
                        article_rating.update({
                            readOnly: false,
                            beginWith: data.rating * 20,
                        });
                    } else if (json.success === false || typeof json.success === 'undefined') {
                        fireToast("Failed updating data!\n" + json.message, 'danger');
                        revertBackRating();
                    }
                }

                function revertBackRating() {
                    article_rating.update({
                        readOnly: false,
                        beginWith: old_rating_value * 20,
                    });
                }
            });

            //comment
            $('.comment-container').on('click', '.edit-comment', function() {
                $(this).parents('.control-button').addClass('d-none');
                var comment_text_div = $(this).parents('.comment-item').find('div.comment-text');
                comment_text_div_value = $(this).parents('.comment-item').find('div.comment-text').text();
                localStorage.setItem('edit_comment', JSON.stringify(comment_text_div_value));
                comment_text_div.remove();
                var comment_text_input = `
                        <textarea class="comment-text-input form-control" rows="5"></textarea>
                        <div class="d-flex justify-content-end gap-2 mt-2">
                            <button type="button" class="btn btn-secondary btn-sm cancel-edit-comment">Cancel</button>
                            <button type="button" class="btn btn-success btn-sm save-edit-comment">Save</button>
                        </div>
                `;
                $(this).parents('.comment-item').append(comment_text_input);
                $(this).parents('.comment-item').find('.comment-text-input').val(JSON.parse(localStorage.getItem(
                    'edit_comment')));
            });

            $('.comment-container').on('click', '.delete-comment', function() {
                var this_elem = $(this);
                var comment_id = this_elem.parents('.comment-item').attr('data-id');
                var url = "{{ route('comment.delete.backend', ['comment_id' => 'comment_id']) }}";
                url = url.replace('comment_id', comment_id)
                data = {
                    _token: '{{ csrf_token() }}'
                }
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: data,
                    dataType: "json",
                    success: success,
                    beforeSend: function() {
                        this_elem.prop('disabled', true);
                        this_elem.siblings('button').prop('disabled', true);
                    },
                    complete: function() {
                        this_elem.prop('disabled', false);
                        this_elem.siblings('button').prop('disabled', false);
                    }
                }).fail(function() {
                    fireToast("Failed deleting data!", 'danger');
                });

                function success(json) {
                    if (json.success === true) {
                        fireToast('Comment deleted sucessfully!', 'success');
                        this_elem.parents('.comment-item').remove();
                    } else if (json.success === false || typeof json.success === 'undefined') {
                        fireToast("Failed deleting data!\n" + json.message, 'danger');
                    }
                }
            });

            $('.comment-container').on('click', '.cancel-edit-comment', function() {
                setCommentItemToShowMode($(this), JSON.parse(localStorage.getItem('edit_comment')));
            });

            $('.comment-item.create-comment').find('button.post-create-comment').on('click', function() {
                var this_elem = $(this);
                var comment_text_input = this_elem.parents('.comment-item').find('textarea.comment-text-input')
                .val();
                if (comment_text_input === '') {
                    return false;
                }

                var data = {
                    _token: "{{ csrf_token() }}",
                    article_id: {{ $article->id }},
                    text: comment_text_input,
                };

                var url = "{{ route('comment.create.backend') }}";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: success,
                    dataType: "json",
                    beforeSend: function() {
                        this_elem.prop('disabled', true).text('Posting..');
                    },
                    complete: function() {
                        this_elem.prop('disabled', false).text('Post');
                    }
                }).fail(function() {
                    fireToast("Failed adding new data!", 'danger');
                });

                function success(json) {
                    if (json.success == true) {
                        fireToast('Comment posted successfully', 'success');
                        $('textarea.comment-text-input').val('');
                        addNewComment(json.data);
                    } else if (json.success == false || typeof json.success === 'undefined') {
                        fireToast("Failed adding new data!\n" + json.message, 'danger');
                    }
                }

                function addNewComment(data) {
                    var new_comment = `
                            <div class="card comment-item p-4" data-id="` + data.id + `">
                                <div class="comment-top-bar d-flex justify-content-between align-items-center mb-2">
                                    <div class="info d-flex align-items-center gap-2">
                                        <div class="fullname">` + data.user_fullname + `</div>
                                        <div class="date">` + data.created_at + `</div>
                                    </div>
                                    <div class="control-button d-flex gap-2">
                                        <button type="button" class="btn btn-warning btn-sm edit-comment"><i class="far fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm delete-comment"><i class="far fa-trash-alt"></i></button>
                                    </div>
                                </div>
                                <div class="comment-text">` + data.text + `</div>
                            </div>
                        `;

                    $('.comment-item.create-comment').after(new_comment);
                }
            });

            $('.comment-container').on('click', 'button.save-edit-comment', function() {
                var this_elem = $(this);
                old_comment_text = JSON.parse(localStorage.getItem('edit_comment'));
                comment_text_input_value = this_elem.parents('.comment-item').find('textarea.comment-text-input')
                    .val();
                if (old_comment_text === comment_text_input_value) {
                    fireToast('No changes on data', 'warning');
                    return false;
                }

                var data = {
                    _token: "{{ csrf_token() }}",
                    article_id: {{ $article->id }},
                    text: comment_text_input_value,
                };

                var comment_id = this_elem.parents('.comment-item').attr('data-id');
                var url = "{{ route('comment.edit.backend', ['comment_id' => 'comment_id']) }}";
                url = url.replace('comment_id', comment_id);

                $.ajax({
                    type: "PATCH",
                    url: url,
                    data: data,
                    success: success,
                    dataType: "json",
                    beforeSend: function() {
                        this_elem.prop('disabled', true).text('Saving..');
                        this_elem.siblings('.cancel-edit-comment').prop('disabled', true);
                    },
                    complete: function() {
                        this_elem.prop('disabled', false).text('Edit');
                        this_elem.siblings('.cancel-edit-comment').prop('disabled', false);
                    }
                }).fail(function() {
                    fireToast("Failed updating data!", 'danger');
                });

                function success(json) {
                    if (json.success === true) {
                        fireToast('Comment updated successfully', 'success');
                        setCommentItemToShowMode(this_elem, json.data.text);
                    } else if (json.success === false || typeof json.success === 'undefined') {
                        fireToast("Failed updating data!\n" + json.message, 'danger');
                    }
                }
            });


            function setCommentItemToShowMode(this_elem, $value) {
                this_elem.parents('.comment-item').find('.control-button').removeClass('d-none');
                var comment_text_input = this_elem.parents('.comment-item').find('textarea.comment-text-input');
                comment_text_input_value = this_elem.parents('.comment-item').find('textarea.comment-text-input').val();
                comment_text_input.remove();
                var comment_text_div = `
                    <div class="comment-text"></div>
                `;
                this_elem.parents('.comment-item').append(comment_text_div);
                this_elem.parents('.comment-item').find('div.comment-text').text($value);
                this_elem.parent().remove();
            }

            @include('components.toast')
        @endif
    </script>
@endsection
