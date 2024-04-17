<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ScreenComponent;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScreenComponentController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'قطع الغيار';
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $brandId = $request->input('brand_id');
        $subcategoryId = $request->input('subcategory_id');

        // Fetch components query
        $componentsQuery = ScreenComponent::orderBy('id', 'desc');

        // Apply search filter
        if ($search) {
            $componentsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('subcategory', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Apply category filter
        if ($categoryId) {
            $componentsQuery->whereHas('subcategory', function ($query) use ($categoryId) {
                $query->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });
            });
        }

        // Apply brand filter
        if ($brandId) {
            $componentsQuery->where('brand_id', $brandId);
        }

        // Apply subcategory filter
        if ($subcategoryId) {
            $componentsQuery->where('subcategory_id', $subcategoryId);
        }

        // Paginate components
        $components = $componentsQuery->paginate(getPaginate());

        // Fetch categories
        $categories = Category::orderBy('id', 'desc')->get();

        // Fetch subcategories for the selected category
        $subcategories = collect();
        if ($categoryId) {
            $subcategories = Subcategory::where('category_id', $categoryId)->orderBy('id', 'desc')->get();
        }

        // Fetch brands
        $brands = Brand::where('type', 1)->orderBy('id', 'desc')->get();

        return view('component.index', compact('pageTitle', 'components', 'categories', 'subcategories', 'brands'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric',
            'auto_request_quantity' => 'nullable|numeric',
        ]);


        ScreenComponent::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request->name,
                'code' => $request->code,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'auto_request_quantity' => $request->auto_request_quantity,
                'brand_id' => $request->brand_id,
                'selling_price' => $request->selling_price
            ]
        );

        return back()->with('success', 'تم حفظ البيانات');


    }


    public function delete($id)
    {
        $component = ScreenComponent::findOrFail($id);
        if ($component->unlinked()) {
            $component->delete();
            return back()->with('success', 'تم حذف المنتج');
        }
        throw ValidationException::withMessages(['لا يمكن حذف المنتج الارتباطه بعناصر اخرى']);

    }


    public function printAll(Request $request)
    {
        $categoryId = $request->input('category_id');
        $brandId = $request->input('brand_id');
        $subcategoryId = $request->input('subcategory_id');

        // Fetch components query
        $componentsQuery = ScreenComponent::orderBy('id', 'desc');

        // Apply category filter
        if ($categoryId) {
            $componentsQuery->whereHas('subcategory', function ($query) use ($categoryId) {
                $query->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });
            });
        }

        // Apply brand filter
        if ($brandId) {
            $componentsQuery->where('brand_id', $brandId);
        }

        // Apply subcategory filter
        if ($subcategoryId) {
            $componentsQuery->where('subcategory_id', $subcategoryId);
        }

        // Fetch products
        $products = $componentsQuery->get();

        // Render the view content
        $content = \Illuminate\Support\Facades\View::make('printer.products', compact('products'))->render();

        return response()->json(['content' => $content]);
    }

}
