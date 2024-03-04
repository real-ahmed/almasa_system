<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class WarehouseController extends Controller
{
    public function index(Request $request, $type)
    {
        $pageTitle = 'المخازن';

        if ($request->search) {
            $warehouses = Warehouse::where('type', $type)->where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        } else {
            $warehouses = Warehouse::where('type', $type)->orderBy('id', 'desc')->paginate(getPaginate());

        }

        return view('warehouse.index', compact('pageTitle', 'warehouses', 'type'));
    }


    public function model($type, $id = null)
    {
        $pageTitle = 'انشاء مخزن';
        $warehouse = null;

        if ($id) {
            $pageTitle = 'تعديل المخزن';
            $warehouse = Warehouse::findOrFail($id);

        }
        $employees = User::whereHas('employee')->orderBy('id', 'desc')->get();
        return view("warehouse.model", compact('pageTitle', 'employees', 'warehouse', 'type'));
    }


    public function save(Request $request, $type, $id = null)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create or update the warehouse
        $warehouse = $id ? Warehouse::findOrFail($id) : new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->type = $type;
        $warehouse->save();

        return redirect()->route('admin.warehouse.all', $type)->with('success', 'تم حفظ المخزن');
    }


    public function delete($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        if ($warehouse->unlinked()) {
            $warehouse->delete();
            return back()->with('success', 'تم حذف المخزن');
        }
        throw ValidationException::withMessages(['لا يمكن حذف المخزن بعد لاحتوائة على عناصر']);
    }

}
