<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('ecommerce.index', compact('products'));
    }

    public function product()
    {
        //query mengambil data produk,load perpage 12 agar presisi pada halaman tersebut
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        //lalu passing data kedua data diatas kedalam view
        return view('ecommerce.product', compact('products'));
    }

    public function categoryProduct($slug)
    {
        //jadi querynya adalah kita cari dulu categorinya berdasarkan slug,setelah datanya ditemukan
        //maka slug akan mengambil data product yang berelasi menggunakan method product yang telah didefinisikan pada file category.php serta diurutkan berdasarkan created_at dan load 12 data persekali load
        $products = Category::where('slug', $slug)->first()->product()->orderBy('created_at', 'DESC')->paginate(12);
        //load view yang  samaa yakni product.blade.php karena tampilannnya akan kita buat juga
        return view('ecommerce.product', compact('products'));
    }

    public function show($slug)
    {
        //query untuk mengambil single data berdasarkan slug-nya
        $product = Product::with(['category'])->where('slug', $slug)->first();
        //load view show.blade.php dan passing data product
        return view('ecommerce.show', compact('product'));
    }
}
