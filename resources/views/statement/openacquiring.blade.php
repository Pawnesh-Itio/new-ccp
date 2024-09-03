@extends('layouts.user_type.auth')
@php
$table = "yes";
$export= "aepsagentstatement";
$status['type'] = "Id";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"approved" => "Approved",
"rejected" => "Rejected",
];
@endphp
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Openacquiring Merchant Onboarding</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-4">
                        @if(!empty($openacquiring->id)) 
                        {{ $openacquiring->id}}
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
                        <form class="openacquiring card px-4" action="{{ route('openacquiring') }}" method="post">
                            {{ csrf_field() }}
                            <br>
                            <div class="row mb-3">
                                <div class="form-group col-md-4">
                                    <label for="merchant_id">Merchant Id</label>
                                    <input type="text" name="merchant_id" value="" class="form-control" required=""
                                        placeholder="Enter Value" id="merchant_id" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="client_id">Client Id</label>
                                    <input type="text" name="client_id" value="" required="" class="form-control"
                                        placeholder="Enter Value" id="client_id" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="client_secret">Client Secret</label>
                                    <input type="text" name="client_secret" class="form-control" value="" required=""
                                        placeholder="Enter Value" id="client_secret" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="form-group col-md-4">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" name="mobile" value="{{Auth::user()->mobile}}" id="mobile"
                                        class="form-control" value="" required="" placeholder="Enter Value">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" required=""
                                        placeholder="Enter Value" id="email" value="{{Auth::user()->email}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 col-md-offset-4">
                                    <button class="btn btn-primary px-3 btn-block" type="submit"
                                        data-loading-text="Please Wait...">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('change', '#country' , function(){
            let country_id = $(this).find(':selected').attr('data-id');
            $('#phone_code').val($(this).find(':selected').attr('data-code'));
            if(country_id>0){
                $.ajax({
                     url: `{{route('get_state')}}`,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{'country_id':country_id}
            })
            .done(function(data) {
                let html ='<option value="">Select State</option>';
                $.each(data, function( index, value ) {
                  html +='<option value="'+value.id+'">'+value.name+'</option>';
                });
                $('#state').html(html);
                $('.select2-container .select2-selection--multiple, .select2-container .select2-selection--single').css('height', '36px');
            })
            .fail(function(errors) {
               });
            }
        });
       $(".openacquiring").validate({
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
                var form = $('.openacquiring');
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
                             // $('#datatable').dataTable().api().ajax.reload();
                        }else{
                            // notify(data.message , 'warning');
                            flasher.success(data.message);
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