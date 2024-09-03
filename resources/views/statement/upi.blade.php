@extends('layouts.user_type.auth')

@section('title', "Open Acquiring Statement")
@section('pagetitle', "Open Acquiring Statement")

@php
$table = "yes";
$export = "open_acquiring";

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
                        <!-- Type: statementPayment, Slug:paymentList, paymentCapture,,paymentRefund,paymentComplain -->
                        <h6>Payment Statement
                            @if(isset($paymentList))
                            @if(!empty($paymentList))
                            <i class="fa fa-question-circle text-capitalize"
                                data-bs-content="{{$paymentList['description']}}" data-bs-trigger="hover focus"
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
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Order Id
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Acquirer
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">User
                                            Details
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Bank
                                            Details
                                        </th>
                                        <th class="text-uppercase  text-xxs font-weight-bolder opacity-7 ps-2">Reference
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
    <!-- Refund Model -->
    <div id="refundCapModal" class="modal model-headers-color">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refCapModalLabel"></h5>
                    @if(isset($paymentRefund))
                    @if(!empty($paymentRefund))
                    <i class="fa fa-question-circle text-capitalize refund" style="display:none"
                        data-bs-content="{{$paymentRefund['description']}}" data-bs-trigger="hover focus"
                        data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                        aria-hidden="true">
                    </i>
                    @endif
                    @endif
                    @if(isset($paymentCapture))
                    @if(!empty($paymentCapture))
                    <i class="fa fa-question-circle text-capitalize capture" style="display:none"
                        data-bs-content="{{$paymentCapture['description']}}" data-bs-trigger="hover focus"
                        data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                        aria-hidden="true">
                    </i>
                    @endif
                    @endif
                </div>

                <form id="refCapForm" action="{{route('capture_refund')}}" method="post">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="reference_id">
                    <input type="hidden" name="transaction_id">
                    <input type="hidden" name="type">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" class="form-control" placeholder="Enter Amount"
                                    required="" id="amount">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label for="remark">Remark</label>
                                <textarea rows="3" name="comment" class="form-control" placeholder="Enter Remark"
                                    id="remark"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                            aria-hidden="true">Close</button>
                        <button class="btn btn-primary px-4" type="submit"
                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Transaction Detail Model -->
    <div id="transactionDetailModel" class="modal model-headers-color">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailModelLabel"> Transaction Detail</h5>
                    @if(isset($transactionDetail))
                    @if(!empty($transactionDetail))
                    <i class="fa fa-question-circle text-capitalize refund" style="display:none"
                        data-bs-content="{{$transactionDetail['description']}}" data-bs-trigger="hover focus"
                        data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                        aria-hidden="true">
                    </i>
                    @endif
                    @endif
                </div>
                <div class="modal-body">
                    <div class="td_content">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                        aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Transaction Detail Model -->
