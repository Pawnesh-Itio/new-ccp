@extends('layouts.user_type.auth')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><span class="msg"></span></h5>
                </div>
                <div class="modal-body">
                    <!-- Start -->
                    <div class="container">
                        <div class="stepwizard col-md-offset-3">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button"
                                        class="btn btn-primary btn-circle profile-icon-box"><i
                                            class="fa fa-regular fa-user-circle-o profile-icon"></i></a>
                                    <p>User Profile</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-2" type="button" class="btn btn-default btn-circle company-icon-box"
                                        disabled="disabled"><i class="fa fa-building-o company-icon"
                                            aria-hidden="true"></i></a>
                                    <p>Company Profile</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-3" type="button" class="btn btn-default btn-circle kyc-icon-box"
                                        disabled="disabled"><i class="fa-solid fa-user-check kyc-icon"></i></a>
                                    <p>E-KYC</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-4" type="button"
                                        class="btn btn-default btn-circle complition-icon-box" disabled="disabled"><i
                                            class="fa-solid fa-check complition-icon"></i></a>
                                    <p>Verification</p>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- User Profile tab -->
                        <div class="row setup-content profile-content active" id="step-1">
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
                                                    <input type="email" class="form-control" value="{{$user->email}}" name="email" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Username</label>
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
                                                    <label>State</label>
                                                    <select name="state" id="state" class="form-control">
                                                        <option value class="text-uppercase">Select any one option
                                                        </option>
                                                        @foreach($state AS $s)
                                                        <option value="{{$s->state}}"
                                                            <?= ($user->state == $s->state) ? 'selected' : ''?>
                                                            class="text-uppercase">
                                                            {{$s->state}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>City</label>
                                                    <input class="form-control" value="{{$user->city}}" type="text"
                                                        name="city" id="city" placeholder="Enter Your City..."
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea name="address" id="address" cols="30" rows="3"
                                                        class="form-control"
                                                        placeholder="Enter Your Address...">{{$user->address}}</textarea>
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
                                        <br>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </form>
                        </div>
                        <!-- Company Profile Tab -->
                        <div class="row setup-content company-content inactive" id="step-2">
                            <form id="companyForm" action="{{url('block-user-resource-update')}}" method="POST">
                                <input type="hidden" name="id" value="new">
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
                                                        placeholder="Enter Website Url" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company Logo</label>
                                                    <input type="file" name="file" class="form-control" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-gorup">
                                                    <label>Sender Id</label>
                                                    <input type="text" name="senderid" class="form-control"
                                                        placeholder="Sender Id" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
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
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </form>
                        </div>
                        <!-- End Company Tab -->
                        <!-- KYC Tab -->
                        <div class="row setup-content kyc-content inactive" id="step-3">
                            <form id="kycForm" action="{{url('block-user-kyc-update')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$user->id}}">
                                <input type="hidden" name="usertype" value="block">
                                <input type="hidden" name="mobile" value="{{$user->mobile}}">
                                <input type="hidden" name="email" value="{{$user->email}}">
                                <div class="col-xs-6 col-md-offset-3">
                                    <div class="col-md-12">
                                        <h3> E-KYC</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Shopname</label>
                                                    <input type="text" name="shopname" value="{{ $user->shopname }}"
                                                        class="form-control" id="shopname"
                                                        placeholder="Enter Your Shopname...">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GSTIN</label>
                                                    <input type="text" name="gstin" value="{{$user->gstin}}"
                                                        class="form-control" id="gstin"
                                                        placeholder="Enter Your GSTIN...">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pancard Number</label>
                                                    <input type="text" name="pancard" class="form-control"
                                                        value="{{$user->pancard}}"
                                                        placeholder="Enter Your Pancard Number..."
                                                        @if(\App\Helpers\Permission::hasNotRole('admin') &&
                                                        $user->kyc=='verified') disabled @endif required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Aadharcard Number</label>
                                                    <input type="text" name="aadharcard" value="{{ $user->aadharcard }}"
                                                        class="form-control" id="aadharcard" required=""
                                                        placeholder="Enter Value" maxlength="12" minlength="12"
                                                        @if(\App\Helpers\Permission::hasNotRole('admin') &&
                                                        $user->kyc=='verified')disabled=""@endif>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Aadharcard Pics( Select both Front and Back side images )</label>
                                                <input type="file" class="form-control" id="aadharcardpics"
                                                    name="aadharcardpics[]" multiple placeholder="Adhaarcard Pic">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Pancard Pic</label>
                                                <input type="file" class="form-control" id="pancardpics"
                                                    name="pancardpics" placeholder="Pancard Pic">
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
</main>
@endsection
@push('style')
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
                    // notify(data.status, 'warning');
                    flasher.error(data.status);
                }
            },
            error: function(errors) {
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
        },
        file: {
            required: true,
        },
        senderid: {
            required: true,
        },
        smsuser: {
            required: true,
        },
        smspwd: {
            required: true,
        }
    },
    messages: {
        companyname: {
            required: "Please Enter Name..."
        },
        website: {
            required: "Please Enter Website..."
        },
        file: {
            required: "Please Add Company Logo..."
        },
        senderid: {
            required: "Please Enter Sender Id..."
        },
        smsuser: {
            required: "Please Enter SMS User..."
        },
        smspwd: {
            required: "Please Enter Pincode..."
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
                    flasher.error(data.status);
                }
            },
            error: function(errors) {
                showError(errors, form.find('.panel-body'));
            }
        });
    }
});
// End kyc form handler.
$("#kycForm").validate({
    rules: {
        shopname: {
            required: true,
        },
        pancard: {
            required: true,
        },
        aadharcard: {
            required: true,
            minlength: 12,
            number: true,
            maxlength: 12
        }
    },
    messages: {
        aadharcard: {
            required: "Please enter aadharcard",
            number: "Aadhar number should be numeric",
            minlength: "Aadhar number must be of 12 digit",
            maxlength: "Aadhar number must be of 12 digit"
        },
        pancard: {
            required: "Please enter pancard",
        },
        shopname: {
            required: "Please enter shop name",
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
                    flasher.error(data.status);
                }
            },
            error: function(errors) {
                showError(errors, form.find('.panel-body'));
            }
        });
    }
});
// End Company form handler.
</script>
@endpush