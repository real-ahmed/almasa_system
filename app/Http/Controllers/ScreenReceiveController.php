<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Repair;
use App\Models\Screen;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class ScreenReceiveController extends Controller
{


    public function index(Request $request)
    {
        $pageTitle = 'فواتير الاستلام';
        $search = $request->input('search');
        $repairQuery = Repair::query();

        if ($search) {
            $repairQuery->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('receptionist', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('screens', function ($subQuery) use ($search) {
                        $subQuery->whereHas('engineer_receive', function ($subSubQuery) use ($search) {
                            $subSubQuery->where('name', 'like', '%' . $search . '%');
                        });

                    })
                    ->orWhere('reference_number', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }


        $repairs = $repairQuery->orderBy('id', 'desc')->paginate(getPaginate());


        return view('screen.receive.index', compact('pageTitle', 'repairs'));
    }

    public function create($id = null)
    {
        $pageTitle = 'فاتورة استلام';
        $repair = null;
        if ($id)
            $repair = Repair::findOrFail($id);
        $engineers = User::where('role', 1)->get();
        $receptionists = User::where('role', 2)->get();
        $brands = Brand::where('type', 2)->get();
        $warehouses = Warehouse::where('type', 2)->get();
        return view('screen.receive.model', compact('repair', 'pageTitle', "engineers", 'brands', 'warehouses', 'receptionists'));
    }


    public function save(Request $request, $repairId = null)
    {



        $request->validate([
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
            'receptionist_id' => 'required',
            'paid' => 'nullable|numeric|min:0',

            'receive_date' => 'required|date_format:m/d/Y',
            'expected_delivery_date' => 'required|date_format:m/d/Y',
            'repair_screens.*.screen_code' => 'required|string',
            'repair_screens.*.serial' => 'required|string',
            'repair_screens.*.issue' => 'required|string',

            'repair_screens.*.screen_brand' => 'required|integer',
            'repair_screens.*.screen_model' => 'required',
            'repair_screens.*.screen_engineer_receive' => 'required|integer',
            'repair_screens.*.screen_warehouse' => 'required|integer',
        ]);



        $customer = Customer::updateOrCreate(
            ['phone' => $request->input('customer_phone')],
            [
                'name' => $request->input('customer_name'),
                'address' => $request->input('customer_address')
            ]


        );


        if (!$repairId) {
            $referenceNumber = 'REC' . date('Ymd') . strtoupper(substr(uniqid(), 8));
        }else{
            $referenceNumber = Repair::findOrFail($repairId)->reference_number;

        }

        // $receptionist = auth()->user()->isadmin ? $request->input('receptionist_id') : auth()->user()->id;
        $receptionist =  $request->input('receptionist_id') ;


        $repair = Repair::updateOrCreate(
            ['id' => $repairId],
            [
                'customer_id' => $customer->id,
                'receptionist_id' => $receptionist, // Make sure 'receptionist_id' is provided
                'reference_number' => $referenceNumber,
                'paid' => $request->paid,
                'receive_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->input('receive_date')),
                'expected_delivery_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->input('expected_delivery_date')),
            ]
        );



        $savedScreenIds = [];
        foreach ($request->input('repair_screens') as $screenData) {
            $screen = Screen::updateOrCreate(
                ['code' => $screenData['screen_code']],
                [
                    'brand_id' => $screenData['screen_brand'],
                    'model' => $screenData['screen_model'],
                    'serial' => $screenData['serial'],
                    'issue' => $screenData['issue'],
                    'engineer_receive_id' => $screenData['screen_engineer_receive'],
                    'warehouse_id' => $screenData['screen_warehouse'],
                ]
            );

            $savedScreenIds[] = $screen->id;

        }

        $existingScreenIds = $repair->screens->pluck('id')->toArray();
        $screensToDelete = array_diff($existingScreenIds, $savedScreenIds);

        if (!empty($screensToDelete)) {
            Screen::whereIn('id', $screensToDelete)->delete();
        }



        $repair->screens()->sync($savedScreenIds);

        return redirect()->route('receptionist.screen.receive.details', $repair->id)->with('success', 'تم حفظ الفاتورة');
    }





    public function details($id)
    {

        $repair = Repair::findOrFail($id);
        $pageTitle = 'وصل استلام الصيانة  ' . $repair->reference_number;
        $printReceive = View::make('printer.invoices.receive', compact('repair'))->render();
        $printScreensCodes = View::make('printer.screensCodes', compact('repair'))->render();
        return view('screen.receive.details', compact('repair', 'pageTitle', 'printReceive', 'printScreensCodes'));
    }



    public function delete($id)
    {
        $repair = Repair::findOrFail($id);
        if ($repair->unlinked()) {
            $repair->delete();
            return back()->with('success','تم حذف الفئة');
        }
        throw ValidationException::withMessages(['لا يمكن حذف الفئة بعناصر اخرى']);
    }
}
