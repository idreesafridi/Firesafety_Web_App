<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QouteController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\TermsAndConditionController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\IncomingChallanController;
use App\Http\Controllers\DeliveryChallanController;
use App\Http\Controllers\CashMemoController;
use App\Http\Controllers\GeneralTemplateController;
use App\Http\Controllers\EmailGeneralTemplateController;
use App\Http\Controllers\SupportQuotationTemplateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SafetyCareController;
use App\Http\Controllers\ViqasEnterpriseController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/clear-cache', function () {
   Artisan::call('cache:clear');
   Artisan::call('route:clear');

   return "Cache cleared successfully";
});

Route::get('info', function() {
   return response()->json([
    'stuff' => phpinfo()
   ]);
});

Route::group(['middleware' => ['auth']], function() {
    
	Route::get('/home', [HomeController::class, 'index'])->name('home');
	Route::get('/', [HomeController::class, 'index'])->name('home');
	Route::get('summary', [HomeController::class, 'summary'])->name('summary'); ///{type}
	
	Route::get('sales-by-products', [HomeController::class, 'salesByProducts'])->name('salesByProducts');
	Route::get('sales-by-customers', [HomeController::class, 'salesByCustomers'])->name('salesByCustomers');
	
	Route::get('invoices-by-stats', [HomeController::class, 'invoicesByStats'])->name('stats.invoices');
	Route::get('invoicesByStatshow', [HomeController::class, 'invoicesByStatshow'])->name('invoicesByStatshow');
	Route::get('pendinginvoicesshow', [HomeController::class, 'pendinginvoicesshow'])->name('pendinginvoicesshow');
	Route::get('clearedinvoicesshow', [HomeController::class, 'clearedinvoicesshow'])->name('clearedinvoicesshow');
    Route::get('allinvoicesshow', [HomeController::class, 'allinvoicesshow'])->name('allinvoicesshow');

	Route::get('pending-invoices', [HomeController::class, 'pendingInvoices'])->name('pending.invoices');
	Route::get('clear-invoices', [HomeController::class, 'clearInvoices'])->name('clear.invoices');
	Route::get('cashmemo-by-stats', [HomeController::class, 'cashmemoByStats'])->name('stats.cashmemo');
	Route::get('cashmemobystatsshow', [HomeController::class, 'cashmemobystatsshow'])->name('cashmemobystatsshow');
	
	Route::get('expenses', [HomeController::class, 'expenses'])->name('expenses');

	Route::get('/changePassword',  [UserController::class, 'changePassword'])->name('changePassword');
	Route::post('/updatePassword', [UserController::class, 'updatePassword'])->name('updatePassword');
	Route::get('/Profile',  [UserController::class, 'Profile'])->name('Profile');
	Route::post('/changeProfile', [UserController::class, 'changeProfile'])->name('changeProfile');

    Route::resource('User', UserController::class);
	Route::get('UserSearch', [UserController::class, 'UserSearch'])->name('UserSearch');
	
	Route::get('/backup', function () {
       return view('backup');
   });
  
    Route::resource('Employees', EmployeeController::class);
    Route::resource('Branch', BranchController::class);
    Route::resource('Supplier', SupplierController::class);
    Route::resource('Category', CategoryController::class);
	Route::post('update_expire_invoice/{id}', [CategoryController::class, 'update_expire_invoice'])->name('update_expire_invoice');
    Route::resource('Products', ProductController::class);
	Route::post('updateInventory', [ProductController::class, 'updateInventory'])->name('update.inventory');

    Route::resource('Customer', CustomerController::class);

	// Qoute
    Route::resource('Quotes', QouteController::class);
	Route::get('choose-template/{id}', [QouteController::class, 'chooseTemplate'])->name('choose-template');
	Route::get('TemplateOne/{id}', [QouteController::class, 'TemplateOne'])->name('TemplateOne');
	Route::get('TemplateTwo/{id}', [QouteController::class, 'TemplateTwo'])->name('TemplateTwo');
	Route::get('TemplateThree/{id}', [QouteController::class, 'TemplateThree'])->name('TemplateThree');
	Route::get('TemplateFour/{id}', [QouteController::class, 'TemplateFour'])->name('TemplateFour');
	
	Route::get('filter-quote', [QouteController::class, 'FilterQuote'])->name('filter-quote');
	Route::get('duplicate-quote/{id}', [QouteController::class, 'DuplicateQuote'])->name('duplicate-quote');
	
	Route::post('checkForPriceAjax', [QouteController::class, 'checkForPriceAjax'])->name('checkForPriceAjax');
	Route::post('checkForCapcityAjax', [QouteController::class, 'checkForCapcityAjax'])->name('checkForCapcityAjax');
	Route::post('loadDescription', [QouteController::class, 'loadDescription'])->name('loadDescription');
	
	Route::post('checkForCityAjax', [QouteController::class, 'checkForCityAjax'])->name('checkForCityAjax');
	Route::post('checkForAddressAjax', [QouteController::class, 'checkForAddressAjax'])->name('checkForAddressAjax');
	Route::post('checkForCustomerAjax', [QouteController::class, 'checkForCustomerAjax'])->name('checkForCustomerAjax');
	Route::post('checkForCustomerNTNAjax', [QouteController::class, 'checkForCustomerNTNAjax'])->name('checkForCustomerNTNAjax');

	Route::get('ConvertQuotation/{id}', [QouteController::class, 'ConvertQuotation'])->name('ConvertQuotation');
	Route::post('SaveConvertQuotation', [QouteController::class, 'SaveConvertQuotation'])->name('SaveConvertQuotation');

	Route::get('create-qoute', [QouteController::class, 'createQuote'])->name('create-qoute');
	Route::post('save-qoute', [QouteController::class, 'saveQuote'])->name('save-qoute');

	// Expense
    Route::resource('Expenses', ExpenseController::class);
    Route::get('/download-expenses/{year?}', [ExpenseController::class, 'downloadExpenses'])->name('downloadExpenses');
	Route::get('expensesdaterange', [ExpenseController::class, 'expensesdaterange'])->name('expensesdaterange'); ///{type}
	Route::get('expensereportshow/{id}', [ExpenseController::class, 'expensereportshow'])->name('expensereportshow');
	Route::get('allexpenseshow', [ExpenseController::class, 'allexpenseshow'])->name('allexpenseshow');



    Route::resource('ExpenseCategory', ExpenseCategoryController::class);
	// Invoice
    Route::resource('Invoice', InvoiceController::class);


	Route::post('ReplicateInvoice', [InvoiceController::class, 'ReplicateInvoice'])->name('ReplicateInvoice');
	Route::get('SalesTaxInvoice/{id}', [InvoiceController::class, 'SalesTaxInvoice'])->name('SalesTaxInvoice');
	Route::post('status/{id}', [InvoiceController::class, 'status'])->name('invoice.status');


	Route::get('expire', [InvoiceController::class, 'expire'])->name('expire');

	Route::get('invoices-by-product/{id}/{from_month}/{to_month}', [InvoiceController::class, 'invoicesByProduct'])->name('product.invoices');
	Route::get('cashmemos-by-product/{id}/{from_month}/{to_month}', [CashMemoController::class, 'cashmemosByProduct'])->name('product.cashmemos');
	
	Route::get('invoices-by-customer/{id}/{from_month}/{to_month}', [InvoiceController::class, 'invoicesByCustomer'])->name('customer.invoices');
	Route::get('cashmemos-by-customer/{id}/{from_month}/{to_month}', [CashMemoController::class, 'cashmemosByCustomer'])->name('customer.cashmemos');
	
	Route::get('invoices-by-category/{id}/{from_month}/{to_month}', [InvoiceController::class, 'invoicesByCategory'])->name('category.invoices');
	Route::get('cashmemos-by-category/{id}/{from_month}/{to_month}', [CashMemoController::class, 'cashmemosByCategory'])->name('category.cashmemos');

	Route::get('invoice-to-sales/{id}', [InvoiceController::class, 'InvoiceToSales'])->name('invoice-to-sales');
	
	Route::get('filter-invoice', [InvoiceController::class, 'FilterInvoice'])->name('filter-invoice');
	Route::get('duplicate-invoice/{id}', [InvoiceController::class, 'DuplicateInvoice'])->name('duplicate-invoice');
	

	Route::get('download-invoice/{id}', [InvoiceController::class, 'DownloadInvoice1'])->name('download-invoice'); //downloadInvoice

	Route::get('invoice-recieve-payment/{id}', [InvoiceController::class, 'invoiceRecievePayment'])->name('invoice.recieve.payment');
	Route::get('invoice-view-payment/{id}', [InvoiceController::class, 'invoiceviewPayment'])->name('invoice.view.payment');
	Route::post('recieveInvocePayment/{id}', [InvoiceController::class, 'recieveInvocePayment'])->name('recieveInvocePayment');
	Route::get('invoice-payments/{id}', [InvoiceController::class, 'invoicePayments'])->name('invoice.payments');
	Route::get('update-invoce-payment/{id}', [InvoiceController::class, 'invocePaymentUpdate'])->name('invocePayment.update');
	Route::post('UpdateInvocePayment/{id}', [InvoiceController::class, 'UpdateInvocePayment'])->name('UpdateInvocePayment');


    Route::resource('Report', ReportController::class);
 
	Route::get('salesReport', [ReportController::class, 'salesReport'])->name('salesReport');
	Route::get('SalesReportShow', [ReportController::class, 'SalesReportShow'])->name('SalesReportShow');
	Route::get('quotationReport', [ReportController::class, 'quotationReport'])->name('quotationReport');
	Route::get('supplierReport', [ReportController::class, 'supplierReport'])->name('supplierReport');
	Route::get('customerReport', [ReportController::class, 'customerReport'])->name('customerReport');
	Route::get('expenseReport', [ReportController::class, 'expenseReport'])->name('expenseReport');
	Route::get('expiryReport', [ReportController::class, 'expiryReport'])->name('expiryReport');

    Route::resource('Salary', SalaryController::class);
	Route::get('fill-salary/{id}', [SalaryController::class, 'fillSalary'])->name('fill-salary');
	

	// Download
	Route::get('downloadPayrol/{id}', [DownloadController::class, 'downloadPayrol'])->name('downloadPayrol');
	Route::get('downloadPayrol1/{id}', [DownloadController::class, 'downloadPayrol1'])->name('downloadPayrol1');
	Route::get('downloadInvoice/{id}', [DownloadController::class, 'downloadInvoice'])->name('downloadInvoice');
	Route::get('downloadSalesInvoice/{id}', [DownloadController::class, 'downloadSalesInvoice'])->name('downloadSalesInvoice');
	Route::get('downloadSalesInvoice2/{id}', [DownloadController::class, 'downloadSalesInvoice2'])->name('downloadSalesInvoice2');
	Route::get('downloadQuoteOne/{id}', [DownloadController::class, 'downloadQuoteOne'])->name('downloadQuoteOne');
	Route::get('downloadQuoteTwo/{id}', [DownloadController::class, 'downloadQuoteTwo'])->name('downloadQuoteTwo');
	Route::get('downloadclearedinvoiceshow1', [DownloadController::class, 'downloadclearedinvoiceshow1'])->name('downloadclearedinvoiceshow1');
	Route::get('downloadclearedinvoiceshow', [DownloadController::class, 'downloadclearedinvoiceshow'])->name('downloadclearedinvoiceshow');
    Route::get('downloadpendinginvoiceshow1', [DownloadController::class, 'downloadpendinginvoiceshow1'])->name('downloadpendinginvoiceshow1');
	Route::get('downloadpendinginvoiceshow', [DownloadController::class, 'downloadpendinginvoiceshow'])->name('downloadpendinginvoiceshow');
    Route::get('downloadcashsaleshow1', [DownloadController::class, 'downloadcashsaleshow1'])->name('downloadcashsaleshow1');
	Route::get('downloadcashsaleshow', [DownloadController::class, 'downloadcashsaleshow'])->name('downloadcashsaleshow');
	Route::get('downloadallinvoiceshow1', [DownloadController::class, 'downloadallinvoiceshow1'])->name('downloadallinvoiceshow1');
	Route::get('downloadallinvoiceshow', [DownloadController::class, 'downloadallinvoiceshow'])->name('downloadallinvoiceshow');
    Route::get('downloadallinvoice1', [DownloadController::class, 'downloadallinvoice1'])->name('downloadallinvoice1');
	Route::get('downloadallinvoice', [DownloadController::class, 'downloadallinvoice'])->name('downloadallinvoice');

	Route::get('downloadQuoteOnewithoutimage/{id}', [DownloadController::class, 'downloadQuoteOnewithoutimage'])->name('downloadQuoteOnewithoutimage');
	Route::get('downloadQuoteTwowithoutimage/{id}', [DownloadController::class, 'downloadQuoteTwowithoutimage'])->name('downloadQuoteTwowithoutimage');
	

	Route::get('downloadQuoteThree/{id}', [DownloadController::class, 'downloadQuoteThree'])->name('downloadQuoteThree');
	Route::get('downloadQuoteFour/{id}', [DownloadController::class, 'downloadQuoteFour'])->name('downloadQuoteFour');
	Route::get('IncommingChallanDownload/{id}', [DownloadController::class, 'IncommingChallanDownload'])->name('IncommingChallanDownload');
	Route::get('DeliveryChallanDownload/{id}', [DownloadController::class, 'DeliveryChallanDownload'])->name('DeliveryChallanDownload');
	Route::get('CashMemoDownload/{id}', [DownloadController::class, 'CashMemoDownload'])->name('CashMemoDownload');
	Route::get('downloadsalesreport', [DownloadController::class, 'downloadsalesreport'])->name('downloadsalesreport');
	Route::get('downloadsalesreport1', [DownloadController::class, 'downloadsalesreport1'])->name('downloadsalesreport1');
	
	Route::get('downloadInvoice2/{id}', [DownloadController::class, 'downloadInvoice2'])->name('downloadInvoice2');
	Route::get('downloadQuote2/{id}', [DownloadController::class, 'downloadQuote2'])->name('downloadQuote2');
	Route::get('IncommingChallanDownload2/{id}', [DownloadController::class, 'IncommingChallanDownload2'])->name('IncommingChallanDownload2');
	Route::get('DeliveryChallanDownload2/{id}', [DownloadController::class, 'DeliveryChallanDownload2'])->name('DeliveryChallanDownload2'); 
	Route::get('CashMemoDownload2/{id}', [DownloadController::class, 'CashMemoDownload2'])->name('CashMemoDownload2');
	
	
    Route::resource('TermsAndCondition', TermsAndConditionController::class);
    Route::resource('Accounts', AccountsController::class);

	Route::get('getAllAmount', [AccountsController::class, 'getAllAmount']);
	Route::get('VerifyPaymentNow/{id}', [AccountsController::class, 'VerifyPaymentNow'])->name('VerifyPaymentNow');
    
    // 	IncomingChallan
    Route::resource('IncomingChallan', IncomingChallanController::class);

	Route::post('IncomingChallanStore', [IncomingChallanController::class, 'IncomingChallanStore'])->name('IncomingChallanStore');
	Route::get('filter-incoming-challan', [IncomingChallanController::class, 'FilterIncomingChallan'])->name('filter-incoming-challan');
	Route::get('duplicate-incoming-challan/{id}', [IncomingChallanController::class, 'DuplicateIncomingChallan'])->name('duplicate-incoming-challan');
	

    // 	DeliveryChallan
    Route::resource('DeliveryChallan', DeliveryChallanController::class);
	Route::post('DeliveryChallanStore', [DeliveryChallanController::class, 'DeliveryChallanStore'])->name('DeliveryChallanStore');
	Route::get('filter-delivery-challan', [DeliveryChallanController::class, 'FilterDeliveryChallan'])->name('filter-delivery-challan');
	Route::get('duplicate-delivery-challan/{id}', [DeliveryChallanController::class, 'DuplicateDeliveryChallan'])->name('duplicate-delivery-challan');
	
    // 	CashMemo
    Route::resource('CashMemo', CashMemoController::class);
	Route::post('CashMemoStore', [CashMemoController::class, 'CashMemoStore'])->name('CashMemoStore');
	Route::get('filter-cash-memo', [CashMemoController::class, 'FilterCashMemo'])->name('filter-cash-memo');
	Route::get('duplicate-cashmemo/{id}', [CashMemoController::class, 'DuplicateCashMemo'])->name('duplicate-cashmemo');
	Route::get('cashmemoShow', [CashMemoController::class, 'cashmemoShow'])->name('cashmemoShow');

    Route::resource('GeneralTemplate', GeneralTemplateController::class);
	Route::get('general_template_with_header/{id}', [GeneralTemplateController::class, 'general_template_with_header'])->name('general_template_with_header');
	Route::get('general_template_without_header/{id}', [CashMemoController::class, 'general_template_without_header'])->name('general_template_without_header');

	
    Route::resource('EmailGeneralTemplate', EmailGeneralTemplateController::class);
    Route::resource('SupportQuotationTemplate', SupportQuotationTemplateController::class);

	Route::get('ExportCashMemo/{id}', [ExportController::class, 'ExportCashMemo'])->name('ExportCashMemo');
	Route::get('ChallanExport/{id}', [ExportController::class, 'ChallanExport'])->name('ChallanExport');
	Route::get('InvoiceExport/{id}', [ExportController::class, 'InvoiceExport'])->name('InvoiceExport');
	Route::get('QouteExport/{id}', [ExportController::class, 'QouteExport'])->name('QouteExport');
	Route::get('SalesreportExport/{id}', [ExportController::class, 'SalesreportExport'])->name('SalesreportExport');

	Route::get('exoprt-expense', [ExportController::class, 'exoprtexpense'])->name('exoprtexpense');
	Route::post('exoprtedexpense', [ExportController::class, 'exoprtedexpense'])->name('exoprtedexpense');


	Route::get('SafetyCareQouteExport/{id}', [ExportController::class, 'SafetyCareQouteExport'])->name('SafetyCareQouteExport');
	Route::get('ViqasEnterpriseQouteExport/{id}', [ExportController::class, 'ViqasEnterpriseQouteExport'])->name('ViqasEnterpriseQouteExport');
	Route::get('GeneralTemplateExport/{id}', [ExportController::class, 'GeneralTemplateExport'])->name('GeneralTemplateExport');

	/// SupportController
    Route::resource('SafetyCare', SafetyCareController::class);
	Route::get('safety_down_with_header/{id}', [SafetyCareController::class, 'safety_down_with_header'])->name('safety_down_with_header');
	Route::get('safety_down_without_header/{id}', [SafetyCareController::class, 'safety_down_without_header'])->name('safety_down_without_header');

    Route::resource('ViqasEnterprise', ViqasEnterpriseController::class);
	Route::get('Viqas_down_with_header/{id}', [ViqasEnterpriseController::class, 'Viqas_down_with_header'])->name('Viqas_down_with_header');
	Route::get('Viqas_down_without_header/{id}', [ViqasEnterpriseController::class, 'Viqas_down_without_header'])->name('Viqas_down_without_header');

	Route::get('ConvertToSupportQuote/{id}', [QouteController::class, 'ConvertToSupportQuote'])->name('ConvertToSupportQuote');
	Route::post('ConvertToSupportQuoteSave', [QouteController::class, 'ConvertToSupportQuoteSave'])->name('ConvertToSupportQuoteSave');
	
	
	Route::get('filter-safety-care', [SafetyCareController::class, 'FilterSafetyCare'])->name('filter-safety-care');
	Route::get('filter-viqas-enterprise', [ViqasEnterpriseController::class, 'FilterViqasEnterprise'])->name('filter-viqas-enterprise');
});