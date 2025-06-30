@php
    $pagename = 'manage article';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('page-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            div#article_wrapper {
                font-size: 0.9em;
            }
            .table-responsive {
                overflow-x: auto; /* Enable horizontal scrolling */
            }
            table#article {
                width: 100%; /* Ensure table takes full width */
            }
            table#article th, table#article td {
                white-space: nowrap; /* Prevent text wrapping */
            }
            .action-button-wrapper {
                flex-direction: row; /* Keep action buttons in a row */
                gap: 0.5rem;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container py-5 min-h-80vh">
        <button type="button" class="btn btn-sm btn-primary mb-2 create-article-btn">+ Tulis Artikel Baru</button>
        <div class="table-responsive"> <!-- Add a responsive wrapper for the table -->
            <table id="article" class="display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Isi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade confirm-delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
              <p>Blank</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-no" data-bs-dismiss="modal">No</button>
              <button type="button" class="btn btn-danger btn-yes">Yes</button>
            </div>
          </div>
        </div>
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
                        targets: 1,
                        data: "title",
                    },
                    { 
                        targets: 2,
                        data: "category",
                    },
                    { 
                        targets: 3,
                        data: "content",
                    },
                    { 
                        targets: 4,
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
                        targets: 5,
                        data: "id",
                        render: function (data, type, row, meta) {
                            let elements = '<div class="action-button-wrapper"><button type="button" class="btn btn-warning btn-sm edit-article-btn">Edit</button><button type="button" class="btn btn-danger btn-sm delete-article-btn">Delete</button></div>';
                            return elements;
                        }
                    },
                ],
                fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull +1);
                }
            });

             $('.create-article-btn').click(function(){
                window.location.href = "{{ route('article.create.view') }}";
            });

            $('table#article tbody').on('click', '.edit-article-btn', function(){
                var id = article_table.row($(this).parents('tr')).data().id;
                var url = "{{ route('article.edit.view', ['article_id' => '__id__']) }}";
                url = url.replace('__id__', id);
                location.href = url;
            });

            var modal = new bootstrap.Modal($('.confirm-delete-modal'), {});

            $('table#article tbody').on('click', '.delete-article-btn', function(){
                var title = article_table.row($(this).parents('tr')).data().title;
                var id = article_table.row($(this).parents('tr')).data().id;
                var content = '<p>Are you sure to delete article with title <i>'+title+'</i>?</p>';
                $('.confirm-delete-modal').find('.modal-body').html(content);
                modal.show();

                $('.confirm-delete-modal').find('.modal-footer button').click(function(e){
                    if($(e.target).hasClass('btn-yes')){
                        deleteArticle(id);
                    }

                    $(this).off('click');
                });
            });

            function deleteArticle(id){
                var url = "{{ route('article.delete.backend', ['article_id'=>'__id__']) }}";
                url = url.replace('__id__', id);
                data = {
                    _token: '{{ csrf_token() }}'
                }
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: data,
                    dataType: "json",
                    success: success,
                    beforeSend: function(){
                        $('.confirm-delete-modal').find('.modal-footer button').prop('hidden', true);
                        var content = '<p>Deleting article. Please wait..</p>';
                        $('.confirm-delete-modal').find('.modal-body').html(content);
                        $('.confirm-delete-modal').find('.modal-title').text('Info')
                    },
                    complete: function(){
                        modal.hide();
                        $('.confirm-delete-modal').find('.modal-footer button').prop('hidden', false);
                        $('.confirm-delete-modal').find('.modal-title').text('Confirmation')
                    }
                }).fail(function(){
                    fireToast('Failed deleting data!', 'danger');
                });

                function success(json){
                    if(json.success === true){
                        article_table.ajax.reload(null, false);
                        fireToast('New data added successfully!', 'success');
                    }else if(json.success === false || typeof json.success === 'undefined'){
                        fireToast("Failed deleting data!\n"+json.message, 'danger');
                    }
                }
            }

            $('.references-container').on('click', 'add-reference-btn', function(){
                var container = $('.references-container');
                var element = `
                    <div class="reference-item mb-2">
                        <input type="text" name="references[]" class="form-control" placeholder="Enter reference URL or citation" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-reference" aria-label="Remove reference">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                var new_entry = $(element)
                $('.references-container').append(new_entry);

                $(newEntry).click('.remove-reference', function(){
                    newEntry.remove();
                });
            });
        } );

        @include('components.toast')
    </script>
@endsection
