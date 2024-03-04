<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ScreenModel;
use Illuminate\Http\Request;

class ScreenModelController extends Controller
{


    public function index(Request $request)
    {
        if ($request->search) {
            $screenModels = ScreenModel::where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        } else {
            $screenModels = ScreenModel::orderBy('id', 'desc')->paginate(getPaginate());

        }
        $brands = Brand::where('type', 2)->orderBy('id', 'desc')->paginate(getPaginate());
        $pageTitle = 'موديلات الشاشات';

        return view('screenmodels.index', compact('pageTitle', 'screenModels', 'brands'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required'
        ]);
        ScreenModel::updateOrCreate(
            ['id' => $id],
            ['name' => $request->name, 'brand_id' => $request->brand_id]

        );
        return back()->with('success', 'تم حفظ البيانات');

    }

    public function delete($id)
    {
        $screenModel = ScreenModel::findOrFail($id);
        if ($screenModel->unlinked()) {
            $screenModel->delete();
            return back()->with('success', 'تم حفظ البيانات');
        }
        return back()->with('error', 'لا يمكن حذف البيانات الانها مرتبطة بعناصر اخرى');


    }

    public function getScreenModels(Request $request)
    {
        $model_id = $request->input('brand_id');
        $models = ScreenModel::where('brand_id', $model_id)->get();
        return response()->json($models);
    }
}
