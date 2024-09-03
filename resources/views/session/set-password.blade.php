@extends('layouts.user_type.guest')

@section('content')
<div class="page-header section-height-75">
    <div class="container">
        <div class="">
            <div class="row pb-4">
                <div class="col-lg-12 ">
                    <div class="card card-plain mt-8 ">
                        <div class="card-header pb-0 text-center bg-transparent">
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
                            <div class="mt-3 alert alert-success alert-dismissible fade show" id="alert-success"
                                role="alert">
                                <span class="alert-text text-white">
                                    {{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                            @endif
                            <h1 class="display-5 mb-1">Congratulations, {{$user->name}} 🎉</h1>
                            <h5 class="mb-0"> on Confirming Your Email! Now, Let's Set Up Your Password </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-4">
                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <div class="card ">
                        <div class="card-body">
                            <form id="passwordForm" action="{{route('StorePassword')}}" method="post"
                                role="form text-left">
                                @csrf
                                <input type="hidden" name="mobile" value="{{$user->mobile}}">
                                <div>
                                    <label for="mobile">New Password <i class="fa fa-question-circle text-capitalize"
                                            data-bs-title="Password Validation" data-bs-html="true" data-bs-content="
                                            <ol>
                                                <li>Password must contain atleast one Uppercase and one Lowercase Letter.</li>
                                                <li>Password must contain atleast one numrical value.</li>
                                                <li>Password must contain atleast one special character</li>
                                                <li>Password length atleast of 8 characters.</li>
                                            </ol>" data-bs-trigger="hover focus" data-bs-toggle="popover"
                                            data-bs-placement="left" data-bs-content="Top popover" aria-hidden="true">
                                        </i></label>
                                    <div class="">
                                        <span id="togglePassword" class="toggle-password">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <input type="password" class="form-control" name="password" id="password"
                                            minlength="8" placeholder="Enter Your New Password..." required>
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <label for="mobile">Confirm Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" name="password_confirmation"
                                            id="password_confirmation" minlength="8"
                                            placeholder="Enter Confirm Password..." required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Set Up Your
                                        Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
.toggle-password {
    position: relative;
    top: 30;
    left: 90%;
}
</style>
@endpush
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@push('script')
<script>
$(document).ready(function() {
    // Password Form Handler Start
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
                equalTo: "New password and confirmed password should be same"
            },
            password: {
                required: "Please enter new password",
                minlength: "Your password lenght should be atleast 8 character",
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
            var form = $('#passwordForm');
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
                        window.location.reload();
                        flasher.success("You are logged in...", 'success');
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
                    form.find('button:submit').prop('disabled', false);
                    if (errors.status == '400') {
                        if (errors.responseJSON.message) {
                            flasher.error(errors.responseJSON.message);
                        } else {
                            flasher.error(errors.responseJSON.status);
                        }
                    } else {
                        flasher.error('Something went wrong, try again later.');
                    }
                }
            });
        }
    });
    // Password Form Handler Ends
});
// 
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
</script>
@endpush