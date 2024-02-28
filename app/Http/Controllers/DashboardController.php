<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class DashboardController extends Controller
{
    function DashboardPage(Request $request):View{
        return view('pages.dashboard.dashboard-page');
    }

    function Summary(Request $request):array{

        $user_id=$request->header('id');

        $product= Product::get()->count();
        $Category= Category::get()->count();
        $Customer=Customer::get()->count();
        $Invoice= Invoice::get()->count();
        $total=  Invoice::get()->sum('total');
        $vat= Invoice::get()->sum('vat');
        $payable =Invoice::get()->sum('payable');

        return[
            'product'=> $product,
            'category'=> $Category,
            'customer'=> $Customer,
            'invoice'=> $Invoice,
            'total'=> round($total,2),
            'vat'=> round($vat,2),
            'payable'=> round($payable,2)
        ];


    }
}
