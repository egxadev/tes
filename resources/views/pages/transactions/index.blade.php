@extends('layouts.app', ['type_menu' => 'transactions'])

@section('title', 'Transaksi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@endpush

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-inline">
                    <h4 class="float-left">{{ end($breadcrumb)->name }}</h4>
                    <a href="{{ route('admin.transactions.create') }}" class="btn btn-warning float-right"><i
                            class="far fa-edit fa-lg"></i> Tambah</a>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-striped table" id="primary-table">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Barang</th>
                                    <th>Tipe Transaksi</th>
                                    <th>Kuantiti</th>
                                    <th>Jumlah Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>

    <script>
        $(document).ready(function() {
            const primaryTable = $('#primary-table').DataTable({
                stateSave: true,
                processing: true,
                ajax: '{{ url()->current() }}',
                columns: [{
                        data: 'index_table',
                        defaultContent: '',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'product_id',
                    },
                    {
                        data: 'type',
                        render: function(data) {
                            if (data == 1) {
                                return '<div class="badge badge-warning">Pembelian</div>';
                            } else {
                                return '<div class="badge badge-danger">Penjualan</div>';
                            }
                        }
                    },
                    {
                        data: 'qty',
                    },
                    {
                        data: 'amount',
                    },
                    {
                        data: 'action',
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(id) {
                            return `
                            <a href="/admin/transactions/${id}" class="btn btn-warning"><i class="fas fa-info-circle"></i></a>
                            <button onClick="deleteAction(this.id, '/admin/transactions/${id}')" class="btn btn-danger text-white" id="${id}">
                                <i class="far fa-trash-alt fa-lg"></i>
                            </button>`;
                        }
                    }
                ]
            });

            primaryTable.on('draw', function() {
                primaryTable.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    var start = this.page.info().page * this.page.info().length;
                    cell.innerHTML = start + i + 1;
                    primaryTable.cell(cell).invalidate('dom');
                });
            }).draw();
        });
    </script>
@endpush
