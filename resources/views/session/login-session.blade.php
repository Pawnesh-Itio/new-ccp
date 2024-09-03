@extends('layouts.user_type.guest')

@section('content')

<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-info text-gradient">Welcome back</h3>
                                <p class="mb-0">Create a new acount<br></p>
                            </div>
                            <div class="card-body">
                                <form id="loginForm" role="form" method="POST" action="{{url('session')}}"
                                    class="login-post">
                                    @csrf
                                    <label>Mobile</label>
                                    <div class="mb-3">
                                        <input type="tel" class="form-control" name="mobile" id="mobile"
                                            placeholder="Mobile" aria-label="mobile" aria-describedby="mobile-addon">
                                        @error('Mobile')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Password" aria-label="Password"
                                            aria-describedby="password-addon">
                                        @error('password')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign
                                            in</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <small class="text-muted">Forgot you password? Reset you password
                                    <a href="{{url('login/forgot-password')}}"
                                        class="text-info text-gradient font-weight-bold">here</a>
                                </small>
                                <p class="mb-4 text-sm mx-auto">
                                    Don't have an account?
                                    <a href="register" class="text-info text-gradient font-weight-bold">Sign up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                                style="background-image:url('{{asset('assets/img/curved-images/curved6.jpg')}}')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@push('script')
<script>
// loginForm
$(document).ready(function() {
$("#loginForm").validate({
    rules: {
        mobile: {
            required: true,
            minlength: 10,
            number: true,
            maxlength: 10
        },
        password: {
            required: true
        }
    },
    messages: {
        mobile: {
            required: "Please enter mobile number...",
            number: "Mobile number should be numeric",
            minlength: "Your mobile number must be 10 digit",
            maxlength: "Your mobile number must be 10 digit"
        },
        mobile: {
            required: "Please enter the password"
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
        var form = $('form#loginForm');
        form.find('span.text-danger').remove();
        form.find('button:submit').prop('disabled', true);
        form.ajaxSubmit({
            dataType: 'json',
            beforeSend: function() {
                form.find('button:submit').prop('disabled', true);
                form.find('button:submit').button('loading');
                swal({
                    title: 'Wait!',
                    text: 'Please wait, we are working on your request',
                    button: false,
                    onOpen: () => {
                        swal.showLoading()
                    }
                })
            },
            complete: function() {  
                form.find('button:submit').button('reset');
                swal.close();
            },
            success: function(data) {
                if (data.status == "success") {
                    swal.close();
                    swal({
                    title: 'Wait!',
                    text: 'Please wait, we are working on your request',
                    icon: "success",
                    button: false,
                    onOpen: () => {
                        swal.showLoading()
                    }
                })
                    window.location.reload();
                    flasher.success("You are logged in.");
                } else {
                   
                    form.find('button:submit').attr('disabled', 'false');
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
});
</script>
</script>
@endpush()