<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumb = [
            (object) ['name' => 'Kategori Produk', 'link' => route('admin.transactions.index')],
        ];

        if ($request->ajax()) {
            $data = Transaction::orderBy('created_at', 'DESC')->get();

            return Datatables::of($data)
                ->addColumn('product_id', function ($item) {
                    return $item->product->name;
                })
                ->addColumn('action', function ($item) {
                    return $item->id;
                })
                ->editColumn('amount', function ($item) {
                    return moneyFormat($item->amount);
                })
                ->make(true);
        }

        return view('pages.transactions.index', compact('breadcrumb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumb = [
            (object) ['name' => 'Transaksi', 'link' => route('admin.transactions.index')],
            (object) ['name' => 'Tambah Transaksi', 'link' => route('admin.transactions.create')],
        ];

        $products = Product::all();

        return view('pages.transactions.create', compact('breadcrumb', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'type' => 'required',
            'qty' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $product = Product::where('id', $request->product_id)->first();

            if ($product->stock < $request->qty && $request->type == 2) {
                return redirect()->route('admin.transactions.index')->with(['error' => 'Penjualan product melebihi stock!']);
            }

            $transaction = new Transaction();
            $transaction->product_id = $request->product_id;
            $transaction->type = $request->type;
            $transaction->qty = $request->qty;
            $transaction->amount = $product->price * $request->qty;
            $transaction->save();

            if ($request->type == 1) {
                $product->stock = $product->stock + $request->qty;
                $product->save();
            } else {
                $product->stock = $product->stock - $request->qty;
                $product->save();
            }

            DB::commit();

            return redirect()->route('admin.transactions.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.transactions.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $breadcrumb = [
            (object) ['name' => 'Transaksi', 'link' => route('admin.transactions.index')],
            (object) ['name' => 'Tambah Transaksi', 'link' => route('admin.transactions.create')],
        ];

        $transaction->load('product');

        return view('pages.transactions.show', compact('breadcrumb', 'transaction'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        if ($transaction) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
