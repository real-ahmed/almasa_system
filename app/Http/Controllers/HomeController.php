<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Customer;
use App\Models\Deduction;
use App\Models\InvoiceScreenComponent;
use App\Models\RepairDeliver;
use App\Models\RepairScreenComponent;
use App\Models\RepairScreenService;
use App\Models\SalaryPayment;
use App\Models\Screen;
use App\Models\ScreenComponent;
use DB;
use Illuminate\Contracts\Support\Renderable;
use function Laravel\Prompts\error;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */


    public function index()
    {


        if (auth()->user()->isadmin) {
            return $this->adminDashboard();
        } elseif (auth()->user()->isengineer) {
            return $this->engineerDashboard();
        } elseif (auth()->user()->isreceptionist) {
            return $this->receptionistDashboard();
        }elseif (auth()->user()->iswarehouseemployee){
            return $this->warehouseDashboard();
        }
    }

    private function adminDashboard()
    {
        $pageTitle = 'لوحة التحكم';

        $cards = array();

        $cards['ProductTotalDailySales'] =
            RepairScreenComponent::whereDate('created_at', now()->toDateString())->sum(DB::raw('price * quantity'))+
            InvoiceScreenComponent::whereDate('created_at', now()->toDateString())->sum(DB::raw('price * quantity'));


        $cards['ProductTotalMonthlySales'] =
            RepairScreenComponent::whereMonth('created_at', now()->month)->sum(DB::raw('price * quantity')) +
            InvoiceScreenComponent::whereMonth('created_at', now()->month)->sum(DB::raw('price * quantity'));

        $cards['ProductTotalDailyCount'] =
            RepairScreenComponent::whereDate('created_at', now()->toDateString())->count()+
            InvoiceScreenComponent::whereDate('created_at', now()->toDateString())->count();



        $cards['RepairTotalMonthlySales'] = RepairScreenService::whereMonth('created_at', now()->month)->sum('price');


        $cards['RepairTotalDailyCount'] = RepairScreenService::whereDate('created_at', now()->toDateString())->count();

        $cards['RepairTotalDailySales'] = RepairScreenService::whereDate('created_at', now()->toDateString())->sum('price');

        $cards['TotalCustomers'] = Customer::count();


        $salaryPayments = SalaryPayment::where('status', 0)->orderBy('id')->take(10)->get();

        return view('dashboard.admin', compact('pageTitle', 'salaryPayments', 'cards'));
    }

    private function engineerDashboard()
    {
//        $pageTitle = 'لوحة التحكم';
//
//        $cards = array();
//
//        $cards['TodayBonus'] = Bonus::whereDate('created_at', now()->toDateString())->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
//        $cards['TodayDeduction'] = Deduction::whereDate('created_at', now()->toDateString())->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
//
//        $cards['RepairTotalDailyCount'] = Screen::whereDate('created_at', now()->toDateString())->where('engineer_maintenance_id',auth()->user()->id)->count();
//
//        $cards['MonthlyBonus'] = Bonus::whereMonth('created_at', now()->month)->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
//        $cards['MonthlyDeduction'] = Deduction::whereMonth('created_at', now()->month)->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
//
//        $cards['RepairTotalMonthlyCount'] = Screen::whereDate('created_at', now()->month)->where('engineer_maintenance_id',auth()->user()->id)->count();
//
//        $screens = Screen::where('engineer_maintenance_id',auth()->user()->id)->where('status', 0)->orderBy('id')->take(10)->get();
        return error('cant access');

    }

    private function receptionistDashboard()
    {
        $pageTitle = 'لوحة التحكم';

        $cards = array();

        $cards['TodayBonus'] = Bonus::whereDate('created_at', now()->toDateString())->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
        $cards['TodayDeduction'] = Deduction::whereDate('created_at', now()->toDateString())->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');


        $cards['MonthlyBonus'] = Bonus::whereMonth('created_at', now()->month)->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
        $cards['MonthlyDeduction'] = Deduction::whereMonth('created_at', now()->month)->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');

        $cards['RepairDeliverTotalDailyCount'] = RepairDeliver::whereDate('created_at', now()->toDateString())->where('status',0)->count();
        $cards['RepairDeliverTotalMonthlyCount'] = RepairDeliver::whereDate('created_at', now()->month)->where('status',0)->count();

        $delivers = RepairDeliver::where('status', 0)->orderBy('id')->take(10)->get();
        return view('dashboard.receptionist', compact('pageTitle','delivers' ,'cards'));

    }



    private function warehouseDashboard()
    {
        $pageTitle = 'لوحة التحكم';

        $cards = array();

        $cards['TodayBonus'] = Bonus::whereDate('created_at', now()->toDateString())->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
        $cards['TodayDeduction'] = Deduction::whereDate('created_at', now()->toDateString())->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');


        $cards['MonthlyBonus'] = Bonus::whereMonth('created_at', now()->month)->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');
        $cards['MonthlyDeduction'] = Deduction::whereMonth('created_at', now()->month)->where('employee_id',auth()->user()->id)->where('status', 0)->sum('amount');

        $cards['TotalProduct'] = ScreenComponent::all()->count();
        $cards['RequestedItemsCount'] = RequestedItemsCount();


        $items = ScreenComponent::all();
        $requestedItems = $items->filter(function ($item) {
            return ($item->InstockQuantity <= $item->auto_request_quantity && $item->auto_request_quantity != null) || $item->manualItemRequest()->exists();
        });
        $components = $requestedItems->take(10);
        return view('dashboard.warehouse', compact('pageTitle','components' ,'cards'));

    }
}
