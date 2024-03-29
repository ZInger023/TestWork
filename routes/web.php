<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogOutController;
use App\Http\Controllers\Controller;


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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/registration', function () {
    return view('registrationPage');
});
Route::post( '/validateRegistration' ,  [RegistrationController::class,'registration'] );
Route::get('/login', function () {
    return view('loginPage');
});
Route::post( '/log' ,  [LoginController::class,'login'] );
Route::get('/dashboard', [Controller::class,'showDashboard']);
Route::get('/myAccount', [Controller::class,'showUpdateProfile'])->name('myAccount');
Route::get('/deleteMyAccount', [Controller::class,'deleteAccount']);
Route::post('/ChangeAccountInfo', [Controller::class,'changeAccountInfo']);
Route::get('/makeMessage', [MessageController::class,'showCreateForm']);
Route::get('/myMessages', [MessageController::class,'showMessages']);
Route::get('/close/{id}', [MessageController::class,'closeMessage']);
Route::get('/delete/{id}', [MessageController::class,'deleteMessage']);
Route::post('/updateMessage/{id}', [MessageController::class,'updateMessage']);
Route::get('/updateMessageForm/{id}', [MessageController::class,'showUpdatePage']);

Route::post( '/TryMessage' ,  [MessageController::class,'insertToBd'] );
Route::get('/message/{id}', [ChatController::class,'showChat'])->name('message');
Route::post( '/{id}/sendToChat' ,  [ChatController::class,'addToChat'] );
Route::get('/updateChat/{id}', [ChatController::class,'showUpdateChatPage']);
Route::post('/updateChatMessage/{id}', [ChatController::class,'updateChatMessage']);
Route::get('/deleteChat/{id}', [ChatController::class,'deleteChatMessage']);

Route::get('/deleteImage/{id}', [MessageController::class,'deleteImage']);

Route::post( '/admin/show' ,  [AdminController::class,'showToAdmin'] );
Route::get( '/admin/show/all' ,  [AdminController::class,'showAll'] );
Route::get( '/admin/setViewed/{id}' ,  [AdminController::class,'setViewed'] );
Route::get( '/logout' ,  [LogOutController::class,'logout'] );

Route::get('/testLog', [Controller::class,'testLog']);
