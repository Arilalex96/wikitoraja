@php
    $pagename = 'manage article';
    $page_title = 'create article';
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
              <li class="breadcrumb-item"><a href="{{ route('article.index.view') }}">Kelola Artikel</a></li>
              <li class="breadcrumb-item active" aria-current="page">Buat Artikel</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Unggah Artikel Baru</h4>
                    </div>
                    <div class="card-body p-5">
                        <form id="createArticleForm" action="{{ route('article.create.backend') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="createArticleTitle" class="form-label">Judul:</label>
                                <input type="text" class="form-control" id="createArticleTitle" name="title" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="createArticleCategory" class="form-label">Kategori:</label>
                                <select class="form-select" id="createArticleCategory" name="category" required>
                                    <option style="display: none;" value="">Pilih kategori</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="createArticleTags" class="form-label">Tag:</label>
                                <input type="text" name="tags" class="form-control" id="createArticleTags"/>
                            </div>
                            <div class="mb-3">
                                <label for="createArticleContent" class="form-label">Isi:</label>
                                <textarea class="form-control" name="content" id="createArticleContent" rows="20" required></textarea>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="createArticleImage" class="form-label">Gambar Pendukung:</label>
                                <div class="image-area my-2" hidden>
                                    <img id="imageResult" src="#" alt="" class="rounded shadow-sm d-block">
                                </div>
                                <input type="file" class="form-control" id="createArticleImage" name="image" accept="image/png, image/jpeg">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Referensi:</label>
                                <div class="references-container">
                                    <div class="reference-item mb-2 d-flex">
                                        <input type="text" name="references[]" class="form-control" placeholder="Masukkan URL referensi atau kutipan" required>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2 add-reference-btn">+ Tambah Referensi Lain</button>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
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
        $('.add-reference-btn').click(function(){
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
        });

        var tags_elmnt = UseBootstrapTag($('#createArticleTags')[0]);

        //preview file upload
        $('#createArticleImage').change(function(){
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

        $('#createArticleForm').submit(function(e){
            e.preventDefault();
            var form = $('#createArticleForm');
            var title = form.find('input[name="title"]').val();
            var category = form.find('select[name="category"]').val();
            var content = form.find('textarea[name="content"]').val();
            var tags = tags_elmnt.getValues();
            var image = form.find('input[name="image"]')[0].files[0];
            var references = [];
            form.find('input[name="references[]"').each(function(i, element) {
                references.push($(element).val());
            })
            var csrf = form.find('input[name="_token"]').val();

            if(title === '' || category === '' || content === '' || image === undefined || references.length == 0){
                fireToast('Failed update new data!', 'warning');
                return false;
            }

            var formData = new FormData();
            formData.append('_token', csrf);
            formData.append('title', title);
            formData.append('category', category);
            formData.append('content', content);
            formData.append('image', image);
            formData.append('references', references);

            if(tags !== ''){
                formData.append('tags', tags);
            }

            $.ajax({
                type: "POST",
                url: "{{ route('article.create.backend') }}",
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
                    .prop('disabled', false).text('Submit');
                }
            }).fail(function(){
                fireToast('Failed adding new article', 'danger');
            });

            function success(json){
                if(json.success === true){
                    fireToast('Article created successfully', 'success');
                    clearFormInput(form);
                }else if(json.success === false || typeof json.success === 'undefined'){
                    fireToast("Failed adding new article!\n" + json.message, 'danger');
                }
            }
                    
        });

        function clearFormInput(form){
            form.find('input[name="title"]').val('');
            form.find('select[name="category"]').val('');
            form.find('textarea[name="content"]').val('');
            tags_elmnt.removeValue(tags_elmnt.getValue());
            form.find('input[name="image"]').val('').change();
            $('.reference-item:not(:first)').remove()
            $('.reference-item').find('input[type="text"]').val('');
        }

        @include('components.toast')
    </script>
@endsection
