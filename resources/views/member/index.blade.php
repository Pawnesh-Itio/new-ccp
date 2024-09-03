@extends('layouts.user_type.auth')
@php
$table = "yes";
$export = $type;
$table = "yes";
$agentfilter ="true";
switch($type){

case 'kycpending':

case 'kycsubmitted':

case 'kycrejected':

$status['type'] = "Kyc";
$status['data'] = [
"pending" => "Pending",
"submitted" => "Submitted",
"verified" => "Verified",
"rejected" => "Rejected",
];

break;

default:
$status['type'] = "member";
$status['data'] = [
"active" => "Active",
"block" => "Block"
];
break;
}
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
                                <h6>{{isset($role->name) ? $role->name : $type}} List
                                    <!-- Type:memberMerchant slug:merchant_list, merchant_fund_t_r, merchant_scheme, merchant_permission, merchant_sound_setting,merchant_gst_charge, merchant_settle_amount -->
                                    @if(isset($merchant_list))
                                    @if(!empty($merchant_list))
                                    <i class="fa fa-question-circle text-capitalize"
                                        data-bs-content="{{$merchant_list['description']}}"
                                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Top popover" aria-hidden="true">
                                    </i>
                                    @endif
                                    @endif
                                </h6>
                            </div>
                            @if($role || sizeOf($roles) > 0)
                            <div class="col-sm-2">
                                <a href="{{route('member', ['type' => $type, 'action' => 'create'])}}"
                                    class="btn btn-primary" onclick="addSetup()">
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
                                </a>
                            </div>
                            @endif
                        </div>
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
                                        @if(App\Helpers\Permission::hasRole(['md','whitelable', 'admin', 'distributor'])
                                        &&
                                        in_array($type, ['md', 'distributor', 'whitelable', 'other']))
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Id Stock
                                        </th>
                                        @endif
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
<!-- Sound Modal Start -->
<div id="soundModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sound Settings</h5>
                @if(isset($merchant_sound_setting))
                @if(!empty($merchant_sound_setting))
                <i class="fa fa-question-circle text-capitalize"
                    data-bs-content="{{$merchant_sound_setting['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="soundForm" action="{{route('updateSound')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="user_id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-12 mb-3">
                            <label>Sound Box Language </label>
                            <select class="form-control" name="soundBoxLanguage" id="soundBoxLanguage" required>
                                <option value="HINDI">Hindi</option>
                                <option value="ENGLISH">English</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label>Sound Box Type</label>
                            <select class="form-control" name="soundBoxType" id="soundBoxType" required>
                                <option value="QS1">QS1</option>
                                <option value="QS2">QS2</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label>Sound Box Serial</label>
                            <input type="text" class="form-control" name="soundBoxSerial" id="soundBoxSerial" required>
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
<!-- End Sound Modal -->
<!-- GST Modal -->
<div id="gstModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">GST Charges</h5>
                @if(isset($merchant_gst_charge))
                @if(!empty($merchant_gst_charge))
                <i class="fa fa-question-circle text-capitalize"
                    data-bs-content="{{$merchant_gst_charge['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="gstModalForm" action="{{route('updateGstRate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="user_id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-12 mb-3">
                            <label>GST Charge (In Percentage %)</label>
                            <select class="form-control" name="gstrate" id="gstrate" required>
                                <option value="18">18</option>
                                <option value="28">28</option>
                            </select>
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
<!-- GST Modal -->
<!-- Settlement Modal -->
<div id="settlementModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Settle Amount</h5>
                @if(isset($merchant_settle_amount))
                @if(!empty($merchant_settle_amount))
                <i class="fa fa-question-circle text-capitalize"
                    data-bs-content="{{$merchant_settle_amount['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="settlementModalForm" action="{{route('fundtransaction')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="user_id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-12 mb-3">
                            <label>Select Beneficiary</label>
                            <select class="form-control" name="beneid" id="beneid" required>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <!--<input type="hidden" class="form-control" name="mode" value="IMPS" required="">-->
                        <div class="form-group col-md-6 mb-4">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter Value"
                                required="">
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <input type="hidden" name="type" value="settleamount">
                            <label>Payment Mode</label>
                            <select name="mode" class="form-control">
                                <option value="IMPS">IMPS</option>
                                <option value="NEFT">NEFT</option>
                            </select>
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
<!-- End Settlement Modal -->
<!-- Transfer Modal -->
<div id="transferModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fund Transfer / Return</h5>
                @if(isset($merchant_fund_t_r))
                @if(!empty($merchant_fund_t_r))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$merchant_fund_t_r['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="transferForm" action="{{route('fundtransaction')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="user_id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6 mb-3">
                            <label>Fund Action</label><br>
                            <select name="type" class="form-control" id="select" required>
                                <option value="">Select Action</option>
                                @if (App\Helpers\Permission::can('fund_transfer'))
                                <option value="transfer">Transfer</option>
                                @endif
                                @if (App\Helpers\Permission::can('fund_return'))
                                <option value="return">Return</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label>Amount</label>
                            <input type="number" name="amount" step="any" class="form-control"
                                placeholder="Enter Amount" required="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 mb-3">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control" rows="3" placeholder="Enter Remark"></textarea>
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
<!-- Permission Modal -->
@if (isset($permissions) && $permissions && App\Helpers\Permission::can('member_permission_change'))
<div id="permissionModal" class="modal model-headers-color">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Member Permission</h5>
                @if(isset($merchant_permission))
                @if(!empty($merchant_permission))
                <i class="fa fa-question-circle text-capitalize"
                    data-bs-content="{{$merchant_permission['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="permissionForm" action="{{route('toolssetpermission')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="user_id">
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
@endif
<!-- End Permission Modal -->
<!-- Commission Modal -->
<div id="commissionModal" class="modal model-headers-color">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h5 class="modal-title" id="exampleModalLabel">Scheme Manager</h5>
                @if(isset($merchant_scheme))
                @if(!empty($merchant_scheme))
                <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$merchant_scheme['description']}}"
                    data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                    data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="schemeForm" method="post" action="{{ route('profileUpdate') }}">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id">
                    <input type="hidden" name="actiontype" value="scheme">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Scheme</label>
                            <select class="form-control select" name="scheme_id" onchange="viewCommission(this)">
                                <option value="">Select Scheme</option>
                                @foreach ($scheme as $element)
                                <option value="{{$element->id}}">{{$element->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label style="width:100%">&nbsp;</label>
                            <button class="btn btn-primary px-4" type="submit"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                                aria-hidden="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-body no-padding commissioData">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                    aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Commission Modal -->
<!-- View Acquirer Model -->

<div id="acquirerViewModal" class="modal model-headers-color">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Acquirer View</h5>
                @if(isset($merchant_acquirer_view))
                @if(!empty($merchant_acquirer_view))
                <i class="fa fa-question-circle text-capitalize"
                    data-bs-content="{{$merchant_acquirer_view['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <div class="modal-body">
                <div class="add-form">
                    <form id="acquirerAddForm" action="{{route('addAcquirer')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="merchant_id">
                        <div class="form-group">
                        <select id="selectField" name="acquirer_id" class="form-control">
                            <!-- Options will be populated dynamically -->
                        </select>
                        </div>
                        <div class="form-gorup">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                                aria-hidden="true">Close</button>
                            <button class="btn btn-primary px-4" type="submit"
                                data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="acquirerData mt-4">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                    aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End View Acquirer Model -->
<!-- Id Modal -->
@if (App\Helpers\Permission::can('member_stock_manager'))
<div id="idModal" class="modal fade" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h4 class="modal-title">Ids Stock</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                @if ($type == "other")
                <form class="idForm" method="post" action="{{ route('profileUpdate') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="actiontype" value="wstock">
                    <input type="hidden" name="id" value="">
                    <table class="table table-bordered" cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="150px">Stock Type</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>Whitelable Id</td>
                            <td>
                                <input type="number" name="wstock" step="any" class="form-control"
                                    placeholder="Enter Value" required="">
                            </td>
                            <td>
                                <button class="btn bg-slate btn-raised legitRipple" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
                @endif
                <br>
                @if ($type == "other" || $type == "whitelable")
                <form class="idForm" method="post" action="{{ route('profileUpdate') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="actiontype" value="mstock">
                    <input type="hidden" name="id" value="">
                    <table class="table table-bordered" cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="150px">Stock Type</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>Master Id</td>
                            <td>
                                <input type="number" name="mstock" step="any" class="form-control"
                                    placeholder="Enter Value" required="">
                            </td>
                            <td>
                                <button class="btn bg-slate btn-raised legitRipple" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
                @endif
                <br>
                @if ($type == "other" || $type == "md" || $type == "whitelable")
                <form class="idForm" method="post" action="{{ route('profileUpdate') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="actiontype" value="dstock">
                    <input type="hidden" name="id" value="">
                    <table class="table table-bordered" cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="150px">Stock Type</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>Distributor Id</td>
                            <td>
                                <input type="number" name="dstock" step="any" class="form-control"
                                    placeholder="Enter Value" required="">
                            </td>
                            <td>
                                <button class="btn bg-slate btn-raised legitRipple" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
                @endif
                <br>
                @if ($type == "other" || $type == "md" || $type == "whitelable" || $type == "distributor")
                <form class="idForm" method="post" action="{{ route('profileUpdate') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="actiontype" value="rstock">
                    <input type="hidden" name="id" value="">
                    <table class="table table-bordered" cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="150px">Stock Type</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>Retailer Id</td>
                            <td>
                                <input type="number" name="rstock" step="any" class="form-control"
                                    placeholder="Enter Value" required="">
                            </td>
                            <td>
                                <button class="btn bg-slate btn-raised legitRipple" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                            </td>
                        </tr>
                    </table>
                </form>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal"
                    aria-hidden="true">Close</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
@endif
<!-- End Id Modal -->
@endsection
@push('style')
<style>
.select2-selection__rendered {
    display: none;
}
</style>
@endpush
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    //$('.select').select2();
    var url = "{{url('fetch')}}/{{$type}}/0";
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
                        'status': status,
                        'actiontype': 'profile-status'
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        // notify("", 'success');
                        flasher.success("Member Updated");
                        $('#datatable').dataTable().api().ajax.reload();
                    } else {
                        if (status == "active") {
                            ele.prop('checked', false);
                        } else {
                            ele.prop('checked', true);
                        }
                        // notify("" ,'warning');
                        flasher.error("Something went wrong, Try again.");
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
                var type = "";
                if (full.status != "block") {
                    check = "checked='checked'";
                }
                if (full.kyc == "verified") {
                    var kyc_status = `<span class="badge bg-gradient-success">KYC Verified</span>`;
                }
                if (full.kyc == "submitted") {
                    var kyc_status = `<span class="badge bg-gradient-info">KYC Submitted</span>`;
                }
                if (full.kyc == "pending") {
                    var kyc_status = `<span class="badge bg-gradient-warning">KYC Pending</span>`;
                }
                if (full.kyc == "rejected") {
                    var kyc_status = `<span class="badge bg-gradient-danger">KYC Rejected</span>`;
                }
                return `<div>
                            <div class="switch-checkboxinp">
                                 ` + kyc_status + `<br>
                                 <label class="switch">
                                    <input id="membarStatus" type="checkbox" ` + check + ` value="` + full.id +
                    `" actionType="` + type + `">
                                    <div class="slider round"></div>
                                 </label>
                              </div>
                            <span class='text-xs b-0 font-weight-bolder'style='color:black'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div>
                        <span class='text-xs b-0'style='color:black' >` + full.updated_at + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<span class="name text-xs b-0 font-weight-bolder" style='color:black'>` + full
                    .name +
                    `</span>` + `<br><span class='text-xs b-0' style='color:black'>` + full
                    .mobile + `</span><br><span class='text-xs b-0' style='color:black'>` + full.role
                    .name + `</span>`;
            }
        },
        {
            "data": "parents",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                if (full.company != null) {
                    var company_name = full.company.companyname;
                    var company_website = full.company.website;
                } else {
                    var company_name = "Pending";
                    var company_website = "Pending";
                }
                return `<span class="name text-xs font-weight-bolder" style='color:black'>` +
                    company_name + `</span>` +
                    `<br><span class='text-xs b-0' style='color:black'>` + company_website +
                    `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                return `<span class='text-xs font-weight-bolder' style='color:black'>Main : </span><span class='text-xs b-0' style='color:black'>`+full.symbol +
                    full.mainwallet + `</span>`;
            }
        },
        @if(App\Helpers\Permission::hasRole(['md', 'whitelable', 'admin', 'distributor']) && in_array(
            $type, ['md', 'distributor', 'whitelable', 'other'])) {
            "data": "name",
            render: function(data, type, full, meta) {
                @if($type == "other")
                return "Whitelable - " + full.wstock + "<br> Md - " + full.mstock +
                    "<br> Distributor - " + full.dstock + "<br> Retailer - " + full.rstock;
                @endif
                @if($type == "whitelable")
                return "Md - " + full.mstock + "<br> Distributor - " + full.dstock +
                    "<br> Retailer - " + full.rstock;
                @endif
                @if($type == "md")
                return "Distributor - " + full.dstock + "<br> Retailer - " + full.rstock;
                @endif
                @if($type == "distributor")
                return "Retailer - " + full.rstock;
                @endif
            }
        },
        @endif {
            "data": "action",
            render: function(data, type, full, meta) {
                var out = '';
                var menu = ``;
                // viewAcquirer
                menu +=
                    `<a class="dropdown-item" href="javascript:void(0)" onclick="viewAcquirer(` + full
                    .id +
                    `)"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span> View Acquirer</a>`;

                @if(App\Helpers\Permission::can(['fund_transfer', 'fund_return']))
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="transfer(` + full
                    .id +
                    `)"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span> Fund Transfer / Return</a>`;
                menu += `<a class="dropdown-item" href="{{url('profile/view')}}/` + full.id +
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-eye"></i></span> View Profile</a>`;
                @endif
                @if(App\Helpers\permission::can(['company_manager']))
                menu += `<a class="dropdown-item" href="{{url('resources/companyprofile')}}/` + full
                    .company_id +
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-eye"></i></span> View Company Profile</a>`;
                @endif
                @if(App\Helpers\Permission::hasNotRole('apiuser'))
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="scheme(` + full
                    .id + `, '` + full.scheme_id +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-eye"></i></span> Scheme</a>`;
                @endif
                @if(App\Helpers\Permission::can('member_permission_change'))
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="getPermission(` +
                    full.id +
                    `)"><span class="sub-action-icon"><i class="fa fa-cog"></i></span> Permission</a>`;
                @endif
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="soundSettings(` +
                    full.id + `,'` + full.soundBoxLanguage + `','` + full.soundBoxType + `','` + full
                    .soundBoxSerial +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span> Sound Settings</a>`;
                menu += `<a class="dropdown-item" href="javascript:void(0)" onclick="setgstcharge(` +
                    full.id + `,'` + full.gstrate +
                    `')"><span class="sub-action-icon"><i class="fa-solid fa-people-roof"></i></span> GST Charge</a>`;
                menu += `<a class="dropdown-item" href="{{url('statement/account/')}}/` + full.id +
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-receipt"></i></span> Account Statement</a>`;
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
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-receipt"></span></i> Upi Statement</a>`;
                @endif
                @if(App\Helpers\Permission::can(['member_account_statement_view']))
                menu2 += `<a class="dropdown-item" href="{{url('statement/account/')}}/` + full.id +
                    `" target="_blank"><span class="sub-action-icon"><i class="fa-solid fa-receipt"></i></span> Account Statement</a>`;
                @endif
                /* out2 +=`<div class="btn-group dropdown-action-perform" role="group">
                       <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Report <i class="fa-solid fa-chevron-down"></i>
                       </button>
                       <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          `+menu+`
                       </div>
                    </div>`;*/
                return out + out2;
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
    $("#soundForm").validate({
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase() === "select") {
                error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('#soundForm');
            var type = $('#soundForm').find('[name="type"]').val();
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
                        flasher.success("Sound Data Update successfully");
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
    $("#gstModalForm").validate({
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase() === "select") {
                error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('#gstModalForm');
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                complete: function() {
                    form.find('button:submit').button('reset');
                },
                success: function(data) {
                    if (data.statuscode == "TXN") {
                        getbalance();
                        form.closest('.modal').modal('hide');
                        // notify("GST successfully applied", 'success');
                        flasher.success("GST Successfully applied");
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
    $("#kycUpdateForm").validate({
        rules: {
            kyc: {
                required: true
            }
        },
        messages: {
            kyc: {
                required: "Please select kyc status",
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
            var form = $('#kycUpdateForm');
            var type = $('#kycUpdateForm').find('[name="type"]').val();
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
                        // notify("Member Kyc Updated Successfull", 'success');
                        flasher.success("Member Kyc Updated Successfull");
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
                        // notify("", 'success');
                        flasher.success("Member Scheme Updated Successfull");
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
    $('form.idForm').submit(function() {
        var form = $(this);
        $(this).ajaxSubmit({
            dataType: 'json',
            beforeSubmit: function() {
                form.find('button:submit').button('loading');
            },
            complete: function() {
                form.find('button:submit').button('reset');
            },
            success: function(data) {
                form[0].reset();
                if (data.status == "success") {
                    // notify('Stock Updated Successfully', 'success');
                    flasher.success("Stock Updated Successfully");
                } else {
                    // notify('Transaction Failed', 'warning');
                    flasher.error("Transaction Failed");
                }
                $('#datatable').dataTable().api().ajax.reload();
            },
            error: function(errors) {
                if (errors.status == 422) {
                    $.each(errors.responseJSON, function(index, value) {
                        form.find('input[name="' + index + '"]').closest(
                            'div.form-group').append(
                            '<span class="text-danger">' + value[0] + '</span>');
                    });
                    setTimeout(function() {
                        form.find('span.text-danger').remove();
                    }, 5000);
                } else if (errors.status == 400) {
                    // notify(errors.responseJSON.status, "Sorry" , 'error');
                    flasher.error(errors.responseJSON.status + "Sorry");
                } else {
                    // notify(errors.statusText, errors.status , 'error');
                    flasher.error(errors.statusText + errors.status);
                }
            }
        });
        return false;
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
                    flasher.success("Permission Set Successfully");
                } else {
                    // notify('Transaction Failed', 'warning');
                    flasher.error("Transaction Failed");
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
    $("#settlementModalForm").validate({
        rules: {
            amount: {
                required: true
            },
            type: {
                required: true
            },
        },
        messages: {
            amount: {
                required: "Please enter request amount",
            },
            type: {
                required: "Please select request type",
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
            var form = $('#settlementModalForm');
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
                        form.closest('.modal').modal('hide');
                        // notify("Fund successfully transfered", 'success');
                        flasher.success("Fund successfully transfered");
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
    // Aquirere Add form
    $("#acquirerAddForm").validate({
        errorElement: "p",
        errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase() === "select") {
                error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
            var form = $('#acquirerAddForm');
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
                        form.closest('.modal').modal('hide');
                        // notify("Fund "+type+" Successfull", 'success');
                        flasher.success("Acquirer added successfully");
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

function soundSettings(id, soundBoxLanguage, soundBoxType, soundBoxSerial) {
    $('#soundForm').find('[name="user_id"]').val(id);
    $('#soundForm').find('[name="soundBoxLanguage"]').val(soundBoxLanguage);
    $('#soundForm').find('[name="soundBoxType"]').val(soundBoxType);
    $('#soundForm').find('[name="soundBoxSerial"]').val(soundBoxSerial);
    $('#soundModal').modal('show');
}

function setgstcharge(id, rate) {
    $('#gstModalForm').find('[name="user_id"]').val(id);
    $('#gstModalForm').find('[name="gstrate"]').select2().val(rate).trigger('change');
    $('#gstModal').modal('show');
}

function settleamount(userid) {
    $('#settlementModalForm').find('[name="user_id"]').val(userid);
    getmemberbenelist(userid);
    //$('#settlementModalForm').find('[name="gstrate"]').select2().val(rate).trigger('change');
    //$('#commissionModal').modal('hide');
    $('#settlementModal').modal('show');
}

function getPermission(id) {
    if (id.length != '') {
        $.ajax({
                url: "{{url('tools/get/permission')}}/" + id,
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
            .done(function(data) {
                $('#permissionForm').find('[name="user_id"]').val(id);
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
                flasher.error("Somthing went wrong");
            });
    }
}

function kycManage(id, kyc, remark) {
    $('#kycUpdateForm').find('[name="id"]').val(id);
    $('#kycUpdateForm').find('[name="kyc"]').select2().val(kyc).trigger('change');
    $('#kycUpdateForm').find('[name="remark"]').val(remark);
    $('#kycUpdateModal').modal();
}

function scheme(id, scheme) {
    //alert();
    $('#schemeForm').find('[name="id"]').val(id);
    if (scheme != '' && scheme != null && scheme != 'null') {
        // alert();
        $('#schemeForm').find('[name="scheme_id"]').select2().val(scheme).trigger('change');
    }
    $('#commissionModal').modal('show');
}

function addStock(id) {
    $('#idModal').find('input[name="id"]').val(id);
    $('#idModal').modal();
}

function addAcquirer(id) {
    if (id != '' && id != 0) {
        $.ajax({
                url: '{{route("getAcquirerList")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "id": id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        buttons: false,
                        text: 'Please wait, we are fetching acquirers',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .success(function(data) {
                swal.close();
                var selectField = $('#selectField');
                selectField.empty();
                selectField.append($('<option>', {
                    value: "",
                    text: "Please select a acquirer..."
                }));
                $.each(data, function(index, option) {
                    selectField.append($('<option>', {
                        value: option.acquirer_id,
                        text: option.acquirer_name
                    }));
                });

            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error("Somthing went wrong");
            });
    }
    $('#acquirerAddModal').find('input[name="merchant_id"]').val(id);
    $('#acquirerAddModal').modal('show');
}

function viewAcquirer(id) {
    // $('#acquirerViewModal').find('input[name="id"]').val(id);
    if (id != '' && id != 0) {
        // 
        $.ajax({
                url: '{{route("getAcquirerList")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "id": id
                }
            })
            .success(function(data) {
                swal.close();
                var selectField = $('#selectField');
                selectField.empty();
                selectField.append($('<option>', {
                    value: "",
                    text: "Please select a acquirer..."
                }));
                $.each(data, function(index, option) {
                    selectField.append($('<option>', {
                        value: option.acquirer_id,
                        text: option.acquirer_name
                    }));
                });

            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error("Somthing went wrong");
            });
    }
    $('#acquirerViewModal').find('input[name="merchant_id"]').val(id);
        // 
        $.ajax({
                url: '{{route("getAllAcquirers")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "merchant_id": id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        buttons: false,
                        text: 'Please wait, we are fetching Acquirers',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .success(function(data) {
                swal.close();
                $('#acquirerViewModal').find('.acquirerData').html(data);
            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error('Somthing went wrong');
            });
    $('#acquirerViewModal').modal('show');
}
@if(isset($mydata['schememanager']) && $mydata['schememanager'] -> value == "all")

function viewCommission(element) {
    var scheme_id = $(element).val();
    if (scheme_id != '' && scheme_id != 0) {
        $.ajax({
                url: '{{route("getMemberPackageCommission")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "scheme_id": scheme_id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        buttons: false,
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
                $('#commissionModal').find('.commissioData').html(data);
            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error("Somthing went wrong");
            });
    }
}
@else
 
function viewCommission(element) {
    var scheme_id = $(element).val();
    if (scheme_id != '' && scheme_id != 0) {
        $.ajax({
                url: '{{route("getMemberCommission")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "scheme_id": scheme_id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        buttons: false,
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
                $('#commissionModal').find('.commissioData').html(data);
            })
            .fail(function() {
                swal.close();
                // notify('Somthing went wrong', 'warning');
                flasher.error('Somthing went wrong');
            });
    }
}
@endif

function getmemberbenelist(userid) {
    $.ajax({
            url: '{{route("getmemberbenelist")}}',
            type: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "id": userid
            },
            beforeSend: function() {
                swal({
                    title: 'Wait!',
                    text: 'Please wait, requesting..',
                    buttons: false,
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
        })
        .success(function(data) {
            swal.close();
            var output = "";
            $.each(data.data, function(index, val) {
                output += `<option value='` + val.id + `'>` + val.bankname + ` (` + val.benename +
                    `)</option>`;
            });
            $('#settlementModalForm').find('select[name="beneid"]').html(output);
            $('#settlementModalForm').find('select[name="beneid"]').select2().trigger('change');
            // $('#settlementModalForm').find('[name="scheme_id"]').select2().val(scheme).trigger('change');
        })
}
function deleteAcquirer(id) {
    // delete token swal
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
                    url: "{{ route('acquirerMemberDelete') }}",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id
                    },
                    success: function(data) {
                        if (data.status == "1") {
                            swal("Poof! Your record  has been deleted!", {
                                icon: "success",
                            }).then((willDelete) => {
                                flasher.success("Acquirer Successfully Deleted");
                                 $("#acquirerViewModal").modal('hide');
                                
                            });
                        }
                    },
                    error: function(errors) {
                        flasher.error("Something went wrong, try again");
                        swal("Your record  is safe!");
                    }
                });
            } else {

                swal("Your Record is safe!");
            }
        });
}
function s2sAgentUpdate(val,id){
    $.ajax({
        url:"{{route('s2s_agent_update')}}",
        type:"post",
        datatype:"json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            's2s_agent':val,
            'id':id
        },
        beforeSend: function() {
                swal({
                    title: 'Wait!',
                    text: 'Please wait, requesting..',
                    buttons: false,
                    onOpen: () => {
                        swal.showLoading()
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });
            }
    })
    .success(function(data){
        swal.close();
        if(data.status == 1){
            flasher.success("S2S Agent Successfully Updated");
        }else{
            flasher.error("Somthing went wrong !");
        }
    });
}
function userStatus(id,status){
    $.ajax({
        url:"{{ route('profileUpdate') }}",
        type:"post",
        datatype:"json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            'id':id,
            'status':status,
            'actiontype': 'profile-status'
        },
    })
    .success(function(data){
        if(data.status == 'success'){
            flasher.success("User status activated");
        }else{
            flasher.error("Somthing went wrong !");
        }
    });
}
</script>
@endpush