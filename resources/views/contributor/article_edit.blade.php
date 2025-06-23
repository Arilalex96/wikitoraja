@php
    $pagename = 'manage article';
    $page_title = 'edit article';
@endphp

@extends('layouts.default')
@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/use-bootstrap-tag@2.2.2/dist/use-bootstrap-tag.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        img#imageResult {
            max-height: 100px;
        }
    </style>
@endsection
@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('article.index.view') }}">Manage Article</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Article</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Article</h4>
                    </div>
                    <div class="card-body">
                        <form id="editArticleForm" action="{{ route('article.edit.backend', ['article_id'=>$article['id']]) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="editArticleTitle" class="form-label">Title:</label>
                                <input type="text" class="form-control" id="editArticleTitle" name="title" value="{{ $article['title'] }}" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="editArticleCategory" class="form-label">Category:</label>
                                <select class="form-select" id="editArticleCategory" name="category" required>
                                    <option style="display: none;" value="">Select category</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editArticleTags" class="form-label">Tags:</label>
                                <input type="text" name="tags" class="form-control" id="editArticleTags"/>
                            </div>
                            <div class="mb-3">
                                <label for="editArticleContent" class="form-label">Content:</label>
                                <textarea class="form-control" name="content" id="editArticleContent" rows="20" required>{{ $article['content'] }}</textarea>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="editArticleImage" class="form-label">Supporting Images:</label>
                                <div class="image-area my-2" hidden>
                                    <img id="imageResult" src="#" alt="" class="rounded shadow-sm d-block">
                                </div>
                                <input type="file" class="form-control" id="editArticleImage" name="image" accept="image/png, image/jpeg">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">References:</label>
                                <div class="references-container">
                                    <div class="reference-item mb-2 d-flex">
                                        <input type="text" name="references[]" class="form-control" placeholder="Enter reference URL or citation" required>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2 add-reference-btn">+ Add Another Reference</button>
                            </div>
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/use-bootstrap-tag@2.2.2/dist/use-bootstrap-tag.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready(function(){
            fillForm();

            $('#editArticleForm').submit(function(e){
                e.preventDefault();
                var form = $('#editArticleForm');
                var title = form.find('input[name="title"]').val();
                var category = form.find('select[name="category"]').val();
                var content = form.find('textarea[name="content"]').val();
                var image = form.find('input[name="image"]')[0].files[0];
                var references = [];
                form.find('input[name="references[]"').each(function(i, element) {
                    references.push($(element).val());
                });

                if(!isAnyInputDirty(form)){
                    fireToast('Cannot update anything. No changes on data!', 'warning')
                    return false;
                }

                //check required input is filled
                if(title === '' || category === '' || content === '' || references.length == 0){
                    fireToast('Please fill all required form!', 'warning')
                    return false;
                }

                var formData = new FormData();
                var csrf = form.find('input[name="_token"]').val();
                formData.append('_token', csrf);

                //check if input is dirty. Dirty input will be pushed into form data
                if(old_form.title.dirty){
                    formData.append('title', title);
                }

                if(old_form.category.dirty){
                    formData.append('category_id', category);
                }

                if(old_form.tags.dirty){
                    var tags = tags_elmnt.getValue();
                    formData.append('tags', tags);
                }

                if(old_form.content.dirty){
                    formData.append('content', content);
                }

                if(image){
                    formData.append('image', image);
                }

                //references
                if(old_form.references.dirty){
                    formData.append('references', references);
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('article.edit.backend', ['article_id'=> $article['id']]) }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: success,
                    dataType: "json",
                    beforeSend: function(){
                        form.find('button[type="submit"]')
                        .prop('disabled', true).text('Saving..');
                    },
                    complete: function(){
                        form.find('button[type="submit"]')
                        .prop('disabled', false).text('Update');
                    }
                }).fail(function(){
                    fireToast('Failed updating data!', 'danger');
                });

                function success(json){
                    if(json.success === true){
                        fireToast('Update data sucess!', 'success');
                        resetDirtyInputFlags(json.data);
                    }else if(json.success === false || typeof json.success === 'undefined'){
                        fireToast("Failed updating data!\n"+json.message, 'danger');
                    }
                }
                        
            });
        });
        $('.add-reference-btn').click(addReferenceInput);

        function addReferenceInput(){
            var container = $('.references-container');
            var element = `
                <div class="reference-item mb-2 d-flex">
                    <input type="text" name="references[]" class="form-control me-2" placeholder="Enter reference URL or citation" required>
                    <button type="button" class="btn btn-danger btn-sm remove-reference" aria-label="Remove reference">
                        <i class="fas fa-times"></i>×
                    </button>
                </div>
            `;
            
            var new_entry = $(element)
            $('.references-container').append(new_entry);

            $(new_entry).on('click', '.remove-reference', function(){
                new_entry.remove();
            });
        }

        var tags_elmnt = UseBootstrapTag($('#editArticleTags')[0]);

        //preview file upload
        $('#editArticleImage').change(function(){
            var input = $(this)[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imageResult').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $('.image-area').removeAttr('hidden');
            }else{
                $('.image-area').attr('hidden', true);
            }
        });

        function fillForm(){
            @if(!$article['tags'] == '')
                tags_elmnt.addValue('{!! $article['tags'] !!}');
            @endif
            $('#editArticleCategory').val('{{ $article['category_id'] }}')
            //show image
            $('#imageResult').attr('src', '{{ asset('/uploaded-image/article/'.$article['image']) }}');
            $('.image-area').removeAttr('hidden');
            //references
            var references = {!! $article['references'] !!};
            $(references).each(function(i, item){
                if(i == 0){
                    var element = $('.references-container > .reference-item > input[type="text"]').eq(i);
                    element.val(item.link);
                }else{
                    addReferenceInput();
                    var element = $('.references-container > .reference-item > input[type="text"]').eq(i);
                    element.val(item.link);
                }
            });

        }

        //saat klik update, cek mana saja input yang dirty
        var old_form = {
            title: {
                value: "{{ $article['title'] }}",
                dirty: false
            },
            category: {
                value: {{ $article['category_id'] }},
                dirty: false
            },
            tags: {
                value: '{!! $article['tags'] !!}',
                dirty: false
            },
            content: {
                value: $('#editArticleForm').find('textarea[name="content"]').val(),
                dirty: false
            },
            references: {
                value: {!! $article['references'] !!},
                dirty: false
            },
            image: {
                dirty: false
            }
        }


        //check if any input is dirty (changed). Return true if any input dirty
        function isAnyInputDirty(form){
            var is_any_change = false;
            var title = form.find('input[name="title"]').val();
            var category = form.find('select[name="category"]').val();
            var content = form.find('textarea[name="content"]').val();
            var tags = tags_elmnt.getValue();
            var image = form.find('input[name="image"]')[0].files[0];
            var references = [];
            form.find('input[name="references[]"').each(function(i, element) {
                references.push($(element).val());
            })

            if(title !== old_form.title.value){
                console.log('title: true');
                old_form.title.dirty = true;
                is_any_change = true;
            }

            if(parseInt(category) !== old_form.category.value){
                console.log('category: true');
                old_form.category.dirty = true;
                is_any_change = true;
            }

            if(tags !== old_form.tags.value){
                console.log('tags: true');
                old_form.tags.dirty = true;
                is_any_change = true;
            }

            if(content !== old_form.content.value){
                console.log('content: true');
                old_form.content.dirty = true;
                is_any_change = true;
            }

            if(image){
                console.log('image: true');
                old_form.image.dirty = true;
                is_any_change = true;
            }

            //check reference
            var references = [];
            form.find('input[name="references[]"').each(function(i, element) {
                 references.push($(element).val());
            });

            if(references.length !== old_form.references.value.length){
                console.log('reference length: true');
                old_form.references.dirty = true;
                is_any_change = true;
            }

            if(old_form.references.dirty === false){
                $(references).each(function(i, item){
                    if(references[i] !== old_form.references.value[i].link){
                        console.log('reference '+ i +' : true');
                        old_form.references.dirty = true;
                        is_any_change = true;
                        return false;
                    }
                });
            }

            return is_any_change;
        }

        function resetDirtyInputFlags(data){
            var tags = [];
            $.each(data.tags, function(i, element) {
                tags.push(element.name);
            });
            tags = tags.join(',');

            old_form = {
                title: {
                    value: data.title,
                    dirty: false
                },
                category: {
                    value: data.category_id,
                    dirty: false
                },
                tags: {
                    value: tags,
                    dirty: false
                },
                content: {
                    value: data.content,
                    dirty: false
                },
                references: {
                    value: data.references,
                    dirty: false
                },
                image: {
                    dirty: false
                }
            }
        }
        @include('components.toast');
    </script>
@endsection
