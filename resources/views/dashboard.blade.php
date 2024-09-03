@extends('layouts.user_type.auth')
@section('content')
<div class="row">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Collection
                                @if(isset($total_collections))
                                @if (!empty($total_collections))
                                <i class="fa fa-question-circle text-capitalize"
                                    data-bs-content="{{$total_collections['description']}}"
                                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                    data-bs-content="Top popover" aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </p>
                            <h5 class="font-weight-bolder mb-0 total-collection">
                                <div class="data-loader"><img src="{{asset('assets/img/loader/486.gif')}}" alt=""></div>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">TOTAL WITHDRAWAL
                                @if(isset($total_withdrawal))
                                @if(!empty($total_withdrawal))
                                <i class="fa fa-question-circle " data-bs-content="{{$total_withdrawal['description']}}"
                                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                    data-bs-content="Top popover" aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </p>
                            <h5 class="font-weight-bolder mb-0 total-withdrawal">
                                <div class="data-loader"><img src="{{asset('assets/img/loader/486.gif')}}" alt=""></div>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="fa fa-globe" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    @if(App\Helpers\Permission::hasRole('admin'))
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Merchants
                                @if(isset($active_merchants))
                                @if(!empty($active_merchants))
                                <i class="fa fa-question-circle" data-bs-content="{{$active_merchants['description']}}"
                                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                    data-bs-content="Top popover" aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </p>
                            <h5 class="font-weight-bolder mb-0 active-user">
                                <div class="data-loader"><img src="{{asset('assets/img/loader/486.gif')}}" alt=""></div>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="fa fa-bar-chart" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!--  -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">TODAY COLLECTION
                                @if(isset($today_collection))
                                @if(!empty($today_collection))
                                <i class="fa fa-question-circle" data-bs-content="{{$today_collection['description']}}"
                                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                    data-bs-content="Top popover" aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </p>
                            <h5 class="font-weight-bolder mb-0 today-collection">
                                <div class="data-loader"><img src="{{asset('assets/img/loader/486.gif')}}" alt=""></div>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="fa fa-certificate" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="row mt-4">
    <!-- Latest transactions -->
    @if(\App\Helpers\Permission::hasRole('admin'))
    <div class="col-lg-8">
        @endif
        @if(\App\Helpers\Permission::hasNotRole('admin'))
        <div class="col-lg-12">
            @endif
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h5 class="font-weight-bolder">LATEST TRANSACTIONS
                                    @if(isset($latest_transaction))
                                    @if(!empty($latest_transaction))
                                    <i class="fa fa-question-circle text-capitalize" style="float:right"
                                        data-bs-content="{{$latest_transaction['description']}}"
                                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Top popover" aria-hidden="true">
                                    </i>
                                    @endif
                                    @endif
                                </h5>
                                <div style="float:right">
                                    <form action="{{route('DataSession')}}" method="post">
                                        @csrf
                                        <input type="hidden" value="refresh" name="data">
                                        <button type="submit" name="submit" class="btn btn-sm btn-primary"
                                            style="float:right"> <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-refresh-ccw fl-svg">
                                                <polyline points="1 4 1 10 7 10"></polyline>
                                                <polyline points="23 20 23 14 17 14"></polyline>
                                                <path
                                                    d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                                </path>
                                            </svg></button>
                                    </form>
                                </div>
                                <div class="table-responsive-lg">
                                    <table id="tbody" class="table text-center">
                                        <div id="tile-loader" class="tile-loader">
                                            <img src="{{asset('assets/img/loader/elips.svg')}}" alt="">
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(\App\Helpers\Permission::hasRole('admin'))
        <!-- User List start -->
        <div class="col-lg-4">
            <!-- card -->
            <div class="card card-frame">
                <div class="card-header pb-0">
                    <h6>LIST OF ACTIVE USERS
                        @if(isset($active_user )&& !empty($active_user))
                        <i class="fa fa-question-circle " style="float:right"
                            data-bs-content="{{$active_user['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                    </h6>
                </div>
                <div id="user-loader" class="user-loader">
                    <img src="{{asset('assets/img/loader/285.gif')}}" alt="">
                </div>
                <!-- User Card Start -->
                <div class="top-user">

                </div>

                <!-- User Card End -->
            </div>
        </div>
        <!-- User List end -->
        @endif
    </div>
    @if(\Auth::User()->status=='onboarding')
    <!-- Block User Stepper form for Onboarding process -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><span class="msg"></span></h5>
                </div>
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
                <div class="modal-body">
                    <!-- Start -->
                    <div class="container">
                        <div class="stepwizard col-md-offset-3">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button"
                                        class="btn<?php if(!empty($user->gender) ){ ?> btn-bg-color <?php }else{ ?> btn-primary <?php } ?>btn-circle profile-icon-box"><i
                                            class="fa fa-regular <?php if(!empty($user->gender) ){ ?> fa-check <?php }else{ ?>fa-user-circle-o <?php } ?> profile-icon"></i></a>
                                    <p>User Profile</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-2" type="button"
                                        class="btn <?php if(!empty($user->company_id)){ ?>btn-bg-color <?php }elseif(!empty($user->gender) && empty($user->company_id)){ ?>btn-primary <?php }else { ?>btn-default <?php } ?> btn-circle company-icon-box"
                                        disabled="disabled"><i
                                            class="fa <?php if(!empty($user->company_id)){ ?>fa-check<?php }else{ ?>fa-building-o company-icon"
                                            <?php } ?> aria-hidden="true"></i></a>
                                    <p>Company Profile</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-3" type="button"
                                        class="btn <?php if($user->kyc!='pending'){?> btn-bg-color <?php }elseif($user->kyc=='pending' && !empty($user->company_id)){ ?>btn-primary<?php }else { ?>btn-default <?php } ?> btn-circle kyc-icon-box"
                                        disabled="disabled"><i
                                            class="fa-solid <?php if($user->kyc=='pending'){ ?> fa-wallet<?php }else{ ?> fa-check<?php } ?> kyc-icon"></i></a>
                                    <p>E-Wallet Setup</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-4" type="button"
                                        class="btn <?php if($user->kyc!='pending'){ ?>btn-primary<?php }else{ ?>btn-default <?php } ?> btn-circle complition-icon-box"
                                        disabled="disabled"><i class="fa-solid fa-check complition-icon"></i></a>
                                    <p>Verification</p>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- User Profile tab -->
                        <div class="row setup-content profile-content <?php if(empty($user->gender)){ ?>active <?php }else{ ?>inactive<?php } ?>"
                            id="step-1">
                            <form id="profileForm" role="form" action="{{url('block-user-update')}}" method="POST">
                                <input type="hidden" id="u_id" name="id" value="{{$user->id}}">
                                <input type="hidden" name="actiontype" value="profile">
                                @csrf
                                <div class="col-xs-6 col-md-offset-3">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mobile</label>
                                                    <input class="form-control" value="{{ $user->mobile }}" type="text"
                                                        placeholder="Enter Your Mobile..." id="mobile" name="mobile"
                                                        required="" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>E-Mail</label>
                                                    <input type="email" class="form-control" value="{{$user->email}}"
                                                        name="email" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Full Name</label>
                                                    <input class="form-control" value="{{ $user->name }}" type="text"
                                                        placeholder="Enter Your Name..." name="name"
                                                        required="required">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value class="text-uppercase">Select Your Gender...
                                                        </option>
                                                        <option value="male"
                                                            <?= ($user->gender == 'male') ? 'selected' : ''?>
                                                            class="text-uppercase">Male</option>
                                                        <option value="female"
                                                            <?= ($user->gender == 'female') ? 'selected' : ''?>
                                                            class="text-uppercase">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <input type="text" name="country" id="country" class="form-control"
                                                        value="{{$user->country}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <input type="text" name="state" id="state" class="form-control"
                                                        value="{{$user->state}}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>City</label>
                                                    <input class="form-control" value="{{$user->city}}" type="text"
                                                        name="city" id="city" placeholder="Enter Your City..."
                                                        required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pincode</label>
                                                    <input type="text" value="{{$user->pincode}}" name="pincode"
                                                        id="pincode" placeholder="Enter Your Pincode..."
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea name="address" id="address" cols="30" rows="3"
                                                        class="form-control"
                                                        placeholder="Enter Your Address...">{{$user->address}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </form>
                        </div>
                        <!-- Company Profile Tab -->
                        <div class="row setup-content company-content <?php if(!empty($user->gender) && empty($user->company_id)){?> active <?php }else { ?>inactive<?php } ?>"
                            id="step-2">
                            <form id="companyForm" action="{{url('block-user-resource-update')}}" method="POST">
                                <input type="hidden" name="id" value="0">
                                <input type="hidden" name="actiontype" value="company">
                                @csrf
                                <div class="col-xs-6 col-md-offset-3">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company Name</label>
                                                    <input type="text" name="companyname" class="form-control"
                                                        placeholder="Enter Company Name..." required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-gorup">
                                                    <label>Website</label>
                                                    <input type="text" name="website" class="form-control"
                                                        placeholder="Enter Website Url" value="http://" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company Logo</label>
                                                    <input type="file" name="file" class="form-control">
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <div class="form-gorup">
                                                    <label>Sender Id</label>
                                                    <input type="text" name="senderid" class="form-control"
                                                        placeholder="Sender Id" required="">
                                                </div>
                                            </div> -->
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>SMS User</label>
                                                    <input type="text" name="smsuser" class="form-control"
                                                        placeholder="SMS User" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>SMS Password</label>
                                                    <input type="text" name="smspwd" class="form-control"
                                                        placeholder="SMS Password" required="">
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </form>
                        </div>
                        <!-- End Company Tab -->
                        <!-- KYC Tab -->
                        <div class="row setup-content kyc-content <?php if($user->kyc=='pending' && !empty($user->company_id)){ ?>active <?php }else{ ?>inactive<?php } ?>"
                            id="step-3">
                            <form id="kycForm" role="form" action="{{url('block-user-kyc-update')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$user->id}}">
                                <input type="hidden" name="usertype" value="block">
                                <input type="hidden" name="mobile" value="{{$user->mobile}}">
                                <input type="hidden" name="email" value="{{$user->email}}">
                                <div class="col-xs-6 col-md-offset-3">
                                    <div class="col-md-12">
                                        <h3>E-Wallet Setup</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Default Currancy</label>
                                                    <select name="currancy_id" id="currancy_id" class="form-control">
                                                        <option value="">Select your currancy</option>
                                                        @foreach($currencies as $currancy)
                                                        <option value="{{$currancy->id}}">{{ $currancy->fullname }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('currancy_id')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GSTIN</label>
                                                    <input type="text" name="gstin" value="{{$user->gstin}}"
                                                        class="form-control" id="gstin"
                                                        placeholder="Enter Your GSTIN...">
                                                    @error('gstin')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Identity proof number</label>
                                                    <input type="text" name="aadharcard" value="{{ $user->aadharcard }}"
                                                        class="form-control" id="aadharcard" required=""
                                                        placeholder="Enter Your ID proof number..."
                                                        @if(\App\Helpers\Permission::hasNotRole('admin') &&
                                                        $user->kyc=='verified')disabled=""@endif>
                                                    @error('aadharcard')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Address proof number</label>
                                                    <input type="text" name="pancard" class="form-control"
                                                        value="{{$user->pancard}}"
                                                        placeholder="Enter your address proof number..."
                                                        @if(\App\Helpers\Permission::hasNotRole('admin') &&
                                                        $user->kyc=='verified') disabled @endif required="">
                                                    @error('pancard')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Identity proof (Select both Front and Back side images)</label>
                                                <input type="file" class="form-control" id="aadharcardpics"
                                                    name="aadharcardpics[]" multiple placeholder="Adhaarcard Pic">
                                                @error('aadharcardpics')
                                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>Address proof</label>
                                                <input type="file" class="form-control" id="pancardpics"
                                                    name="pancardpics" placeholder="Pancard Pic">
                                                @error('pancardpics')
                                                <p class="text-danger text-xs mt-2">{{$message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </form>
                        </div>
                        <!-- End KYC Tab -->
                        <!-- Complition Tab -->
                        <div class="row setup-content complition-content inactive" id="step-4">
                            <h1 class="text-center text-success">Success</h1>
                            <p class="text-center">You have succesfully submited your details. Currently we are
                                verifying your KYC details.</p>
                            <p class="text-center">Will reach out if we need further details. Now you can explore
                                you panel</p>
                            <p class="text-center">Thank you for choosing us.</p>
                            <p class="text-center"><b>You will be redirect to your dasboard in <span
                                        class="timer"></span>sec.</b></p>
                        </div>
                        <!-- End Complition Tab -->
                    </div>
                    <!-- End -->
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- End Block User Stepper form for Onboarding Process -->
    @endsection
    @push('style')
    <!-- Block User stepper css -->
    <style>
    .active {
        display: block;
    }

    .inactive {
        display: none;
    }

    .stepwizard-step p {
        margin-top: 10px;
    }

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
        top: 38px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 5px;
        background-color: #ccc;
        z-order: 0;
    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 80px;
        height: 80px;
        text-align: center;
        font-size: 30px;
        line-height: 2;
        border-radius: 40px;

    }

    .has-error {
        background-color: red;
        color: red;
    }

    .btn-bg-color {
        background-color: green;
        color: white;
    }
    </style>
    <!-- Block User stepper css -->
    <style>
    .tile-loader {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255, 255, 255, 1);
        position: absolute;
        width: 100%;
        height: auto;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
    }

    .user-loader {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(248, 249, 250, 1);
        position: absolute;
        height: 450px;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
    }
    </style>
    @endpush
    @push('script')
    <script>
    $(window).load(function() {
        $('#staticBackdrop').find('.msg').text("User Profile");
        $('#staticBackdrop').modal('show');
    });
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
            country: {
                required: true,
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
            country: {
                required: "Please enter country",
            },
            state: {
                required: "Please enter state",
            },
            city: {
                required: "Please enter city",
            },
            pincode: {
                required: "Please enter pincode",
                number: "Mobile number should be numeric",
                minlength: "Your Pincode number must be 6 digit",
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
                        $('#staticBackdrop').find('.msg').text("Company Profile");
                        $(".profile-content").removeClass("active");
                        $(".profile-content").addClass("inactive");
                        $(".company-content").removeClass("inactive");
                        $(".company-content").addClass("active");
                        $(".company-icon-box").addClass("btn-primary");
                        $(".company-icon-box").removeClass("btn-default");
                        $(".profile-icon").removeClass("fa-user-circle-o");
                        $(".profile-icon-box").css("background-color", "green");
                        $(".profile-icon").addClass("fa-check");
                    } else {
                        form.find('button:submit').prop('disabled', false);
                        // notify(data.status, 'warning');
                        flasher.error(data.message);
                    }
                },
                error: function(errors) {
                    form.find('button:submit').prop('disabled', false);
                    showError(errors, form.find('.panel-body'));
                }
            });
        }
    });
    // End Profile form handler.
    // Start Company handler
    $("#companyForm").validate({
        rules: {
            companyname: {
                required: true,
            },
            website: {
                required: true,
            }
        },
        messages: {
            companyname: {
                required: "Please Enter Name...",
            },
            website: {
                required: "Please Enter Website...",
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
            var form = $('form#companyForm');
            form.find('span.text-danger').remove();
            $('form#companyForm').ajaxSubmit({
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
                        $('#staticBackdrop').find('.msg').text("E-KYC");
                        $(".company-content").removeClass("active");
                        $(".company-content").addClass("inactive");
                        $(".kyc-content").removeClass("inactive");
                        $(".kyc-content").addClass("active");
                        $(".kyc-icon-box").addClass("btn-primary");
                        $(".kyc-icon-box").removeClass("btn-default");
                        $(".company-icon").removeClass("fa-building-o");
                        $(".company-icon-box").css("background-color", "green");
                        $(".company-icon").addClass("fa-check");
                    } else {
                        // notify(data.status, 'warning');
                        form.find('button:submit').prop('disabled', false);
                        flasher.error(data.message);
                    }
                },
                error: function(errors) {
                    form.find('button:submit').prop('disabled', false);
                    showError(errors, form.find('.panel-body'));
                }
            });
        }
    });
    // End kyc form handler.
    $("#kycForm").validate({
        rules: {
            pancard: {
                required: true,
            },
            aadharcard: {
                required: true,
            },
            currancy_id:{
                required:true,
            },

        },
        messages: {
            aadharcard: {
                required: "Please enter identity proof number",
            },
            pancard: {
                required: "Please enter address proof number",
            },
            currancy_id:{
                required: "Please select your currancy"
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
            $('form#kycForm').ajaxSubmit({
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
                        $('#staticBackdrop').find('.msg').text("Verification");
                        $(".kyc-content").removeClass("active");
                        $(".kyc-content").addClass("inactive");
                        $(".complition-content").removeClass("inactive");
                        $(".complition-content").addClass("active");
                        $(".complition-icon-box").addClass("btn-primary");
                        $(".complition-icon-box").removeClass("btn-default");
                        $(".kyc-icon").removeClass("fa-user-check");
                        $(".kyc-icon-box").css("background-color", "green");
                        $(".kyc-icon").addClass("fa-check");
                        // Timer
                        var counter = 6;
                        var interval = setInterval(function() {
                            counter--;
                            // Display 'counter' wherever you want to display it.
                            $('.timer').text(counter);
                            if (counter < 1) {
                                clearInterval(interval);
                                location.reload(true);
                            }
                        }, 1000);
                        // End Timer
                    } else {
                        // notify(data.status, 'warning');
                        form.find('button:submit').prop('disabled', false);
                        flasher.error(data.message);
                    }
                },
                error: function(errors) {
                    form.find('button:submit').prop('disabled', false);
                    showError(errors, form);
                    if (errors.status == '422') {
                        alert(errors.status);
                    }
                }
            });
        }
    });
    // End Company form handler.
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: "{{route('latesttransaction')}}",
            type: 'get',
            dataType: 'json',
            success: function(response) {

                $('#tile-loader').fadeIn();
                var tdata = `<thead style="color:black">
                         <th class="text-uppercase text-xs font-weight-bolder opacity-7">TXN ID</th>
                         <th class="text-uppercase text-xs font-weight-bolder opacity-7">TYPE</th>
                         <th class="text-uppercase text-xs font-weight-bolder opacity-7">AMOUNT</th>
                         <th class="text-uppercase text-xs font-weight-bolder opacity-7">STATUS</th>
                         <th class="text-uppercase text-xs font-weight-bolder opacity-7">TIME</th>
                         </thead>
                         <tbody id="tbody" style="color:black">`;
                response.forEach(function(data) {
                    tdata += `<tr>`;

                    tdata += `<td class='text-uppercase text-xs'>` + data.mytxnid +
                        `</td>`;
                    tdata += `<td class='text-uppercase text-xs'>` + data.product + `</td>`;
                    tdata += `<td class='text-uppercase text-xs'>` + data.amount + `</td>`;
                    tdata +=
                        `<td class='text-uppercase text-xs'> <span class="badge bg-success">` +
                        data.status + `</span></td>`;
                    tdata += `<td class='text-uppercase text-xs'>` + data.created_at +
                        `</td>`;
                    tdata += `</tr>`;
                })
                tdata += `</body>`;
                $("#tbody").append(tdata);
            },
            complete: function() {
                $('#tile-loader').fadeOut();
            }
        });

        // datacount 
        $.ajax({
            url: "{{route('datacount')}}",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('.data-loader').fadeIn();
                var totalCollection = "<?php if(isset($currencyDetails)){ echo $currencyDetails->symbol; } ?> "+response.totalcollection;
                var totalPayout = "<?php if(isset($currencyDetails)){ echo $currencyDetails->symbol; } ?> "+response.totalpayout;
                var totalUsers = response.totalusers ;
                var todayCollection = "<?php if(isset($currencyDetails)){ echo $currencyDetails->symbol; } ?> "+response.todaycollection;
                $(".total-collection").append(totalCollection);
                $(".total-withdrawal").append(totalPayout);
                $(".active-user").append(totalUsers);
                $(".today-collection").append(todayCollection);
            },
            complete: function() {
                $('.data-loader').fadeOut();
            }
        });
        // end
        // top user
        $.ajax({
            url: "{{route('topuser')}}",
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('.user-loader').fadeIn();
                var udata = "";
                response.forEach(function(data) {
                    udata += `<div class="card-body align-items-center border-bottom pb-3">
                         <div class="row">
                         <div class="col-2">`;
                    if (data.profile) {
                        udata += `<img src="{{asset('')}}assets/img/user-profile/` + data
                            .profile +
                            `" class="rounded-circle " width="40px" height="40px" alt="user"> `;
                    } else {
                        udata +=
                            `<img src="{{asset('')}}assets/img/user-profile/profile.png" class="rounded-circle " width="40px" alt="user">`;
                    }
                    udata += `</div>`;
                    udata += `<div class="col-5 text-xs ">
                            <div class="row">
                                <div class="col-12 font-weight-bolder">
                                    ` + data.name + `
                                </div><br><br>
                                <div class="col-12">
                                    ` + data.mobile + `
                                </div>
                            </div>
                        </div>`;
                    udata += ` <div class="col-5 text-xs ">
                            ` + data.created_at + `
                        </div>`;
                    udata += `</div>
                </div>`;
                })
                $(".top-user").append(udata);
            },
            complete: function() {
                $('.user-loader').fadeOut();
            }
        });
        // end
    });
    </script>
    @endpush