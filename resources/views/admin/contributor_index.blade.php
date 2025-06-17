@php
    $pagename = 'manage contributor';
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

        /* make table font smaller */
        div#article_wrapper {
            font-size: 0.95em;
        }
    </style>
@endsection
@section('content')
    <div class="container py-5 min-h-80vh">
        <table id="contributor" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aktivasi</th>
                    <th>Dibuat Pada</th>
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
            contributor_table = $('table#contributor').DataTable({
                serverMethod: 'GET',
                ajax: {
                    url: "{{ route('contributor.index.json') }}",
                },
                columns: [
                    { data: null },
                ], 
                columnDefs: [
                    { 
                        targets: 1,
                        data: "name",
                    },
                    { 
                        targets: 2,
                        data: "email",
                    },
                    { 
                        targets: 3,
                        data: "active",
                         render: function (data, type, row, meta) {
                            if(data === true){
                                return '<span class="badge rounded-pill bg-success">Active</span>';
                            }else{
                                return '<span class="badge rounded-pill bg-danger">Not Active</span>';
                            }
                        }
                    },
                    { 
                        targets: 4,
                        data: "created_at",
                    },
                    {
                        targets: 5,
                        data: "active",
                        render: function (data, type, row, meta) {
                            var elements = '';
                            if(data === true){
                                elements = '<button type="button" class="btn btn-danger deactivate btn-sm edit-status-btn">Deactivate</button>';
                            }else if(data === false){
                                elements = '<button type="button" class="btn btn-success activate btn-sm edit-status-btn">Activate</button>';
                            }

                            return elements;
                        }
                    },
                ],
                fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull +1);
                }
            });

            $('table#contributor tbody').on('click', '.edit-status-btn', function(){
                button = $(this);
                var id = contributor_table.row($(this).parents('tr')).data().id;
                
                var active = null;
                if(button.hasClass('deactivate')){
                    active = false;
                }else if(button.hasClass('activate')){
                    active = true;
                }

                editContributorStatus(id, active, button);
            });

            function editContributorStatus(id, active, this_elem){
                var url = "{{ route('contributor.edit_activation.backend', ['user_id' => 'user_id']) }}"
                url = url.replace('user_id', id)
                data = {
                    _token: '{{ csrf_token() }}',
                    active: (active ? 1:0)
                }
                
                $.ajax({
                    type: "PATCH",
                    url: url,
                    data: data,
                    success: success,
                    dataType: "json",
                    beforeSend: function(){
                        if(active === false){
                            button.prop('disabled', true).text('Deactivating..');
                        }else if(active === true){
                            button.prop('disabled', true).text('Activating..');
                        }
                    },
                    complete: function(){
                        contributor_table.ajax.reload(null, false);
                    }
                }).fail(function(){
                    fireToast('Failed updating data!', 'danger');
                });

                function success(json){
                    if(json.success == false || typeof json.success === 'undefined'){
                        fireToast("Failed updating data!\n"+json.message, 'danger');
                    }
                }
            }
        } );

    </script>
@endsection