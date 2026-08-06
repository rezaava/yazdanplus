<?php

use App\Exports\ShopReportExport;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Pardakht;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Store_registration;
use App\Http\Controllers\UserAccount;
use App\Http\Controllers\Buy;
use App\Http\Controllers\Transactions;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Reflogin;
use App\Http\Controllers\TeacherDayGiftController;
use App\Http\Controllers\Offs;
use App\Http\Controllers\ContractController;
use App\Exports\ShopTransactionsExport;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin22Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewBuyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StoreController;
use Maatwebsite\Excel\Facades\Excel;

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

// Route::prefix('/shops')->group(function () {
    Route::get('/b_order', [AdminController::class, 'b_order']);


    Route::get('/sms_mobile', [ShopController::class, 'sms_mobile']);

    Route::get('/contract_test', [ShopController::class, 'contract_test']);

    Route::get('/list', [ShopController::class, 'listShop']);

    Route::get('/role', [DashboardController::class, 'role']);

Route::get('/shop/{slug}', [ShopController::class, 'shop']);
Route::get('/shop/{slug}/{slug_name}', [ShopController::class, 'shop1']);

Route::get('/drhadizade', [DashboardController::class, 'abc']);
Route::get('/drhadizade2', [DashboardController::class, 'drhadizade2']);
Route::get('/drhadizade3', [DashboardController::class, 'drhadizade3']);

Route::get('/', [IndexController::class, 'index']);
Route::get('/contact', [IndexController::class, 'contact']);
Route::post('/send-contact', [IndexController::class, 'send_contact']);
Route::get('/about-us', [IndexController::class, 'about']);
Route::get('/sharayet', [IndexController::class, 'sharayet'])->middleware('auth');
Route::post('/sharayet', [IndexController::class, 'sharayet_post']);

Route::get('/login', [AuthController::class, 'login'])->name("login");
Route::get('/logout', [AuthController::class, 'logout'])->name("logout");
Route::get('/change-phone/{mobile}', [AuthController::class, 'change_phone']);
Route::post('/verified-code', [AuthController::class, 'verify_code']);
Route::post('/verify-check', [AuthController::class, 'verify_check']);
Route::get('/verified_code/{mobile}', [AuthController::class, 'verified']);


Route::get('/shop-registration', [StoreController::class, 'Store']);
Route::post('/register', [StoreController::class, 'register']);

Route::get('/report/download/{yearMonth}', [DashboardController::class, 'downloadMonthlyInstallmentReport'])
    ->name('download.monthly.report');

Route::get('/show_reportForm/{yearMonth}', [DashboardController::class, 'show_reportForm']);
Route::get('/reportform', [DashboardController::class, 'reportForm']);
Route::post('/report', [DashboardController::class, 'reportOfDate']);

Route::get('/wallet', [AccountController::class, 'wallet'])->middleware('auth');
Route::get('/profile', [AccountController::class, 'profile'])->middleware('auth');
Route::get('/edit-pic', [AccountController::class, 'edit_pic']);
Route::post('/edit-pic', [AccountController::class, 'edit_pic_post']);
Route::get('/edit-profile', [AccountController::class, 'see_profile'])->middleware('auth');
Route::post('/edit-profile', [AccountController::class, 'edit_profile'])->middleware('auth');
Route::get('/referrer2', [AccountController::class, 'referrer2'])->middleware('auth');
Route::post('/referrer2', [AccountController::class, 'check_referrer2'])->middleware('auth');

Route::post('/factor/{slug_code}', [BuyController::class, 'factor']);
Route::get('/buy/{slug_code}', [BuyController::class, 'buy']);
Route::get('/buy/{slug_code}/{slug_name}', [BuyController::class, 'buy1']);
Route::post('/payment/{id}/{month}', [BuyController::class, 'payment'])->middleware('auth');
Route::get('/payment_show/{order_id}/{month}', [BuyController::class, 'payment_show']); 
Route::get('/payment_details/{order_id}', [BuyController::class, 'payment_details'])->middleware('auth');
Route::get('/show_details/{id}/{time}', [BuyController::class, 'show_details']);
Route::post('/taeed_code/{id}', [TransactionController::class, 'taeed_code']);
// Route::get('/admin/taeed-order/{id}/{month}', [BuyController::class, 'taeedOrder'])->middleware(['role:admin|shop_admin']);
// Route::get('/admin/rad-order/{id}/{month}', [BuyController::class, 'cancelOrder'])->middleware(['role:admin|shop_admin']);

Route::get('/orders', [TransactionController::class, 'show_orders'])->middleware('auth');
Route::get('/coin', [TransactionController::class, 'coin'])->middleware('auth');
Route::get('/mablagh_ghest', [TransactionController::class, 'mablagh_ghest'])->middleware('auth');


