@extends('layouts.user_type.auth')
@php
$table = "yes";
$export= "cosmosid";
$status['type'] = "Id";
$status['data'] = [
"success" => "Success",
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
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Acquiring Agent List
                                    <!-- Type: agentOpenacquiring, Slug: agentList, agentEdit -->
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
                        </div>
                    </div>
                    <div class="card-body mb-4">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">User
                                            Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Merchant
                                            Id</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Client Id
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Client
                                            Secret</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Status
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
<!-- Edit Merchant Modal -->
<div id="editMerchantModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Merchant Information</h5>
                @if(isset($agentEdit))
                @if(!empty($agentEdit))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$agentEdit['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>

            <form id="acquiringFormedit" action="{{route('statementUpdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="status" value="1">
                        <input type="hidden" name="actiontype" value="acquiringid">
                        {{ csrf_field() }}

                        <div class="form-group col-md-12 mb-3">
                            <label for="merchant_id">Merchant ID</label>
                            <input type="text" class="form-control" name="merchant_id" id="merchant_id" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label for="client_id">Client Id</label>
                            <input type="text" class="form-control" name="client_id" id="client_id" required>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label for="client_secret">Client Secret</label>
                            <input type="text" class="form-control" name="client_secret" id="client_secret" required>
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
<!-- End Edit Merchant Modal -->
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    $("#acquiringFormedit").validate({
        rules: {
            merchant_id: {
                required: true,
            },
        },
        messages: {
            merchant_id: {
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
            var form = $('#acquiringFormedit');
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
    var url = "{{url('openacquiringstatement')}}/{{$id}}";
    var here_date_format = "DD MMM, YYYY h:m a";
    var onDraw = function() {};
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {
                return `<div>
                            <span class='text-xs font-weight-bold mb-0'style='color:black'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div><span class="text-xs b-0"style='color:black'>` + moment(full.created_at).format(
                    here_date_format) + `</span>`;
            }
        },
        {
            "data": "user_id",
            render: function(data, type, full, meta) {
                return `<div>
                            <span class='text-xs font-weight-bold mb-0' style='color:black'><b>` + full.name + `(` +
                    full.user_id + `)</b> </span>
                            <div class="clearfix"></div>
                        </div><span class='text-xs b-0' style='color:black'>(` + full.role + `)</span>`;
            }
        },
        {
            "data": "merchant_id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                    .merchant_id + `</span>`;
            }
        },
        {
            "data": "client_id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                    .client_id + `</span>`;
            }
        },
        {
            "data": "client_secret",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full
                    .client_secret + `</span>`;
            }
        },

        {
            "data": "status",
            render: function(data, type, full, meta) {

                if (full.status == "1") {

                    var out = `<span class="badge bg-success">Approved</span>`;
                } else {
                    var out = `<span class="badge bg-warning">Pending</span>`;
                }
                var menu = '';
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="editmerchant(` +
                    full.id + `,'` + full.merchant_id + `','` + full.client_id + `','` + full
                    .client_secret +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Edit</a>`;
                out += `&nbsp;|&nbsp;
                      <div class="btn-group dropup" role="group">
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
});

function editmerchant(id, merchant_id, client_id, client_secret) {
    $('#acquiringFormedit').find('[name="id"]').val(id);
    $('#acquiringFormedit').find('[name="merchant_id"]').val(merchant_id);
    $('#acquiringFormedit').find('[name="client_id"]').val(client_id);
    $('#acquiringFormedit').find('[name="client_secret"]').val(client_secret);
    $('#editMerchantModal').modal('show');
}
</script>
@endpush