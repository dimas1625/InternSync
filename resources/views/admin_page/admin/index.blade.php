@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Admin</h2>
            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ route('admin.create') }}')" class="btn btn-sm btn-primary">
                    + Tambah Admin
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_admin">
                    <thead>
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataAdmin;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataAdmin = $('#table_admin').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('admin/list') }}",
                    type: "POST",
                    dataType: "json",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_lengkap",
                        className: "text-center"
                    },
                    {
                        data: "email",
                        className: "text-center"
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            var btn = data
                                .replace(
                                    /<a href="([^"]+)" class="btn btn-info btn-sm">Detail<\/a>/g,
                                    '<button class="btn btn-info btn-sm" onclick="modalAction(\'$1\')">Detail</button>'
                                )
                                .replace(
                                    /<a href="([^"]+)" class="btn btn-warning btn-sm">Edit<\/a>/g,
                                    '<button class="btn btn-warning btn-sm" onclick="modalAction(\'$1\')">Edit</button>'
                                )
                                .replace(
                                    /<form action="([^"]+)" method="POST"[^>]*>[\s\S]*?<\/form>/g,
                                );
                            return btn;

                        }
                    }
                ],
                language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Admin...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan admin yang sesuai",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ entri",
                    infoEmpty: "Data tidak tersedia",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    },
                    processing: '<div class="d-flex justify-content-center"><i class="fas fa-spinner fa-pulse fa-2x fa-fw text-primary"></i><span class="ms-2">Memuat data...</span></div>'
                },
            });
        });
    </script>
@endpush
