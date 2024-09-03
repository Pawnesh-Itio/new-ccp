@extends('layouts.user_type.auth')

@section('content')
<!-- Type: userprofile,Slug: profileDetail, kycDetail, passwordManager, pinManager, rolemanager, mappingManager -->
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
                        <img src="{{asset('assets/img/user-profile/')}}/{{($user->profile == 'none' || $user->profile == null) ? 'profile.png' : $user->profile}}"
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
                @if (App\Helpers\Permission::hasRole('admin'))
                <div class="col-lg-12 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <br>
                @else
                <div class="col-lg-6 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                @endif
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
                                    <i class="fa fa-wallet"></i>
                                    <span class="ms-1">{{ __('E Wallet Details') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="notice-tab"
                                    data-bs-target="#notice" role="tab" aria-controls="notice" aria-selected="false">
                                    <i class="fa fa-key me-sm-1"></i>
                                    <span class="ms-1">{{ __('Password Manager') }}</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="pin-manager-tab-link"
                                    data-bs-target="#pin-manager" role="tab" aria-controls="pin-manager"
                                    aria-selected="false">
                                    <i class="fa fa-th me-sm-1"></i>
                                    <span class="ms-1">{{ __('Pin Manager') }}</span>
                                </a>
                            </li> -->
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
                    <form id="profile_image" action="{{route('profileUpdate')}}" class="form-control" method="POST"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <input type="hidden" name="actiontype" value="profile_picture">
                            <input name="file" id="file" class="form-control" type="file" />

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
                <h6 class="mb-0">{{ __('User Information') }}
                    <span style="float:right">
                        @if(isset($profileDetail))
                        @if(!empty($profileDetail))
                        <i class="fa fa-question-circle text-capitalize"
                            data-bs-content="{{$profileDetail['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </span>
                </h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form id="profileForm" action="{{route('profileUpdate')}}" method="POST" role="form text-left">
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
                                <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $user->name }}" type="text"
                                        placeholder="User Name" id="user-name" name="name"
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif>
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
                                        placeholder="Mobile" id="mobile" name="mobile"
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif>
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
                                <label for="country" class="form-control-label">{{__('Country')}}</label>
                                <div class="@error('user.country')border border-danger rounded-3 @enderror">
                                    <input type="text" name="country" id="country" value="{{$user->country}}"
                                        placeholder="Country" class="form-control" required
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif />
                                    @error('country')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state" class="form-control-label">{{__('State')}}</label>
                                <div class="@error('user.state')border border-danger rounded-3 @enderror">
                                    <input type="text" name="state" id="state" value="{{$user->state}}"
                                        placeholder="State" class="form-control" required
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif />
                                    @error('state')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-control-label">{{__('City')}}</label>
                                <div class="@error('user.City')border border-danger rounded-3 @enderror">
                                    <input type="text" name="city" id="city" value="{{$user->city}}" placeholder="City"
                                        class="form-control" required @if(\App\Helpers\Permission::hasNotRole('admin')
                                        && $user->kyc == "verified")
                                    disabled="" @endif />
                                    @error('city')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pincode" class="form-control-label">{{__('Pincode')}}</label>
                                <div class="@error('user.pincode')border border-danger rounded-3 @enderror">
                                    <input type="text" name="pincode" id="pincode" value="{{$user->pincode}}"
                                        placeholder="Pincode" class="form-control"
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif>
                                    @error('pincode')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender" class="form-control-label">{{__('Gender')}}</label>
                                <div class="@error('user.gender')border border-danger rounded-3 @enderror">
                                    <select name="gender" id="gender" class="form-control"
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                        disabled="" @endif>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="form-control-label">{{__('Address')}}</label>
                                <div class="@error('user.address')border border-danger rounded-3 @enderror">
                                    <textarea name="address" id="address" cols="30" rows="3" class="form-control"
                                        @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc == "verified")
                                    disabled="" @endif>{{$user->address}}</textarea>
                                    @error('address')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Security Pin -->
                        @if(App\Helpers\Permission::hasRole('admin'))
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="mpin" class="form-control-label">{{__('Security Pin')}}</label>
                                <div class="@error('user.mpin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="mpin" placeholder="Secuirty Pin" class="form-control">
                                    @error('mpin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> -->
                        @endif
                        <!-- End Security Pin -->
                    </div>
                    @if ((Auth::id() == $user->id && App\Helpers\Permission::can('profile_edit')) ||
                    App\Helpers\Permission::can('member_profile_edit'))
                    @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc != "verified" ||
                    \App\Helpers\Permission::hasRole('admin') )
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="ud_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                    @endif
                    @endif
                </form>
            </div>
        </div>
        <div class="card fade profile-tab" id="news" role="tabpanel" aria-labelledby="news-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('E Wallet Details') }}
                    <span style="float:right">
                        @if(isset($kycDetail))
                        @if(!empty($kycDetail))
                        <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$kycDetail['description']}}"
                            data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                            data-bs-content="Top popover" aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </span>
                </h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form id="kycForm" action="{{route('kyc_update')}}" method="POST" enctype="multipart/form-data"
                    role="form text-left">
                    @csrf
                    <input type="hidden" id="u_id" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="kyc_details">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gst-number" class="form-control-label">{{ __('GST Number') }}</label>
                                <div class="@error('user.gstin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="gstin" value="{{$user->gstin}}" class="form-control"
                                        id="gstin" @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc ==
                                    "verified")
                                    disabled="" @endif>
                                    @error('gstin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gst-number" class="form-control-label">{{ __('Default Currancy') }}</label>
                                <div class="@error('user.currancy_id')border border-danger rounded-3 @enderror">
                                    <input type="text" name="currancy_id"
                                        @if(!empty($default_currancy))value="{{$default_currancy->fullname}} ({{$default_currancy->symbol}})"
                                        @endif class="form-control" id="currancy_id" disabled="">
                                    @error('currancy_id')
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
                                    class="form-control-label">{{ __('ID proof number ') }}</label>
                                <div class="@error('user.aadharcard')border border-danger rounded-3 @enderror">
                                    <input type="text" name="aadharcard" value="{{ $user->aadharcard }}"
                                        class="form-control" id="aadharcard" required="" placeholder="Enter Value"
                                        @if(\App\Helpers\Permission::hasNotRole('admin')&& $user->kyc
                                    =='verified')disabled=""@endif>
                                    @error('aadharcard')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-pancard"
                                    class="form-control-label">{{ __('Address proof number') }}</label>
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
                    @if($user->kyc =="pending" || $user->kyc =="rejected")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pancard" class="form-control-label">{{__('ID proof pics')}}</label>
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
                                <label for="aadharcardpics"
                                    class="form-control-label">{{__('Address proof pics')}}</label>
                                <div class="@error('user.aadharcardpics')border border-danger rounded-3 @enderror">
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
                        @if ($user->aadharcardpic && $user->aadharcardpic !='none')
                        @php
                        $aadharcardpic = json_decode($user->aadharcardpic);
                        @endphp

                        @foreach($aadharcardpic AS $ap)
                        <div class="col-md-6 mb-2">
                            <div class="thumbnail">
                                <a href="{{asset('assets/img/kyc')}}/{{$ap}}" target="_blank">
                                    <img src="{{asset('assets/img/kyc')}}/{{$ap}}" width="300px" height="200px"
                                        alt="Aadharcard Pic">

                                </a>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        @if ($user->pancardpic && $user->pancardpic !='none')
                        <div class="col-md-6 mb-2">
                            <div class="thumbnail">
                                <a href="{{asset('assets/img/kyc')}}/{{$user->pancardpic}}" target="_blank">
                                    <img src="{{asset('assets/img/kyc')}}/{{$user->pancardpic}}" width="300px"
                                        height="200px" alt="Pancard Pic">

                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        @if(App\Helpers\Permission::hasRole('admin'))
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="kyc_status" class="form-control-label">{{__('KYC Status')}}</label>
                                <div class="@error('user.kyc_status')border border-danger rounded-3 @enderror">
                                    <select name="kyc" class="form-control">
                                        <option value>Choose Any One Option</option>
                                        <option value="verified" <?php if($user->kyc == 'verified'){ ?>selected
                                            <?php } ?>>
                                            Verified</option>
                                        <option value="submitted" <?php if($user->kyc == 'submitted'){ ?>selected
                                            <?php } ?>>Submitted</option>
                                        <option value="pending" <?php if($user->kyc == 'pending'){ ?>selected
                                            <?php } ?>>Pending</option>
                                        <option value="rejected" <?php if($user->kyc == 'rejected'){ ?>selected
                                            <?php } ?>>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(App\Helpers\Permission::hasRole('admin'))
                        <!-- <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="mpin" class="form-control-label">{{__('Security Pin')}}</label>
                                <div class="@error('user.mpin')border border-danger rounded-3 @enderror">
                                    <input type="text" name="mpin" placeholder="Secuirty Pin" class="form-control">
                                    @error('mpin')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> -->
                        @endif
                    </div>
                    @if ((Auth::id() == $user->id && App\Helpers\Permission::can('profile_edit')) ||
                    App\Helpers\Permission::can('member_profile_edit'))
                    @if(\App\Helpers\Permission::hasNotRole('admin') && $user->kyc != "verified" ||
                    \App\Helpers\Permission::hasRole('admin') )
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="kyc_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                    @endif
                    @endif
                </form>
            </div>
        </div>
        <div class="card fade profile-tab" id="notice" role="tabpanel" aria-labelledby="notice-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Password Manager') }}
                    <span style="float:right">
                        @if(isset($passwordManager))
                        @if(!empty($passwordManager))
                        <i class="fa fa-question-circle text-capitalize"
                            data-bs-content="{{$passwordManager['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </span>
                </h6>
            </div>
            <div class="card-body pt-4 p-3">
                @if ((\Auth::id() == $user->id && App\Helpers\Permission::can('password_reset')) ||
                App\Helpers\Permission::can('member_password_reset'))
                <form id="passwordForm" action="{{route('profileUpdate')}}" method="POST" role="form text-left">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{$user->id}}">
                    <input type="hidden" name="actiontype" value="password">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">{{ __('New Password') }}</label>
                                <div class="@error('user.new_password')border border-danger rounded-3 @enderror">
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <input type="password" name="password" id="password" class="form-control"
                                                required>
                                        </div>
                                        <div class="col-sm-1">
                                            <span id="togglePassword" class="toggle-password">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            @error('password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div
                                    class="@error('user.password_confirmation')border border-danger rounded-3 @enderror">
                                    <div class="row">
                                        <div class="col-sm-1">
                                        </div>
                                        <label for="password_confirmation"class="form-control-label">{{ __('Confirm Password') }}</label>
                                        <div class="col-sm-11">
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation" class="form-control" required>
                                            @error('confirm_password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="pm_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
        @if (App\Helpers\Permission::hasRole('admin'))
        <!-- Role tab start -->
        <div class="card fade profile-tab" id="role-manager" role="tabpanel" aria-labelledby="role-manager-tab">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Role Manager') }}
                    <span style="float:right">
                        @if(isset($rolemanager))
                        @if(!empty($rolemanager))
                        <i class="fa fa-question-circle text-capitalize"
                            data-bs-content="{{$rolemanager['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </span>
                </h6>
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
                <h6 class="mb-0">{{ __('Mapping Manager') }}
                    <span style="float:right">
                        @if(isset($mappingManager))
                        @if(!empty($mappingManager))
                        <i class="fa fa-question-circle text-capitalize"
                            data-bs-content="{{$mappingManager['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </span>
                </h6>
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
<!-- OTP Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">OTP Verification</h5>
                <button type="button" class="btn-close cross-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="alert bg-gradient-dark text-white text-center alert-dismissible fade show msg" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <form id="otpForm" role="form">
                @csrf
                <div class="modal-body text-center">
                    <input type="text" name="password" class="form-control">
                    <input type="hidden" name="mobile" class="form-control">
                    <input type="hidden" name="email" class="form-control">
                    <div class="form-group">
                        <label>Enter Your OTP</label>
                        <div id="otp_div">
                            <input type="text" maxlength="1" id="otpInput1" class="otp-input" />
                            <input type="text" maxlength="1" id="otpInput2" class="otp-input" />
                            <input type="text" maxlength="1" id="otpInput3" class="otp-input" />
                            <input type="text" maxlength="1" id="otpInput4" class="otp-input" />
                            <input type="text" maxlength="1" id="otpInput5" class="otp-input" />
                            <input type="text" maxlength="1" id="otpInput6" class="otp-input" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="timer" class="text-dark justify-content "></span>
                    <button type="button" id="otpresendbtn" onclick="otpRsend()"
                        class="btn bg-gradient-dark text-white">Re-send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End OTP Modal -->
@endsection
@push('style')
<style>
.toggle-password {
    position: relative;
    right: 55px;
    top: 14px;
}

#otp_div {
    display: flex;
    justify-content: center;
}

.otp-input {
    width: 40px;
    height: 40px;
    font-size: 24px;
    text-align: center;
    margin: 0 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
}
</style>
@endpush
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    // Start Profile handler
    $("#profileForm").validate({
        rules: {
            name: {
                required: true,
            },
            mobile: {
                required: true,
                minlength: 10,
                number: true,
                maxlength: 10
            },
            email: {
                required: true,
                email: true
            },
            state: {
                required: true,
            },
            city: {
                required: true,
            },
            pincode: {
                required: true,
                minlength: 6,
                number: true,
                maxlength: 6
            },
            address: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Please enter name",
            },
            mobile: {
                required: "Please enter mobile",
                number: "Mobile number should be numeric",
                minlength: "Your mobile number must be 10 digit",
                maxlength: "Your mobile number must be 10 digit"
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email address",
            },
            state: {
                required: "Please select state",
            },
            city: {
                required: "Please enter city",
            },
            pincode: {
                required: "Please enter pincode",
                number: "Mobile number should be numeric",
                minlength: "Your mobile number must be 6 digit",
                maxlength: "Your mobile number must be 6 digit"
            },
            address: {
                required: "Please enter address",
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
            var form = $('form#profileForm');
            form.find('span.text-danger').remove();
            $('form#profileForm').ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Profile Successfully Updated", 'success');
                        form.find('button:submit').prop('disabled', false);
                        flasher.success("Profile Successfully Updated");
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.message);
                        form.find('button:submit').prop('disabled', false);
                    }
                },
                error: function(errors) {
                    showError(errors, form.find('.panel-body'));
                    form.find('button:submit').prop('disabled', false);
                }
            });
        }
    });
    // End profile Handler
    // Profile Image Handler Start
    $("#profile_image").validate({
        rules: {
            file: {
                required: true,
            },
        },
        messages: {
            file: {
                required: "Please add a file",
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
            var form = $('form#profile_image');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Profile Successfully Updated", 'success');
                        flasher.success("Profile Image Successfully Updated");
                        form.find('button:submit').prop('disabled', false);
                        location.reload();
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.message);
                        form.find('button:submit').prop('disabled', false);
                    }
                },
                error: function(errors) {
                    showError(errors, form.find('.panel-body'));
                    form.find('button:submit').prop('disabled', false);
                }
            });
        }
    });
    // Profile Image Handler End
    // Start KYC Handler
    $("#kycForm").validate({
        rules: {
            aadharcard: {
                required: true,
            },
            pancard: {
                required: true,
            }
        },
        messages: {
            aadharcard: {
                required: "Please enter ID number",
            },
            pancard: {
                required: "Please enter Address proof number",
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
            var form = $('form#kycForm');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Profile Successfully Updated", 'success');
                        flasher.success("Profile Successfully Updated");
                        form.find('button:submit').prop('disabled', false);
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.message);
                        form.find('button:submit').prop('disabled', false);
                    }
                },
                error: function(errors) {
                    showError(errors, form.find('.panel-body'));
                    form.find('button:submit').prop('disabled', false);
                }
            });
        }
    });
    // End KYC Handler
    // Start password Handler
    $("#passwordForm").validate({
        rules: {
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
            password: {
                required: true,
                minlength: 8,
            }
        },
        messages: {
            password_confirmation: {
                required: "Please enter confirmed password",
                minlength: "Your password lenght should be atleast 8 character",
                equalTo: "New password and confirmed password should be equal"
            },
            password: {
                required: "Please enter new password",
                minlength: "Your password lenght should be atleast 8 character",
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
            var form = $('form#passwordForm');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "email") {
                        // OTP Model Logic here 
                        $('#staticBackdrop').find('.msg').text(
                            "OTP have been sent on you email address " + data
                            .email + "!");
                        $('#staticBackdrop').find('input[name="mobile"]').val(data
                            .mobile);
                        $('#staticBackdrop').find('input[name="email"]').val(data
                            .email);
                        $('#staticBackdrop').find('input[name="password"]').val(data
                            .password);
                        $('#staticBackdrop').modal('show');
                        startTimer() // Start Timer for OTP resend 
                    }
                    if (data.status == "ERR") {
                        flasher.error("Somthing went wrong, Please try again later!");
                    }
                    if (data.status == "success") {
                        flasher.success("Password Successfully Changed");
                        form.find('button:submit').prop('disabled', false);
                    } else {
                        form.find('button:submit').prop('disabled', false);
                        if (data.message == 'validation.password.mixed') {
                            flasher.error(
                                "Your password must contain atleast one Upper Case and one Lower Case Letter"
                            );
                        }
                        if (data.message == 'validation.password.letters') {
                            flasher.error(
                                "Your password must contain atleast one Letter");
                        }
                        if (data.message == 'validation.password.symbols') {
                            flasher.error(
                                "Your password must contain atleast one Symbol");
                        }
                        if (data.message == 'validation.password.numbers') {
                            flasher.error(
                                "Your password must contain atleast one Number");
                        }
                    }
                },
                error: function(errors) {
                    showError(errors, form.find('.panel-body'));
                    form.find('button:submit').prop('disabled', false);
                }
            });
        }
    });
    // End Password Handler 
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
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Role Successfully Changed", 'success');
                        flasher.success("Role Successfully Changed");
                        form.find('button:submit').prop('disabled', false);
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.status);
                        form.find('button:submit').prop('disabled', false);
                    }
                },
                error: function(errors) {
                    showError(errors);
                    form.find('button:submit').prop('disabled', false);
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
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Mapping Successfully Changed", 'success');
                        flasher.success("Mapping Successfully Changed");
                        form.find('button:submit').prop('disabled', false);
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.status);
                        form.find('button:submit').prop('disabled', false);
                    }
                },
                error: function(errors) {
                    showError(errors);
                    form.find('button:submit').prop('disabled', false);
                }
            });
        }
    });
    // Mapping manager hadnler end
    // Bank Form Handler
    $("#bankForm").validate({
        rules: {
            account: {
                required: true
            },
            bank: {
                required: true
            },
            ifsc: {
                required: true
            }
        },
        messages: {
            account: {
                required: "Please enter member account"
            },
            bank: {
                required: "Please enter member bank"
            },
            ifsc: {
                required: "Please enter bank ifsc"
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
            var form = $('form#bankForm');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                    form.find('button:submit').prop('disabled', true);
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // notify("Bank Details Successfully Changed", 'success');
                        flasher.success("Bank Details Successfully Changed");
                        form.find('button:submit').prop('disabled', false);
                    } else {
                        // notify(data.status, 'warning');
                        form.find('button:submit').prop('disabled', false);
                        flasher.error(data.status);
                    }
                },
                error: function(errors) {
                    form.find('button:submit').prop('disabled', false);
                    showError(errors, form.find('.panel-body'));
                }
            });
        }
    });
});
// End Bank Form
// Pin form Handler Start
$("#pinForm").validate({
    rules: {
        oldpin: {
            required: true,
        },
        pin_confirmation: {
            required: true,
            minlength: 4,
            equalTo: "#pin"
        },
        pin: {
            required: true,
            minlength: 4,
        }
    },
    messages: {
        oldpin: {
            required: "Please enter old pin",
        },
        pin_confirmation: {
            required: "Please enter confirmed pin",
            minlength: "Your pin lenght should be atleast 4 character",
            equalTo: "New pin and confirmed pin should be equal"
        },
        pin: {
            required: "Please enter new pin",
            minlength: "Your pin lenght should be atleast 4 character",
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
        var form = $('form#pinForm');
        form.find('span.text-danger').remove();
        form.ajaxSubmit({
            dataType: 'json',
            beforeSubmit: function() {
                form.find('button:submit').button('loading');
                form.find('button:submit').prop('disabled', true);
            },
            complete: function() {
                form.find('button:submit').button('reset');
            },
            success: function(data) {
                if (data.status == "success") {
                    form[0].reset();
                    // notify("Pin Successfully Changed", 'success');
                    flasher.success("Pin Successfully Changed");
                    form.find('button:submit').prop('disabled', false);
                } else {
                    // notify(data.status, 'warning');
                    form.find('button:submit').prop('disabled', false);
                    flasher.error(data.status);
                }
            },
            error: function(errors) {
                form.find('button:submit').prop('disabled', false);
                showError(errors, form.find('.panel-body'));
            }
        });
    }
});
// Pin form Handler End
function otpSend() {
    var mobile = "{{Auth::user()->mobile}}";
    if (mobile.length > 0) {
        $.ajax({
            url: '{{ route("getotp") }}',
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
        // notify("Enter your registered mobile number", 'warning');
        flasher.error("Enter your registered mobile number");
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', () => {
        // Toggle the type attribute
        password.type = password.type === 'password' ? 'text' : 'password';

        // Toggle the eye icon class
        togglePassword.querySelector('i').classList.toggle('fa-eye');
        togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
// OTP Resend Function Started
function otpRsend() {
    var mobile = $('#staticBackdrop').find('input[name="mobile"]').val();
    var email = $('#staticBackdrop').find('input[name="email"]').val();
    if (mobile.length > 0) {
        $.ajax({
            url: '{{route("forgotPasswordOtp") }}',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'mobile': mobile
            },
            beforeSend: function() {
                $('#otpresendbtn').prop('disabled', true);
            },
            complete: function() {
                startTimer(); // Start Timer for OTP resend 
            },
            success: function(data) {
                flasher.success("OTP have been re-sent on you email address.")
                $('#staticBackdrop').find('.msg').text("OTP have been re-sent on you email address " +
                    email + "!");
            }

        })
    } else {
        // notify("Enter your registered mobile number", 'warning');
        flasher.error("Enter your registered mobile number");
    }
}
// OTP Resend Function Ended
// Timer Function Started
function startTimer() {
    var otpresendbtn = $('#otpresendbtn');
    var timerText = $('#timer');
    var interval = 1000;

    otpresendbtn.prop('disabled', true); // disable resend button
    var duration = 30; // duration for OTP resend request
    var timer = setInterval(() => {
        duration--;
        timerText.text('Resend OTP in ' + duration + ' seconds!') // Display Timer 
        if (duration <= 0) {
            clearInterval(timer); //Remove Interval 
            timerText.text(''); // remove timer display 
            otpresendbtn.prop('disabled', false); // Enable resend button
        }
    }, interval);
}
// Timer Function Ended
//OTP Submission handler Start
$(document).ready(function() {
    $('.otp-input').on('input', function() {
        var value = $(this).val();
        if (!$.isNumeric(value)) {
            if ($(this).val() != '') {
                flasher.error("Only numerical values are allowed");
            }
            $(this).val('');
        } else {
            $(this).next('.otp-input').focus();
        }
        var allFilled = true;
        var otp = '';
        $('.otp-input').each(function() {
            var digit = $(this).val();
            if (!digit || !$.isNumeric(digit)) {
                allFilled = false;
                return false; // Exit each loop early if any field is not filled
            }
            otp += digit;
        });
        if (allFilled) {
            submitOTP(otp); // Call function to submit OTP via AJAX
        }
    });

    $('.otp-input').on('keydown', function(e) {
        if (e.key === 'Backspace' && $(this).val() === '') {
            $(this).prev('.otp-input').focus();
        }
    });
});

// OTP Submission handler Ended
// OTP Function
function submitOTP(otp) {
    var mobile = $('#staticBackdrop').find('input[name="mobile"]').val();
    var password = $('#staticBackdrop').find('input[name="password"]').val();
    $.ajax({
        url: '{{route("password_update")}}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            otp: otp,
            mobile: mobile,
            password: password
        },
        success: function(response) {
            if (response.status == "success") {
                flasher.success("Password updated successfully");
                $('.btn-close.cross-btn').click();
                $('#passwordForm').trigger('reset');
            } else {
                flasher.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            $('otp-input').val('');
            flasher.error('Somthing went wrong, please try again later !');
        }
    });
}
// End OTP Function
</script>
@endpush