<?php

namespace App\Http\Controllers\Admin;

use App\Models\Photo;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $breadcrumb = [
            (object) ['name' => 'Dashboard', 'link' => route('admin.dashboard')],
            (object) ['name' => 'Produk', 'link' => route('admin.products.index')],
            (object) ['name' => 'Tambah Produk', 'link' => route('admin.products.create')],
        ];

        $categories = Category::all();

        return view('pages.products.create', compact('breadcrumb', 'categories'));
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
            'price' => 'required',
            'stock' => 'required',
            'categories' => 'required',
            'type' => 'required',
            'status' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $product = new Product();
            $product->nama = $request->name;
            $product->harga = $request->price;
            $product->stok = $request->stock;
            $product->type = $request->type;
            $product->diskonPersen = $request->discount;
            $product->isActive = $request->status;
            $product->keterangan = $request->description;
            $product->save();

            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('public/products', $imageName);

            $photo = new Photo();
            $photo->produkId = $product->id;
            $photo->link = url('/') . '/storage/products/' . $imageName;
            $photo->save();

            $product->categories()->attach($request->input('categories'));
            $product->save();

            DB::commit();

            return redirect()->route('admin.products.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.products.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $breadcrumb = [
            (object) ['name' => 'Produk', 'link' => route('admin.products.index')],
            (object) ['name' => 'Edit Produk', 'link' => route('admin.products.edit', $product->id)],
        ];

        $categories = Category::all();

        return view('pages.products.edit', compact('breadcrumb', 'categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'categories' => 'required',
            'type' => 'required',
            'status' => 'required',
            'description' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $product->nama = $request->name;
            $product->harga = $request->price;
            $product->stok = $request->stock;
            $product->type = $request->type;
            $product->diskonPersen = $request->discount;
            $product->isActive = $request->status;
            $product->keterangan = $request->description;
            $product->save();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->hashName();
                $image->storeAs('public/products', $imageName);

                $photo = new Photo();
                $photo->produkId = $product->id;
                $photo->link = url('/') . '/storage/products/' . $imageName;
                $photo->save();
            }

            $product->categories()->sync($request->input('categories'));
            $product->save();

            DB::commit();

            return redirect()->route('admin.products.index')->with(['success' => 'Data Berhasil Diperbarui!']);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.products.index')->with(['error' => 'Data Gagal Diperbarui!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
