<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request){
        if($request->search){
            $suppliers = Supplier::where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        }else{
            $suppliers = Supplier::orderBy('id','desc')->paginate(getPaginate());

        }
        $pageTitle = 'التجار';

        return view('supplier.index',compact('pageTitle','suppliers'));

    }


    public function save(Request $request,$id=0){
        $request->validate([
            'name'=>'required',
            'phone'=>'required'
        ]);
        Supplier::updateOrCreate(
            ['id'=>$id],
            ['name'=>$request->name,'phone'=>$request->phone]
        );
        return back()->with("success",'تم حفظ التاجر');
    }

    public function delete($id){
        $supplier = Supplier::findOrFail($id);
        if ($supplier->unlinked()) {
            $supplier->delete();
            return back()->with('success','تم حفظ البيانات');
        }
        throw ValidationException::withMessages(['لا يمكن حذف المورد بعناصر اخرى']);
    }
}
