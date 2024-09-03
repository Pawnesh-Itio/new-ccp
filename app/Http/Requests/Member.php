<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Helpers\Permission;

class Member extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (\Auth::check()) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $post)
    {
        $data = [
            'name'      => 'sometimes|required',
            'mobile'    => 'sometimes|required|numeric|digits:10',
            'email'     => 'sometimes|required|email',
            'address'=> 'sometimes|required',
            'city' => 'sometimes|required',
            'state'  => 'sometimes|required',
            'pincode'  => 'sometimes|required',
            'pancard'  => 'sometimes|required',
            'aadharcard'  => 'sometimes|required|numeric|digits:12',
            'role_id'  => 'sometimes|required|numeric',
        ];

        if($post->file('aadharcardpics')){
            $rules['photopics'] = 'sometimes|required|mimes:pdf,jpg,JPG,jpeg|max:1024';
        }

        if($post->file('signaturepics')){
            $rules['pancardpics'] = 'sometimes|required|mimes:pdf,jpg,JPG,jpeg|max:1024';
        }

        if($post->file('profiles')){
            $rules['profiles'] = 'sometimes|required|mimes:jpg,JPG,jpeg,png|max:500';
        }

        if (Permission::can('member_password_reset')) {
            $data['password'] = "sometimes|required|min:8";
        }else{
            $data['password'] = "sometimes|required|min:8|confirmed";
        }

        if($post->has('oldpassword')){
            $data['password'] = $data['password']."|different:oldpassword";
        }
        return $data;
    }
}
