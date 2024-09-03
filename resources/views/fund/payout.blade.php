@extends('layouts.user_type.auth')
@php
$table = "yes";
$agentfilter="yes";
@endphp
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-3" style="padding:0px">
                <div class="comapany-header-type">
                    <div class="top-header-card-heading2">
                        <h6 class="card-title">New Beneficiary Details</h6>
                    </div>
                </div>
                <form id="beneficiaryForm" class="card px-4" action="{{route('fundtransaction')}}" method="post">
                    {{ csrf_field() }}
                    @if($errors->any())
                    <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">
                            {{$errors->first()}}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">
                            {{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="m-3  alert alert-primary alert-dismissible fade show" id="alert-primary" role="alert">
                        <span class="alert-text text-white">
                            {{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <br>
                    <input type="hidden" value="addbeneficiary" name="type">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bank Name :</label>
                        <select id="bank" name="benebank" class="js-example-basic-single form-select" data-width="100%"
                            required>
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank)
                            <option value="{{$bank->id}}" ifsc="{{$bank->masterifsc}}">{{$bank->bankname}}</option>
                            @endforeach
                        </select>
                        @error('bank')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Account No.</label>
                        <input type="text" class="form-control" name="beneaccount" id="beneaccount" required=""
                            placeholder="Enter Bank Account" minlength="6">
                        @error('beneaccount')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">IFSC Code</label>
                        <input type="text" class="form-control" name="beneifsc" id="beneifsc"
                            placeholder="Enter Bank Ifsc Code" required="">
                        @error('beneifsc')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Beneficiary Name</label>
                        <input type="text" class="form-control" name="benename" id="benename" required=""
                            placeholder="Enter Your Name">
                        @error('benename')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Beneficiary Number</label>
                        <input type="text" class="form-control" name="benemobile" id="benemobile" minlength="10"
                            maxlength="10" required="" placeholder="Enter Beneficiary Number">
                        @error('benemobile')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary px-3">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-9">
                <div class="comapany-header-type">
                    <div class="top-header-card-heading2">
                        <h6 class="card-title">Beneficiary List</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Bank Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Beneficiary Details</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody" style="color:black">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="fundRequestModal" class="modal model-headers-color">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Payout to Beneficiary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>

                    <form id="fundRequestForm" action="{{route('fundtransaction')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="bank">
                        <div class="modal-body">
                            <div style="overflow-x:auto;">
                                <table class="table table-bordered table-hover p-b-15" cellspacing="0"
                                    style="margin-bottom: 30px">
                                    <thead class="text-center">
                                        <tr style="background-color:#f1f1f1">

                                            <th>Name</th>
                                            <th>Account</th>
                                            <th>Bank</th>
                                            <th>Ifsc</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <td class="benename"></td>
                                        <td class="beneaccount"></td>
                                        <td class="benebank"></td>
                                        <td class="beneifsc"></td>

                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row">
                                <!--<input type="hidden" class="form-control" name="mode" value="IMPS" required="">-->
                                <div class="form-group col-md-6 mb-4">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" name="amount" placeholder="Enter Value"
                                        required="">
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <label>Payment Mode</label>
                                    <select name="mode" class="form-control">
                                        <option value="IMPS">IMPS</option>
                                        <option value="NEFT">NEFT</option>
                                    </select>
                                    <input type="hidden" name="inpbenename" class="inpbenename">
                                    <input type="hidden" name="inpbenemobile" class="inpbenemobile">
                                    <input type="hidden" name="inpbeneaccount" class="inpbeneaccount">
                                    <input type="hidden" name="inpbenebank" class="inpbenebank">
                                    <input type="hidden" name="inpbeneifsc" class="inpbeneifsc">
                                </div>

                            </div>


                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <label>T-Pin</label>
                                    <input type="password" name="pin" class="form-control"
                                        placeholder="Enter transaction pin" required="">
                                    <a href="{{url('profile/view')}}" target="_blank"
                                        class="text-primary pull-right">Generate Or Forgot Pin??</a>
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
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</main>
@endsection

@push('style')
@endpush

@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch')}}/beneficiarylist/0";
    var here_date_format = "DD MMM, YYYY h:m a";
    var onDraw = function() {};
    var options = [{
            "data": "name",
            render: function(data, type, full, meta) {
                var out = '';
                out += `<span class=' text-xs font-weight-bold mb-0' style='color:black'>` + full.id +
                    `</span><br><span class='text-xs b-0' style='color:black'>` + moment(full.created_at).format(
                        here_date_format) + `</span>`;
                return out;
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return "<span class='text-xs font-weight-bold mb-0' style='color:black'>Account - </span><span class='text-xs  b-0' style='color:black'>" +
                    full.beneaccno +
                    "</span><br><span class='text-xs font-weight-bold mb-0' style='color:black'>Bank - </span><span class='text-xs b-0' style='color:black'>" +
                    full.bankname +
                    "</span><br><span class='text-sm font-weight-bold mb-0' style='color:black'>IFSC - </span><span class='text-xs b-0' style='color:black'>" +
                    full.ifsc + "</span>";
            }
        },
        {
            "data": "bank",
            render: function(data, type, full, meta) {
                return "<span class='text-xs font-weight-bold mb-0' style='color:black'>Holder - </span><span class='text-xs b-0' style='color:black'>" +
                    full.benename +
                    "</span><br><span class='text-sm font-weight-bold mb-0'style='color:black'>Mobile - </span><span class='text-xs b-0' style='color:black'>" +
                    full.benemobile + "</span>";
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                var btn = `<button class="btn btn-primary btn-default" onclick="sendmoney('` + full
                    .beneaccno + `','` + full.ifsc + `','` + full.benename + `','` + full.benemobile +
                    `','` + full.bankname +
                    `')"><i class="fa fa-paper-plane"></i> Send</button>&nbsp;|&nbsp;<button type="button" class="btn btn-danger btn-defaultr" onclick="benedelete('` +
                    full.id + `')"><i class="fa fa-trash"></i></button>`;

                return btn;
            }
        }
    ];
    datatableSetup(url, options, onDraw);
    $('#bank').on('change', function(e) {
        $('input[name="beneifsc"]').val($(this).find('option:selected').attr('ifsc')).trigger('blur');
    });
});

function benedelete(id) {
    swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this record!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{route('fundtransaction')}}",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'type': "deletebeneficiary",
                        'id': id
                    },
                    success: function(data) {
                        if (data.statuscode == "TXN") {
                            swal("Poof! Your record  has been deleted!", {
                                icon: "success",
                            }).then((willDelete) => {
                                location.reload();
                            });
                        }
                    },
                    error: function(errors) {
                        swal("Your record  is safe!");
                    }
                });
            } else {
                swal("Your Record is safe!");
            }
        });
}

function sendmoney(beneaccount, beneifsc, benename, benemobile, bankname) {

    $('#fundRequestForm').find('input[name="inpbenemobile"]').val(benemobile);
    $('#fundRequestForm').find('input[name="inpbeneaccount"]').val(beneaccount);
    $('#fundRequestForm').find('input[name="inpbenebank"]').val(bankname);
    $('#fundRequestForm').find('input[name="inpbeneifsc"]').val(beneifsc);
    $('#fundRequestForm').find('input[name="inpbenename"]').val(benename);

    $('#fundRequestModal').find('.benename').text(benename);
    $('#fundRequestModal').find('.beneaccount').text(beneaccount);
    $('#fundRequestModal').find('.beneifsc').text(beneifsc);
    $('#fundRequestModal').find('.benebank').text(bankname);
    $('#fundRequestModal').modal('show');
}
</script>
@endpush