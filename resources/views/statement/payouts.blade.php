@extends('layouts.user_type.auth')

@php
$table = "yes";
$export = "payout";

$status['type'] = "Report";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"reversed" => "Reversed",
"refunded" => "Refunded",
];
@endphp
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <!-- Type: statementPayout, Slug: payoutList, payoutEdit, payoutComplain -->
                        <h6>Payout Statement
                            @if(isset($payoutList))
                            @if(!empty($payoutList))
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$payoutList['description']}}" data-bs-trigger="hover focus"
                                data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                aria-hidden="true">
                            </i>
                            @endif
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Order ID
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">User
                                            Details
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Bank
                                            Details
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Refrence
                                            Details</th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">
                                            Amount/Commission</th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Status
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
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/payoutstatement/{{$id}}";
    var onDraw = function() {};
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {
                return `<div>
                           <!-- <span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.apiname + `</span><br>-->
                            <span class='text-xs' style='color:black'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='color:black' class="text-xs b-0">` + full.created_at +
                    `</span>`;
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
                return `<span style='color:black'>Account - </span><span class='text-xs' style='color:black'>` +
                    full.number +
                    `</span><br><span style='color:black'>Bank - </span><span class='text-xs' style='color:black'>` +
                    full.option3 +
                    `</span><br><span style='color:black'>IFSC - </span><span class='text-xs' style='color:black'>` +
                    full
                    .option4 + `</span>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                //return `Ref No. - `+full.refno+`<br>Txnid - `+full.txnid+`<br>Payid - `+full.payid;
                return `<span style='color:black'>Txnid - </span><span class='text-xs' style='color:black'>` +
                    full.txnid + `</span>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<span style='color:black'>Amount - </span><span class='text-xs' style='color:black'><i class="fa fa-inr"></i> ` +
                    full.amount +
                    `</span><br><span style='color:black'>Charge - </span><span class='text-xs' style='color:black'><i class="fa fa-inr"></i> ` +
                    full
                    .charge + `</span>`;

            }
        },

        {
            "data": "status",
            render: function(data, type, full, meta) {
                if (full.status == "success") {
                    var out = `<span class="badge bg-success">` + full.status + `</span>`;
                } else if (full.status == "complete") {
                    var out = `<span class="badge bg-primary">` + full.status + `</span>`;
                } else if (full.status == "pending") {
                    var out = `<span class="badge bg-warning">Pending</span>`;
                } else if (full.status == "reversed") {
                    var out = `<span class="badge bg-primary">Reversed</span>`;
                } else {
                    var out = `<span class="badge bg-danger">` + full.status + `</span>`;
                }
                var menu = ``;
                @if(App\Helpers\Permission::can('aeps_status'))
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="status(` + full
                    .id +
                    `, 'payout')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span> Check Status</a>`;
                @endif

                @if(App\Helpers\Permission::can('aeps_statement_edit'))

                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="editReport(` + full
                    .id + `,'` + full.refno + `','` + full.txnid + `','` + full.payid + `','` + full
                    .remark + `', '` + full.status +
                    `', 'payout')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span> Edit</a>`;
                @endif
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="complaint(` + full
                    .id +
                    `, 'aeps')"><span class="sub-action-icon"><i class="fa fa-solid fa-people-roof"></i></span> Complaint</a>`;


                out += `&nbsp;|&nbsp;<div class="btn-group dropup" role="group">
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
</script>
@endpush