@php
    $pagename = 'manage article';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('page-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .min-h-80vh {
            min-height: 80vh !important;
        }

        .action-button-wrapper {
            display: flex;
            gap: 5px;
        }

        /* make table font smaller */
        div#article_wrapper {
            font-size: 0.95em;
        }
    </style>
@endsection
@section('content')
    <div class="container py-5 min-h-80vh">
        <table id="article" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Isi</th>
                    <th>Pratinjau</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection
@section('page-js')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready( function () {
            article_table = $('table#article').DataTable({
                serverMethod: 'GET',
                ajax: {
                    url: "{{ route('article.index.json') }}",
                },
                columns: [
                    { data: null },
                ], 
                columnDefs: [
                    {
                        className: "dt-center",
                        targets: 5
                    },
                    { 
                        targets: 1,
                        data: "title",
                    },
                    { 
                        targets: 2,
                        data: "category",
                    },
                    { 
                        targets: 3,
                        data: "author",
                    },
                    { 
                        targets: 4,
                        data: "content",
                    },
                    { 
                        targets: 5,
                        render: function(){
                            var preview_btn = `
                                <button type="button" class="btn btn-sm btn-primary preview-btn">
                                    <i class="far fa-eye"></i>
                                </button>
                            `;

                            return preview_btn;
                        }
                    },
                    { 
                        targets: 6,
                        data: "status",
                        render: function(data){
                            if(data ===  null){
                                return '<span class="badge rounded-pill bg-warning text-dark">Pending</span>';
                            }else if(data ===  1){
                                return '<span class="badge rounded-pill bg-success">Approved</span>';
                            }else if(data ===  0){
                                return '<span class="badge rounded-pill bg-danger">Rejected</span>';
                            }
                        }
                    },
                    {
                        targets: 7,
                        data: "status",
                        render: function (data, type, row, meta) {
                            var reject_btn = `
                                <button type="button" class="btn btn-danger reject btn-sm edit-status-btn">
                                    Reject
                                </button>
                            `;
                            var approve_btn = `
                                <button type="button" class="btn btn-success approve btn-sm edit-status-btn">
                                    Approve
                                </button>
                            `;
                            
                            if(data === 1){
                                return '<div class="action-button-wrapper">' + reject_btn + '</div>';
                            }else if(data === 0){
                                return '<div class="action-button-wrapper">' + approve_btn + '</div>';
                            }else if(data ===  null){
                                return '<div class="action-button-wrapper">' +  approve_btn + reject_btn + '</div>';
                            }
                        }
                    },
                ],
                fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull +1);
                }
            });

            $('table#article tbody').on('click', '.edit-status-btn', function(){
                button = $(this);
                var id = article_table.row($(this).parents('tr')).data().id;
                if(button.hasClass('approve')){
                    editArticleStatus(id, true, button)
                }else if(button.hasClass('reject')){
                    editArticleStatus(id, false, button)
                }
            });

            $('table#article tbody').on('click', '.preview-btn', function(){
                var article_slug = article_table.row($(this).parents('tr')).data().slug;
                let url = "{{ route('article_preview.view',['article_slug' => '_article_slug_']) }}";
                url = url.replace("_article_slug_", article_slug);
                window.open(url, '_blank');
            });

            function editArticleStatus(id, status){
                var url = "{{ route('article_status.edit.backend', ['article_id' => 'article_id']) }}"
                url = url.replace('article_id', id)
                data = {
                    _token: '{{ csrf_token() }}',
                    status: (status ? 1 : 0)
                }
                
                $.ajax({
                    type: "PATCH",
                    url: url,
                    data: data,
                    success: success,
                    dataType: "json",
                    beforeSend: function(){
                        $(button).parents('.action-button-wrapper').css('display', 'none')
                        $(button).parents('td').append('<span>Updating..</span>');
                    },
                    complete: function(){
                        article_table.ajax.reload(null, false);
                    }
                }).fail(function(){
                    fireToast("Failed updating data!", 'danger');
                    showButton();
                });

                function success(json){
                    if(json.success === true){
                        article_table.ajax.reload(null, false);
                        fireToast("Article status updated sucessfully", 'success');
                    }else if(json.success == false || typeof json.success === 'undefined'){
                        fireToast("Failed updating data!\n"+json.message, 'danger');
                        showButton();
                    }
                }

                function showButton(){
                    $(button).parents('td').find('span').remove();
                    $(button).parents('.action-button-wrapper').css('display', 'flex');
                }
            }

        } );

        @include('components.toast')
    </script>
@endsection