@extends('layouts.user_type.auth')

@php
$table = "yes";
$agentfilter = "hide";

$status['type'] = "Scheme";
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
                                <!-- Type:resourceScheme slug: scheme_list,scheme_add,scheme_edit,scheme_payout_sc,scheme_payin_sc -->
                                <h6 class="card-title">Home Scheme Manager
                                    @if(isset($scheme_list))
                                    @if(!empty($scheme_list))
                                    <i class="fa fa-question-circle text-capitalize"
                                        data-bs-content="{{$scheme_list['description']}}" data-bs-trigger="hover focus"
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Name</th>
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
<!-- Add Scheme Modal -->
<div id="setupModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Scheme</h5>
                <!-- add -->
                @if(isset($scheme_add))
                @if(!empty($scheme_add))
                <i class="fa fa-question-circle text-capitalize add" style="display:none" data-bs-content="{{$scheme_add['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
                <!-- Edit -->
                @if(isset($scheme_edit))
                @if(!empty($scheme_edit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none" data-bs-content="{{$scheme_edit['description']}}"
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
                        <input type="hidden" name="actiontype" value="scheme">
                        {{ csrf_field() }}
                        <div class="form-group mb-2 col-md-12">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Scheme Name"
                                required="">
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
<!-- End Add Scheme Modal -->
<!-- Payout Modal -->
<div id="payoutModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h5 class="modal-title" id="exampleModalLabel">Payout Service Charge</h5>
                @if(isset($scheme_payout_sc))
                @if(!empty($scheme_payout_sc))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none" data-bs-content="{{$scheme_payout_sc['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form class="commissionForm" method="post" action="{{ route('resourceupdate') }}">
                <div class="modal-body p-0" style="margin-bottom:20px">
                    {!! csrf_field() !!}
                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>
                            @if(\App\Helpers\Permission::hasRole('admin'))
                            <th>Type</th>
                            @endif
                            <th>Charges</th>
                        </thead>
                        <tbody>
                            @foreach ($payoutOperator as $element)
                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]" value="{{$element->id}}">
                                    {{$element->name}}
                                </td>
                                @if(\App\Helpers\Permission::hasRole('admin'))
                                <td>
                                    @if($element->recharge1 == "dmt1accverify")
                                    <input type="hidden" name="type[]" value="flat">
                                    Flat
                                    @else
                                    <select class="form-control" name="type[]" required="">

                                        <option value="flat">Flat (Rs)</option>
                                        <option value="percent">Percent (%)</option>
                                    </select>
                                    @endif
                                </td>
                                @endif

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="apiuser[]" placeholder="Enter Value"
                                        class="form-control" required="">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
<!-- En Payout Modal -->
<!-- Upi Modal -->
<div id="upiModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h5 class="modal-title" id="exampleModalLabel">Payin Service Charge</h5>
                @if(isset($scheme_payin_sc))
                @if(!empty($scheme_payin_sc))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none" data-bs-content="{{$scheme_payin_sc['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form class="commissionForm" method="post" action="{{ route('resourceupdate') }}">
                <div class="modal-body p-0" style="margin-bottom:20px">
                    {!! csrf_field() !!}
                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>
                            @if(\App\Helpers\Permission::hasRole('admin'))
                            <th>Type</th>
                            @endif
                            <th>Charges</th>
                        </thead>
                        <tbody>
                            @foreach ($upiOperator as $element)
                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]" value="{{$element->id}}">
                                    {{$element->name}}
                                </td>
                                @if(\App\Helpers\Permission::hasRole('admin'))
                                <td>
                                    @if($element->recharge1 == "dmt1accverify")
                                    <input type="hidden" name="type[]" value="flat">
                                    Flat
                                    @else
                                    <select class="form-control" name="type[]" required="">

                                        <option value="flat">Flat (Rs)</option>
                                        <option value="percent">Percent (%)</option>
                                    </select>
                                    @endif
                                </td>
                                @endif

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="apiuser[]" placeholder="Enter Value"
                                        class="form-control" required="">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
<!-- End Upi Modal -->
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/resource{{$type}}/0";
    var onDraw = function() {
        $('input#schemeStatus').on('click', function(evt) {
            evt.stopPropagation();
            var ele = $(this);
            var id = $(this).val();
            var status = "0";
            if ($(this).prop('checked')) {
                status = "1";
            }

            $.ajax({
                    url: "{{ route('resourceupdate') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id,
                        'status': status,
                        "actiontype": "scheme"
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("Company Updated", 'success');
                        flasher.success("Scheme Status Updated");
                        $('#setupModal').modal('hide');
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
            "data": "name",
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
                                    <input id="schemeStatus" type="checkbox" ` + check + ` value="` + full.id +
                    `" actionType="` + type + `">
                    <span class="slider round"></span>
                                 </label>
                              </div>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {

                var out = '';
                var menu = ``;
                menu +=
                    `<a class="dropdown-item" href="javascript:void(0)" onclick="editSetup(this)"><i class="fa fa-edit"></i>&nbsp;Edit Scheme</a>`;

                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="commission(` + full
                    .id + `, 'payout','payoutModal')"><i class="fa fa-inr"></i>&nbsp;Payout Charge</a>`;
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="commission(` + full
                    .id + `, 'upi','upiModal')"><i class="fa fa-inr"></i>&nbsp;Payin Charge</a>`;

                out += `<div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                    </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            ` + menu + `
                                    
                        </div>
                    </div>`;
                var out2 = '';
                var menu2 = ``;

                @if(App\Helpers\Permission::can(['member_billpayment_statement_view']))
                menu2 += `<a class="dropdown-item" href="{{url('statement/upi/')}}/` + full.id +
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-receipt"></span></i>Upi Statement</a>`;
                @endif

                @if(App\Helpers\Permission::can(['member_account_statement_view']))

                menu2 += `<a class="dropdown-item" href="{{url('statement/account/')}}/` + full.id +
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-receipt"></i></span> Account Statement</a>`;
                @endif

                return out + out2;
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
    $('form.commissionForm').submit(function() {
        var form = $(this);
        form.closest('.modal').find('tbody').find('span.pull-right').remove();
        $(this).ajaxSubmit({
            dataType: 'json',
            beforeSubmit: function() {
                form.find('button[type="submit"]').button('loading');
            },
            complete: function() {
                form.find('button[type="submit"]').button('reset');
            },
            success: function(data) {
                // notify("Task Successfully Completed", 'success');
                flasher.success("Task Successfully Completed");
                $.each(data.status, function(index, values) {
                    if (values.id) {
                        form.find('input[value="' + index + '"]').closest('tr')
                            .find('td').eq(0).append(
                                '<span class="pull-right text-success"><i class="fa fa-check"></i></span>'
                            );
                    } else {
                        form.find('input[value="' + index + '"]').closest('tr')
                            .find('td').eq(0).append(
                                '<span class="pull-right text-danger"><i class="fa fa-times"></i></span>'
                            );
                        if (values != 0) {
                            form.find('input[value="' + index + '"]').closest('tr')
                                .find('input[name="value[]"]').closest('td').append(
                                    '<span class="text-danger pull-right"><i class="fa fa-times"></i> ' +
                                    values + '</span>');
                        }
                    }
                });



                setTimeout(function() {
                    form.find('span.pull-right').remove();
                }, 10000);
            },
            error: function(errors) {
                showError(errors, form);
            }
        });
        return false;
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
    $('.add').css("display","block");
    $('.edit').css("display","none");
}

function editSetup(ele) {
    var id = $(ele).closest('tr').find('td').eq(0).text();
    var name = $(ele).closest('tr').find('td').eq(1).text();

    $('#setupModal').find('.msg').text("Edit");
    $('#setupModal').find('input[name="id"]').val(id);
    $('#setupModal').find('input[name="name"]').val(name);
    $('#setupModal').modal('show');
    $('.edit').css("display","block");
    $('.add').css("display","none");
}

function commission(id, type, modal) {
    $.ajax({
            url: "{{ url('resources/get') }}/" + type + "/commission",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: {
                'scheme_id': id
            }
        })
        .done(function(data) {
            if (data.length > 0) {
                $.each(data, function(index, values) {
                    if (type != "gst" && type != "itr") {
                        @if(App\Helpers\Permission::hasRole('admin'))
                        $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find(
                            'select[name="type[]"]').val(values.type);
                        @endif
                    }
                    $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find(
                        'input[name="apiuser[]"]').val(values.apiuser);
                });
            }
        })
        .fail(function(errors) {
            // notify('Oops', errors.status+'! '+errors.statusText, 'warning');
            flasher.error("Oops" + errors.status + '! ' + errors.statusText);
        });

    $('#' + modal).find('input[name="scheme_id"]').val(id);
    $('#' + modal).modal('show');
}
@if(isset($mydata['schememanager']) && $mydata['schememanager'] -> value == "all")

function viewCommission(id, name) {
    if (id != '') {
        $.ajax({
                url: '{{route("getMemberPackageCommission")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "scheme_id": id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        text: 'Please wait, we are fetching commission details',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .success(function(data) {
                swal.close();
                $('#commissionModal').find('.schemename').text(name);
                $('#commissionModal').find('.commissioData').html(data);
                $('#commissionModal').modal('show');
            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error("Somthing went wrong");

            });
    }
}
@else

function viewCommission(id, name) {
    if (id != '') {
        $.ajax({
                url: '{{route("getMemberCommission")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "scheme_id": id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        text: 'Please wait, we are fetching commission details',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .success(function(data) {
                swal.close();
                $('#commissionModal').find('.schemename').text(name);
                $('#commissionModal').find('.commissioData').html(data);
                $('#commissionModal').modal('show');
            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error("Somthing went wrong");

            });
    }
}
@endif
</script>
@endpush