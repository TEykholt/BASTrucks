<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', [\App\Http\Controllers\TicketController::class, 'getAllTicketsFromUser']);

Route::post('/ticketInput/addTicket', [\App\Http\Controllers\TicketController::class, 'addTicket']);
Route::get('/ticketInput', [\App\Http\Controllers\TicketController::class, 'loadTicketInput']);

Route::get('/closeTicket/{id}', 'TicketController@closeTicket');

Route::get('/kpi', [\App\Http\Controllers\kpiController::class, 'GetAllKPIs']);

Route::get('/Feedback', function() {
    return view("Feedback");
});
Route::get('/feedback/new', [\App\Http\Controllers\FeedbackController::class, 'addFeedback']);

Route::post('/', [\App\Http\Controllers\TicketController::class, 'addTicket']);
Route::post('/ticketviewer', [\App\Http\Controllers\TicketController::class, 'GetSingle']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
