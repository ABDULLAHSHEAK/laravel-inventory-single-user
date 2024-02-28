<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{

    function CustomerPage():View{
        return view('pages.dashboard.customer-page');
    }

    function CustomerCreate(Request $request){
        return Customer::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile')
        ]);
    }


    function CustomerList(){
        return Customer::all();
    }


    function CustomerDelete(Request $request){
        $customer_id=$request->input('id');
        return Customer::where('id',$customer_id)->delete();
    }


    function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        return Customer::where('id',$customer_id)->first();
    }


     function CustomerUpdate(Request $request){
        $customer_id=$request->input('id');
        return Customer::where('id',$customer_id)->update([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
        ]);
    }



}
