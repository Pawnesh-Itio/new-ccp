@extends('layouts.user_type.auth')
@php
$table = "yes";
$export= "cosmosid";
$status['type'] = "Id";
$status['data'] = [
"approved" => "Approved",
"pending" => "Pending"
];
@endphp
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Cosmos Agent List
                            <!-- Type: agentCosmos, Slug: agentList, agentEdit -->
                            @if(isset($agentList))
                            @if(!empty($agentList))
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$agentList['description']}}" data-bs-trigger="hover focus"
                                data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                aria-hidden="true">
                            </i>
                            @endif
                            @endif
                        </h6>
                    </div>
                    <div class="card-body mb-4">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">User
                                            Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Business
                                            Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Mcc</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">VPA</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">S-ID</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Status
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
<!-- View Full Data Modal -->
<div id="viewFullDataModal" class="modal fade right" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h4 class="modal-title">Agent Details</h4>
            </div>
            <div class="modal-body p-0">
                <table class="table table-bordered table-striped ">
                    <tbody>
                        <tr>
                            <th>Bc Id</th>
                            <td class="bc_id"></td>
                        </tr>
                        <tr>
                            <th>Bbps Agent Id</th>
                            <td class="bbps_agent_id"></td>
                        </tr>
                        <tr>
                            <th>Bbps Id</th>
                            <td class="bbps_id"></td>
                        </tr>
                        <tr>
                            <th>Bc Name</th>
                            <td><span class="bc_f_name"></span> <span class="bc_l_name"></span></td>
                        </tr>
                        <tr>
                            <th>Bc Mailid</th>
                            <td class="emailid"></td>
                        </tr>
                        <tr>
                            <th>Phone 1</th>
                            <td class="phone1"></td>
                        </tr>
                        <tr>
                            <th>Phone 2</th>
                            <td class="phone2"></td>
                        </tr>
                        <tr>
                            <th>Shopname</th>
                            <td class="shopname"></td>
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
<!-- End View Full Data Modal -->
@if (App\Helpers\Permission::can('aepsid_statement_edit'))
<!-- Edit Data Modal Start -->
<div id="editModal1" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h6 class="modal-title">Edit Report</h6>
            </div>
            <form id="editUtiidForm" action="{{route('statementUpdate')}}" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="actiontype" value="aepsid">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>BBPS Agent Id</label>
                        <input type="text" name="bbps_agent_id" class="form-control" placeholder="Enter id" required="">
                    </div>

                    <div class="form-group">
                        <label>BBPS Id</label>
                        <input type="text" name="bbps_id" class="form-control" placeholder="Enter id" required="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal"
                        aria-hidden="true">Close</button>
                    <button class="btn bg-slate btn-raised legitRipple" type="submit"
                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Data Modal End -->
<!-- Merchant Edit Modal Start -->
<div id="editMerchantModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Map SID & VPA</h5>
                @if(isset($agentEdit))
                @if(!empty($agentEdit))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$agentEdit['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>

            <form id="cosmosFormedit" action="{{route('statementUpdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="cosmosid">
                        {{ csrf_field() }}

                        <div class="form-group col-md-12 mb-3">
                            <label>Merchant SID</label>
                            <input type="text" class="form-control" name="sid" id="sid" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label>Merchant VPA</label>
                            <input type="text" class="form-control" name="vpa" id="vpa" required>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label>MCC Code</label>
                            <input type="text" class="form-control" name="mcc" id="mcc" required>
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
<!-- Edit Merchant Modal end -->
@endif
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/cosmosagentstatement/{{$id}}";
    var here_date_format = "DD MMM, YYYY h:m a";
    var onDraw = function() {};
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {
                // alert(full.id);
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
            "data": "businessName",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                    .businessName + `</span>`;
            }
        },
        {
            "data": "mcc",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.mcc +
                    `</span>`;
            }
        },
        {
            "data": "vpa",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.vpa +
                    `</span>`;
            }
        },
        {
            "data": "sid",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.sid +
                    `</span>`;
            }
        },

        {
            "data": "status",
            render: function(data, type, full, meta) {
                /* if(full.status == "success" || full.complianceStatus == "Ok"){*/
                var out = `<span class="badge bg-success">Approved</span>`;
                /* }else if(full.status == "pending"){
                     var out = `<span class="badge bg-warning">Pending</span>`;
                 }else{
                     var out = `<span class="badge bg-danger">Rejected</span>`;
                 }*/

                var menu = '';
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="editmerchant(` +
                    full.id + `,'` + full.sid + `','` + full.vpa + `','` + full.mcc +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Edit</a>`;
                out += `&nbsp;|&nbsp;<div class="btn-group dropup role="group">
                    <button id="btnGroupDrop1" type="button" class="btn bg-gradient-primary dropdown-toggle dd-btn" data-bs-toggle="dropdown" aria-expanded="false"></button>
					  <div class="dropdown-menu dd-menu" aria-labelledby="btnGroupDrop1">
					       ` + menu + `

					  </div>
					</div>`;

                return out;
            }
        }
    ];
    datatableSetup(url, options, onDraw);

    $("#cosmosFormedit").validate({
        rules: {
            bbps_agent_id: {
                required: true,
            },
        },
        messages: {
            bbps_agent_id: {
                required: "Please enter id",
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
            var form = $('#cosmosFormedit');
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
                        // notify("Task Successfully Completed", 'success');
                        flasher.success("Task Successfully completed");
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

    $("#editModal").on('hidden.bs.modal', function() {
        $('#setupModal').find('form')[0].reset();
    });


});

function editmerchant(id, sid, vpa, mcc) {
    $('#cosmosFormedit').find('[name="id"]').val(id);
    $('#cosmosFormedit').find('[name="sid"]').val(sid);
    $('#cosmosFormedit').find('[name="vpa"]').val(vpa);
    $('#cosmosFormedit').find('[name="mcc"]').val(mcc);
    $('#editMerchantModal').modal('show');
}

function viewFullData(id) {
    $.ajax({
            url: `{{url('fetch')}}/aepsagentstatement/` + id + `/view`,
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
            $('#viewFullDataModal').modal();
        })
        .fail(function(errors) {
            // notify('Oops', errors.status+'! '+errors.statusText, 'warning');
            flasher.error('Oops' + errors.sattus + '! ' + errors.statusText);
        });
}

function editUtiid(id, bbps_agent_id, bbps_id) {
    $('#editModal').find('[name="id"]').val(id);
    $('#editModal').find('[name="bbps_agent_id"]').val(bbps_agent_id);
    $('#editModal').find('[name="bbps_id"]').val(bbps_id);
    $('#editModal').modal('show');
}
</script>
@endpush