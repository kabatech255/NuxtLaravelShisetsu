<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'TopController@index')->name('index');
Route::get('/shops', 'ShopController@index')->name('shop.index');
Route::get('/shops/{keyword}', 'ShopController@filterByKeyword')->name('shop.filterByKeyword');
Route::get('/shops/{shop_code}', 'ShopController@show')->name('shop.show');
Route::post('/upload', 'AssetController@upload')->name('upload_file');

Route::group(['middleware' => 'api'], function () {
  Route::get('/currentAuthor', 'ExaminatorController@currentAuthor')->name('currentAuthor');
  Route::get('/currentAdmin', function () {
    $admin = Auth::guard('admin')->user();
    return $admin->employee;
  })->name('currentAdmin');
  Route::post('/login', 'Auth\LoginController@login')->name('login');
  Route::post('/testlogin', 'Auth\LoginController@testLogin')->middleware('rewriteuser')->name('testlogin');

  // 一般認証ページ
  Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::post('/monthly_logs', 'MonthlyLogController@store')->name('monthlyLog.store');
    Route::get('/monthly_logs/{storeCode}', 'MonthlyLogController@index')->name('monthlyLog.index');
    Route::put('/monthly_logs/{monthlyLog}/complete', 'MonthlyLogController@complete')->name('monthlyLog.complete');
    Route::post('/monthly_logs/{monthlyLog}/details', 'MonthlyLogDetailController@store')->name('monthlyLogDetail.store');
    Route::put('/monthly_logs/{monthlyLog}/details/{monthlyLogDetail}/update', 'MonthlyLogDetailController@update')->name('monthlyLogDetail.update');
    Route::delete('/monthly_logs/{monthlyLog}/details/{monthlyLogDetail}/delete', 'MonthlyLogDetailController@destroy')->name('monthlyLogDetail.destroy');
    Route::put('/monthly_logs/{monthlyLog}/details/{monthlyLogDetail}/worry', 'MonthlyLogDetailController@worry')->name('monthlyLogDetail.worry');
    Route::delete('/monthly_logs/{monthlyLog}/details/{monthlyLogDetail}/unworry', 'MonthlyLogDetailController@unworry')->name('monthlyLogDetail.unworry');
    Route::get('/monthly_logs/{monthlyLog}/details', 'MonthlyLogDetailController@index')->name('monthlyLogDetail.index');
    // マイページトップ関連
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::get('/department', 'DepartmentController@index')->name('department.index');
    Route::get('/schedule_member_list', 'ExaminatorController@scheduleMemberList')->name('examinator.scheduleMemberList');
    // Todoリスト
    Route::post('/examinator/{examinator}/todo/store', 'TodoController@store')->name('todo.store');
    Route::delete('/examinator/{examinator}/todo/{todo}/delete', 'TodoController@destroy')->name('todo.destroy');
    Route::put('/examinator/{examinator}/todo/{todo}/done', 'TodoController@done')->name('todo.done');
    Route::put('/examinator/{examinator}/todo/{todo}/didnt', 'TodoController@didnt')->name('todo.didnt');
    // Schedule
    Route::post('/examinator/{examinator}/schedule/store', 'ScheduleController@store')->name('schedule.store');
    Route::put('/examinator/{examinator}/schedule/{schedule}/update', 'ScheduleController@update')->name('schedule.update');
    Route::delete('/examinator/{examinator}/schedule/{schedule}/delete', 'ScheduleController@destroy')->name('schedule.destroy');
    // 集計
    Route::get('/analysis/{examCode}', 'AnalysisController@index')->name('analysis.index');
    Route::get('/analysis/shop_ranks/{examCode}', 'AnalysisController@showShopRanks')->name('analysis.shop_ranks');
    // プロフィール編集
    Route::put('/examinator/{examinator}/update_profile', 'ExaminatorController@updateProfile')->name('examinator.update_profile');
  });

  // 管理者ページ
  Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

    Route::namespace('Auth')->group(function () {
      Route::post('/login', 'LoginController@login')->name('login');
      Route::post('/register', 'RegisterController@register')->name('register');
    });

    // 認証後にできる処理
    Route::middleware('auth:admin')->group(function() {
      Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
      // マスター
      Route::get('/master/index', 'MasterController@index')->name('master.index');
    });
  });
});
