<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', 'App\Http\Controllers\UserController@welcome')->name('welcome');
//Route::get('/', [UserController::welcome,'welcome']);
Route::get('/start', 'App\Http\Controllers\UserController@welcome')->name('start');

//Auth::routes();
Auth::routes(['verify' => true]);



/* STARTING */
Route::get('/way', 'App\Http\Controllers\AccountController@userSelection')->name('user.selection');
Route::get('/wizard', 'App\Http\Controllers\Wizard@wizard')->name('wizard');


/* ACCOUNT */
Route::get('/jobs-feed', 'App\Http\Controllers\JobsFrontController@jobFeed')->name('home');
Route::get('/account/profile', 'App\Http\Controllers\AccountController@profile')->name('profile');
Route::post('/account/profile/save', 'App\Http\Controllers\AccountController@save')->name('profile.save');
Route::post('/account/profile/save/business', 'BusinessController@saveBusiness')->name('profile.bjsave');


Route::get('/account/notifications', 'App\Http\Controllers\AccountController@notifications')->name('notifications');
Route::get('/account/messages', 'App\Http\Controllers\AccountController@messages')->name('messages');
Route::get('/account/contracts', 'App\Http\Controllers\AccountController@contracts')->name('contracts');
Route::get('/account/contracts/view/{id}', 'App\Http\Controllers\AccountController@contractView')->name('contract.view');

/* BJ PAYMENT */
Route::get('/account/payment/method', 'App\Http\Controllers\PaymentsController@methodForm')->name('payment.method');

Route::get('/account/receipts', 'App\Http\Controllers\PaymentsController@index')->name('payment.receipts');


/* JOBS */
Route::get('/account/new-job', 'App\Http\Controllers\JobsController@frmNewJob')->name('new-job');
Route::get('/account/edit-job/{id}', 'App\Http\Controllers\JobsController@frmEditJob')->name('edit-job');
Route::post('/account/new-job/save', 'App\Http\Controllers\JobsController@save')->name('new-job.save');

Route::get('/account/jobs', 'App\Http\Controllers\JobsController@jobs')->name('jobs');

Route::get('/job-feed', 'App\Http\Controllers\JobsFrontController@jobFeed');

Route::get('/job/view/{id}/{slug}', 'App\Http\Controllers\JobsFrontController@viewJob')->name('viewJob');
Route::get('/job/save/{id}', 'App\Http\Controllers\JobsFrontController@saveJob')->name('viewJob.save');

Route::get('/job/unpublish/{id}', 'App\Http\Controllers\JobsController@statusChange')->name('viewJob.unpublish');
Route::get('/job/publish/{id}', 'App\Http\Controllers\JobsController@statusChange')->name('viewJob.publish');

Route::get('/download/{code}', 'App\Http\Controllers\FileController@download')->name('download');

Route::post('/job/acceptHire/{code}', 'App\Http\Controllers\JobsController@acceptHire')->name('acceptHire');
Route::get('/job/dashboard/{code}', 'App\Http\Controllers\JobsController@dashboard')->name('job.pjDashboard');
Route::get('/job/time-sheet/{code}', 'App\Http\Controllers\JobsController@timeSheet')->name('job.timeSheet');
Route::get('/job/tasks/{code}', 'App\Http\Controllers\JobsController@tasks')->name('job.tasks');
Route::get('/job/payments/{code}', 'App\Http\Controllers\JobsController@pj_payments')->name('job.payments');
Route::post('/job/finish/{code}', 'App\Http\Controllers\JobsController@finish')->name('job.finish');
Route::post('/job/finish-rate/{code}', 'App\Http\Controllers\JobsController@finishAndRate')->name('job.finishAndRate');
Route::post('/account/receipt/pay', 'App\Http\Controllers\PaymentsController@pay')->name('receipt.pay');
Route::get('/account/invoice/{id}', 'App\Http\Controllers\PaymentsController@getInvoice')->name('account.invoice');
Route::post('/account/receipt/pay/transaction', 'App\Http\Controllers\PaymentsController@transaction')->name('receipt.transaction');


