<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ScreenComponent;
use App\Models\StoragePartition;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StoragePartitionController extends Controller
{
    public function index(Request $request){
        if($request->search){
            $partitions = StoragePartition::where('name', 'like', '%' . $request->search . '%')->paginate(getPaginate());

        }else{
            $partitions = StoragePartition::paginate(getPaginate());

        }
        $pageTitle = 'اقسام قطع الغيار';
        $screenComponentCount = ScreenComponent::count();
        return view('storage_partition.index',compact('pageTitle','partitions','screenComponentCount'));
    }

    public function save(Request $request,$id= 0){
        $request->validate([
            'name'=> 'required'
        ]);
        StoragePartition::updateOrCreate(
            ['id'=>$id],
            ['name'=>$request->name]
        );

        return back()->with('success','تم حفظ البيانات');
    }

    public function delete(Request $request,$id){
        $partitions = StoragePartition::findOrFail($id);
        if ($partitions->unlinked()) {
            $partitions->delete();
            return back()->with('success','تم حذف القسم');
        }
        throw ValidationException::withMessages(['لا يمكن حذف القسم الانه مرتبط بعناصر اخرى']);
    }
}
