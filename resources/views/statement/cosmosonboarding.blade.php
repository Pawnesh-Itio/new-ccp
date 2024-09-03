@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    @if($iscagent)
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-cover text-center"
                    style="background-image: url('https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/curved-images/curved1.jpg')">
                    <div class="card-body z-index-2 py-9">
                        <h2 class="text-white">Onboarding Status</h2>
                        @if(isset($iscagent->sid) && $iscagent->sid != "")
                        <label class="badge badge-light text-success"
                            style="background-color:white"><strong>Completed</strong></label><br>
                        <span class="text-white">Note: You are eligible to the transaction.</span>
                        @else
                        <label class="badge badge-light text-danger "
                            style="background-color:white"><strong>Pending</strong></label>
                        @endif
                    </div>
                    <div class="mask bg-gradient-primary border-radius-lg"></div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Cosmos Merchant Onboarding</h6>
                    </div>
                    <div class="card-body mb-4">
                        <!-- Onboarding form -->
                        <form class="cosmosForm card px-4" action="{{ route('cosmosonboard') }}" method="post">
                            {{ csrf_field() }}
                            <br>
                            <div class="row mb-3">
                                <div class="form-group col-md-4">
                                    <label>Bussiness Name</label>
                                    <input type="text" name="businessName" value="{{Auth::user()->shopname}}"
                                        class="form-control" required="" placeholder="Enter Value">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile</label>
                                    <input type="number" name="mobileNumber" value="{{Auth::user()->mobile}}"
                                        required="" class="form-control" placeholder="Enter Value">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>GST Number</label>
                                    <input type="text" name="gstn" class="form-control" value="{{Auth::user()->gstin}}" required=""
                                        placeholder="Enter Value">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-md-12">
                                    <label>Address</label>
                                    <textarea name="address" class="form-control" rows="3" required=""
                                        placeholder="Enter Value">{{Auth::user()->address}}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-md-4">
                                    <label>Country</label>
                                    <input type="text" name="locationCountry" class="form-control" value="" required=""
                                        placeholder="Enter Value">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>State</label>
                                    <select name="state" class="form-control select" required="">
                                        <option value="">Select State</option>
                                        @foreach ($state as $state)
                                        <option value="{{$state->state}}"<?php if($state->state == Auth::user()->state){ echo "selected"; } ?>>{{$state->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control" value="{{Auth::user()->city}}" required=""
                                        placeholder="Enter Value">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-md-4">
                                    <label>Pincode</label>
                                    <input type="number" name="pincode" class="form-control" value="{{Auth::user()->pincode}}" required=""
                                        maxlength="6" minlength="6" placeholder="Enter Value">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Pancard Number</label>
                                    <input type="text" name="pancard" value="{{Auth::user()->pancard}}"
                                        class="form-control" value="" required="" placeholder="Enter Value">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>WebURL</label>
                                    <input type="text" name="WebURL" class="form-control" required=""
                                        placeholder="Enter Value">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 col-md-offset-4">
                                    <button class="btn btn-primary px-3 btn-block" type="submit"
                                        data-loading-text="Please Wait...">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!-- End Onboarding From -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</main>
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function () {
       $(".cosmosForm").validate({
            rules: {
                businessName: {
                    required: true
                }
            },
            messages: {
                businessName: {
                    required: "Please enter value.",
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('.cosmosForm');
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                         swal({
                                title: 'Wait!',
                                text: 'Processing......',
                                onOpen: () => {
                                    swal.showLoading()
                                },
                                allowOutsideClick: () => !swal.isLoading()
                            });
                    },
                    complete: function () {
                        form.find('button:submit').button('reset');
                    },
                    success:function(data){         
                        swal.close();
                        if(data.statuscode == "TXN"){
                            swal({
                                type: 'success',
                                title : 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                               });
                                window.location.reload();
                             // $('#datatable').dataTable().api().ajax.reload();
                        }else{
                            // notify(data.message , 'warning');
                            flasher.error(data.message);    
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });
    });
</script>
@endpush