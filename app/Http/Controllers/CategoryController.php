<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CategoryController extends Controller
{
    function categoryPage()
    {
        return view('pages.dashboard.category-page');
    }

    function categoryList(Request $request)
    {
        $id = $request->header('id');
        $category = Category::where('user_id', $id)->get();
        return response()->json($category);

    }

    function createCategory(Request $request)
    {
        try {
            $id = $request->header('id');
            if (Category::where('user_id', $id)->where('name', $request->input('name'))->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category already exists',
                ]);
            }
            Category::create([
                'name' => $request->input('name'),
                'user_id' => $id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'category created successfully'
            ], 201);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category already exists',
            ]);
        }
    }



function deleteCategory(Request $request)
{
    $category_id = $request->input('id');
    $user_id = $request->header('id');
    return Category::where('user_id', $user_id)->where('id', $category_id)->delete();
}

function showCategory(Request $request)
{
    $category_id = $request->input('id');
    $user_id = $request->header('id');
    return Category::where('user_id', $user_id)->where('id', $category_id)->first();
}

function updateCategory(Request $request)
{
    $category_id = $request->input('id');
    $user_id = $request->header('id');
    return Category::where('user_id', $user_id)
        ->where('id', $category_id)
        ->update([
            'name' => $request->input('name')
        ]);
}
}
