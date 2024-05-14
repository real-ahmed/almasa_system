<?php

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


Auth::routes(["register" => false, "reset" => false]);


Route::middleware(['auth'])->group(function () {
    Route::namespace('App\Http\Controllers')->group(function () {

        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


        Route::controller("UserController")->name("user.")->group(function () {
            Route::get("profile-setting", "setting")->name("setting");
            Route::post("save-profile-setting/{id}", "updateAccountInfo")->name("update");
            Route::post("save-password-setting/{id}", "updatePassword")->name("update.password");

        });


        Route::controller('BonusController')->name('bonus.')->group(function () {
            Route::get('bonus', 'index')->name('all');
        });

        Route::controller('DeductionController')->name('deduction.')->group(function () {
            Route::get('deduction', 'index')->name('all');
        });

        Route::controller('SalaryPaymentController')->name('salary.')->group(function () {
            Route::get('salary/{type?}', 'index')->name('all');
        });

        Route::controller("ScreenComponentController")->name("component.")->group(function () {
            Route::get('component/{StoragePartition?}', 'index')->name('all');
        });


        Route::controller("StoragePartitionController")->name("partition.")->group(function () {
            Route::get('partition', 'index')->name('all');
        });


        Route::middleware(['admin'])->name("admin.")->group(function () {
//            Route::controller('GeneralSettingController')->name("setting.")->group(function () {
//                Route::get('general/setting', 'index')->name('general');
//                Route::post("general/setting/save", 'update')->name("general.save");
//                Route::post("general/logoIcon/save", 'logoIconUpdate')->name("logoIcon.save");
//
//            });

            Route::controller("RepairTypeController")->name("repair.type.")->group(function () {
                Route::get('repair/type', 'index')->name('all');
                Route::post("repair/type/save/{id?}", 'save')->name('save');
            });


            Route::controller("ScreenComponentController")->name("component.")->group(function () {
                Route::get('component/delete/{id?}', 'delete')->name('delete');


            });

            Route::controller("StoragePartitionController")->name("partition.")->group(function () {
                Route::get('partition/delete/{id?}', 'delete')->name('delete');
            });

//            Route::controller("BrandController")->name("brand.")->group(function () {
//                Route::get('brand/{type}', 'index')->name('all');
//                Route::post('brand/{type}/save/{id?}', 'save')->name('save');
//                Route::get('brand/delete/{id?}', 'delete')->name('delete');
//            });


//            Route::controller("ScreenModelController")->name("screen.model.")->group(function () {
//                Route::get('screen/model', 'index')->name('all');
//                Route::post('screen/model/save/{id?}', 'save')->name('save');
//                Route::get('screen/model/delete/{id?}', 'delete')->name('delete');
//                Route::get('screen/model/get-models/', 'getScreenModels')->name('getModels');
//            });


//            Route::controller("CategoryController")->name("category.")->group(function () {
//                Route::get('category', 'index')->name('all');
//                Route::post('category/save/{id?}', 'save')->name('save');
//                Route::get('category/delete/{id?}', 'delete')->name('delete');
//
//
//            });


//            Route::controller("SubcategoryController")->name("subcategory.")->group(function () {
//                Route::get('subcategory', 'index')->name('all');
//                Route::post('subcategory/save/{id?}', 'save')->name('save');
//                Route::get('subcategory/delete/{id?}', 'delete')->name('delete');
//                Route::get('category/get-subcategories/', 'getSubcategories')->name('getSubcategories');
//
//            });


//            Route::controller("ScreenComponentController")->name("component.")->group(function () {
//                Route::post('component/save/{id?}', 'save')->name('save');
//                Route::get('component/delete/{id?}', 'delete')->name('delete');
//
//
//            });
            Route::controller("WarehouseController")->name("warehouse.")->group(function () {
                Route::get('warehouse/{type}', 'index')->name('all');
                Route::get('warehouse/{type}/model/{id?}', 'model')->name('model');
                Route::post('warehouse/{type}/save/{id?}', 'save')->name('save');
                Route::get('warehouse/delete/{id?}', 'delete')->name('delete');


            });
            Route::controller("SupplierController")->name("supplier.")->group(function () {
                Route::get('supplier', 'index')->name('all');
                Route::post('supplier/save/{id?}', 'save')->name('save');
                Route::get('supplier/delete/{id?}', 'delete')->name('delete');

            });
//            Route::controller("PurchaseController")->name("purchase.")->group(function () {
//                Route::get('purchase', 'index')->name('all');
//                Route::get('purchase/model/{id?}', 'model')->name('model');
//                Route::post('purchase/save/{id?}', 'save')->name('save');
//                Route::get('purchase/delete/{id?}', 'delete')->name('delete');
//                Route::get('purchase/invoice/{id?}', 'getInvoice')->name('invoice');
//                Route::get('purchase/invoice/print/{id?}', 'printInvoice')->name('print');
//
//
//            });

//            Route::controller("EmployeeController")->name("employee.")->group(function () {
//                Route::get('employee', "index")->name("show.all");
//                Route::post("employee/save", "createEmployee")->name("create");
//                Route::post("employee/salary/set/{id}", "updateSalary")->name("update.salary");
//                Route::post("employee/repairtypes-bonus/save/{id}", "updateRepairBonus")->name("update.repairtypes.bonus");
//
//
//            });


//            Route::controller('BonusController')->name('bonus.')->group(function () {
//                Route::post('bonus/save/{id?}', 'save')->name('save');
//                Route::get('bonus/delete/{id?}', 'delete')->name('delete');
//            });

//            Route::controller('DeductionController')->name('deduction.')->group(function () {
//                Route::post('deduction/save/{id?}', 'save')->name('save');
//                Route::get('deduction/delete/{id?}', 'delete')->name('delete');
//            });


            Route::controller('SalaryPaymentController')->name('salary.')->group(function () {
                Route::post('salary/save/{id?}', 'save')->name('save');
            });


//            Route::controller("ScreenController")->name("screen.")->group(function () {
//                Route::get("screens", "index")->name("all");
//
//            });


            Route::controller("ScreenReceiveController")->name("screen.receive.")->group(function () {
                Route::get("screen/receive/delete/{id?}", "delete")->name("delete");

            });


//            Route::controller("UserController")->name("user.")->group(function () {
//                Route::get("profile-setting/{id}", "setting")->name("setting");
//
//            });

        });


        Route::middleware(['receptionist'])->name("receptionist.")->group(function () {


            Route::controller("AttendanceController")->name("attendance.")->group(function () {
                Route::get("attendance/requests/arrival/", "arrivalRequests")->name("request.arrival");
                Route::get("attendance/requests/departure/", "departureRequests")->name("request.departure");
                Route::get("attendance/departure/history", "recordHistory")->name("history");
                Route::post("attendance/save/{id?}", "save")->name("save");

            });


            Route::controller("AdvancePaymentController")->name("user.advance.payment.")->group(function () {
                Route::get("advance/payment/{employeeId}", "index")->name("all");
                Route::post("advance/payment/save/{id?}", "save")->name("save");
                Route::get("advance/payment/delete/{id?}", "delete")->name("delete");

            });

            Route::controller("UserController")->name("user.")->group(function () {
                Route::get("profile-setting/{id}", "setting")->name("setting");

            });

            Route::controller('GeneralSettingController')->name("setting.")->group(function () {
                Route::get('general/setting', 'index')->name('general');
                Route::post("general/setting/save", 'update')->name("general.save");
                Route::post("general/logoIcon/save", 'logoIconUpdate')->name("logoIcon.save");

            });

            Route::controller("ScreenModelController")->name("screen.model.")->group(function () {
                Route::get('screen/model', 'index')->name('all');
                Route::post('screen/model/save/{id?}', 'save')->name('save');
                Route::get('screen/model/delete/{id?}', 'delete')->name('delete');
                Route::get('screen/model/get-models/', 'getScreenModels')->name('getModels');
            });

            Route::controller("EmployeeController")->name("employee.")->group(function () {
                Route::get('employee/{type}', "index")->name("show.all");
                Route::post("employee/save", "createEmployee")->name("create");
                Route::post("employee/salary/set/{id}", "updateSalary")->name("update.salary");
                Route::post("employee/repairtypes-bonus/save/{id}", "updateRepairBonus")->name("update.repairtypes.bonus");


            });


            Route::controller('BonusController')->name('bonus.')->group(function () {
                Route::post('bonus/save/{id?}', 'save')->name('save');
                Route::get('bonus/delete/{id?}', 'delete')->name('delete');
            });


            Route::controller('DeductionController')->name('deduction.')->group(function () {
                Route::post('deduction/save/{id?}', 'save')->name('save');
                Route::get('deduction/delete/{id?}', 'delete')->name('delete');
            });

            Route::controller("ScreenModelController")->name("screen.model.")->group(function () {
                Route::get('screen/model/get-models/', 'getScreenModels')->name('getModels');
            });

            Route::controller("ScreenController")->name("screen.")->group(function () {
                Route::get("screens", "index")->name("all");
                Route::get("screen/sale/all", "forSaleScreens")->name("sale.all");
                Route::get("screen/delete/{id?}", "delete")->name("delete");
                Route::post("screen/save/{id?}", "save")->name("save");
                Route::post("screen/buy/{id?}", "buy")->name("buy");
                Route::get("screen/buy/return/{id?}", "returnBuyScreen")->name("buy.return");
            });

            Route::controller("ScreenSaleInvoiceController")->name("sale.screen.")->group(function () {
                Route::get("sale/screens/", "index")->name("all");
                Route::get('sale/screens/model/{id?}', 'model')->name('model');
                Route::post('sale/screens/save/{id?}', 'save')->name('save');
                Route::get('sale/screens/details/{id?}', 'details')->name('details');
                Route::get('sale/screens/delete/{id?}', 'delete')->name('delete');
            });

            Route::controller("CustomerController")->name("customer.")->group(function () {
                Route::get('customer/get/Info/', 'getInfo')->name('get.info');
                Route::get('customer/', 'index')->name('all');
                Route::post('customer/save/{id?}', 'save')->name('save');

            });


            Route::controller("ScreenReceiveController")->name("screen.receive.")->group(function () {
                Route::get("screen/receive", "index")->name("all");
                Route::get("screen/receive/model/{id?}", "create")->name("model");
                Route::post("screen/receive/save/{id?}", "save")->name("save");
                Route::get("screen/receive/details/{id?}", "details")->name("details");

            });


            Route::controller("RepairDeliverController")->name("repair.deliver.")->group(function () {
                Route::get("repair/deliver/{type?}", "index")->name("all");
                Route::get("repair/deliver/details/{id?}", "details")->name("details");
                Route::get("repair/deliver/save/{id}", "save")->name("save");
                Route::post("repair/deliver/paid/save/{id}", "paid")->name("paid.save");
                Route::post("repair/deliver/note/save/{id}", "saveNote")->name("note.save");

            });


            Route::controller("SaleInvoiceController")->name("sale.")->group(function () {
                Route::get("sales/", "index")->name("all");
                Route::get('sale/model/{id?}', 'model')->name('model');
                Route::post('sale/save/{id?}', 'save')->name('save');
                Route::get('sale/details/{id?}', 'details')->name('details');
                Route::get('sale/delete/{id?}', 'delete')->name('delete');
                Route::get('sale/invoice/print/{id?}', 'printInvoice')->name('print');
            });


            Route::controller('ExpenseController')->name('expense.')->group(function () {
                Route::get('expenses/', 'index')->name('all');
                Route::post('expenses/save/{id?}', 'save')->name('save');
                Route::get('expenses/delete/{id?}', 'delete')->name('delete');
            });


        });


        Route::middleware(['warehouse.employee'])->name("warehouse.employee.")->group(function () {

            Route::controller("CategoryController")->name("category.")->group(function () {
                Route::get('category', 'index')->name('all');
                Route::post('category/save/{id?}', 'save')->name('save');
                Route::get('category/delete/{id?}', 'delete')->name('delete');


            });

            Route::controller("SubcategoryController")->name("subcategory.")->group(function () {
                Route::get('subcategory', 'index')->name('all');
                Route::post('subcategory/save/{id?}', 'save')->name('save');
                Route::get('subcategory/delete/{id?}', 'delete')->name('delete');
                Route::get('category/get-subcategories/', 'getSubcategories')->name('getSubcategories');

            });

            Route::controller("PurchaseController")->name("purchase.")->group(function () {
                Route::get('purchase', 'index')->name('all');
                Route::get('purchase/model/{id?}', 'model')->name('model');
                Route::post('purchase/save/{id?}', 'save')->name('save');
                Route::get('purchase/delete/{id?}', 'delete')->name('delete');
                Route::get('purchase/invoice/{id?}', 'getInvoice')->name('invoice');
                Route::get('purchase/invoice/print/{id?}', 'printInvoice')->name('print');


            });
            Route::controller("SupplierController")->name("supplier.")->group(function () {
                Route::get('supplier', 'index')->name('all');
                Route::post('supplier/save/{id?}', 'save')->name('save');
                Route::get('supplier/delete/{id?}', 'delete')->name('delete');

            });


        });


        Route::middleware(['repair'])->name("repair.")->group(function () {

            Route::controller('RepairRequestController')->name('request.')->group(function () {
                Route::get('repair/requests/{type?}', 'index')->name('all');
                Route::get('repair/requests/model/{id?}', 'model')->name('model');
                Route::post('repair/requests/save/{id?}', 'save')->name('save');
                Route::get('repair/requests/details/{id?}', 'details')->name('details');

            });

        });

        Route::middleware(['item.request'])->name("item.")->group(function () {

            Route::controller('ItemRequestController')->name('request.')->group(function () {
                Route::get('item/request/', 'index')->name('all');
                Route::post('item/request/save/{id?}', 'save')->name('save');
                Route::get('item/request/delete/{id?}', 'delete')->name('delete');


            });
            Route::controller("ScreenComponentController")->name("component.")->group(function () {
                Route::post('component/save/{id?}', 'save')->name('save');
                Route::get('component/print', 'printAll')->name('print');
            });

            Route::controller("StoragePartitionController")->name("partition.")->group(function () {
                Route::post('partition/save/{id?}', 'save')->name('save');
            });


            Route::controller("BrandController")->name("brand.")->group(function () {
                Route::get('brand/{type}', 'index')->name('all');
                Route::post('brand/{type}/save/{id?}', 'save')->name('save');
                Route::get('brand/delete/{id?}', 'delete')->name('delete');
            });

        });


    });
});








