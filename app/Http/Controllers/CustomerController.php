<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{
    public function customerPage(): View
    {
        return view('pages.dashboard.customer-page');
    }

    public function customerList(Request $request): View|JsonResponse
    {
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return response()->json($customers);

    }

    public function createCustomer(Request $request): View|JsonResponse
    {
        try {
            $user_id = $request->header('id');
            if (Customer::Where('user_id', $user_id)
                ->Where('mobile', $request->input('mobile'))
                ->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Customer already exists',
                ]);
            }
            Customer::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'user_id' => $user_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
            ], 201);
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer already exists',
            ]);
        }


    }

    public function showCustomer(Request $request): View|JsonResponse
    {
        $user_id = $request->header('id');
        $customer = Customer::where('id', $request->input('id'))->where('user_id', $user_id)->first();
        return response()->json($customer);
    }

    public function updateCustomer(Request $request): View|JsonResponse
    {
        try {
            $user_id = $request->header('id'); //get user id from request
            $customer_id = $request->input('id');
            Customer::where('id', $customer_id)->where('user_id', $user_id)->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer updated successfully',
            ]);

        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 500);
        }
    }

    public function deleteCustomer(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');
        return Customer::where('id', $customer_id)->where('user_id', $user_id)->delete();

    }


}
