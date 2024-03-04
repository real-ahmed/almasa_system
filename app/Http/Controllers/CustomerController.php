<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;


class CustomerController extends Controller
{

    public function index(Request $request){
        $pageTitle = "العملاء";
        $search = $request->input('search');
        $repairQuery = Customer::query();

        if ($search) {
            $repairQuery->where('name','like','%'.$search.'%')
            ->orWhere('phone','like','%'.$search.'%')
            ->orWhere('address','like','%'.$search.'%');
        }
        $customers = $repairQuery->orderBy('id', 'desc')->paginate(getPaginate());
        return view('customer.index',compact('pageTitle','customers'));
    }

    public function save($id,Request $request){
        $request->validate([
            'name' => 'required',
            'phone' => 'required|string|max:12',
            'address' => 'required|string|max:255',
        ]);
        Customer::find($id)->update(['name' => $request->name,'address' => $request->address, 'phone' => $request->phone]);
        return back()->with('success', 'تم تحديث البيانات');
    }
    public function getInfo(Request $request)
    {
        $phone = $request->input('phone');
        try{
            $customer = Customer::where('phone', $phone)->first();
            $name = $customer->name;
            $address = $customer->address;
        }catch (\Exception $e){
            $name = '';
            $address='';
        }

        return response()->json(['name' => $name, 'address' => $address]);
    }
}
