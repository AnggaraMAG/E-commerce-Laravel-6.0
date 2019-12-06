<?php

namespace App\Http\View;

use Illuminate\View\View;
use App\Category;


class CategoryComposer
{


    public function compose(View $view)
    {
        //jadi query kita pindahkan kesini
        $categories = Category::with(['child'])->withCount(['child'])->getParent()->orderBy('name', 'ASC')->get();

        //kemudian passing data tersebut dengan nama variable categories
        $view->with('categories', $categories);
    }
}
