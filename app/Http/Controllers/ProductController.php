<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Str;
use File;
use App\Jobs\ProductJob;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::with(['category'])->orderBy('created_at', 'DESC');

        //jika terdapat parameter pencarian di url / q  pada url tidak sama dengan kosong
        if (request()->q != '') {
            //maka lakukan filtering data berdasarkan name dan valuenya sesuai dengan pencarian yang dilakukan user
            $product = $product->where('name', 'LIKE', '%' . request()->q . '%');
        }
        //load 10 data perhalaman /paginate
        $product =  $product->paginate(10);

        return view('products.index', compact('product'));
    }

    public function create()
    {
        $category = Category::orderBy('name', 'DESC')->get();
        return view('products.create', compact('category'));
    }

    public function store(Request $request)
    {

        // return dd($request->all());
        //validasi request
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'image' => 'required|image|mimes:png,jpeg,jpg',
        ]);

        //Jika filenya ada
        if ($request->hasFile('image')) {
            //simpan sementara difile kedalam variable
            $file = $request->file('image');
            //nama filenya dibuat costumer dengan perpaduan time dan slug dari nama produk.adapun extensinya kita gunakan bawaan file tersebut

            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();

            //simpan filekedalam folder public/products dan parameter kedua adalah nama custom untuk file tersebut
            $file->storeAs('public/products', $filename);


            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'image' => $filename,
                'price' => $request->price,
                'weight' => $request->weight,
                'status' => $request->status,
            ]);
            return redirect(route('product.index'))->with(['success' => 'Produk Baru ditambahkan!']);
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $category = Category::orderBy('name', 'DESC')->get();

        return view('products.edit', compact('product', 'category'));
    }

    public function update(Request $request, $id)
    {
        //validasi data sebelum di input ke database
        $this->validate($request, [
            'name' => 'required|string|max:30',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        $product = Product::findOrFail($id);
        $filename = $product->image;

        //jika ada file gambar yang dikirim
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            //maka upload file tersebut
            $file->storeAs('public/products', $filename);
            //dan hapus gambar yang lama
            File::delete(storage_path('app/public/products/' . $product->image));
        }
        //kemudian update product tersebut
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'price' => $request->price,
            'weight' => $request->weight,
            'image' => $filename,
        ]);
        return redirect(route('product.index'))->with(['success' => 'Data Berhasil Diperbarui!']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id); //memanggil data product berdasarkan id
        //hapus file image dari storage path diikuti dengan nama image yang diambil dari database
        File::delete(storage_path('app/public/products' . $product->image));
        //kemudian hapus data product dari table
        $product->delete();
        //lalu redirect kehalaman list produk
        return redirect(route('product.index'))->with(['succes' => 'Product Berhasil dihapus!']);
    }

    public function massUploadForm(Request $request)
    {
        $category = Category::orderBy('name', 'DESC')->get();
        return view('products.bulk', compact('category'));

        //validasi data masuk
        $this->validate($request, [
            'category_id' => 'required|exists:category,id',
            'file' => 'required|mimes:xlsx',
        ]);


        //jika file excelnya ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '-product' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads', $filename); //maka file tersimpan di file public/uploads
        }

        //membuat jadwal untuk proses file tersebut dengan menggunakan job
        //adapun pada dispatch kita mengirim dua parameter sebagai informasi
        //yakni kategori id dan nama filenya yang sudah disimpan

        Productjob::dispatch($request->category_id, $filename);
        return redirect()->back()->with(['success' => 'Upload Product Dijadwalkan']);
    }
}
