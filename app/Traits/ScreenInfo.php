<?php
namespace App\Traits;

use App\Models\Screen;
use Illuminate\Http\Request;

trait ScreenInfo{

    public function index(Request $request)
    {
        $pageTitle = 'الشاشات';

        $search = $request->input('search');

        $screensQuery = Screen::query();
        if ($search) {
            $screensQuery->where(function ($query) use ($search) {
                $query->whereHas('repairs.customer', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('repairs.receptionist', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('brand', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('warehouse', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('engineer_receive', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('repairs', function ($subQuery) use ($search) {
                        $subQuery->where('reference_number', 'like', '%' . $search . '%');
                    })->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%')
                    ->orWhereDate('created_at', 'like', '%' . $search . '%');
            });
        }
        $screens = $screensQuery->orderBy('id', 'desc')->paginate(getPaginate());

        return view('screen.index', compact('screens', 'pageTitle'));
    }

}
