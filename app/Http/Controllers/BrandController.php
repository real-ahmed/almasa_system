<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ScreenModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    public function index(Request $request,$type){
        if($request->search){
            $brands = Brand::where('type',$type)->where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        }else{
            $brands = Brand::where('type',$type)->orderBy('id','desc')->paginate(getPaginate());

        }
        $pageTitle = 'البراندات';

        return view('brand.index',compact('pageTitle','brands','type'));
    }


    public function save(Request $request,$type,$id=null){
        $request->validate([
            'name'=> 'required'
        ]);
        Brand::updateOrCreate(
            ['id'=>$id],
            ['name'=>$request->name,'type'=> $type]


        );

        return back()->with('success','تم حفظ البيانات');

    }

    public function delete($id)
    {
        $category = Brand::findOrFail($id);
        if ($category->unlinked()) {
            $category->delete();
            return back()->with('success','تم حذف البراند');
        }
        throw ValidationException::withMessages(['لا يمكن حذف البراند بعناصر اخرى']);

    }




}