/* Ajax Calls */
Route::post('upload', 'App\Http\Controllers\FileController@upload')->name('upload');
Route::post('upload-data', 'App\Http\Controllers\FileController@uploadImgData')->name('upload.data');
Route::post('rm-file', 'App\Http\Controllers\FileController@remove')->name('rm-file');
Route::post('frm-file', 'App\Http\Controllers\FileController@forceRemove')->name('frm-file');
Route::post('/api/getSkills', 'App\Http\Controllers\ApiController@getSkills')->name('api.getSkills');
Route::post('/api/saveTimeZone', 'App\Http\Controllers\ApiController@saveTimeZone')->name('api.saveTimeZone');
Route::post('/job/apply', 'App\Http\Controllers\JobsController@apply')->name('viewJob.apply');
Route::post('/job/hire', 'App\Http\Controllers\JobsController@hire')->name('hire');
Route::post('/job/getHire', 'App\Http\Controllers\JobsController@getHire')->name('getHire');
Route::post('/job/task/startInterval', 'App\Http\Controllers\JobsController@startInterval')->name('task.startInterval');
Route::post('/job/task/stopInterval', 'App\Http\Controllers\JobsController@stopInterval')->name('task.stopInterval');
Route::post('/job/calcPayroll', 'App\Http\Controllers\JobsController@calcPayroll')->name('job.calcPayroll');

Route::post('/wizard/save-1', 'App\Http\Controllers\Wizard@saveStep1')->name('wsave1');
Route::post('/wizard/save-2', 'App\Http\Controllers\Wizard@saveStep2')->name('wsave2');

Route::post('/account/payment/save', 'App\Http\Controllers\PaymentsController@methodSave')->name('payment.save');

Route::post('/job/new-task', 'App\Http\Controllers\JobsController@addTask')->name('addTask');
Route::get('/job/task/delete/{code}', 'App\Http\Controllers\JobsController@deleteTask')->name('task.delete');
Route::get('/job/time-sheets/{code}', 'App\Http\Controllers\JobsController@getTimeSheets')->name('job.getTimeSheets');
Route::post('/api/payments/widthdrawal/bank', 'App\Http\Controllers\ApiController@requestBankWithdrawal')->name('payment.request-baw');

Route::post('/api/avatar/save', 'App\Http\Controllers\ApiController@avatarSave')->name('avatar.save');


Route::post('/api/job/event/changeDate', 'App\Http\Controllers\ApiController@changeJobEventDate')->name('api.changeJobEventDate');
Route::post('/api/job/event/changeStatus', 'App\Http\Controllers\ApiController@changeJobEventStatus')->name('api.changeJobEventStatus');

Route::post('/api/job/deli/delete', 'App\Http\Controllers\ApiController@removeDeliverable')->name('api.removeDeliverable');


Route::post('/api/skills/add', 'App\Http\Controllers\ApiController@addSkill')->name('api.addSkill');

/* NOTIFICATIONS */
Route::get('/api/readNotification/{id}', 'App\Http\Controllers\ApiController@readNotification')->name('readNotification');

/* MAILS */

Route::get('mailable', function () {
    $invoice = App\User::find(1);

    return new App\Mail\Welcome();
});






/* BACKEND */
Route::get('/backend', 'BackendController@showUsers')->name('backend.users');

Route::get('/backend/withdrawal-requests', 'BackendController@showWithdrawalRequests')->name('backend.withdrawal-requests');
Route::post('/backend/withdrawal-requests/validate', 'BackendController@validateRequest')->name('backend.request.validate');
Route::post('/backend/bank-transfer/validate', 'BackendController@validateTransfer')->name('backend.transfer.validate');

Route::get('/backend/transfers', 'BackendController@showIncommingTransfers')->name('backend.transfers');
Route::get('/backend/languages', 'BackendController@translations')->name('backend.translations');



/* ARTISAN */

Route::get('/storage-link', function() {
    $output = [];
    \Artisan::call('cache:clear', $output);
    dd($output);
});