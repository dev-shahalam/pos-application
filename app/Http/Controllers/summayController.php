<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class summayController extends Controller
{
    public function summary(Request $request)
    {

        $user_id = $request->header('id');

        $product = Product::where('user_id', $user_id)->count();
        $customer = Customer::where('user_id', $user_id)->count();
        $category = Category::where('user_id', $user_id)->count();
        $invoice = Invoice::where('user_id', $user_id)->count();
        $total_sale = Invoice::where('user_id', $user_id)->sum('total');
        $total_vat = Invoice::where('user_id', $user_id)->sum('vat');
        $total_payable = Invoice::where('user_id', $user_id)->sum('payable');

        return array(
            'product' => $product,
            'category' => $customer,
            'customer' => $category,
            'invoice' => $invoice,
            'total' => round($total_sale,2),
            'vat' => round($total_vat,2),
            'payable' => round($total_payable,2)
        );

    }
}