Route::prefix('/admin')->middleware('auth')->group(function () {
    

    Route::get('/import_excel', [AdminController::class, 'import_excel']);
    Route::post('/import_excel', [AdminController::class, 'import_excel_post']);

    Route::get('/user/show/{id}', [DashboardController::class, 'user_show'])
    ->middleware(['role:admin']);

    Route::get('/shop_pass/{id}', [DashboardController::class, 'shop_user']);
    Route::post('/shop_pass/{id}', [DashboardController::class, 'shop_pass_post']);

    Route::post('/save-tasvie-order/{id}', [DashboardController::class, 'save_order_tasvie'])
    ->middleware(['role:admin']);

    Route::get('/tasvie-order/{id}', [DashboardController::class, 'tasvie_order'])
    ->middleware(['role:admin']);

    Route::get('/dateoftasvieh',[DashboardController::class,'dateOfTasvieh']);
    Route::post('/exceldownload',[DashboardController::class,'excelTasvieha']);

    Route::get('/dashboard', [SiteController::class, 'index']);

    Route::get('/filter', [DashboardController::class, 'index2'])->middleware(['role:admin']);
    Route::get('/list/sale', [DashboardController::class, 'index']);
    Route::post('/add_comment/{id}', [DashboardController::class, 'add_comment']);

    Route::post('/order_excel', [DashboardController::class, 'orderExcel'])
    ->name('order.excel')
    ->middleware('auth');

    Route::get('/order/details/{id}',[DashboardController::class, 'order_details'])
    ->middleware(['role:admin|marketer|shop_admin|content_manager|Employee_admin']);

    Route::get('/users', [DashboardController::class, 'users']);
    Route::post('/users/trans/excel', [DashboardController::class, 'users_trans_excel']);
    Route::get('/users/trans', [DashboardController::class, 'users_trans']);
    // Route::post('/users/trans', [DashboardController::class, 'users_trans_post']);
    Route::get('/users/audit/{id}', [DashboardController::class, 'userAudit']);
    Route::get('/users/logs', [DashboardController::class, 'users_logs']);
    Route::get('/users/performance/{id}', [DashboardController::class, 'performance']);
    Route::get('/users/edit/{id}', [DashboardController::class, 'useredit']);
    Route::post('/users/user-edit/{id}', [DashboardController::class, 'userupdate']);
    Route::get('/users/user-sms/{id}', [DashboardController::class, 'user_sms']);
    Route::post('/users/send-sms/{id}', [DashboardController::class, 'send_sms']);
    Route::get('/users/employee/', [DashboardController::class, 'list_employee']);
    Route::get('/users/employee/add/', [DashboardController::class, 'add_employee']);
    Route::post('/users/employee/add', [DashboardController::class, 'add_employee_post']);
    Route::get('/users/employee/del/{id}', [DashboardController::class, 'employee_del']);
    Route::post('/users/report', [DashboardController::class, 'reportUser'])->name('userreport');
    Route::get('/users/report/user/{userId}', [DashboardController::class, 'showUserTransactions'])->name('user.transactions');
    Route::get('/users/transactions/export-user-installments-excel', [TransactionController::class, 'exportUserInstallmentsExcel'])->name('transactions.exportUserInstallmentsExcel');
    Route::get('/users/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/users/add/user', [DashboardController::class, 'add_user']);
    Route::post('/users/add/user', [DashboardController::class, 'add_user_post']);
    Route::get('/users/user/show/{id}', [DashboardController::class, 'user_show']);
    Route::post('/chrge-wallet/{id}', [DashboardController::class, 'chrgewallet']);
    Route::get('/up-role/{id}/{role}', [DashboardController::class, 'up_role']);
    Route::get('/down-role/{id}/{role}', [DashboardController::class, 'down_role']);

    Route::post('/shops/buy/verify/{order}', [NewBuyController::class, 'verifyCode']);
    Route::get('/shops/buy', [NewBuyController::class, 'new_buy']);
    Route::post('/shops/buy/{id}', [NewBuyController::class, 'post_new_buy']);
    Route::get('/shops/{shop}/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::post('/shops/{shop}/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/shops/{shop}/contracts/delete', [ContractController::class, 'delete']);
    Route::get('/shops', [DashboardController::class, 'shops']);
    Route::get('/shops/report_g/{shopId}/excel', [DashboardController::class, 'exportReportExcel'])->name('shops.report_g.excel');
    Route::get('/shops/report_g/{id}', [DashboardController::class, 'reportG'])->name('admin.shops.report_g');
    Route::get('/shops/bill/{id}', [DashboardController::class, 'bill']);
    Route::get('/shops/conditions/{id}', [DashboardController::class, 'conditions']);
    Route::post('/shops/conditions/{id}', [DashboardController::class, 'conditions_post']);
    Route::get('/shops/conditions/delete/{id}', [DashboardController::class, 'conditions_delete']);
    Route::get('/shops/add', [DashboardController::class, 'shopsAdd'])->middleware(['role:admin']);
    Route::post('/shops/add', [DashboardController::class, 'shopsAddPost']);
    Route::post('/shops/edit/{id}', [DashboardController::class, 'shopsEditPost'])->middleware(['role:admin']);
    Route::get('/shops/edit/{id}', [DashboardController::class, 'shopsEdit']);
    Route::get('/shops/tasvie_riz_order/{id}', [DashboardController::class, 'tasvie_riz_order']);
    Route::get('/shops/form_tasvie/{id}', [DashboardController::class, 'form_tasvie']);
    Route::post('/shops/factor_tasvie/{id}', [DashboardController::class, 'factor_tasvie']);
    Route::get('/shops/audit/{id}', [DashboardController::class, 'audit']);
    Route::get('/shops/show_audit/{id}', [DashboardController::class, 'show_audit']);
    Route::post('/admin/audit/{id}/excel', [DashboardController::class, 'auditExcel'])
    ->name('audit.excel');

    
    Route::get('/shops/report/shop/{shop_id}/transactions', [DashboardController::class, 'showShopTransactions'])->name('report.shop.transactions');
});

Route::post('/shops/{id}/audit-preview', [DashboardController::class, 'auditPreview'])
    ->name('audit.preview');

Route::post('/admin/shops/get-month-summary', [\App\Http\Controllers\ShopController::class, 'getMonthSummary'])
    ->name('admin.shops.getMonthSummary');
    
Route::post('/shops/get-month-summary', [ShopController::class, 'getMonthSummary']);



Route::patch('/ghest_payment/{transaction}', [TransactionController::class, 'ghest_payment'])->name('ghest_payment')->middleware('auth');
