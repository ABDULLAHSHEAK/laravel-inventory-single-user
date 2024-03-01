<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\User;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;


class InvoiceController extends Controller
{

    function InvoicePage(Request $request):View{
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();
        return view('pages.dashboard.invoice-page',compact('user'));
    }

    function SalePage():View{
        return view('pages.dashboard.sale-page');
    }

    function invoiceCreate(Request $request){

        DB::beginTransaction();

        try {

        $total=$request->input('total');
        $discount=$request->input('discount');
        $vat=$request->input('vat');
        $payable=$request->input('payable');

        $customer_id=$request->input('customer_id');

        $invoice= Invoice::create([
            'total'=>$total,
            'discount'=>$discount,
            'vat'=>$vat,
            'payable'=>$payable,
            'customer_id'=>$customer_id,
        ]);


       $invoiceID=$invoice->id;

       $products= $request->input('products');

       foreach ($products as $EachProduct) {
            InvoiceProduct::create([
                'invoice_id' => $invoiceID,
                'product_id' => $EachProduct['product_id'],
                'qty' =>  $EachProduct['qty'],
                'sale_price'=>  $EachProduct['sale_price'],
            ]);
        }

       DB::commit();

       return 1;

        }
        catch (Exception $e) {
            DB::rollBack();
            return 0;
        }

    }

    function invoiceSelect(){
        return Invoice::with('customer')->get();
    }

    function InvoiceDetails(Request $request){
        $customerDetails=Customer::where('id',$request->input('cus_id'))->first();
        $invoiceTotal=Invoice::where('id',$request->input('inv_id'))->first();
        $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))
            ->with('product')
            ->get();
        return array(
            'customer'=>$customerDetails,
            'invoice'=>$invoiceTotal,
            'product'=>$invoiceProduct,
        );
    }

    function invoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            $user_id=$request->header('id');
            InvoiceProduct::where('invoice_id',$request->input('inv_id'))
                ->where('user_id',$user_id)
                ->delete();
            Invoice::where('id',$request->input('inv_id'))->delete();
            DB::commit();
            return 1;
        }
        catch (Exception $e){
            DB::rollBack();
            return 0;
        }
    }
}
