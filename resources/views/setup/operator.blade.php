@extends('layouts.user_type.auth')

@php
$table = "yes";
$agentfilter = "hide";
$product['type'] = "Operator Type";
$product['data'] = [
"mobile" => "Mobile",
"dth" => "Dth",
"electricity" => "Electricity",
"pancard" => "Pancard",
"dmt" => "Dmt",
"fund" => "Fund",
"lpggas" => "Lpg Gas",
"gasutility" => "Piped Gas",
"landline" => "Landline",
"postpaid" => "Postpaid",
"broadband" => "Broadband",
"loanrepay" => "Loan Repay",
"lifeinsurance" => "Life Insurance",
"fasttag" => "Fast Tag",
"cable" => "Cable",
"insurance" => "Insurance",
"schoolfees" => "School Fees",
"muncipal" => "Minicipal",
"housing" => "Housing"
];
asort($product['data']);
$status['type'] = "Operator";
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
                                <h6 class="card-title">Operator List
                                    <!-- type:setupoperator slug:operator_list,operator_add,operator_add,operator_edit -->
                                    @if(isset($operator_list))
                                    @if(!empty($operator_list))
                                    <i class="fa fa-question-circle text-capitalize" style="float:right"
                                        data-bs-content="{{$operator_list['description']}}"
                                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Top popover" aria-hidden="true">
                                    </i>
                                    @endif
                                    @endif
                                </h6>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" onclick="addSetup()">
                                    <span style="margin-right: 4px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-plus-circle">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                    </span>
                                    Add New
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-4">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Type</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Status
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Operator
                                            Api</th>
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
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Api
                </h5>
                <!-- edit -->
                @if(isset($operator_edit))
                @if(!empty($operator_edit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none"
                    data-bs-content="{{$operator_edit['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                <!-- Add -->
                @endif
                @if(isset($operator_add))
                @if(!empty($operator_add))
                <i class="fa fa-question-circle text-capitalize add" style="display:none"
                    data-bs-content="{{$operator_add['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="setupManager" action="{{route('setupupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="operator">
                        {{ csrf_field() }}
                        <div class="form-group mb-2 col-md-6">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Recharge1</label>
                            <input type="text" name="recharge1" class="form-control" placeholder="Enter value"
                                required="">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Recharge2</label>
                            <input type="text" name="recharge2" class="form-control" placeholder="Enter value"
                                required="">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Operator Type</label>
                            <select name="type" class="form-control select" required>
                                <option value="">Select Operator Type</option>
                                <option value="upi">UPI</option>
                                <option value="payout">Payout</option>
                                <option value="mobile">Mobile</option>
                                <option value="dth">DTH</option>
                                <option value="electricity">Electricity Bill</option>
                                <option value="pancard">Pancard</option>
                                <option value="dmt">Dmt</option>
                                <option value="aeps">Aeps</option>
                                <option value="fund">Fund</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Api</label>
                            <select name="api_id" class="form-control" required>
                                <option value="">Select Api</option>
                                @foreach ($apis as $api)
                                <option value="{{$api->id}}">{{$api->product}}</option>
                                @endforeach
                            </select>
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
    var url = "{{url('fetch')}}/setup{{$type}}/0";
    var onDraw = function() {
        $('input#operatorStatus').on('click', function(evt) {
            evt.stopPropagation();
            var ele = $(this);
            var id = $(this).val();
            var status = "0";
            if ($(this).prop('checked')) {
                status = "1";
            }

            $.ajax({
                    url: "{{route('setupupdate')}}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id,
                        'status': status,
                        "actiontype": "operator"
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("Company Updated", 'success');
                        flasher.success("Operator Status Updated");
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
    // <span class="name text-xs b-0 font-weight-bolder" style='color:black'>
    // <span class='text-xs b-0' style='color:black'></span>

    var options = [{
            "data": "id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "type",
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

                return `<label class="switch">
                                <input type="checkbox" id="operatorStatus" ` + check + ` value="` + full.id +
                    `" actionType="` + type + `">
                                <span class="slider round"></span>
                            </label>`;
            }
        },
        { "data" : "name",
                render:function(data, type, full, meta){
                    var out = "";
                    out += `<select class="form-control select" required="" onchange="apiUpdate(this, `+full.id+`)">`;
                    @foreach ($apis as $api)
                    var apiid = "{{$api->id}}";
                    out += `<option value="{{$api->id}}"`;
                    if(apiid == full.api_id){
                        out += `selected="selected"`;
                    }
                    out += `>{{$api->product}}</option>`;
                    @endforeach
                    out += `</select>`;
                    return out;
                }
            },
            { "data" : "action",
                render:function(data, type, full, meta){
                    return `<button type="button" class="btn btn-sm btn-icon btn-primary" onclick="editSetup(`+full.id+`, \``+full.name+`\`, \``+full.recharge1+`\`, \``+full.recharge2+`\`, \``+full.type+`\`, \``+full.api_id+`\`)"> Edit</button>`;
                }
            },
    ];
    datatableSetup(url, options, onDraw);

    $("#setupManager").validate({
        rules: {
            name: {
                    required: true,
                },
                recharge1: {
                    required: true,
                },
                recharge2: {
                    required: true,
                },
                type: {
                    required: true,
                },
                api_id: {
                    required: true,
                },
        },
        messages: {
            name: {
                    required: "Please enter operator name",
                },
                recharge1: {
                    required: "Please enter value",
                },
                recharge2: {
                    required: "Please enter value",
                },
                type: {
                    required: "Please select operator type",
                },
                api_id: {
                    required: "Please select api",
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
                        if (id == "0") {
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

function addSetup() {
    $('#setupModal').find('.msg').text("Add");
    $('#setupModal').find('input[name="id"]').val("0");
    $('#setupModal').modal('show');
    $('.edit').css("display", "none");
    $('.add').css("display", "block");
}


function editSetup(id, name, recharge1, recharge2, type, api_id) {
    $('#setupModal').find('.msg').text("Edit");
    	$('#setupModal').find('input[name="id"]').val(id);
    	$('#setupModal').find('input[name="name"]').val(name);
        $('#setupModal').find('input[name="recharge1"]').val(recharge1);
        $('#setupModal').find('input[name="recharge2"]').val(recharge2);
        $('#setupModal').find('[name="type"]').val(type).trigger('change');
        $('#setupModal').find('[name="api_id"]').val(api_id).trigger('change');
    	$('#setupModal').modal('show');
    $('.edit').css("display", "block");
    $('.add').css("display", "none");
}

function apiUpdate(ele, id){
        var api_id = $(ele).val();
        if(api_id != ""){
            $.ajax({
                url: '{{ route('setupupdate') }}',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data: {'id':id, 'api_id':api_id, "actiontype":"operator"}
            })
            .done(function(data) {
                if(data.status == "success"){
                    // notify("Operator Updated", 'success');
                    flasher.success("Operator API Updated")
                }else{
                    // notify("Something went wrong, Try again." ,'warning');
                    flasher.error("Something went wrong, Try again.")
                }
                $('#datatable').dataTable().api().ajax.reload();
            })
            .fail(function(errors) {
                showError(errors, "withoutform");
            });
        }
    }
</script>
@endpush