<?php

namespace App\Http\Controllers;

use App\Helpers\Permission ;
use App\Models\User;
use App\Models\Role;
use App\Models\Pindata;
use App\Models\PortalSetting;
use App\Models\Circle;
use App\Models\Company;
use App\Models\Companydata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class InfoUserController extends Controller
{ 

    public function create()
    {
        $userData = auth()->user();
        $companyId = $userData->company_id;
        $data['company'] = Company::where('id',$companyId)->first();
        $data['companyData'] = Companydata::where('company_id',$companyId)->first();
        return view('profiles/company-profile')->with($data);
    }

    public function store(Request $request)
    {
     if(isset($_POST['cp_submit']))
        {
           $attributes = request()->validate([
            'companyname' => ['required', 'max:50'],
            'website'     => ['max:50'],
            'cp_id'       => ['required']
             ]);
           Company::where('id',$attributes['cp_id'])
            ->update([
            'companyname'    => $attributes['companyname'],
            'website'        => $attributes['website'],
             ]);
           return redirect('/company-profile')->with('success','Company Details updated successfully');
        }
        if(isset($_POST['img_submit'])){
            $attributes = request()->validate([
                'file' => ['required','max:1000','mimes:jpg,bmp,png,jpeg,JPEG,PNG,JPG,BMP'],
                'id'   => ['required']
                 ]);
            $company = Company::find($attributes['id']);
            if($request->hasFile('file')){
                try {
                    unlink(public_path('assets/img/logos/').$company->logo);
                } catch (\Exception $e) {
                }
                $fileName = 'logo'.$request->id.'.'.$request->file->extension(); 
                $request->file->move(public_path('assets/img/logos/'), $fileName);
                Company::where('id',$request->id)
                ->update([
                    'logo'=>$fileName
                ]);
                return redirect('/company-profile')->with('success','Company Logo updated successfully');
            }
        }
        if(isset($_POST['cno_submit'])){
            $attributes = request()->validate([
                'company_id' => ['required'],
                'notice'       => ['required']
            ]);
            Companydata::where('company_id',$attributes['company_id'])
            ->update([
                'notice' => $attributes['notice']
            ]);
            return redirect('/company-profile')->with('success','Company notice updated successfully');
        }
        if(isset($_POST['cn_submit'])){
            $attributes = request()->validate([
                'company_id' => ['required'],
                'news'       => ['required']
            ]);
            Companydata::where('company_id',$attributes['company_id'])
            ->update([
                'news' => $attributes['news']
            ]);
            return redirect('/company-profile')->with('success','Company News updated successfully');
        }
        if(isset($_POST['cs_submit'])){
            $attributes = request()->validate([
                'number' => ['required','numeric','digits:10'],
                'email'       => ['required','email'],
                'company_id' => ['required']
            ]);
            Companydata::where('company_id',$attributes['company_id'])
            ->update([
                'number' => $attributes['number'],
                'email'  => $attributes['email']
            ]);
            return redirect('/company-profile')->with('success','Company Suppport Details updated successfully');
        }
    }

}