@extends('layouts.user_type.auth')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Personal Information</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <form class="memberForm" action="{{ route('memberstore') }}" method="POST" role="form text-left"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @if (!$role)
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="card-title">Member Type Information</h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Member Type</label>
                                        <select name="role_id" class="form-control select" required="">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="role_id" value="{{$role->id}}">
                            @endif
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" id="name" placeholder="Enter Name..." required
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="number" name="mobile" id="mobile" placeholder="Enter Mobile..."
                                            required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" placeholder="Enter Email..."
                                            required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" id="address" required placeholder="Enter Address..."
                                            class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select name="country" id="country" onchange="getState(this.value)"
                                            class="form-control" required>
                                            <option value="">Select any one country</option>
                                            @foreach($countries as $cs)
                                            <option value="{{$cs['country_name']}}">{{$cs['country_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select name="state" id="state" onchange="getCity(this.value)" class="form-control" required disabled>
                                            <option value="">Select State</option>
                                            <!-- Options will be dynamically added here -->
                                        </select>
                                        <div class="loader-state hide">
                                            <img src="{{asset('assets/img/loader/city.gif')}}" width="44px"
                                                height="44px" alt="...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <select name="city" id="city" class="form-control" required disabled>
                                            <option value="">Select City</option>
                                            <!-- Options will be dynamically added here -->
                                        </select>
                                        <div class="loader-city hide">
                                            <img src="{{asset('assets/img/loader/city.gif')}}" width="44px"
                                                height="44px" alt="...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input name="pincode" type="number" id="pincode" class="form-control"
                                            placeholder="Enter Pincode..." maxlength="6" minlength="6" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Identity Proof Number</label>
                                        <input type="text" name="aadharcard" id="aadharcard" class="form-control"
                                            placeholder="Enter Identity Proof Number..." required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Address Proof Number</label>
                                        <input type="text" name="pancard" id="pancard" class="form-control"
                                            placeholder="Enter Address Proof number..." required>
                                    </div>
                                </div>
                                <!-- Company form -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" name="companyname" class="form-control"
                                            placeholder="Enter Company Name..." required="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" name="website" class="form-control"
                                            placeholder="Enter Website Url" required="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company Logo</label>
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                </div>
                                @if(App\Helpers\Permission::hasRole('admin') || (isset($mydata['schememanager']) &&
                                $mydata['schememanager']->value == "all"))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Scheme</label>
                                        <select name="scheme_id" class="form-control select" required="">
                                            <option value="">Select Scheme</option>
                                            @foreach ($scheme as $scheme)
                                            <option value="{{$scheme->id}}">{{$scheme->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currancy_id" id="currancy_id" class="form-control" required>
                                            <option value="">Select any one currency</option>
                                            @foreach($currencies as $currancy)
                                            <option value="{{$currancy->id}}">{{$currancy->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if($role->slug == "whitelable")
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="card-title">Whitelable Information</h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" name="companyname" class="form-control" value="" required=""
                                            placeholder="Enter Company Name...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Domain</label>
                                        <input type="url" name="website" class="form-control" value="" required=""
                                            placeholder="Enter Domain...">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="d-flex jusxtify-content-end">
                                <button type="submit" data-loading-text="Please Wait..."
                                    class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('style')
<style>
.loader-state {
    position: absolute;
    top:42%;
    left:48%;
}
.loader-city {
    position: absolute;
    top:42%;
    left:80%;
}
</style>
@endpush
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    $(".memberForm").validate({
        rules: {
            name: {
                required: true,
            },
            mobile: {
                required: true,
                minlength: 10,
                number: true,
                maxlength: 13
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
            },
            address: {
                required: true,
            },
            aadharcard: {
                required: true,
            }
            @if($role -> slug == "whitelable"),
            companyname: {
                required: true,
            },
            website: {
                required: true,
                url: true
            },
            currancy_id: {
                required: true
            }
            @endif
        },
        messages: {
            name: {
                required: "Please enter name",
            },
            mobile: {
                required: "Please enter mobile",
                number: "Mobile number should be numeric",
                minlength: "Your mobile number must be 10 digit",
                maxlength: "Your mobile number must be 13 digit"
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email address",
            },
            state: {
                required: "Please select state",
            },
            city: {
                required: "Please select city",
            },
            pincode: {
                required: "Please enter pincode",
            },
            address: {
                required: "Please enter address",
            },
            currancy_id:{
                required: "Please select currency",
            },
            aadharcard: {
                required: "Please enter address proof number",
            }
            @if($role -> slug == "whitelable"),
            companyname: {
                required: "Please enter company name",
            },
            website: {
                required: "Please enter company website",
                url: "Please enter valid company url"
            }
            @endif
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase() === "select") {
                error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('form.memberForm');
            form.find('span.text-danger').remove();
            $('form.memberForm').ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        // alert("success");
                        form[0].reset();
                        // notify("Member Successfully Created" , 'success');
                        flasher.success("Member Successfully Created");
                    } else {
                        // alert("error");
                        // notify(data.status , 'warning');
                        flasher.error(data.status);
                    }
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
        }
    });
});

function getState(country) {
    $('.loader-state').show();
    $('#state').prop('disabled', true);
    $.ajax({
        url: "{{route('state')}}/"+country,
        method: "get",
        dataType: "json",
        success: function(data) {
            $('#state').empty();
            $('#state').append("<option value=''>Select State</option>");
            data.forEach(function (state){
                $('#state').append(new Option(state.state_name,state.state_name));
            });
            $('.loader-state').hide();
            $('#state').prop('disabled',false);
        }
    });
}
function getCity(state){
    $('.loader-city').show();
    $('#city').prop('disabled', true);
    $.ajax({
        url: "{{route('city')}}/"+state,
        method:"get",
        data:"json",
    })
    .success(function(data){
        $('#city').empty();
        $('#city').append("<option value=''>Select City</option>");
        data.forEach(function (city){
            $('#city').append(new Option(city.city_name,city.city_name));
        });
        $('.loader-city').hide();
        $('#city').prop('disabled',false);
    });
}
</script>
@endpush