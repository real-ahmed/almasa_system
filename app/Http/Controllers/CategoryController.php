<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\RepairType;
use App\Models\ScreenModel;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{




    public function index(Request $request){
        if($request->search){
            $categories = Category::where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        }else{
            $categories = Category::orderBy('id','desc')->paginate(getPaginate());

        }
        $pageTitle = 'انواع قطع الغيار';

        return view('category.index',compact('pageTitle','categories'));
    }


    public function save(Request $request,$id=0){
        $request->validate([
            'name'=> 'required'
        ]);
        Category::updateOrCreate(
            ['id'=>$id],
            ['name'=>$request->name]
        );

        return back()->with('success','تم حفظ البيانات');

    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        if ($category->unlinked()) {
            $category->delete();
            return back()->with('success','تم حذف الفئة');
        }
        throw ValidationException::withMessages(['لا يمكن حذف الفئة بعناصر اخرى']);
    }



    public function getSubcategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }


    public function getModels(Request $request)
    {
        $model_id = $request->input('brand_id');
        $models = ScreenModel::where('brand_id', $model_id)->get();
        return response()->json($models);
    }

}
