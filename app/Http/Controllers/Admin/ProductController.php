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
                ->make(true);
        }

        return view('pages.products.index', compact('breadcrumb'));
    }
}
