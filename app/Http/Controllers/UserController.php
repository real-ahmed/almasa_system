<?php

namespace App\Http\Controllers;


use App\Models\RepairType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function setting($id = 0)
    {
        $pageTitle = "اعدادات الحساب";
        $repairTypes = null;
        if ($id && auth()->user()->isreceptionist) {
            $user = User::findOrFail($id);

        } else {
            $user = auth()->user();
        }
        if ($user->role == 1) {
            $repairTypes = RepairType::all();
        }
        return view('setting.user', compact('pageTitle', "user", 'repairTypes'));
    }


    public function updateAccountInfo(Request $request, $id)
    {

        if (auth()->user()->id != $id && !auth()->user()->isreceptionist ) {
            return back()->with('error', 'لا يمكنك تغير بيانات مستخدم اخر');
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:12',


        ]);


        if (auth()->user()->role == 0) {
            $this->updateAccountInfoByAdmin($request, $id);
        } elseif (auth()->user()->role == 2) {

            $this->updateAccountInfoByReceptionist($request, $id);
        }else{

            $this->updateAccountInfoByUser($request, $id);
        }

        return back()->with('success', 'تم تحديث البيانات');
    }

    private function updateAccountInfoByAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->name = $request->name;
        if ($user->role != 0) {
            $user->role = $request->role;
            $user->status = $request->status;
        }
        $user->save();

    }


    private function updateAccountInfoByReceptionist(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->status = $request->status;

        $user->save();

    }


    private function updateAccountInfoByUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
    }


    public function updatePassword(Request $request, $id)
    {


        if (auth()->user()->id == $id) {
            return $this->userUpdatePassword($request);
        } else {
            return $this->adminUpdatePassword($request, $id);
        }


    }

    private function userUpdatePassword(Request $request)
    {


        $passwordValidation = Password::min(6);

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);
        $user = auth()->user();

        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            return back()->with('success', 'تم تحديث البيانات');

        } else {
            return back()->with('success', 'كلمة المرور غير صحيحة');

        }
    }

    private function adminUpdatePassword(Request $request, $id)
    {
        if (auth()->user()->rule != 0) {
            return back()->with('error', 'لا يمكنك تغير بيانات مستخدم اخر');
        }

        $passwordValidation = Password::min(6);

        $request->validate([
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);

        $user = User::findOrFail($id);

        $password = Hash::make($request->password);
        $user->password = $password;
        $user->save();
        return back()->with('success', 'تم تحديث البيانات');

    }
}
