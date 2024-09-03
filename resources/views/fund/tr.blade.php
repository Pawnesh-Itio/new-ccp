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
                        <!-- Type:fund, slug: fundMemberList, fundTr  -->
                        <h6>Member list
                            @if(isset($fundMemberList))
                            @if(!empty($fundMemberList))
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$fundMemberList['description']}}" data-bs-trigger="hover focus"
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Parent
                                            Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Company
                                            Profile</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Wallet
                                            Details</th>
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
    <!-- Transfer Modal -->
    <div id="transferModal" class="modal model-headers-color">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fund Transfer / Return</h5>
                    @if(isset($fundTr))
                    @if(!empty($fundTr))
                    <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$fundTr['description']}}"
                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-content="Top popover" aria-hidden="true">
                    </i>
                    @endif
                    @endif
                </div>
                <form id="transferForm" action="{{route('fundtransaction')}}" method="post">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6">
                                <label>Fund Action</label>
                                <br>
                                <select name="type" class="form-control">
                                    <option value="">Select Action</option>

                                    <option value="transfer">Transfer</option>

                                    <option value="return">Return</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Amount</label>
                                <input type="number" name="amount" step="any" class="form-control"
                                    placeholder="Enter Amount" required="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label>Remark</label>
                                <textarea name="remark" class="form-control" rows="3"
                                    placeholder="Enter Remark"></textarea>
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
    <!-- End Transfer Modal -->
</main>
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/tr/0";
    var onDraw = function() {
        $('input#membarStatus').on('click', function(evt) {
            evt.stopPropagation();
            var ele = $(this);
            var id = $(this).val();
            var status = "block";
            if ($(this).prop('checked')) {
                status = "active";
            }

            $.ajax({
                    url: "{{ route('profileUpdate') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id,
                        'status': status
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("Member Updated", 'success');
                        flasher.success("Member Updated");
                    } else {
                        // notify("Something went wrong, Try again." ,'warning');
                        flasher.error("Something went wrong,Try again");
                    }
                })
                .fail(function(errors) {
                    if (status == "active") {
                        ele.prop('checked', false);
                    } else {
                        ele.prop('checked', true);
                    }
                    showError(errors, "withoutform");
                });
        });
    };
    var options = [{
            "data": "name",
            'className': "notClick",
            render: function(data, type, full, meta) {
                var check = "";
                if (full.kyc == "pending") {
                    check += `<label class="badge bg-warning">Kyc Pending</label>`;
                } else {
                    check += `<label class="badge bg-success">Kyc Success</label>`;
                }
                return `<div>` + check +
                    `<span class='text-xs font-weight-bold mb-0' style='color:black'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div>
                        <span class='text-xs b-0' style='color:black'>` + full.updated_at + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.name +
                    `</span>` + `<br><span class='text-xs b-0' style='color:black'>` + full.mobile +
                    `</span><br><span class='text-xs b-0' style='color:black'>` + full.role.name +
                    `</span>`;
            }
        },
        {
            "data": "parents",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + data +
                    `</span>`
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.company
                    .companyname + `</span>` + `<br><span class='text-xs b-0' style='color:black'>` +
                    full.company.website + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>Main - </span><span class='text-xs b-0' style='color:black'>` +
                    full.mainwallet +
                    " /-</span><br><span class='text-xs font-weight-bold mb-0' style='color:black'>Locked - </span><span class='text-xs b-0' style='color:black'>" +
                    full.lockedamount + " /-</span>";
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                return `<button class="btn btn-primary px-3 btn-block" onclick="transfer('` + full.id +
                    `')">Transfer / Return</button>`;
            }
        }
    ];

    datatableSetup(url, options, onDraw);

    $("#transferForm").validate({
        rules: {
            type: {
                required: true
            },
            amount: {
                required: true,
                min: 1
            }
        },
        messages: {
            type: {
                required: "Please select transfer action",
            },
            amount: {
                required: "Please enter amount",
                min: "Amount value should be greater than 0"
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
            var form = $('#transferForm');
            var type = $('#transferForm').find('[name="type"]').val();
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
                        // notify("Fund "+type+" Successfull", 'success');
                        flasher.success("Fund " + type + " Successfull");
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

function transfer(id) {
    $('#transferForm').find('[name="user_id"]').val(id);
    $('#transferModal').modal('show');
}
</script>
@endpush