<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\BuyScreen;
use App\Models\Repair;
use App\Models\RepairDeliver;
use App\Models\Screen;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class ScreenController extends Controller
{


    public function index(Request $request)
    {
        $pageTitle = 'الشاشات';
        $screens = $this->buildScreenQuery($request)->orderBy('id', 'desc')->paginate(getPaginate());
        $engineers = User::where('role', 1)->get();
        $receptionists = User::where('role', 2)->get();
        $brands = Brand::where('type', 2)->get();
        $warehouses = Warehouse::where('type', 2)->get();
        return view('screen.index', compact('screens', 'pageTitle', 'engineers', 'brands', 'receptionists', 'warehouses'));
    }

    private function buildScreenQuery(Request $request)
    {
        $search = $request->input('search');
        $screensQuery = Screen::query();
        if ($search) {
            $screensQuery->where(function ($query) use ($search) {
                $query->whereHas('repairs.customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('repairs.receptionist', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('brand', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('warehouse', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('engineer_receive', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('repairs', function ($subQuery) use ($search) {
                        $subQuery->where('reference_number', 'like', '%' . $search . '%');
                    })->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }
        return $screensQuery;
    }

    public function forSaleScreens(Request $request)
    {
        $pageTitle = 'الشاشات البيع';
        $screens = $this->buildScreenQuery($request)->whereIn('status', [4, 5])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('screen.sale.index', compact('screens', 'pageTitle'));
    }

    public function buy(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
        ]);

        $screen = Screen::findOrFail($id);

        if ($screen->status != 1 && !auth()->user()->isadmin) {
            throw ValidationException::withMessages(['لا يمكن تعديل الشاشة ']);

        }

        if($screen->buy()->exists()){
            $buyScreen = BuyScreen::find($screen->buy->id);
            $buyScreen->price = $request->price;
            $buyScreen->save();
            return back()->with('success', 'تم تعديل السعر');
        }
        $buyScreen = new BuyScreen();
        $buyScreen->screen_id = $id;
        $buyScreen->price = $request->price;
        $buyScreen->save();
        $screen->status = 4;
        $screen->save();

        $repair = Repair::find($screen->repairs->first()->id);

        if ($repair->deliver()->exists()) {
            $deliver = RepairDeliver::find($repair->deliver->id);
            $deliver->setTotal();
            $deliver->save();

            if ($repair->screens()->whereNotIn('status', [4, 5])->doesntExist()) {
                $deliver->delete();
            }


        }


        return back()->with('success', 'تم التحويل للبيع');

    }

    public function save(Request $request, $id)
    {

        $request->validate([
            'serial' => 'required|string',
        ]);

        $screen = Screen::findOrFail($id);

        if ($screen->status != 0) {
            throw ValidationException::withMessages(['لا يمكن تعديل الشاشة بعد تغير حالتها ']);

        }

        $screen->brand_id = $request->input('brand_id');
        $screen->model = $request->input('model');
        $screen->engineer_receive_id = $request->input('engineer_receive');
        $screen->warehouse_id = $request->input('warehouse');
        $screen->serial = $request->input('serial');


        $screen->save();

        return back()->with('success', 'تم تحديث الشاشة');

    }

    public function delete($id)
    {
        $screen = Screen::findOrFail($id);
        if ($screen->unlinked()) {
            $screen->delete();
            return back()->with('success', 'تم حذف الشاشة');
        }
        throw ValidationException::withMessages(['لا يمكن حذف الشاشة بعد الصيانة او عند احتوائها على قطع غيار']);
    }

    public function returnBuyScreen(Request $request, $id)
    {
        $screen = Screen::findOrFail($id);

        if ($screen->status != 4) {
            throw ValidationException::withMessages(['لا يمكن إرجاع هذه الشاشة (تاكد انها ليست في فاتورة بيع)']);
        }

        $screen->buy->delete();

        $screen->status = 1;
        $screen->save();

        $repair = Repair::find($screen->repairs->first()->id);


        if ($repair->deliver()->exists()) {
            $deliver = RepairDeliver::find($repair->deliver->id);
            $deliver->setTotal();
            $deliver->save();

        } elseif ($repair->screens->where('status', 0)->first() == null) {
            $deliver = new RepairDeliver();
            $deliver->repair_id = $repair->id;
            $deliver->reference_number = 'DEL' . date('Ymd') . strtoupper(substr(uniqid(), 8));
            $deliver->setTotal();
            $deliver->save();
        }


        return back()->with('success', 'تم إرجاع الشاشة بنجاح');
    }

}
