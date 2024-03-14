<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    function CategoryPage(Request $request){
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();
        return view('pages.dashboard.category-page',compact('user'));
    }

    function CategoryList(){
        return Category::all();
    }

    function CategoryCreate(Request $request){
        return Category::create([
            'name'=>$request->input('name')
        ]);
    }

    function CategoryDelete(Request $request){
        $category_id=$request->input('id');
        return Category::where('id',$category_id)->delete();
    }


    function CategoryByID(Request $request){
        $category_id=$request->input('id');
        return Category::where('id',$category_id)->first();
    }

    function CategoryUpdate(Request $request){
        $category_id=$request->input('id');
        return Category::where('id',$category_id)->update([
            'name'=>$request->input('name'),
        ]);
    }
}
