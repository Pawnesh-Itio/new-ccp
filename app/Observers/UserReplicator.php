<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Arr;

class UserReplicator
{
    public function created(User $user): void
    {
        // Arr = user_id,mainwallet,name,mobile,Scheme_id,role_slug

        $userArr = $user->toArray();
        // Get Roleslug
        $role_slug = \App\Models\Role::where("id",$userArr['role_id'])->select("slug")->first();
        $data['user_id'] = $userArr['id'];
        $data['name'] = $userArr['name'];
        $data['mobile'] = $userArr['mobile'];
        $data['role_slug'] = $role_slug->slug;
        if(Arr::has($userArr, 'scheme_id')){
        $data['scheme_id'] = $userArr['scheme_id'];
        }
        \App\Models\Api\Userdata::insert($data);
    }
    public function updated(User $user): void
    {
        $userArr = $user->toArray();

        // Get Roleslug
        $role_slug = \App\Models\Role::where("id",$userArr['role_id'])->select("slug")->first();
        $user_id = $userArr['id'];
        $data['name'] = $userArr['name'];
        $data['mobile'] = $userArr['mobile'];
        $data['role_slug'] = $role_slug->slug;
        // Main Wallet exist or not
        if(Arr::has($userArr, 'mainwallet')){
            $data['mainwallet'] = $userArr['mainwallet'];
        }
        // Scheme Exist or not
        if(Arr::has($userArr, 'scheme_id') ){
        $data['scheme_id'] = $userArr['scheme_id'];
        }
        \App\Models\Api\Userdata::where('user_id', $user_id)->update($data);
    }
}
