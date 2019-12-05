<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::with(['parent'])->orderBy('created_at', 'DESC')->paginate(10);

        $parent = Category::getParent()->orderBy('name', 'ASC')->get();

        return view('categories.index', compact('category', 'parent'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:categories'
        ]);
        $request->request->add(['slug' => $request->name]);

        Category::create($request->except('_token'));

        return redirect(route('category.index'))->with(['success' => 'Kategori Baru ditambahkan!']);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parent = Category::getParent()->orderBy('name', 'ASC')->get();
        return view('categories.edit', compact('category', 'parent'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:categories,name,' . $id
        ]);


        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect(route('category.index'))->with(['success' => 'Kategori Di perbarui!']);
    }

    public function destroy($id)
    {
        //tambahkan kategori kedalam array withcount()
        //fungsi ini akan membentuk field baru yang bernama product_count

        $category = Category::withCount(['child', 'product'])->findOrFail($id);
        //kemudian pada if statemennya kita cek juga jika = 0
        if ($category->child_count == 0 && $category->product_count == 0) {
            $category->delete();
            return redirect(route('category.index'))->with(['success' => 'Kategori dihapus!']);
        }
        return redirect(route('category.index'))->with(['error' => 'Kategori ini memiliki anak kategori!']);
    }
}
