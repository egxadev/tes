<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumb = [
            (object) ['name' => 'Produk', 'link' => route('admin.products.index')],
        ];

        if ($request->ajax()) {
            $data = Product::all();

            return Datatables::of($data)
                ->editColumn('price', function ($item) {
                    return moneyFormat($item->price);
                })
                ->addColumn('action', function ($item) {
                    return $item->id;
                })
                ->make(true);
        }

        return view('pages.products.index', compact('breadcrumb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumb = [
            (object) ['name' => 'Produk', 'link' => route('admin.products.index')],
            (object) ['name' => 'Tambah Produk', 'link' => route('admin.products.create')],
        ];

        return view('pages.products.create', compact('breadcrumb'));
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
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->save();

        if ($product) {
            return redirect()->route('admin.products.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        if ($product) {
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
