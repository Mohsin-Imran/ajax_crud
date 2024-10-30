<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/index', [ContactController::class, 'fetchContacts'])->name('contact.fetch');
Route::get('edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
Route::post('update/{id}', [ContactController::class, 'update'])->name('contact.update');
Route::get('delete/{id}', [ContactController::class, 'delete'])->name('contact.delete');
