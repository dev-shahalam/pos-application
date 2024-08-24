<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TokenVerifyMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// web pages view route

// Auth Routes

Route::get('login',[UserController::class,'login'])->name('login');
Route::get('registration',[UserController::class,'register'])->name('registration');
Route::get('/dashboard',[UserController::class,'dashboard'])->name('dashboard')
    ->middleware(TokenVerifyMiddleware::class);
Route::get('/dashboard/profile',[UserController::class,'userProfile'])->name('profile')
    ->middleware(TokenVerifyMiddleware::class);
Route::get('send-otp',[UserController::class,'sendOtpPage'])->name('sendOtpPage');
Route::get('submit-otp',[UserController::class,'submitOtpPage'])->name('submitOtpPage');
Route::get('reset-password',[UserController::class,'resetPasswordPage'])->name('resetPasswordPage');

// Auth api route
Route::post('registration',[UserController::class,'registrationUser'])->name('registration');
Route::post('login',[UserController::class,'loginUser'])->name('login');
Route::get('logout',[UserController::class,'logoutUser'])->name('logout');
Route::post('send-otp',[UserController::class,'sendOtp']);
Route::post('submit-otp',[UserController::class,'submitOtp']);
Route::post('reset-password',[UserController::class,'resetPassword']);

//Prof
Route::get('/user-data',[UserController::class,'userProfileData'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('/dashboard/profile',[UserController::class,'userProfileUpdate'])
    ->middleware(TokenVerifyMiddleware::class);



// Category Routes

Route::get('categoryPage',[CategoryController::class,'categoryPage'])
    ->middleware(TokenVerifyMiddleware::class);
Route::get('list-category',[CategoryController::class,'categoryList'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('create-category',[CategoryController::class,'createCategory'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('delete-category',[CategoryController::class,'deleteCategory'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('category-by-id',[CategoryController::class,'showCategory'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('update-category',[CategoryController::class,'updateCategory'])
    ->middleware(TokenVerifyMiddleware::class);



// Customer Routes
Route::get('/customerPage',[customerController::class,'customerPage'])
    ->middleware(TokenVerifyMiddleware::class);
Route::get('/list-customer',[customerController::class,'customerList'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('create-customer',[customerController::class,'createCustomer'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('customer-by-id',[customerController::class,'showCustomer'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('update-customer',[customerController::class,'updateCustomer'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('delete-customer',[customerController::class,'deleteCustomer'])
    ->middleware(TokenVerifyMiddleware::class);


// Products Route
Route::get('productPage',[ProductController::class,'productPage'])
    ->middleware(TokenVerifyMiddleware::class);
Route::get('product-list',[ProductController::class,'productList'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('create-product',[ProductController::class,'createProduct'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('delete-product',[ProductController::class,'deleteProduct'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('show-product',[ProductController::class,'showProductById'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('update-product',[ProductController::class,'updateProduct'])
    ->middleware(TokenVerifyMiddleware::class);


// Invoice Routes
Route::get('invoicePage',[InvoiceController::class,'invoicePage'])
    ->middleware(TokenVerifyMiddleware::class);
Route::get('salePage',[InvoiceController::class,'salePage'])
    ->middleware(TokenVerifyMiddleware::class);



Route::post('invoice-create',[InvoiceController::class,'createInvoice'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('invoice-details',[InvoiceController::class,'detailsInvoice'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('delete-invoice',[InvoiceController::class,'deleteInvoice'])
    ->middleware(TokenVerifyMiddleware::class);
Route::get('invoice-list',[InvoiceController::class,'selectInvoice'])
    ->middleware(TokenVerifyMiddleware::class);
Route::post('show-invoice',[InvoiceController::class,'showInvoice'])
    ->middleware(TokenVerifyMiddleware::class);


