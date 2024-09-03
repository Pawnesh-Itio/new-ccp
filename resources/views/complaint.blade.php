@extends('layouts.user_type.auth')
@php
$table = "yes";
$agentfilter ="true";
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
                        <!-- Type: statementComplaint, Slug: complaintList, complaintEdit -->
                        <h6>Complaint List
                            @if(isset($complaintList))
                            @if(!empty($complaintList))
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$complaintList['description']}}" data-bs-trigger="hover focus"
                                data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                aria-hidden="true">
                            </i>
                            @endif
                            @endif
                        </h6>
                    </div>
                    <div class="card-body mb-4">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">User
                                            Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">
                                            Transaction Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Subject
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Query
                                            Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Solution
                                            Details</th>
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
<!-- Uti Id Details -->
<div id="utiidModal" class="modal fade right" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h4 class="modal-title">Uti Id Details</h4>
            </div>
            <div class="modal-body p-0">
                <table class="table table-bordered table-striped ">
                    <tbody>
                        <tr>
                            <th>Vle Id</th>
                            <td class="vleid"></td>
                        </tr>
                        <tr>
                            <th>Vle Password</th>
                            <td class="vlepassword"></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td class="name"></td>
                        </tr>
                        <tr>
                            <th>Localtion</th>
                            <td class="location"></td>
                        </tr>
                        <tr>
                            <th>Contact Person</th>
                            <td class="contact_person"></td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td class="state"></td>
                        </tr>
                        <tr>
                            <th>Pincode</th>
                            <td class="pincode"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td class="email"></td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td class="mobile"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal"
                    aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End UTI Id Details -->
<!-- Complaint Edit Modal -->
<div id="complaintEditModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Report</h5>
                @if(isset($complaintEdit))
                @if(!empty($complaintEdit))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$complaintEdit['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="complaintEditForm" action="{{route('complaintstore')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-12 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label>Solution</label>
                            <textarea type="text" class="form-control" name="solution" required></textarea>
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
<!-- End Complaint Modal -->
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    $("#complaintEditForm").validate({
        rules: {
            status: {
                required: true,
            },
            solution: {
                required: true,
            }
        },
        messages: {
            status: {
                required: "Please select status",
            },
            solution: {
                required: "Please enter your solution",
            },
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
            var form = $('#complaintEditForm');
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.status) {
                        form[0].reset();
                        form.find('select').val(null).trigger('change');
                        form.closest('.modal').modal('hide');
                        $('#datatable').dataTable().api().ajax.reload();
                        // notify("Complaint successfully updated", 'success');
                        flasher.success("Complaint successfully updated")
                    } else {
                        // notify(data.status , 'warning');
                        flasher.error(data.status)
                    }
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
        }
    });
    var url = "{{url('fetch')}}/complaints/0";
    var here_date_format = "DD MMM, YYYY h:m a";
    var onDraw = function() {};
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {
                return `<div>
                            <span class='text-xs font-weight-bold mb-0' style='color:black'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div><span class='text-xs b-0' style='color:black'>` + moment(full.created_at).format(
                    here_date_format) + `</span>`;
            }
        },
        {
            "data": "username",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + data +
                    `</span>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<a href="javascript:void(0)" class="label label-info text-xs b-0" style='color:black'onclick="viewData('` +
                    full.transaction_id + `')">` + full.product + ` ( ` + full.transaction_id + ` )` +
                    `</a>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                    .complaintsubject.subject + `</span>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                    .description + `</span>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                if (full.resolver) {
                    return "<span class='text-xs font-weight-bold mb-0' style='color:black'>Resolved By - </span><span class='text-xs b-0' style='color:black'>" +
                        full.resolver.name + "(" + full.resolver.id +
                        ")</span><br><span class='text-xs b-0' style='color:black'>" + full.solution +
                        "</span>";
                } else {
                    return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                        .solution + `</span>`;
                }
            }
        },
        {
            "data": "status",
            render: function(data, type, full, meta) {
                if (full.status == "resolved") {
                    var out = `<span class="label text-success">Resolved</span>`;
                } else {
                    var out = `<span class="label text-warning">Pending</span>`;
                }

                var menu = '';
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="editComplaint(` +
                    full.id + `, '` + full.status + `', '` + full.solution +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Edit</a>`;
                out += `&nbsp;|&nbsp;<div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn bg-gradient-primary dropdown-toggle dd-btn" data-bs-toggle="dropdown" aria-expanded="false"></button>
					  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					       ` + menu + `

					  </div>
					</div>`;

                return out;
            }
        }
    ];

    datatableSetup(url, options, onDraw);

});

function viewData(id) {
    $.ajax({
            url: `{{url('fetch')}}/utiidstatement/` + id,
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
            $.each(data, function(index, values) {
                $("." + index).text(values);
            });
            $('#utiidModal').modal();
        })
        .fail(function(errors) {
            // notify('Oops', errors.status+'! '+errors.statusText, 'warning');
            flasher.error('Oops, ' + errors.status + '! ' + errors.statusText);
        });
}

function viewUtiid(id) {
    $.ajax({
            url: `{{url('fetch')}}/utiidstatement/` + id,
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
            $.each(data, function(index, values) {
                $("." + index).text(values);
            });
            $('#utiidModal').modal();
        })
        .fail(function(errors) {
            // notify('Oops', errors.status+'! '+errors.statusText, 'warning');
            flasher.error('Oops' + error.status + '! ' + errors.statusText);
        });
}

function editComplaint(id, status, solution) {
    $('#complaintEditModal').find('[name="id"]').val(id);
    $('#complaintEditModal').find('[name="solution"]').val(solution);
    $('#complaintEditModal').find('[name="status"]').val(status).trigger('change');
    $('#complaintEditModal').modal('show');
}
</script>
@endpush