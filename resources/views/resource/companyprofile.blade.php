@extends('layouts.user_type.auth')

@section('content')

<div>
    <!-- Type: companyprofile, slug: companyDetails, companyNews, companyNotice, companySupport, companyLogo -->
    <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4"
            style="background-image: url('{{asset('assets/img/curved-images/curved0.jpg')}}'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4"> 
                <div class="col-auto"> 
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{asset('assets/img/logos/')}}/<?php if(!empty($company->logo)){ echo $company->logo; } else {echo "logo.jpg"; } ?> " alt="..."
                            style="height:80px;width:100px" class=" border-radius-lg shadow-sm">
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
                            {{ $company->companyname }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $company->website }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active " id="profile-details-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-details" role="tab" aria-controls="profile-details"
                                    aria-selected="true">
                                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none"
                                            fill-rule="evenodd">
                                            <g id="Rounded-Icons" transform="translate(-2319.000000, -291.000000)"
                                                fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="Icons-with-opacity"
                                                    transform="translate(1716.000000, 291.000000)">
                                                    <g id="box-3d-50" transform="translate(603.000000, 0.000000)">
                                                        <path class="color-background"
                                                            d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"
                                                            id="Path"></path>
                                                        <path class="color-background"
                                                            d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"
                                                            id="Path" opacity="0.7"></path>
                                                        <path class="color-background"
                                                            d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"
                                                            id="Path" opacity="0.7"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ms-1">{{ __('Details') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="news-tab"
                                    data-bs-target="#news" role="tab" aria-controls="news" aria-selected="false">
                                    <i class="fa fa-regular fa-newspaper"></i>
                                    <span class="ms-1">{{ __('News') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="notice-tab"
                                    data-bs-target="#notice" role="tab" aria-controls="notice" aria-selected="false">
                                    <i class="fa fa-regular fa-flag"></i>
                                    <span class="ms-1">{{ __('Notice') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="support-details-tab"
                                    data-bs-target="#support-details" role="tab" aria-controls="support-details"
                                    aria-selected="false">
                                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>settings</title>
                                        <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none"
                                            fill-rule="evenodd">
                                            <g id="Rounded-Icons" transform="translate(-2020.000000, -442.000000)"
                                                fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="Icons-with-opacity"
                                                    transform="translate(1716.000000, 291.000000)">
                                                    <g id="settings" transform="translate(304.000000, 151.000000)">
                                                        <polygon class="color-background" id="Path"
                                                            opacity="0.596981957"
                                                            points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                        </polygon>
                                                        <path class="color-background"
                                                            d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"
                                                            id="Path" opacity="0.596981957"></path>
                                                        <path class="color-background"
                                                            d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z"
                                                            id="Path"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ms-1">{{ __('Support Details') }}</span>
                                </a>
                            </li>
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
                        @if(isset($companyLogo))
                        @if(!empty($companyLogo))
                        <i class="fa fa-question-circle text-capitalize"
                            data-bs-content="{{$companyLogo['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </div>
                    <form id="logoupload" action="{{route('resourceupdate')}}" class="form-control" method="POST"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$company->id}}">
                            <input type="hidden" name="actiontype" value="company">
                            <input name="file" class="form-control" type="file" @if(\App\Helpers\Permission::hasNotRole('admin') && Auth::user()->kyc == "verified")
                                    disabled="" @endif />

                            <div class="panel-body p-5">
                                <p>Note : Prefered image size is 260px * 56px</p>
                            </div>
                        </div>
                        @if(\App\Helpers\Permission::hasNotRole('admin') && Auth::user()->kyc != "verified" || \App\Helpers\Permission::hasRole('admin'))
                              
                        <div class="modal-footer">
                            <button type="submit" name="img_submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                        @endif
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
                <h6 class="mb-0">{{ __('Company Information') }}
                    @if(isset($companyDetails))
                    @if(!empty($companyDetails))
                    <span style="float:right">
                        <i class="fa fa-question-circle text-capitalize"
                            data-bs-content="{{$companyDetails['description']}}" data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                            aria-hidden="true">
                        </i>
                        @endif
                        @endif
                    </span>
                </h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form id="profileForm" action="{{route('resourceupdate')}}" method="POST" role="form text-left">
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
                    <!-- <input type="hidden" id="cp_id" name="cp_id" value="{{$company->id}}"> -->
                    <input type="hidden" name="id" value="{{$company->id}}">
                    <input type="hidden" name="actiontype" value="company">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company-name" class="form-control-label">{{ __('Company Name') }}</label>
                                <div class="@error('company.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $company->companyname }}" type="text"
                                        placeholder="Company Name" id="company-name" name="companyname" @if(\App\Helpers\Permission::hasNotRole('admin') && Auth::user()->kyc == "verified")
                                    disabled="" @endif>
                                    @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company-website" class="form-control-label">{{ __('Website') }}</label>
                                <div class="@error('website')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ $company->website }}" type="text"
                                        placeholder="www.example.com" id="website" name="website"@if(\App\Helpers\Permission::hasNotRole('admin') && Auth::user()->kyc == "verified")
                                    disabled="" @endif>
                                    @error('website')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                    @if(\App\Helpers\Permission::hasNotRole('admin') && Auth::user()->kyc != "verified" ||\App\Helpers\Permission::hasRole('admin'))
                        <button type="submit" name="cp_submit"
                            class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                            @endif
                    </div>
                </form>
            </div>
        </div>
        <!-- News -->
        <div class="fade profile-tab" id="news" role="tabpanel" aria-labelledby="news-tab">
            <div class="card card-frame">
                <div class="card-body text-center">
                    <h6>{{ __('Company News') }}
                        @if(isset($companyNews))
                        @if(!empty($companyNews))
                        <span style="float:right">
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$companyNews['description']}}" data-bs-trigger="hover focus"
                                data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                aria-hidden="true">
                            </i>
                            @endif
                            @endif
                        </span>
                    </h6>
                </div>
            </div>
            <br>
            @foreach($companydata as $cd)
            @if($cd->slug == 'c_news')
            <div class="card bg-gradient-default">
                <div class="card-body">
                    <h3 class="card-title text-info text-gradient">{{$cd->title}}</h3>
                    <blockquote class="blockquote  mb-0 ps-3">
                        <p class="text-dark ms-3"> <?= $cd->description ?></p>
                        <footer class="blockquote-footer text-gradient text-info text-sm ms-3">
                            <cite title="Source Title">{{$cd->title}}</cite>
                        </footer>
                    </blockquote>
                </div>
            </div>
            <br>
            @endif
            @endforeach
        </div>
        <!-- End News -->
        <!-- Notice Start -->
        <div class="fade profile-tab" id="notice" role="tabpanel" aria-labelledby="notice-tab">
            <div class="card card-frame">
                <div class="card-body text-center">
                    <h6>{{ __('Company Notice') }}
                        @if(isset($companyNotice))
                        @if(!empty($companyNotice))
                        <span style="float:right">
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$companyNotice['description']}}" data-bs-trigger="hover focus"
                                data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                aria-hidden="true">
                            </i>
                        </span>
                        @endif
                        @endif
                    </h6>
                </div>
            </div>
            <br>
            @foreach($companydata as $cd)
            @if($cd->slug == 'c_notice')
            <div class="card bg-gradient-default">
                <div class="card-body">
                    <h3 class="card-title text-info text-gradient">{{$cd->title}}</h3>
                    <blockquote class="blockquote  mb-0 ps-3">
                        <p class="text-dark ms-3"> <?= $cd->description ?></p>
                        <footer class="blockquote-footer text-gradient text-info text-sm ms-3">
                            <cite title="Source Title">{{$cd->title}}</cite>
                        </footer>
                    </blockquote>
                </div>
            </div>
            <br>
            @endif
            @endforeach
        </div>
        <!-- Notice End -->
        <!-- Support Start -->
        <div class="fade profile-tab" id="support-details" role="tabpanel" aria-labelledby="support-details-tab">
            <div class="card card-frame">
                <div class="card-body text-center">
                    <h6>{{ __('Company Support Details') }}
                        @if(isset($companySupport))
                        @if(!empty($companySupport))
                        <span style="float:right">
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$companySupport['description']}}" data-bs-trigger="hover focus"
                                data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                aria-hidden="true">
                            </i>
                        </span>
                        @endif
                        @endif
                    </h6>
                </div>
            </div>
            <br>
            @foreach($companydata as $cd)
            @if($cd->slug == 'c_support')
            <div class="card bg-gradient-default">
                <div class="card-body">
                    <h3 class="card-title text-info text-gradient">{{$cd->title}}</h3>
                    <blockquote class="blockquote  mb-0 ps-3">
                        <p class="text-dark ms-3"> <?= $cd->description ?></p>
                        <footer class="blockquote-footer text-gradient text-info text-sm ms-3">
                            <cite title="Source Title">{{$cd->title}}</cite>
                        </footer>
                    </blockquote>
                </div>
            </div>
            <br>
            @endif
            @endforeach
        </div>
        <!-- Support End -->
    </div>
</div>
@endsection
@push('script')
<script>
// Form Handler

$(document).ready(function() {
    // Company Profile Image Handler Start
    $("#logoupload").validate({
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
            var form = $('form#logoupload');
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
                        flasher.success("Company Logo Successfully Updated");
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
    // Company Profile Image Handler End
    $("#profileForm").validate({
        rules: {
            companyname: {
                required: true,
            }
        },
        messages: {
            companyname: {
                required: "Please enter name",
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
                        // notify("Company Profile Successfully Updated" , 'success');
                        form.find('button:submit').prop('disabled', false);
                        flasher.success("Company Profile Successfully Updated");
                    } else {
                        // notify(data.status , 'warning');
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

});
</script>
@endpush