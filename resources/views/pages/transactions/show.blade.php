@extends('layouts.app', ['type_menu' => 'transactions'])

@section('title', 'Detail Transaksi')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-inline">
                    <h4 class="float-left">{{ end($breadcrumb)->name }}</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-striped table" id="primary-table">
                            <tr>
                                <td style="width: 15%">ID transaksi</td>
                                <td style="width: 5%">:</td>
                                <td>{{ $transaction->id }}</td>
                            </tr>
                            <tr>
                                <td style="width: 15%">Produk</td>
                                <td style="width: 5%">:</td>
                                <td>{{ $transaction->product->name }}</td>
                            </tr>
                            <tr>
                                <td style="width: 15%">Kuantiti</td>
                                <td style="width: 5%">:</td>
                                <td>{{ $transaction->qty }}</td>
                            </tr>
                            <tr>
                                <td style="width: 15%">Harga Satuan</td>
                                <td style="width: 5%">:</td>
                                <td>{{ moneyFormat($transaction->product->price) }}</td>
                            </tr>
                            <tr>
                                <td style="width: 15%">Total Pembelian</td>
                                <td style="width: 5%">:</td>
                                <td>{{ moneyFormat($transaction->amount) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
