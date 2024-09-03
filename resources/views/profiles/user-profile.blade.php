@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4"
            style="background-image: url('{{asset('assets/img/curved-images/curved0.jpg')}}'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{asset('assets/img/user-profile/')}}/{{(Auth::user()->profile == 'none' || Auth::user()->profile == null) ? 'profile.png' : Auth::user()->profile}}"
                            alt="..." style="height:80px;width:100px" class=" border-radius-lg shadow-sm">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                            class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                            <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Edit Image"></i>
                        </a>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $user->name }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-1 py-1 active " id="profile-details-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-details" role="tab" aria-controls="profile-details"
                                    aria-selected="true">
                                    <i class="fa fa-user me-sm-1"></i>
                                    <span class="ms-1">{{ __('Profile Details') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="news-tab"
                                    data-bs-target="#news" role="tab" aria-controls="news" aria-selected="false">
                                    <i class="fa fa-check-square"></i>
                                    <span class="ms-1">{{ __('KYC Details') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="notice-tab"
                                    data-bs-target="#notice" role="tab" aria-controls="notice" aria-selected="false">
                                    <i class="fa fa-key me-sm-1"></i>
                                    <span class="ms-1">{{ __('Password Manager') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="pin-manager-tab-link"
                                    data-bs-target="#pin-manager" role="tab" aria-controls="pin-manager"
                                    aria-selected="false">
                                    <i class="fa fa-th me-sm-1"></i>
                                    <span class="ms-1">{{ __('Pin Manager') }}</span>
                                </a>
                            </li>
                            @if (App\Helpers\Permission::hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="role-manager-tab-link"
                                    data-bs-target="#role-manager" role="tab" aria-controls="role-manager"
                                    aria-selected="false">
                                    <i class="fa fa-th me-sm-1"></i>
                                    <span class="ms-1">{{ __('Role Manager') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="mapping-manager-tab-link"
                                    data-bs-target="#mapping-manager" role="tab" aria-controls="mapping-manager"
                                    aria-selected="false">
                                    <i class="fa fa-th me-sm-1"></i>
                                    <span class="ms-1">{{ __('Mapping Manager') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Update Model Start -->
        <div class="modal fade" id="exampleModal" tabindex="11" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image Upload</h5>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{url('user-profile')}}" class="form-control" method="POST"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <input type="hidden" name="actiontype" value="profile_picture">
                            <input name="file" class="form-control" type="file" />

                            <div class="panel-body p-5">
                                <p>Note : Prefered image size is 260px * 56px</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="img_submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Image Update Model End-->
    </div>
    <div class="container-fluid py-4 tab-content" id="myTabContent">
        <div class="card fade show active profile-tab" id="profile-details" role="tabpanel"
            aria-labelledby="profile-details-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('User Information') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{url('user-profile')}}" method="POST" role="form text-left">
                    @csrf
                    @if($errors->any())
                    <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">
                            {{$errors->first()}}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">
                            {{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="m-3  alert alert-primary alert-dismissible fade show" id="alert-primary" role="alert">
                        <span class="alert-text text-white">
                            {{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <input type="hidden" id="u_id" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="profile">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('User Name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->name }}" type="text"
                                        placeholder="User Name" id="user-name" name="name">
                                    @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile" class="form-control-label">{{ __('Mobile') }}</label>
                                <div class="@error('mobile')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->mobile }}" type="text"
                                        placeholder="Mobile" id="mobile" name="mobile">
                                    @error('mobile')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state" class="form-control-label">{{__('State')}}</label>
                                <div class="@error('user.state')border border-danger rounded-3 @enderror">
                                    <select name="state" id="state" class="form-control">
                                        <option value class="text-uppercase">Select any one option</option>
                                        @foreach($state AS $s)
                                        <option value="{{$s->state}}"
                                            <?= ($user->state == $s->state) ? 'selected' : ''?> class="text-uppercase">
                                            {{$s->state}}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-control-label">{{__('City')}}</label>
                                <div class="@error('user.City')border border-danger rounded-3 @enderror">
                                    <input type="text" name="city" id="city" value="{{$user->city}}" placeholder="City"
                                        class="form-control" required />
                                    @error('city')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pincode" class="form-control-label">{{__('Pincode')}}</label>
                                <div class="@error('user.pincode')border border-danger rounded-3 @enderror">
                                    <input type="text" name="pincode" id="pincode" value="{{$user->pincode}}"
                                        placeholder="Pincode" class="form-control">
                                    @error('pincode')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender" class="form-control-label">{{__('Gender')}}</label>
                                <div class="@error('user.gender')border border-danger rounded-3 @enderror">
                                    <select name="gender" id="gender" class="form-control">
                                        <option value class="text-uppercase">Select you gender</option>
                                        <option value="male" <?= ($user->gender == 'male') ? 'selected' : ''?>
                                            class="text-uppercase">Male</option>
                                        <option value="female" <?= ($user->gender == 'female') ? 'selected' : ''?>
                                            class="text-uppercase">Female</option>
                                    </select>
                                    @error('gender')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="form-control-label">{{__('Address')}}</label>
                                <div class="@error('user.address')border border-danger rounded-3 @enderror">
                                    <textarea name="address" id="address" cols="30" rows="5"
                                        class="form-control">{{$user->address}}</textarea>
                                    @error('address')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Security Pin -->
                        @if(App\Helpers\Permission::hasRole('admin'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mpin" class="form-control-label">{{__('Security Pin')}}</label>
                                <div class="@error('user.mpin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="mpin" placeholder="Secuirty Pin" class="form-control">
                                    @error('mpin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- End Security Pin -->
                    </div>
                    @if ((Auth::id() == $user->id && App\Helpers\Permission::can('profile_edit')) ||
                    App\Helpers\Permission::can('member_profile_edit'))
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="ud_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="card fade profile-tab" id="news" role="tabpanel" aria-labelledby="news-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('KYC  Details') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{url('user-profile')}}" method="POST" enctype="multipart/form-data"
                    role="form text-left">
                    @csrf
                    <input type="hidden" id="u_id" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="kyc_details">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-shop" class="form-control-label">{{ __('Shop Name') }}</label>
                                <div class="@error('user.shop')border border-danger rounded-3 @enderror">
                                    <input type="text" name="shopname" value="{{ $user->shopname }}"
                                        class="form-control" id="shopname">
                                    @error('shopname')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gst-number" class="form-control-label">{{ __('GST Number') }}</label>
                                <div class="@error('user.gstin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="gstin" value="{{$user->gstin}}" class="form-control"
                                        id="gstin">
                                    @error('gstin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-aadharcard"
                                    class="form-control-label">{{ __('Aadharcard Number ') }}</label>
                                <div class="@error('user.aadharcard')border border-danger rounded-3 @enderror">
                                    <input type="text" name="aadharcard" value="{{ $user->aadharcard }}"
                                        class="form-control" id="aadharcard" required="" placeholder="Enter Value"
                                        maxlength="12" minlength="12" @if(\App\Helpers\Permission::hasNotRole('admin')
                                        && $user->kyc =='verified')disabled=""@endif>
                                    @error('aadharcard')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-pancard" class="form-control-label">{{ __('Pancard Number') }}</label>
                                <div class="@error('user.pancard')border border-danger rounded-3 @enderror">
                                    <input type="text" name="pancard" value="{{ $user->pancard }}" class="form-control"
                                        id="pancard" placeholder="Enter Value"
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif>
                                    @error('pancard')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($user->kyc !="verified")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pancard" class="form-control-label">{{__('Pancard Pics')}}</label>
                                <div class="@error('user.pancard')border border-danger rounded-3 @enderror">
                                    <div class="form-group">
                                        <input type="file" class="form-control" id="pancardpics" name="pancardpics"
                                            placeholder="Pancard Pic">
                                    </div>
                                    @error('pancard')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="aadharcardpics" class="form-control-label">{{__('Aadharcard Pics')}}</label>
                                <div class="@error('user.pancard')border border-danger rounded-3 @enderror">
                                    <input type="file" class="form-control" id="aadharcardpics" name="aadharcardpics[]"
                                        multiple placeholder="Adhaarcard Pic">
                                    @error('aadharcardpics')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">

                        @if ($user->aadharcardpic)
                        @php
                        $aadharcardpic = json_decode($user->aadharcardpic);
                        @endphp
                        @foreach($aadharcardpic AS $ap)
                        <div class="col-md-6 mb-2">
                            <div class="thumbnail">
                                <a href="{{asset('assets/img/kyc')}}/{{$ap}}" target="_blank">
                                    <img src="{{asset('assets/img/kyc')}}/{{$ap}}" alt="Aadharcard Pic">

                                </a>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        @if ($user->pancardpic)
                        <div class="col-md-6 mb-2">
                            <div class="thumbnail">
                                <a href="{{asset('assets/img/kyc')}}/{{$user->pancardpic}}" target="_blank">
                                    <img src="{{asset('assets/img/kyc')}}/{{$user->pancardpic}}" alt="Pancard Pic">

                                </a>
                            </div>
                        </div>
                        @endif


                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="kyc_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card fade profile-tab" id="notice" role="tabpanel" aria-labelledby="notice-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Password Manager') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{url('user-profile')}}" method="POST" role="form text-left">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="password">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="old_password" class="form-control-label">{{ __('Old Password') }}</label>
                                <div class="@error('user.old_password')border border-danger rounded-3 @enderror">
                                    <input type="password" name="oldpassword" id="old_password" class="form-control"
                                        required>
                                    @error('old_password')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">{{ __('New Password') }}</label>
                                <div class="@error('user.new_password')border border-danger rounded-3 @enderror">
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    @error('password')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation"
                                    class="form-control-label">{{ __('Confirm Password') }}</label>
                                <div
                                    class="@error('user.password_confirmation')border border-danger rounded-3 @enderror">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required>
                                    @error('confirm_password')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="pm_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card fade profile-tab" id="pin-manager" role="tabpanel" aria-labelledby="pin-manager-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Pin Manager') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="m-3  alert alert-success alert-dismissible fade show otp-msg" id="alert-success"
                    role="alert">
                    <span class="alert-text text-white otp-txt">
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                <form action="{{route('set_pin')}}" method="POST" role="form text-left">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{$user->id}}">
                    <input type="hidden" id="id" name="mobile" value="{{$user->mobile}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="old_pin" class="form-control-label">{{ __('New Pin') }}</label>
                                <div class="@error('user.old_pin')border border-danger rounded-3 @enderror">
                                    <input type="password" name="pin" id="pin" class="form-control"
                                        placeholder="Enter New Pin" required>
                                    @error('old_pin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pin_confirmation" class="form-control-label">{{ __('Confirm Pin') }}</label>
                                <div class="@error('user.pin_confirmation')border border-danger rounded-3 @enderror">
                                    <input type="password" name="pin_confirmation" id="pin_confirmation"
                                        class="form-control" placeholder="Enter Confirmation Pin" required>
                                    @error('pin_confirmation')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="otp" class="form-control-label">{{ __('OTP') }}</label>
                                <div class="@error('user.otp')border border-danger rounded-3 @enderror">
                                    <input type="password" name="otp" id="otp" class="form-control"
                                        placeholder="Enter OTP" required>
                                    @error('otp')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="javascript:void(0)" onclick="otpSend()" class="btn btn-sm btn-primary">Get
                                    OTP</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="pin_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
        @if (App\Helpers\Permission::hasRole('admin'))
        <!-- Role tab start -->
        <div class="card fade profile-tab" id="role-manager" role="tabpanel" aria-labelledby="role-manager-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Role Manager') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="m-3  alert alert-success alert-dismissible fade show otp-msg" id="alert-success"
                    role="alert">
                    <span class="alert-text text-white otp-txt">
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                <form id="roleForm" action="{{route('profileUpdate')}}" method="POST" role="form text-left">
                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="rolemanager">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_id" class="form-control-label">{{ __('Member Role') }}</label>
                                <div class="@error('user.role_id')border border-danger rounded-3 @enderror">
                                    <select name="role_id" class="form-control" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="security_pin" class="form-control-label">{{ __('Security Pin') }}</label>
                                <div class="@error('user.security_pin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="security_pin" id="security_pin" class="form-control"
                                        placeholder="Enter Security_pin Pin" required>
                                    @error('security_pin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Role tab end -->
        <!-- mapping tab start -->
        <div class="card fade profile-tab" id="mapping-manager" role="tabpanel" aria-labelledby="mapping-manager-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Mapping Manager') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="m-3  alert alert-success alert-dismissible fade show otp-msg" id="alert-success"
                    role="alert">
                    <span class="alert-text text-white otp-txt">
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                <form id="memberForm" action="{{route('profileUpdate')}}" method="POST" role="form text-left">
                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="mapping">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id" class="form-control-label">{{ __('Parent Member') }}</label>
                                <div class="@error('user.parent_id')border border-danger rounded-3 @enderror">
                                    <select name="parent_id" class="form-control" required="">
                                        <option value="">Select Member</option>
                                        @foreach ($parents as $parent)
                                        <option value="{{$parent->id}}">{{$parent->name}} ({{$parent->mobile}})
                                            ({{$parent->role->name}})</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mpin" class="form-control-label">{{ __('Security Pin') }}</label>
                                <div class="@error('user.mpin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="mpin" class="form-control" placeholder="Security Pin">
                                    @error('mpin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Mapping tab end  -->
        @endif
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    // Role manage handler start
    $("#roleForm").validate({
        rules: {
            role_id: {
                required: true
            }
        },
        messages: {
            role_id: {
                required: "Please select member role"
            }
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase().toLowerCase() === "select") {
                error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('form#roleForm');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Role Successfully Changed", 'success');
                        flasher.success("Role Successfully Changed");
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.status);
                    }
                },
                error: function(errors) {
                    showError(errors);
                }
            });
        }
    });
    // Manage role handler
    // Mapping manager handler start
    $("#memberForm").validate({
        rules: {
            parent_id: {
                required: true
            }
        },
        messages: {
            parent_id: {
                required: "Please select parent member"
            }
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase().toLowerCase() === "select") {
                error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('form#memberForm');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Mapping Successfully Changed", 'success');
                        flasher.success("apping Successfully Changed");
                    } else {
                        notify(data.status, 'warning');
                        flasher.error(data.status);
                    }
                },
                error: function(errors) {
                    showError(errors);
                }
            });
        }
    });
    // Mapping manager hadnler end
});

function otpSend() {
    var mobile = "{{Auth::user()->mobile}}";
    if (mobile.length > 0) {
        $.ajax({
            url: '{{ route("get_otp") }}',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'mobile': mobile
            },
            beforeSend: function() {
                swal({
                    title: 'Wait!',
                    text: 'Please wait, we are working on your request',
                    icon: "success",
                    button: false,
                    onOpen: () => {
                        swal.showLoading()
                    }
                })
            },
            complete: function() {
                swal.close();
            },
            success: function(data) {
                $(".otp-msg").css("display", "block");
                $(".otp-txt").append(data.message);

            }

        })
    } else {
        notify("Enter your registered mobile number", 'warning');
    }
}
</script>
@endpush