@extends('layouts.user_type.auth')
@php
$table = "yes";
$agentfilter ="true";
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
                                <!-- Type: toolsPermission,slug: permissionList, permissionAdd, PermissionEdit. -->
                                <h6 class="card-title">Permissions List
                                    @if(isset($permissionList))
                                    @if(!empty($permissionList))
                                    <i class="fa fa-question-circle text-capitalize"
                                        data-bs-content="{{$permissionList['description']}}"
                                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Top popover" aria-hidden="true">
                                    </i>
                                    @endif
                                    @endif
                                </h6>

                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" onclick="addrole()">
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
                                    Add new
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Display
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Type</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Help
                                            Box</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Last
                                            Update</th>
                                            
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
<div id="permissionModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Permission</h5>
                @if(isset($permissionAdd))
                @if(!empty($permissionAdd))
                <i class="fa fa-question-circle text-capitalize add" style="display:none" data-bs-content="{{$permissionAdd['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
                @if(isset($permissionEdit))
                @if(!empty($permissionEdit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none" data-bs-content="{{$permissionEdit['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>

            <form id="permissionManager" action="{{route('toolsstore', ['type'=>'permission'])}}" method="post">
                <div class="modal-body">
                    <div class="row mb-4">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" name="slug" class="form-control" placeholder="Enter Permission Name"
                                required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Display Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Display Name"
                                required="">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-md-6">
                            <label>Type</label>
                            <input type="text" name="type" class="form-control" placeholder="Enter Permission Type"
                                required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Help Box</label>
                            <textarea  name="help_box" class="form-control" placeholder="Enter Permission Type"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mb-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                        aria-hidden="true">Close</button>
                    <button class="btn btn-primary px-4" type="submit"
                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch/permissions/0')}}";
    var onDraw = function() {};
    var options = [{
            "data": "id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "slug",
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
            "data": "help",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.help_box + `</span>`;
            }
        },
        {
            "data": "updated_at",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                return `<button  class="btn btn-sm btn-icon btn-primary" onclick="editRole(this)">Edit</button>`;
            }
        },
    ];
    datatableSetup(url, options, onDraw);

    $("#permissionManager").validate({
        rules: {
            slug: {
                required: true,
            },
            name: {
                required: true,
            },
            help_box: {
                required: true,
            },
        },
        messages: {
            mobile: {
                required: "Please enter role slug",
            },
            name: {
                required: "Please enter role name",
            },
            help_box: {
                required: "Please enter help description",
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
            var form = $('#permissionManager');
            var id = $('#permissionManager').find("[name='id']").val();
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
                        // notify("Task Successfully Completed", 'success');
                        flasher.success("Task Successfully Completed");
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

    $("#permissionModal").on('hidden.bs.modal', function() {
        $('#permissionModal').find('.msg').text("Add");
        $('#permissionModal').find('form')[0].reset();
    });
});

function addrole() {
    $('.add').css("display","block");
    $('.edit').css("display","none");
    $('#permissionModal').find('.msg').text("Add");
    $('#permissionModal').find('input[name="id"]').val("0");
    $('#permissionModal').modal('show');
}

function editRole(ele) {

    var id = $(ele).closest('tr').find('td').eq(0).text();
    var slug = $(ele).closest('tr').find('td').eq(1).text();
    var name = $(ele).closest('tr').find('td').eq(2).text();
    var type = $(ele).closest('tr').find('td').eq(3).text();
    var help = $(ele).closest('tr').find('td').eq(4).text();
    console.log(help);
    $('.add').css("display","none");
    $('.edit').css("display","block");

    $('#permissionModal').find('.msg').text("Edit");
    $('#permissionModal').find('input[name="id"]').val(id);
    $('#permissionModal').find('input[name="slug"]').val(slug);
    $('#permissionModal').find('input[name="name"]').val(name);
    $('#permissionModal').find('input[name="type"]').val(type);
    $('#permissionModal').find('textarea[name="help_box"]').val(help);
    $('#permissionModal').modal('show');
}
</script>
@endpush