@extends('layouts.user_type.auth')

@php
$table = "yes";
$export = "wallet";
$agentfilter ="yes";
@endphp
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <!-- Type: statementAccount, slug:accountList -->
                        <h6>Account Statement
                            @if(isset($accountList))
                            @if(!empty($accountList))
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$accountList['description']}}" data-bs-trigger="hover focus"
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
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Date Time
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Refrence
                                            Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Product
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Provider
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Txnid</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Number
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">ST Type
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Status
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Opening
                                            Bal.</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Amount
                                        </th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Closing
                                            Bal.</th>
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
@push('style')
<style>
.table-striped {
    color: black;
}
</style>
@endpush

@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/accountstatement/{{$id}}";
    var onDraw = function() {
        $('[data-popup="tooltip"]').tooltip();
        $('[data-popup="popover"]').popover({
            template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
        });
    };
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {
                var out = "";
                out += `</a><span class='text-xs b-0' style='color:black'>` + full.created_at +
                    `</span>`;
                return out;
            }
        },
        {
            "data": "full.username",
            render: function(data, type, full, meta) {
                var uid = "{{Auth::id()}}";
                if (full.credited_by == uid) {
                    var name = `<span class='text-xs b-0' style='color:black'>` + full.username +
                        `</span>`;
                } else {
                    var name = `<span class='text-xs b-0' style='color:black'>` + full.sendername +
                        `</span>`;
                }
                return name;
            }
        },
        {
            "data": "product",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`
            }
        },
        {
            "data": "providername",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`
            }
        },
        {
            "data": "id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`
            }
        },
        {
            "data": "number",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`
            }
        },
        {
            "data": "rtype",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`
            }
        },
        {
            "data": "status",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'> `+"{{$currencyDetails->symbol}} " + full
                    .balance + `</span>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                if (full.status == "pending" || full.status == "success" || full.status == "reversed" ||
                    full.status == "failed" || full.status == "refunded") {
                    if (full.trans_type == "credit") {
                        return `<span class='text-xs'style='color:black'>`+"{{$currencyDetails->symbol}} " +
                            (parseFloat(full.amount) - parseFloat(full.charge) + `</span>`);
                    } else if (full.trans_type == "debit") {
                        return `<span class='text-xs'style='color:black'> `+"{{$currencyDetails->symbol}} " +
                            (parseFloat(full.amount) + parseFloat(full.charge)) + `</span>`;
                    } else if (full.trans_type == "none") {
                        return `<span class='text-xs'style='color:black'> `+"{{$currencyDetails->symbol}} " +
                            (parseFloat(full.amount) - parseFloat(full.charge)) + `</span>`;
                    }
                } else {
                    return `<span class='text-xs' style='color:black'> `+"{{$currencyDetails->symbol}} " + full
                        .balance + `</span>`;
                }
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                if (full.status == "pending" || full.status == "success" || full.status == "reversed" ||
                    full.status == "refunded" || full.status == "failed") {
                    if (full.trans_type == "credit") {
                        return `<span class='text-xs'style='color:black'>`+"{{$currencyDetails->symbol}} " + (
                            parseFloat(full.balance) + parseFloat(parseFloat(full.amount) -
                                parseFloat(full.charge))) + `</span>`;
                    } else if (full.trans_type == "debit") {
                        return `<span class='text-xs 'style='color:black'> `+"{{$currencyDetails->symbol}} " +
                            (parseFloat(full.balance) - parseFloat(parseFloat(full.amount) + parseFloat(
                                full.charge))) + `</span>`;
                    } else if (full.trans_type == "none") {
                        return `<span class='text-xs 'style='color:black'>`+"{{$currencyDetails->symbol}} " +
                            (parseFloat(full.balance) - parseFloat(parseFloat(full.amount) - parseFloat(
                                full.charge))) + `</span>`;
                    }
                } else {
                    return `<span class='text-xs'style='color:black'>`+"{{$currencyDetails->symbol}} " + full
                        .balance + `</span>`;
                }
            }
        },
    ];

    datatableSetup(url, options, onDraw, '#datatable', {
        columnDefs: [{
            orderable: false,
            width: '80px',
            targets: [0]
        }]
    });
});
</script>
@endpush