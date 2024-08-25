<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ProductInvoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function invoicePage(){
        return view('pages.dashboard.invoice-page');
    }
    function salePage(){
        return view('pages.dashboard.sale-page');
    }
    function createInvoice(Request $request){
        DB::beginTransaction();
        try {
            $user_id=$request->header('id');
            $customer_id=$request->input('customer_id');
            $total=$request->input('total');
            $discount=$request->input('discount');
            $vat=$request->input('vat');
            $payable=$request->input('payable');

            $invoice=Invoice::create([
                'user_id'=>$user_id,
                'customer_id'=>$customer_id,
                'total'=>$total,
                'discount'=>$discount,
                'vat'=>$vat,
                'payable'=>$payable
            ]);

            $invoice_id=$invoice->id;
            $products=$request->input('products');

            foreach ($products as $product) {
                ProductInvoice::create([
                'invoice_id'=>$invoice_id,
                'user_id'=>$user_id,
                'product_id'=>$product['product_id'],
                'qty'=>$product['qty'],
                'sale_price'=>$product['sale_price'],
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
    function selectInvoice(Request $request){
        $user_id=$request->header('id');
        return Invoice::Where('user_id',$user_id)->with('customer')->get();
    }
    function detailsInvoice(Request $request){
        $user_id=$request->header('id');
        $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('customer_id'))->first();
        $invoiceTotal=Invoice::where('user_id','=',$user_id)->where('id',$request->input('invoice_id'))->first();
        $productInvoice=ProductInvoice::where('invoice_id',$request->input('invoice_id'))
            ->where('user_id',$user_id)->with('product')
            ->get();

        return array(
            'customer'=>$customerDetails,
            'invoice'=>$invoiceTotal,
            'product'=>$productInvoice,
        );

    }
    function deleteInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            ProductInvoice::where('invoice_id', $request->input('invoice_id'))->where('user_id', $user_id)->delete();
            Invoice::where('id', $request->input('invoice_id'))->where('user_id', $user_id)->delete();
            DB::commit();
            return 1;
        }
        catch (Exception $e) {
            DB::rollBack();
            return 0;
        }
    }
}
