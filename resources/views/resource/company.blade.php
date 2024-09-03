@extends('layouts.user_type.auth')

@php
$table = "yes";
$agentfilter = "hide";
$status['type'] = "Company";
$status['data'] = [
"1" => "Active",
"0" => "De-active"
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
                                <h6 class="card-title">Company Manager
                                </h6>
                            </div>
                            <div class="col-sm-2">
                                <!-- type:resourcecompany slug:company_list,company_add,company_edit -->
                                @if(isset($company_list))
                                @if(!empty($company_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$company_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-4">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Company
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Merchant
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Domain
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Status
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--Setup Modal start -->
<div id="setupModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Edit</span> Company
                </h5>
                                    <!-- edit -->
                                    @if(isset($company_edit))
                    @if(!empty($company_edit))
                    <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$company_edit['description']}}"
                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-content="Top popover" aria-hidden="true">
                    </i>
                    @endif
                    @endif
            </div>
            <form id="setupManager" action="{{route('resourceupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="company">
                        {{ csrf_field() }}
                        <div class="form-group mb-2 col-md-12">
                            <label>Name</label>
                            <input type="text" name="companyname" class="form-control" placeholder="Enter Company Name"
                                required="">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control" placeholder="Enter Website Url"
                                required="">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Senderid</label>
                            <input type="text" name="senderid" class="form-control" placeholder="Enter Sms Senderid">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Smsuser</label>
                            <input type="text" name="smsuser" class="form-control" placeholder="Enter Sms Username">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Smspwd</label>
                            <input type="text" name="smspwd" class="form-control" placeholder="Enter Sms Password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                        aria-hidden="true">Close</button>
                    <button class="btn btn-primary px-4" type="submit"
                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Setup Modal -->
@endsection
@push('style')
<style>
.action-icon-add {
    list-style: none;
}
</style>
@endpush
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/resource{{$type}}/0";
    var onDraw = function() {
        $('input#companyStatus').on('click', function(evt) {
            evt.stopPropagation();
            var ele = $(this);
            var id = $(this).val();
            var status = "0";
            if ($(this).prop('checked')) {
                status = "1";
            }

            $.ajax({
                    url: "{{route('resourceupdate')}}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id,
                        'status': status,
                        "actiontype": "company"
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("Company Updated", 'success');
                        flasher.success("Company Status Updated");
                        $('#datatable').dataTable().api().ajax.reload();
                    } else {
                        if (status == "1") {
                            ele.prop('checked', false);
                        } else {
                            ele.prop('checked', true);
                        }
                        // notify("Something went wrong, Try again.", 'warning');
                        flasher.error("Something went wrong, Try again.");
                    }
                })
                .fail(function(errors) {
                    if (status == "1") {
                        ele.prop('checked', false);
                    } else {
                        ele.prop('checked', true);
                    }
                    showError(errors, "withoutform");
                });
        });
    };
    var options = [{
            "data": "id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "companyname",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "username",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.name + `</span>`;
            }
        },
        {
            "data": "website",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                var check = "";
                if (full.status == "1") {
                    check = "checked='checked'";
                }

                return `<div class="switch-checkboxinp">
                                 <label class="switch">
                                    <input id="companyStatus" type="checkbox" ` + check + ` value="` + full.id +
                    `" actionType="` + type + `">
                    <span class="slider round"></span>
                                 </label>
                              </div>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {

                var out = `<a href="javascript:void(0)" class='text-xs b-0' onclick="editSetup('` + full
                    .id + `', '` + full
                    .companyname + `', '` + full.website + `', '` + full.senderid + `', '` + full
                    .smsuser + `', '` + full.smspwd + `')">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                       </svg>Edit Manager Detail                                      
                                    </a>`;
                return out;
            }
        },
    ];
    datatableSetup(url, options, onDraw);

    $("#setupManager").validate({
        rules: {
            name: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Please enter bank name",
            }
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
            var form = $('#setupManager');
            var id = form.find('[name="id"]').val();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button[type="submit"]').button('loading');
                },
                success: function(data) {
                    if (data.status == "success") {
                        if (id == "new") {
                            form[0].reset();
                        }
                        form.find('button[type="submit"]').button('reset');
                        // notify("", 'success');
                        flasher.success("Task Successfully Completed");
                        $('#setupModal').modal('hide');
                        $('#datatable').dataTable().api().ajax.reload();
                    } else {
                        // notify(data.status, 'warning');
                        flasher.error(data.status);
                    }
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
        }
    });

    $("#setupModal").on('hidden.bs.modal', function() {
        $('#setupModal').find('.msg').text("Add");
        $('#setupModal').find('form')[0].reset();
    });

});


function editSetup(id, companyname, website, senderid, smsuser, smspwd) {
    $('#setupModal').find('.msg').text("Edit");
    $('#setupModal').find('input[name="id"]').val(id);
    $('#setupModal').find('input[name="companyname"]').val(companyname);
    $('#setupModal').find('input[name="website"]').val(website);
    $('#setupModal').find('input[name="senderid"]').val(senderid);
    $('#setupModal').find('input[name="smsuser"]').val(smsuser);
    $('#setupModal').find('input[name="smspwd"]').val(smspwd);
    $('#setupModal').modal('show');
}
</script>
@endpush