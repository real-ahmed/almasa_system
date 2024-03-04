<?php

namespace App\Http\Controllers;

use App\Models\RepairType;
use Illuminate\Http\Request;

class RepairTypeController extends Controller
{
    public function index(Request $request){
        if($request->search){
            $types = RepairType::where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        }else{
            $types = RepairType::orderBy('id','desc')->paginate(getPaginate());

        }
        $pageTitle = 'انواع الصيانة';

        return view('repairtypes.index',compact('pageTitle','types'));

    }


    public function save(Request $request,$id =0){
        $request->validate([
            'name' => 'required',
            'default_bonus' => 'required|numeric',
            'bonus_type' => 'required',
            'price' => 'required',

        ]);
        $type = new RepairType();
        if ($id){
            $type= RepairType::findOrFail($id);
        }

        $type->name = $request->name;
        $type->default_bonus = $request->default_bonus;
        $type->bonus_type = $request->bonus_type;
        $type->price = $request->price;
        $type->save();
        return back()->with('success','تم حفظ البيانات');

    }


    public function delete(){

    }

}
