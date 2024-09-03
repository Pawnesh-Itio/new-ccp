@extends('layouts.user_type.auth')

@php
$table = "yes";
$agentfilter = "hide";
$status['type'] = "Api";
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
                                <h6 class="card-title">Api Manager
                                    <!-- type:setupapi slug:api_list,api_add,api_edit -->
                                    @if(isset($company_list))
                                    @if(!empty($company_list))
                                    <i class="fa fa-question-circle text-capitalize" style="float:right"
                                        data-bs-content="{{$company_list['description']}}" data-bs-trigger="hover focus"
                                        data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                        aria-hidden="true">
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Product
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Display
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Api Code
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">
                                            Credentials</th>
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
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Api
                </h5>
                <!-- edit -->
                @if(isset($api_edit))
                @if(!empty($api_edit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none"
                    data-bs-content="{{$api_edit['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                <!-- Add -->
                @endif
                @if(isset($api_add))
                @if(!empty($api_add))
                <i class="fa fa-question-circle text-capitalize add" style="display:none"
                    data-bs-content="{{$api_add['description']}}" data-bs-trigger="hover focus" data-bs-toggle="popover"
                    data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="setupManager" action="{{route('setupupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="api">
                        {{ csrf_field() }}
                        <div class="form-group mb-2 col-md-6">
                            <label>Product Name</label>
                            <input type="text" name="product" class="form-control" placeholder="Enter value"
                                required="">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Display Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter value" required="">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Url</label>
                            <input type="text" name="url" class="form-control" placeholder="Enter url">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter Value">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" placeholder="Enter url">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Optional1</label>
                            <input type="text" name="optional1" class="form-control" placeholder="Enter Value">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Api Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Enter url" required="">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Product Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="recharge">Recharge</option>
                                <option value="bill">Bill Payment</option>
                                <option value="money">Money transfer</option>
                                <option value="pancard">Pancard</option>
                                <option value="fund">Fund</option>
                                <option value="payment">Payment</option>
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
<!--View Credentials Modal start -->
<div id="credentialsModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">View</span> Api Credentials
                </h5>
                <!-- View -->
                @if(isset($api_view))
                @if(!empty($api_view))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none"
                    data-bs-content="{{$api_view['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group mb-2 col-md-6">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-2 col-md-6">
                        <label>username</label>
                        <input type="text" name="username" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-2 col-md-6">
                        <label>password</label>
                        <input type="text" name="password" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-2 col-md-6">
                        <label>optional1</label>
                        <input type="text" name="optional1" class="form-control" disabled>
                    </div>
                    <div class="form-group mb-2 col-md-6">
                        <label>product</label>
                        <input type="text" name="product" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End View Credentails Modal -->
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
        $('[data-popup="popover"]').popover({
            template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
        });
        $('input#apiStatus').on('click', function(evt) {
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
                        "actiontype": "api"
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("Company Updated", 'success');
                        flasher.success("Api Status Updated");
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
            "data": "product",
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
            "data": "code",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {

            "data": "action",
            render: function(data, type, full, meta) {
                return `<a href="javascript:void(0)" onclick="viewCredentials(\`` +full.url + `\`, \`` + full.username + `\`, \`` + full.password + `\`, \`` + full
                    .optional1 +
                    `\`, \`` + full.product + `\`)">Api Credentials</a>`;
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
                                <input type="checkbox" id="apiStatus" ` + check + ` value="` + full.id +
                    `" actionType="` + type + `">
                                <span class="slider round"></span>
                            </label>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                return `<button type="button"  class="btn btn-sm btn-icon btn-primary" onclick="editSetup(` +
                    full.id + `, \`` + full.product + `\`, \`` + full.name + `\`, \`` + full.url +
                    `\`, \`` + full.username + `\`, \`` + full.password + `\`, \`` + full.optional1 +
                    `\`, \`` + full.code + `\`, \`` + full.type + `\`)"> Edit</button>`;
            }
        },
    ];
    datatableSetup(url, options, onDraw);

    $("#setupManager").validate({
        rules: {
            name: {
                required: true,
            },
            product: {
                required: true,
            },
            code: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter display name",
            },
            product: {
                required: "Please enter product name",
            },
            url: {
                required: "Please enter api url",
            },
            code: {
                required: "Please enter api code",
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


function editSetup(id, product, name, url, username, password, optional1, code, type) {
    $('#setupModal').find('.msg').text("Edit");
    $('#setupModal').find('input[name="id"]').val(id);
    $('#setupModal').find('input[name="product"]').val(product);
    $('#setupModal').find('input[name="name"]').val(name);
    $('#setupModal').find('input[name="url"]').val(url);
    $('#setupModal').find('input[name="username"]').val(username);
    $('#setupModal').find('input[name="password"]').val(password);
    $('#setupModal').find('input[name="optional1"]').val(optional1);
    $('#setupModal').find('input[name="code"]').val(code);
    $('#setupModal').find('[name="type"]').val(type).trigger('change');
    $('#setupModal').modal('show');
    $('.edit').css("display", "block");
    $('.add').css("display", "none");
}

function viewCredentials(url, username, password, optional1, product) {
    $('#credentialsModal').find('input[name="url"]').val(url);
    $('#credentialsModal').find('input[name="username"]').val(username);
    $('#credentialsModal').find('input[name="password"]').val(password);
    $('#credentialsModal').find('input[name="optional1"]').val(optional1);
    $('#credentialsModal').find('input[name="product"]').val(product);
    $('#credentialsModal').modal('show');
}
</script>
@endpush