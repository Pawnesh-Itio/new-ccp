@extends('layouts.user_type.auth')

@php
$table = "yes";
$agentfilter = "hide";
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
                                <h6 class="card-title">Acquirer Manager
                                    <!-- type:setupapi slug:acquirer_list,acquirer_add,acquirer_edit -->
                                    @if(isset($acquirer_list))
                                    @if(!empty($acquirer_list))
                                    <i class="fa fa-question-circle text-capitalize" style="float:right"
                                        data-bs-content="{{$acquirer_list['description']}}"
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Acquirer
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Acquirer
                                            Slug</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">API
                                            Endpoint</th>
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
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Acquirer
                </h5>
                <!-- edit -->
                @if(isset($acquireri_edit))
                @if(!empty($acquirer_edit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none"
                    data-bs-content="{{$acquirer_edit['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                <!-- Add -->
                @endif
                @if(isset($acquirer_add))
                @if(!empty($acquirer_add))
                <i class="fa fa-question-circle text-capitalize add" style="display:none"
                    data-bs-content="{{$acquirer_add['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="setupManager" action="{{route('setupupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <label>Acquirer Details</label>
                        <hr>
                        <input type="hidden" name="acquirer_id">
                        <input type="hidden" name="actiontype" value="acquirer">
                        {{ csrf_field() }}
                        <div class="form-group mb-2 col-md-6">
                            <label>Acquirer Name</label>
                            <input type="text" name="acquirer_name" class="form-control" placeholder="Enter value"
                                required>
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>API Endpoint</label>
                            <input type="text" name="api_endpoint" class="form-control" placeholder="Enter value"
                                required>
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Acquirer Slug</label>
                            <input type="text" name="acquirer_slug" class="form-control" placeholder="Enter value"
                                required>
                        </div>
                    </div>
                    <div class="row">
                        <label>Key Setup</label>
                        <hr>
                        <div class="form-group mb-2 col-md-6">
                            <label>Test Public Key</label>
                            <input type="text" name="test_public_key" class="form-control"
                                placeholder="Test Public Key">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Test Secret Key</label>
                            <input type="text" name="test_secret_key" class="form-control"
                                placeholder="Test Secret Key">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Live Public Key</label>
                            <input type="text" name="live_public_key" class="form-control"
                                placeholder="Live Public Key">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Live Secret Key</label>
                            <input type="text" name="live_secret_key" class="form-control"
                                placeholder="Live Secret Key">
                        </div>
                    </div>
                    <div class="row">
                        <label>URL Setup</label>
                        <hr>
                        <div class="form-group mb-2 col-md-6">
                            <label>Return URL</label>
                            <input type="text" name="return_url" class="form-control" placeholder="Return url">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Webhook URL</label>
                            <input type="text" name="webhook_url" class="form-control" placeholder="Return url">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Success URL</label>
                            <input type="text" name="success_url" class="form-control" placeholder="Success url">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Failure URL</label>
                            <input type="text" name="failur_url" class="form-control" placeholder="Failure url">
                        </div>
                    </div>
                    <div class="row">
                        <label>Other</label>
                        <hr>
                        <div class="form-group mb-2 col-md-6">
                            <label>Public Key</label>
                            <input type="text" name="public_key" class="form-control" placeholder="Public Key">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Ter No</label>
                            <input type="text" name="terno" class="form-control" placeholder="Ter No">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Token</label>
                            <input type="text" name="token" class="form-control" placeholder="Token">
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label>Authorization</label>
                            <input type="text" name="authorization" class="form-control" placeholder="Authorization">
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
        $('[data-popup="popover"]').popover({
            template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
        });
        $('input#acquirerStatus').on('click', function(evt) {
            evt.stopPropagation();
            var ele = $(this);
            var id = $(this).val();
            var status = "no";
            if ($(this).prop('checked')) {
                status = "yes";
            }

            $.ajax({
                    url: "{{route('setupupdate')}}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    dataType: 'json',
                    data: {
                        'acquirer_id': id,
                        'is_active': status,
                        "actiontype": "acquirer"
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("Company Updated", 'success');
                        flasher.success("Acquirer Status Updated");
                        $('#datatable').dataTable().api().ajax.reload();
                    } else {
                        if (status == "yes") {
                            ele.prop('checked', false);
                        } else {
                            ele.prop('checked', true);
                        }
                        // notify("Something went wrong, Try again.", 'warning');
                        flasher.error("Something went wrong, Try again.");
                    }
                })
                .fail(function(errors) {
                    if (status == "yes") {
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
            "data": "acquirer_id",
            render: function(data, type, full, meta) {
                var check = "";
                if (full.is_active == "yes") {
                    check = "checked='checked'";
                }
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>

                <div class="switch-checkboxinp">
                                 <label class="switch">
                                    <input id="acquirerStatus" type="checkbox" ` + check + ` value="` + full
                    .acquirer_id +
                    `" actionType="` + type + `">
                    <span class="slider round"></span>
                                 </label>
                              </div>
                `;
            }
        },
        {
            "data": "acquirer_name",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data +
                    `</span>`;
            }
        },
        {
            "data": "acquirer_slug",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data +
                    `</span>`;
            }
        },
        {
            "data": "api_endpoint",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data +
                    `</span>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                return `<button type="button"  class="btn btn-sm btn-icon btn-primary" onclick="editSetup(` +
                    full.acquirer_id + `, \`` + full.acquirer_name + `\`, \`` + full.acquirer_slug + `\`, \`` + full
                    .api_endpoint + `\`)"> Edit</button>
                    <button type="button" class="btn bg-danger legitRipple btn-xs"
                    onclick="deleteAcquirer(` +
                    full.acquirer_id + `)"> <i class="fa fa-trash text-white"></i></button>
                    `;
            }
        }
    ];
    datatableSetup(url, options, onDraw);

    $("#setupManager").validate({
        rules: {
            acquirer_name: {
                required: true,
            },
            api_endpoint: {
                required: true,
            },
            testing_endpoint: {
                required: true,
            },
        },
        messages: {
            acquirer_name: {
                required: "Please enter acquirer name",
            },
            api_endpoint: {
                required: "Please enter api endpoint",
            },
            testing_endpoint: {
                required: "Please enter testing endpoint",
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
                    form.find('button[type="submit"]').button(
                        'loading');
                },
                success: function(data) {
                    if (data.status == "success") {
                        if (id == "0") {
                            form[0].reset();
                        }
                        form.find('button[type="submit"]').button(
                            'reset');
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
    $('#setupModal').find('input[name="acquirer_id"]').val("0");
    $('#setupModal').modal('show');
    $('.edit').css("display", "none");
    $('.add').css("display", "block");
}


function editSetup(acquirer_id, acquirer_name,acquirer_slug, api_endpoint) {
    $.ajax({
            url: '{{route("getAcquirerFields")}}',
            type: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "acquirer_id": acquirer_id
            },
            beforeSend: function() {
                swal({
                    title: 'Wait!',
                    buttons: false,
                    text: 'Please wait, we are fetching Acquirers',
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
        })
        .success(function(response) {
            swal.close();
            var responseObject = JSON.parse(response.data);
            $('#setupModal').find('.msg').text("Edit");
            $('#setupModal').find('input[name="acquirer_id"]').val(acquirer_id);
            $('#setupModal').find('input[name="acquirer_name"]').val(acquirer_name);
            $('#setupModal').find('input[name="api_endpoint"]').val(api_endpoint);
            $('#setupModal').find('input[name="acquirer_slug"]').val(acquirer_slug);
            if ('test_public_key' in responseObject) {
                $('#setupModal').find('input[name="test_public_key"]').val(responseObject.test_public_key);
            }
            if ('test_secret_key' in responseObject) {
                $('#setupModal').find('input[name="test_secret_key"]').val(responseObject.test_secret_key);
            }
            if ('live_public_key' in responseObject) {
                $('#setupModal').find('input[name="live_public_key"]').val(responseObject.live_public_key);
            }
            if ('live_secret_key' in responseObject) {
                $('#setupModal').find('input[name="live_secret_key"]').val(responseObject.live_secret_key);
            }
            if ('return_url' in responseObject) {
                $('#setupModal').find('input[name="return_url"]').val(responseObject.return_url);
            }
            if ('webhook_url' in responseObject) {
                $('#setupModal').find('input[name="webhook_url"]').val(responseObject.webhook_url);
            }
            if ('success_url' in responseObject) {
                $('#setupModal').find('input[name="success_url"]').val(responseObject.success_url);
            }
            if ('failur_url' in responseObject) {
                $('#setupModal').find('input[name="failur_url"]').val(responseObject.failur_url);
            }
            if ('public_key' in responseObject) {
                $('#setupModal').find('input[name="public_key"]').val(responseObject.public_key);
            }
            if ('terno' in responseObject) {
                $('#setupModal').find('input[name="terno"]').val(responseObject.terno);
            }
            if ('token' in responseObject) {
                $('#setupModal').find('input[name="token"]').val(responseObject.token);
            }
            if ('authorization' in responseObject) {
                $('#setupModal').find('input[name="authorization"]').val(responseObject.authorization);
            }
            $('#setupModal').modal('show');
            $('.edit').css("display", "block");
            $('.add').css("display", "none");
            // $('#acquirerFieldsViewModal').find('.acquirerFieldData').html(data);
        })
        .fail(function() {
            swal.close();
            // notify('Somthing went wrong', 'warning');
            flasher.error('Somthing went wrong');
        });
}

function viewAcquirerFields(id) {
    $('#acquirerFieldsViewModal').find('button[name="addfield"]').val(id);

    // 
    $.ajax({
            url: '{{route("getAcquirerFields")}}',
            type: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "acquirer_id": id
            },
            beforeSend: function() {
                swal({
                    title: 'Wait!',
                    buttons: false,
                    text: 'Please wait, we are fetching Acquirers',
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
        })
        .success(function(data) {
            swal.close();
            // $('#acquirerFieldsViewModal').find('.acquirerFieldData').html(data);
        })
        .fail(function() {
            swal.close();
            // notify('Somthing went wrong', 'warning');
            flasher.error('Somthing went wrong');
        });
    $('#acquirerFieldsViewModal').modal('show');
}
// Acquirer Delete
function deleteAcquirer(id) {
    // delete token swal
    swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this record!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('acquirerDelete') }}",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id
                    },
                    success: function(data) {
                        if (data.status == "1") {
                            swal("Poof! Your record  has been deleted!", {
                                icon: "success",
                            }).then((willDelete) => {
                                flasher.success(
                                    "Acquirer Successfully Deleted");
                                $('#datatable').dataTable().api().ajax.reload();

                            });
                        }
                    },
                    error: function(errors) {
                        flasher.error("Something went wrong, try again");
                        swal("Your record  is safe!");
                    }
                });
            } else {

                swal("Your Record is safe!");
            }
        });
}
// End Acquirer Delete
</script>
@endpush