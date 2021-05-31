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
Route::get('/', [\App\Http\Controllers\TicketController::class, 'loadDashboard']);

Route::get('/admin', 'AdminPannelController@index');
Route::get('/admin/ticketType', 'TicketTypeController@index');
Route::get('/admin/ticketStatus', 'TicketStatusController@index');

//ticket status
Route::get('/admin/ticketStatus/delete/{id}', 'TicketStatusController@Delete');
Route::get('/admin/ticketStatus/create', 'TicketStatusController@create');
Route::post('/admin/ticketStatus/add', 'TicketStatusController@Add');
Route::post('/admin/ticketStatus/{id}', 'TicketStatusController@Update');
Route::get('/admin/ticketStatus/{id}', 'TicketStatusController@edit');

//ticket types
Route::get('/admin/ticketType/delete/{id}', 'TicketTypeController@Delete');
Route::get('/admin/ticketType/create', 'TicketTypeController@create');
Route::post('/admin/ticketType/add', 'TicketTypeController@Add');
Route::post('/admin/ticketType/{id}', 'TicketTypeController@Update');
Route::get('/admin/ticketType/{id}', 'TicketTypeController@edit');

Route::post('/ticketInput/addTicket', [\App\Http\Controllers\TicketController::class, 'addTicket']);
Route::get('/ticketInput', [\App\Http\Controllers\TicketController::class, 'loadTicketInput']);

Route::get('/closeTicket/{id}', 'TicketController@closeTicket');
Route::get('/openTicket/{id}', 'TicketController@openTicket');
Route::post('/checkArchive', [\App\Http\Controllers\TicketController::class, 'checkArchive']);


Route::get('/kpi', [\App\Http\Controllers\kpiController::class, 'GetAllKPIs']);

Route::get('/Feedback/{id}','FeedbackController@load_ticket_feedback' );
Route::post('/Feedback/new', [\App\Http\Controllers\FeedbackController::class, 'addFeedback']);

Route::post('/updateTicket', [\App\Http\Controllers\TicketController::class, 'updateTicket']);
Route::post('/updateTicketMessage', [\App\Http\Controllers\TicketController::class, 'updateTicketMessage']);
Route::post('/ticketviewer/editTicketAttachements', [\App\Http\Controllers\TicketController::class, 'editTicketAttachements']);

Route::post('/assignTicketPerson', [\App\Http\Controllers\TicketPersonController::class, 'TicketPersonAssign']);
Route::post('/assignTicketPersonByUsername', [\App\Http\Controllers\TicketPersonController::class, 'TicketPersonAssignByUsername']);
Route::post('/unassignTicketPerson', [\App\Http\Controllers\TicketPersonController::class, 'TicketPersonRemove']);

Route::post('/getUserAutoFill', [\App\Http\Controllers\UserController::class, 'getAutoCompleteUsers']);

Route::post('/', [\App\Http\Controllers\TicketController::class, 'addTicket']);
Route::post('/ticketviewer', [\App\Http\Controllers\TicketController::class, 'getTicketViewer']);

Route::get('/profile/{name}', [\App\Http\Controllers\UserController::class, 'getProfilePage']);

Route::post('/userPreference', [\App\Http\Controllers\UserController::class, 'updateUserSettings']);
Route::post('/userEdit', [\App\Http\Controllers\UserController::class, 'updateUser']);

Route::post('/userEdit', [\App\Http\Controllers\UserController::class, 'updateUser']);


Route::get('/ticketviewer/{id}', [\App\Http\Controllers\TicketController::class, 'getTicketViewer']);

Route::post('/ticketviewerArchive', [\App\Http\Controllers\TicketController::class, 'getTicketViewerArchive']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