</main>
@endsection
@push('script');
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/upistatement/{{$id}}";
    var onDraw = function() {};
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {

                var data = '';
                var billObject = JSON.parse(full.billing_response);
                var responseObject = JSON.parse(full.response);
                if (full.acquirer_slug == 'Stripe') {
                    data = `<a href="javascript:void(0)" onclick="transactionDetailStripe
                    ('` +billObject[1].name + `', '` + billObject[1].phone + `', '` + billObject[1].id + `', '` + billObject[1].email + `','` +billObject[1].address.state + `','` +billObject[1].address.postal_code + `','` +billObject[1].address.country + `',
                    '` +billObject[1].address.city + `','` +billObject[1].address.line1 + `','` +billObject[0].metadata.ip + `','` +billObject[0].status + `','` +billObject[0].amount_received + `','` +billObject[0].payment_method_types + `',
                    '` +billObject[0].payment_method + `','` +billObject[0].id + `','` +billObject[0].latest_charge + `','` +billObject[0].description + `','` +billObject[0].currency + `','` +billObject[0].created + `')"> Transaction Detail 
                      </a>
                      `;
                }
                if (full.acquirer_slug == 'GTW' && billObject &&  responseObject && Object.keys(responseObject).length !== 0) {
                    data = `<a href="javascript:void(0)" onclick="transactionDetailGTW
                    ( '` + billObject.bill_amt + `', '` + responseObject.descriptor + `','` +responseObject.response + `','` +responseObject.reference+ `',
                    '` +billObject.bill_currency + `','` +billObject.fullname + `','` +billObject.bill_email + `','` +billObject.bill_address + `','` +billObject.bill_city + `','` +billObject.bill_state + `',
                    '` +billObject.bill_country + `','` +billObject.bill_zip + `','` +billObject.bill_phone + `','` +billObject.bill_ip + `','` +billObject.source + `','` +billObject.product_name + `','` +responseObject.status + `')"> Transaction Detail 
                      </a>
                      `;
                }
                return `<div>
                            <span class='text-xs font-weight-bold mb-0'style='color:black'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div>` +
                    `<span  class="text-xs b-0"style='color:black'>` + full.created_at +
                    `</span></div><br>` +
                    data;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<div>
                            <span class='text-xs font-weight-bold mb-0' style='color:black'>` + full.product + `</b> </span>
                            <div class="clearfix"></div>
                        </div>`;
            }
        },
        {
            "data": "username",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>` + data +
                    `</span>`;
            }
        },

        /* { "data" : "bank",
             render:function(data, type, full, meta){
                 return `Payer VPA - `+full.payer_vpa+`<br>Name - `+full.option1+`<br>Payee VPA - `+full.payeeVPA;
             }
         },*/
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>Mobile - </span><span class='text-xs b-0' style='color:black'>` +
                    full.mobile + '</span>';
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>Ref No. - </span><span class='text-xs b-0' style='color:black'>` +
                    full.refno +
                    `</span><br><span class='text-sm font-weight-bold' style='color:black'>Txnid - </span><a  href="javascript:void(0)" onclick='transactionDetail()'><span class='text-xs b-0' style='color:black'>` +
                    full.mytxnid + `</span></a>`;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {

                return `<span class='text-xs font-weight-bold mb-0' style='color:black'>Amount - {{$currencyDetails->symbol}} </span><span class='text-xs b-0' style='color:black'>`+
                    full.amount +
                    `</span><br><span class='text-xs font-weight-bold mb-0' style='color:black'>GST - {{$currencyDetails->symbol}} </span><span class='text-xs b-0'style='color:black'>`+
                    full.gst +
                    `</span><br><span class='text-xs font-weight-bold mb-0' style='color:black'>Charge - {{$currencyDetails->symbol}}</span><span class='text-xs b-0'style='color:black'>`+
                    full.charge + `</span>`;

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
                menu +=
                    `<a class="dropdown-item" href="javascript:void(0)" onclick="open_qcquiring_status('` +
                    full.user_id + `', '` + full.refno +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Check Status</a>`;
                @endif

                @if(App\Helpers\Permission::can('capture'))
                if (full.status == 'authorised' || full.status == 'partial capture') {
                    menu +=
                        `<a class="dropdown-item" href="javascript:void(0)" onclick="capture_refund(` +
                        full.id + `, '` + full.user_id + `', '` + full.refno + `', 1, '` + full
                        .mytxnid +
                        `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Capture</a>`;
                }
                @endif

                @if(App\Helpers\Permission::can('refund'))
                if (full.status == 'complete' || full.status == 'partial capture' || full.status ==
                    'partial refund') {
                    menu +=
                        `<a class="dropdown-item" href="javascript:void(0)" onclick="capture_refund(` +
                        full.id + `, '` + full.user_id + `', '` + full.refno + `', 2, '` + full
                        .mytxnid +
                        `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Refund</a>`;
                }
                @endif
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="complaint(` + full
                    .id +
                    `, 'UPI')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span>Complaint</a>`;

                out += `&nbsp;|&nbsp;
                <div class="btn-group dropdown" role="group">
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

    $("#refCapForm").validate({
        rules: {
            amount: {
                required: true,
            }
        },
        messages: {
            amount: {
                required: "Please enter amount",
            },
        },
        errorElement: "p",
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function() {
            var form = $('#refCapForm');
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
                        form.closest('.modal').modal('hide');
                        flasher.success(data.status);
                        // notify("Complaint successfully submitted", 'success');
                    } else {
                        // notify(data.status, 'warning');
                        form[0].reset();
                        form.closest('.modal').modal('hide');
                        flasher.error(data);
                    }
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
        }
    });
});

function viewUtiid(id) {
    $.ajax({
            url: `{{url('statement/fetch')}}/utiidstatement/` + id,
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
            notify('Oops', errors.status + '! ' + errors.statusText, 'warning');
        });
}

function open_qcquiring_status(user_id, reference_id) {
    $.ajax({
            url: `{{url('statement/open_acquiring_status')}}`,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'user_id': user_id,
                reference_id: reference_id
            }
        })
        .done(function(data) {
            // notify(data.statuscode + '! ' + data.message, 'success');
            if (data.statuscode == "TXN") {
                flasher.success(data.statuscode + '! ' + data.message);
            } else {
                flasher.error(data.statuscode + '! ' + data.message);
            }
        })
        .fail(function(errors) {
            // notify('Oops', errors.status + '! ' + errors.statusText, 'warning');
            flasher.error('Oops', errors.status + '!' + errors.statusText);
        });
}

function capture_refund(id, user_id, reference_id, type, transaction_id) {
    $('#refundCapModal').find('[name="id"]').val(id);
    $('#refundCapModal').find('[name="reference_id"]').val(reference_id);
    $('#refundCapModal').find('[name="user_id"]').val(user_id);
    $('#refundCapModal').find('[name="transaction_id"]').val(transaction_id);
    $('#refundCapModal').find('[name="type"]').val(type);
    if (type == "1") {
        $('.capture').css("display", "block");
        $('.refund').css("display", "none");
        $('#refundCapModal #refCapModalLabel').text('Capture');

    } else {
        $('.capture').css("display", "none");
        $('.refund').css("display", "block");
        $('#refundCapModal #refCapModalLabel').text('Refund');
    }
    $('#refundCapModal').modal('show');
}

function transactionDetailStripe(name,phone,cust_id,email,state,postal_code,country,city,line1,ip,status,amount_recived,pay_method_type,pay_method,pay_intent,charge,product,currency,date) {

    var table = `<table class="table table-borderless caption-top">
                <caption style="color:black">Buyer's Detail</caption>
                <tbody class="text-xs "style='color:black'>
                <tr>
                    <td  class="text-xs col-sm-3">Customer ID : </td>
                    <td  class="text-xs col-sm-9">`+cust_id+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Buyer's Name : </td>
                    <td  class="text-xs col-sm-9">`+name+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Buyer's Phone :</td>
                    <td  class="text-xs col-sm-9">`+phone+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Buyer's Email : </td>
                    <td  class="text-xs col-sm-9">`+email+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Buyer's Address</td>
                    <td  class="text-xs col-sm-9">`+ line1+', '+city+', '+state+', '+country+' - '+postal_code+ `</td>
                  </tr>
                </tbody>
              </table>
              <table class="table table-borderless caption-top">
                <caption style="color:black">Transaction Details</caption>
                <tbody class="text-xs "style='color:black'>
                  <tr>
                    <td  class="text-xs col-sm-3">Transaction status : </td>
                    <td  class="text-xs col-sm-9">`+status+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Product name :</td>
                    <td  class="text-xs col-sm-9">`+product+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Amount recived : </td>
                    <td  class="text-xs col-sm-9">`+amount_recived/100+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Amount recived : </td>
                    <td  class="text-xs col-sm-9">`+currency+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Payment method</td>
                    <td  class="text-xs col-sm-9">`+pay_method_type+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Payment method ID</td>
                    <td  class="text-xs col-sm-9">`+pay_method+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Payment intent ID</td>
                    <td  class="text-xs col-sm-9">`+pay_intent+`</td>
                  </tr>
                  <tr>
                    <td  class="text-xs col-sm-3">Last charge ID</td>
                    <td  class="text-xs col-sm-9">`+charge+`</td>
                  </tr>
                </tbody>
              </table>
              `;
    $('#transactionDetailModel').find('.td_content').html(table)
    $('#transactionDetailModel').modal('show');
}
function transactionDetailGTW(bill_amt,descriptor,response,reference,bill_currency,fullname,bill_email,bill_address,bill_city,bill_state,bill_country,bill_zip,bill_phone,bill_ip,source,product,status) {

var table = `<table class="table table-borderless caption-top">
            <caption style="color:black">Buyer's Detail</caption>
            <tbody class="text-xs "style='color:black'>
              <tr>
                <td  class="text-xs col-sm-3">Buyer's Name : </td>
                <td  class="text-xs col-sm-9">`+fullname+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Buyer's Phone :</td>
                <td  class="text-xs col-sm-9">`+bill_phone+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Buyer's Email : </td>
                <td  class="text-xs col-sm-9">`+bill_email+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Buyer's Address</td>
                <td  class="text-xs col-sm-9">`+ bill_address+', '+bill_city+', '+bill_state+', '+bill_country+' - '+bill_zip+ `</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-borderless caption-top">
            <caption style="color:black">Transaction Details</caption>
            <tbody class="text-xs "style='color:black'>
              <tr>
                <td  class="text-xs col-sm-3">Transaction status : </td>
                <td  class="text-xs col-sm-9 text-success">`+status+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Trans Response :</td>
                <td  class="text-xs col-sm-9 text-nowrap">`+response+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Product name :</td>
                <td  class="text-xs col-sm-9">`+product+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Descriptor :</td>
                <td  class="text-xs col-sm-9">`+descriptor+`</td>
              </tr>
              <tr>
                <td  class="text-xs col-sm-3">Bill Amt.(`+bill_currency+`) :</td>
                <td  class="text-xs col-sm-9 text-success">$`+bill_amt+`</td>
              </tr>
            </tbody>
          </table>
          `;
$('#transactionDetailModel').find('.td_content').html(table)
$('#transactionDetailModel').modal('show');
}
</script>
@endpush