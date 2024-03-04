<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Image;



class GeneralSettingController extends Controller
{
    public function index(){
        $pageTitle = "الاعدادات العامة";
        $general = GeneralSetting::first();

        return view("setting.general",compact("pageTitle","general"));
    }

    public function update(Request $request){
        $request->validate([
            'site_name' => 'required|string|max:40',
            'invoice_name' => 'required|string|max:255',
            'insurance_term' => 'string|max:255',
            'address' => 'string|max:255',
            'password' => 'required|min:6',


        ]);
        $general = GeneralSetting::first();
        $general->site_name = $request->site_name;
        $general->invoice_name = $request->invoice_name;
        $general->address = $request->address;
        $general->phone = $request->phone;
        $general->sac_phone = $request->sac_phone;
        $general->money_sign = $request->money_sign;
        $general->invoice_policy = $request->invoice_policy;
        $general->insurance_term = $request->insurance_term;
        $general->default_new_user_pass = $request->password;
        $general->save();


        return back()->with('success', 'تم تحديث البيانات');
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'site_logo' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
            'invoice_logo' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
            'small_logo' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
        ]);
        if ($request->hasFile('site_logo')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->site_logo)->save($path . '/site_logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }
        if ($request->hasFile('invoice_logo')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->invoice_logo)->save($path . '/invoice_logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('small_logo')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->small_logo)->save($path . '/small_logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }
        return back()->with('success', 'تم تحديث البيانات');
    }


}
