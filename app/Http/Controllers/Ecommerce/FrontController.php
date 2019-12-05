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
        //load data kategori yang akan ditampilkan pada sidebar
        $categories = Category::with(['child'])->withCount(['child'])->getParent()->orderBy('name', 'ASC')->get();
        //lalu passing data kedua data diatas kedalam view
        return view('ecommerce.product', compact('products', 'categories'));
    }
}
