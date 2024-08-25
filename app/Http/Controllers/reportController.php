<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class reportController extends Controller
{
    function salesReportPage(){
        return view('pages.dashboard.report-page');
    }
    function salesReport(Request $request){
        $user_id=$request->header('id');
        $FormDate=date('Y-m-d',strtotime($request->FormDate));
        $ToDate=date('Y-m-d',strtotime($request->ToDate));

        $total=Invoice::where('user_id',$user_id)->whereDate('created_at','>=',$FormDate)
            ->whereDate('created_at','<=',$ToDate)->sum('total');
        $vat=Invoice::where('user_id',$user_id)->whereDate('created_at','>=',$FormDate)
            ->whereDate('created_at','<=',$ToDate)->sum('vat');
        $discount=Invoice::where('user_id',$user_id)->whereDate('created_at','>=',$FormDate)
            ->whereDate('created_at','<=',$ToDate)->sum('discount');
        $payable=Invoice::where('user_id',$user_id)->whereDate('created_at','>=',$FormDate)
            ->whereDate('created_at','<=',$ToDate)->sum('payable');

        $list=Invoice::where('user_id',$user_id)->whereDate('created_at','>=',$FormDate)
            ->whereDate('created_at','<=',$ToDate)->with('customer')->get();


        $data=[
            'total'=>$total,
            'vat'=>$vat,
            'discount'=>$discount,
            'payable'=>$payable,
            'list'=>$list,
            'FormDate'=>$FormDate,
            'ToDate'=>$ToDate
        ];

        $pdf=PDF::loadView('report.SalesReport',$data);
        return $pdf->download('invoice.pdf');

    }
}
