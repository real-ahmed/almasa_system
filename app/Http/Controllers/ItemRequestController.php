<?php

namespace App\Http\Controllers;

use App\Models\ManualItemRequest;
use App\Models\ScreenComponent;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ItemRequestController extends Controller
{

    public function index(Request $request)
    {
        $pageTitle = 'طلبات قطع الغيار';
        $search = $request->input('search');

        $items = ScreenComponent::where('name', 'like', '%'.$search.'%')
            ->orWhere('code', 'like', '%'.$search.'%')
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('subcategory', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('manualItemRequest.employee', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orderBy('id','desc')
            ->get(); // Fetch all records from the database

        // Filter the items using PHP after retrieving them from the database
        $requestedItems = $items->filter(function ($item) {
            return ($item->InstockQuantity <= $item->auto_request_quantity && $item->auto_request_quantity != null ) || $item->manualItemRequest()->exists();
        });

        // Paginate the filtered items manually
        $page = request()->get('page', 1);
        $perPage = getPaginate();

        $components = new LengthAwarePaginator(
            $requestedItems->forPage($page, $perPage),
            $requestedItems->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $products = ScreenComponent::all();

        return view('component.request.index', compact('pageTitle', 'components','products'));
    }



    public function save(Request $request,$id=null){
        $this->validate($request,[
            'product_id'=>'required|exists:screen_components,id',
            'request_quantity'=>'required|numeric|min:1'
        ]);

        // Check if the component_id exists in any row of ManualItemRequest
        $existingRequest = ManualItemRequest::where('component_id', $request->product_id)->exists();

        if ($existingRequest && !$id) {
            throw ValidationException::withMessages(['لا يمكن اشاء طلب الان المنتج مطلوب بالفعل']);
        }

        ManualItemRequest::updateOrCreate(
            ['id'=>$id],
            [
                'component_id'=>$request->product_id,
                'requested_quantity'=>$request->request_quantity,
                'employee_id'=>$request->employee_id??auth()->user()->id,

            ]
        );
        return back()->with('success', 'تم حفظ الطلب بنجاح');

    }
    public function delete($id=null){
        ManualItemRequest::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الطلب بنجاح');
    }

}
