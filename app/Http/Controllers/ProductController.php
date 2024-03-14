<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{


    function ProductPage(Request $request):View{
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();
        return view('pages.dashboard.product-page',compact('user'));
    }


    function CreateProduct(Request $request)
    {

        // Prepare File Name & Path
        // $img_url = null; // Default to null
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $t = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$t}-{$file_name}";
            $img_url = "uploads/{$img_name}";

            // Upload File
            $img->move(public_path('uploads'), $img_name);
        }else{
            $img_url = 'uploads/default.jpg ';
        }

        $code = rand(1000, 9999);
        // $name = Str::lower($request->input('name'));

        // Save To Database
        return Product::create([
            'name' => $request->input('name'),
            'product_code' => Str::lower($request->input('name')) . $code,
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'expire_date' => $request->input('expire_date'),
            'created_by' => $request->input('created_by'),
            'img_url' => $img_url,
            'category_id' => $request->input('category_id')
        ]);

    }


    function DeleteProduct(Request $request)
    {
        $product_id=$request->input('id');
        $filePath=$request->input('file_path');
        File::delete($filePath);
        return Product::where('id',$product_id)->delete();

    }


    function ProductByID(Request $request)
    {
        $product_id=$request->input('id');
        return Product::where('id',$product_id)->first();
    }


    function ProductList()
    {
        return Product::all();
    }




    function UpdateProduct(Request $request)
    {
        $product_id=$request->input('id');

        if ($request->hasFile('img')) {

            // Upload New File
            $img=$request->file('img');
            $t=time();
            $file_name=$img->getClientOriginalName();
            $img_name="{$t}-{$file_name}";
            $img_url="uploads/{$img_name}";
            $img->move(public_path('uploads'),$img_name);

            // Delete Old File
            $filePath=$request->input('file_path');
            File::delete($filePath);

            // Update Product

            return Product::where('id',$product_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'stock'=>$request->input('stock'),
                'expire_date'=>$request->input('expire_date'),
                'updated_by'=>$request->input('updated_by'),
                'img_url'=>$img_url,
                'category_id'=>$request->input('category_id')
            ]);

        }

        else {
            return Product::where('id',$product_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'stock' => $request->input('stock'),
                'expire_date' => $request->input('expire_date'),
                'updated_by' => $request->input('updated_by'),
                'category_id'=>$request->input('category_id'),
            ]);
        }
    }
}
