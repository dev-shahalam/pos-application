<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;



class ProductController extends Controller
{
    // Frontend
    public function productPage(){
        return view('pages.dashboard.product-page');
    }





    // Api for product
    public function createProduct(Request $request){
        $user_id=$request->header('id');

        //get image from request / frontend
        $img=$request->file('img');

        // generate image name & path
        $t=time();
        $file_name=$img->getClientOriginalName();
        $img_name=$user_id.$t.$file_name;
        $img_path="uploads/{$img_name}";

        //upload image
        $img->move(public_path('uploads'),$img_name);

        //save data in database
        return Product::create([
            'name'=>$request->input('name'),
            'price'=>$request->input('price'),
            'unit'=>$request->input('unit'),
            'img_url'=>$img_path,
            'user_id'=>$user_id,
            'category_id'=>$request->input('category_id')
        ]);
    }
    public function deleteProduct(Request $request){
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        $img_path=$request->input('img_path');
        File::delete($img_path);
       return Product::where('id',$product_id)->where('user_id',$user_id)->delete();
    }
    public function showProductById(Request $request){
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        return Product::where('id',$product_id)->where('user_id',$user_id)->first();
    }
    public function productList(Request $request){
        $user_id=$request->header('id');
        return Product::where('user_id',$user_id)->get();
    }
    Public function updateProduct(Request $request){
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        if ($request->hasFile('img')){
            $img=$request->file('img'); //get image from request

            // generate image name & path
            $t=time();
            $file_name=$img->getClientOriginalName();
            $img_name=$user_id.$t.$file_name;
            $img_path="uploads/{$img_name}";

            $img->move(public_path('uploads'),$img_name);
            // old image delete
            $old_img_path=$request->input('img_path');
            File::delete($old_img_path);

            return Product::where('id',$product_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'img_url'=>$img_path,
                'user_id'=>$user_id,
                'category_id'=>$request->input('category_id')
            ]);

        }else{
            return Product::where('id',$product_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'user_id'=>$user_id,
                'category_id'=>$request->input('category_id')
            ]);
        }

    }




}
