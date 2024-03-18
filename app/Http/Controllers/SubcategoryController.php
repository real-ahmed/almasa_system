<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Request $request){
        if($request->search){
            $subcategories = Subcategory::where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        }else{
            $subcategories = Subcategory::orderBy('id','desc')->paginate(getPaginate());

        }
        $categories = Category::orderBy('id','desc')->get();
        $pageTitle = 'الانواع الفرعية لقطع الغيار';

        return view('subcategory.index',compact('pageTitle','subcategories','categories'));
    }
    public function save(Request $request,$id=0){
        $request->validate([
            'name'=> 'required',
            'category_id'=> 'required'
        ]);
        Subcategory::updateOrCreate(
            ['id'=>$id],
            ['name'=>$request->name ,'category_id'=>$request->category_id]

        );
        return back()->with('success','تم حفظ البيانات');

    }

    public function delete($id)
    {
        $category = Subcategory::findOrFail($id);
        if ($category->unlinked()) {
            $category->delete();
            return back()->with('success', 'تم حفظ البيانات');
        }
        return back()->with('error','لا يمكن حذف البيانات الانها مرتبطة بعناصر اخرى');


    }


    public function getSubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }
}
