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
                                <!-- Type: toolsRoles, slug:roleList, roleAdd, roleEdit, rolePermission, roleScheme -->
                                <h6 class="card-title">Roles List
                                    @if(isset($roleList))
                                    @if(!empty($roleList))
                                    <i class="fa fa-question-circle text-capitalize"
                                        data-bs-content="{{$roleList['description']}}" data-bs-trigger="hover focus"
                                        data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                        aria-hidden="true">
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Last
                                            Update</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="color:black">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Role Modal Start -->
<div id="roleModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Role</h5>
                @if(isset($roleAdd))
                @if(!empty($roleAdd))
                <i class="fa fa-question-circle text-capitalize add" style="display:none"
                    data-bs-content="{{$roleAdd['description']}}" data-bs-trigger="hover focus" data-bs-toggle="popover"
                    data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
                @if(isset($roleEdit))
                @if(!empty($roleEdit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none"
                    data-bs-content="{{$roleEdit['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="rolemanager" action="{{route('toolsstore', ['type'=>'roles'])}}" method="post">
                <div class="modal-body">
                    <div class="row mb-4">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6">
                            <label>Role Name</label>
                            <input type="text" name="slug" class="form-control" placeholder="Enter Role Name"
                                required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Display Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Display Name"
                                required="">
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
<!-- End Role Modal -->
<!-- Permission Modal Start -->
@if (isset($permissions) && $permissions)
<div id="permissionModal" class="modal model-headers-color">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Member Permission</h5>
                @if(isset($rolePermission))
                @if(!empty($rolePermission))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$rolePermission['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="permissionForm" action="{{route('toolssetpermission')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="role_id">
                <input type="hidden" name="type" value="permission">
                <div class="modal-body p-0">
                    <table id="datatable" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th width="170px;">Section Category</th>
                                <th>
                                    <span class="pull-left m-t-5 m-l-10">Permissions</span>
                                    <div class="md-checkbox pull-right">
                                        <input type="checkbox" id="selectall">
                                        <label for="selectall">Select All</label>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $key => $value)
                            <tr>
                                <td>
                                    <div class="md-checkbox mymd">
                                        <input type="checkbox" class="selectall" id="{{ucfirst($key)}}">
                                        <label for="{{ucfirst($key)}}">{{ucfirst($key)}}</label>
                                    </div>
                                </td>
                                <td class="row">
                                    @foreach ($value as $permission)
                                    <div class="md-checkbox col-md-4 p-0">
                                        <input type="checkbox" class="case" id="{{$permission->id}}"
                                            name="permissions[]" value="{{$permission->id}}">
                                        <label for="{{$permission->id}}">{{$permission->name}}</label>
                                        @if(!empty($permission->help_box))
                                        <span>
                                            <i class="fa fa-question-circle text-capitalize"
                                                data-bs-content="{{$permission->help_box}}"
                                                data-bs-trigger="hover focus" data-bs-toggle="popover"
                                                data-bs-placement="top" data-bs-content="Top popover"
                                                aria-hidden="true">
                                            </i>
                                        </span>
                                        @endif
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endif
<!-- Permission Modal End -->
<!-- Scheme Modal Start -->
<div id="schemeModal" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Scheme Manager</h5>
                @if(isset($roleScheme))
                @if(!empty($roleScheme))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$roleScheme['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="schemeForm" method="post" action="{{ route('toolssetpermission') }}">
                <div class="modal-body p-4">
                    {!! csrf_field() !!}
                    <input type="hidden" name="role_id">
                    <input type="hidden" name="type" value="scheme">
                    <div class="row">
                        <label for="ageSelect" class="form-label fw-bold">Scheme</label>
                        <select class="form-select" name="permissions[]" required="" id="permissions" data-width="100%">
                            <option value="">Select Scheme</option>
                            @foreach ($scheme as $element)
                            <option value="{{$element->id}}">{{$element->name}}</option>
                            @endforeach
                        </select>
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
<!-- Scheme Modal End -->
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch/roles/0')}}";
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
            "data": "updated_at",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                var menu = ``;
                var out = ``;

                @if(App\Helpers\Permission::can(['fund_transfer', 'fund_return']))

                menu +=
                    `<a class="dropdown-item" href="javascript:void(0)" onclick="editRole(this)"><span class="fa fa-pencil"> <i class="fa-solid fa-people-roof"></i></span>Edit</a>`;

                @endif

                @if(App\Helpers\Permission::can('member_permission_change'))

                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="getPermission(` +
                    full.id +
                    `)"><span class="fa fa-cogs"> <i class="fa-solid fa-people-roof"></i></span>Permission</a>`;

                @endif

                @if(App\Helpers\Permission::can('member_scheme_change'))


                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="scheme(` + full
                    .id + `, '` + full.scheme +
                    `')"><span class="fa fa-wallet"> <i class="fa-solid fa-people-roof"></i></span>Scheme</a>`;

                @endif

                out += `<div class="btn-group dropup" role="group">
                   <button id="btnGroupDrop1" type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                   Action
                   </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        ` + menu + `
                                    
                    </div>
                    </div>`;

                return out;
            }
        },
    ];
    datatableSetup(url, options, onDraw);

    $("#rolemanager").validate({
        rules: {
            slug: {
                required: true,
            },
            name: {
                required: true,
            },
        },
        messages: {
            mobile: {
                required: "Please enter role slug",
            },
            name: {
                required: "Please enter role name",
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
            var form = $('#rolemanager');
            var id = $('input[name="id"]').val();
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

    $("#roleModal").on('hidden.bs.modal', function() {
        $('#roleModal').find('.msg').text("Add");
        $('#roleModal').find('form')[0].reset();
    });

    $('form#permissionForm').submit(function() {
        var form = $(this);
        $(this).ajaxSubmit({
            dataType: 'json',
            beforeSubmit: function() {
                form.find('button[type="submit"]').button('loading');
            },
            complete: function() {
                form.find('button[type="submit"]').button('reset');
            },
            success: function(data) {
                if (data.status == "success") {
                    // notify('Permission Set Successfully', 'success');
                    flasher.success('Permission Set Successfully');
                } else {
                    // notify('Transaction Failed', 'warning');
                    flasher.error('Transaction Failed');
                }
            },
            error: function(errors) {
                showError(errors, form);
            }
        });
        return false;
    });

    $('#selectall').click(function(event) {
        if (this.checked) {
            $('.case').each(function() {
                this.checked = true;
            });
            $('.selectall').each(function() {
                this.checked = true;
            });
        } else {
            $('.case').each(function() {
                this.checked = false;
            });
            $('.selectall').each(function() {
                this.checked = false;
            });
        }
    });

    $('.selectall').click(function(event) {
        if (this.checked) {
            $(this).closest('tr').find('.case').each(function() {
                this.checked = true;
            });
        } else {
            $(this).closest('tr').find('.case').each(function() {
                this.checked = false;
            });
        }
    });

    $("#schemeForm").validate({
        rules: {
            scheme_id: {
                required: true
            }
        },
        messages: {
            scheme_id: {
                required: "Please select scheme",
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
            var form = $('#schemeForm');
            var type = $('#schemeForm').find('[name="type"]').val();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        getbalance();
                        form.closest('.modal').modal('hide');
                        // notify("Role Scheme Updated Successfull", 'success');
                        flasher.success("Role Scheme Updated Successfull");
                        $('#datatable').dataTable().api().ajax.reload();
                    } else {
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

function addrole() {
    $('.add').css('display', 'block');
    $('.edit').css('display', 'none');
    $('#roleModal').find('.panel-title').text("Add New Role");
    $('#roleModal').find('input[name="id"]').val("0");
    $('#roleModal').modal('show');
}

function editRole(ele) {
    var id = $(ele).closest('tr').find('td').eq(0).text();
    var slug = $(ele).closest('tr').find('td').eq(1).text();
    var name = $(ele).closest('tr').find('td').eq(2).text();

    $('.add').css('display', 'none');
    $('.edit').css('display', 'block');

    $('#roleModal').find('.msg').text("Edit");
    $('#roleModal').find('input[name="id"]').val(id);
    $('#roleModal').find('input[name="slug"]').val(slug);
    $('#roleModal').find('input[name="name"]').val(name);
    $('#roleModal').modal('show');
}

function getPermission(id) {

    if (id.length != '') {
        $.ajax({
                url: "{{url('tools/getdefault/permission')}}/" + id,
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
            .done(function(data) {
                $('#permissionForm').find('[name="role_id"]').val(id);
                $('.case').each(function() {
                    this.checked = false;
                });
                $.each(data, function(index, val) {
                    $('#permissionForm').find('input[value=' + val.permission_id + ']').prop('checked',
                        true);
                });
                $('#permissionModal').modal('show');
            })
            .fail(function() {
                // notify('Somthing went wrong', 'warning');
                flasher.error('Somthing went wrong');
            });
    }
}

function scheme(id, scheme) {
    $('#schemeForm').find('[name="role_id"]').val(id);
    if (scheme != '' && scheme != null && scheme != 'null') {
        $('#schemeForm').find('[name="permissions[]"]').select2().val(scheme).trigger('change');
    }
    $('#schemeModal').modal('show');
    $('#select2').select2();;
}
</script>
@endpush