@extends('layouts.user_type.guest')

@section('content')

<div class="page-header section-height-75">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                <div class="card card-plain mt-8">
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
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h4 class="mb-0">Forgot your password? Enter your Mobile here</h4>
                    </div>
                    <div class="card-body">

                        <form id="forgot_password_form" class="login-post" method="POST" action="{{route('authReset')}}"
                            role="form text-left">
                            @csrf
                            <div>
                                <input type="hidden" name="type" value="request">
                                <label for="mobile">Mobile</label>
                                <div class="">
                                    <input type="text" class="form-control" name="mobile" id="mobile" pattern="[0-9]*"
                                        maxlength="14" minlength="10" placeholder="Enter Mobile..." required>
                                    @error('mobile')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Recover
                                    your password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                    <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                        style="background-image:url({{asset('assets/img/curved-images/curved6.jpg')}})"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- OTP Validation Modal -->
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
<!-- End OTP Validation Modal -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>
@endsection
@push('style')
<style>
.cross-btn {
    background-color: #141b3dff;
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
@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@push('script')
<script>
$(document).ready(function() {
    // Password Form Handler Start
    $("#forgot_password_form").validate({
        rules: {
            mobile: {
                required: true,
                number: true
            }
        },
        messages: {
            mobile: {
                required: "Please enter mobile number",
            }
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase() === "select") {
                error.insertAfter(element.closest(".input-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('#forgot_password_form');
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    swal({
                        title: 'Wait!',
                        text: 'We are checking your details',
                        buttons: false,
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                },
                success: function(data) {
                    swal.close();
                    if (data.status == "TXN") {
                        $('#staticBackdrop').find('.msg').text(
                            "OTP have been sent on you email address " + data
                            .email + "!");
                        $('#staticBackdrop').find('input[name="mobile"]').val(data
                            .mobile);
                        $('#staticBackdrop').find('input[name="email"]').val(data
                            .email);
                        $('#staticBackdrop').modal('show');
                        startTimer() // Start Timer for OTP resend 

                    } else {
                        flasher.error(data.message);
                    }
                },
                error: function(errors) {
                    swal.close();
                    if (errors.status == '400') {
                        if (errors.responseJSON.message) {
                            swal({
                                type: 'warning',
                                title: 'Warning',
                                text: errors.responseJSON.message,
                                confirmButtonText: 'Okay'
                            });
                        } else {
                            swal({
                                type: 'warning',
                                title: 'Warning',
                                text: errors.responseJSON.status,
                                confirmButtonText: 'Okay'
                            });
                        }
                    } else {
                        flasher.error('Something went wrong, try again later.');
                    }
                }
            });
        }
    });
});

// Resend OTP 
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
    $.ajax({
        url: '{{route("ConfirmEmail")}}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            otp: otp,
            mobile: mobile
        },
        success: function(response) {
            if (response.status == "success") {
                window.location.replace("<?= url("register/set-password") ?>");
                flasher.success("E-mail Verified Successfully");
            } else {
                flasher.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            flasher.error('Somthing went wrong, please try again later !');
        }
    });
}
// End OTP Function
</script>
@endpush