@extends('layouts.user_type.auth')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- type:setupportalsetting slug:PS_Walllet_ST,PS_Bank_ST,operator_add,operator_edit -->
            <!-- Wallet Settlement type -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Wallet Settlement Type
                                </h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($PS_walllet_ST))
                                @if(!empty($PS_walllet_ST))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$PS_Walllet_ST['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="settlementtype">
                        <input type="hidden" name="name" value="Wallet Settlement Type">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Settlement Type</label>
                                <select name="value" required="" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="auto"
                                        {{(isset($settlementtype->value) && $settlementtype->value == "auto") ? "selected=''" : ''}}>
                                        Auto</option>
                                    <option value="mannual"
                                        {{(isset($settlementtype->value) && $settlementtype->value == "mannual") ? "selected=''" : ''}}>
                                        Manual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Wallet Settlement type -->
            <!-- Bank Settlement type -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Bank Settlement Type
                                </h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($PS_Bank_ST))
                                @if(!empty($PS_Bank_ST))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$PS_Bank_ST['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="banksettlementtype">
                        <input type="hidden" name="name" value="Wallet Settlement Type">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Settlement Type</label>
                                <select name="value" required="" class="form-control select">
                                    <option value="">Select Type</option>
                                    <option value="auto"
                                        {{(isset($banksettlementtype->value) && $banksettlementtype->value == "auto") ? "selected=''" : ''}}>
                                        Auto</option>
                                    <option value="mannual"
                                        {{(isset($banksettlementtype->value) && $banksettlementtype->value == "mannual") ? "selected=''" : ''}}>
                                        Manual</option>
                                    <option value="down"
                                        {{(isset($banksettlementtype->value) && $banksettlementtype->value == "down") ? "selected=''" : ''}}>
                                        Down</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Bank Settlement type -->
            <!-- Bank Settlement Charge -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Bank Settlement Charge</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="settlementcharge">
                        <input type="hidden" name="name" value="Bank Settlement Charge">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Charge</label>
                                <input type="number" name="value" value="{{$settlementcharge->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Bank Settlement Charge  -->
            <!-- Bank Settlement Charge Upto 25000 -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Bank Settlement Charge Upto 25000</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="impschargeupto25">
                        <input type="hidden" name="name" value="Bank Settlement Charge Upto 25000">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Charge</label>
                                    <input type="number" name="value" value="{{$impschargeupto25->value ?? ''}}"
                                        class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Bank Settlement Charge Upto 25000  -->
            <!-- Bank Settlement Charge Above 25000 -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Bank Settlement Charge Above 25000</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="impschargeabove25">
                        <input type="hidden" name="name" value="Bank Settlement Charge Above 25000">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Charge</label>
                                <input type="number" name="value" value="{{$impschargeabove25->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Bank Settlement Charge Above 25000  -->
            <!-- Login Otp Required -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Login Otp Required</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="otplogin">
                        <input type="hidden" name="name" value="Login required otp">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Login Type</label>
                                <select name="value" required="" class="form-control select">
                                    <option value="">Select Type</option>
                                    <option value="yes"
                                        {{(isset($otplogin->value) && $otplogin->value == "yes") ? "selected=''" : ''}}>
                                        With Otp</option>
                                    <option value="no"
                                        {{(isset($otplogin->value) && $otplogin->value == "no") ? "selected=''" : ''}}>
                                        Without Otp</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Login Otp Required  -->
            <!-- Bank Payout Api -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Bank Payout Api</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="bankpayoutapi">
                        <input type="hidden" name="name" value="Bank Settlement Api">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Payout Api</label>
                                <select name="value" required="" class="form-control select">
                                    <option value="">Select Type</option>
                                    <option value="secure"
                                        {{(isset($bankpayoutapi->value) && $bankpayoutapi->value == "secure") ? "selected=''" : ''}}>
                                        Secure</option>
                                    <option value="paytm"
                                        {{(isset($bankpayoutapi->value) && $bankpayoutapi->value == "paytm") ? "selected=''" : ''}}>
                                        Paytm</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Bank Payout Api -->
            <!-- Sending mail id for otp -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Sending mail id for otp</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="otpsendmailid">
                        <input type="hidden" name="name" value="Sending mail id for otp">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Mail Id</label>
                                <input type="text" name="value" value="{{$otpsendmailid->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Sending mail id for otp -->
            <!-- Sending mailer name id for otp -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Sending mailer name id for otp</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="otpsendmailname">
                        <input type="hidden" name="name" value="Sending mailer name id for otp">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Mailer Name</label>
                                <input type="text" name="value" value="{{$otpsendmailname->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Sending mailer name id for otp  -->
            <!-- Bc Id for dmt -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Bc Id for dmt</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="bcid">
                        <input type="hidden" name="name" value="Bc Id for dmt">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Bcid</label>
                                <input type="text" name="value" value="{{$bcid->value ?? ''}}" class="form-control"
                                    required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Bc Id for dmt -->
            <!-- CP Id for dmt -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">CP Id for dmt</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="cpid">
                        <input type="hidden" name="name" value="CP Id for dmt">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Cpid</label>
                                <input type="text" name="value" value="{{$cpid->value ?? ''}}" class="form-control"
                                    required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End CP Id for dmt -->
            <!-- Transaction Id Code -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Transaction Id Code</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="transactioncode">
                        <input type="hidden" name="name" value="Transaction Id Code">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="value" value="{{$transactioncode->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Transaction Id Code  -->
            <!-- Main Wallet Locked Amount -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Main Wallet Locked Amount</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="mainlockedamount">
                        <input type="hidden" name="name" value="Main Wallet Locked Amount">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="value" value="{{$mainlockedamount->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Main Wallet Locked Amount -->
            <!-- Aeps Bank Settlement Locked Amount -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Aeps Bank Settlement Locked Amount</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="aepslockedamount">
                        <input type="hidden" name="name" value="Aeps Bank Settlement Locked Amount">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="value" value="{{$aepslockedamount->value ?? ''}}"
                                    class="form-control" required="" placeholder="Enter value">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Aeps Bank Settlement Locked Amount  -->
            <!-- Aeps Settlement Time -->
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-10">
                                <h6 class="card-title">Aeps Settlement Time</h6>
                            </div>
                            <div class="col-sm-2">
                                @if(isset($operator_list))
                                @if(!empty($operator_list))
                                <i class="fa fa-question-circle text-capitalize" style="float:right"
                                    data-bs-content="{{$operator_list['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="actiontype" value="portalsetting">
                        <input type="hidden" name="code" value="aepsslabtime">
                        <input type="hidden" name="name" value="Aeps Settlement Time">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Time (Comma Seperated)</label>
                                <textarea name="value" class="form-control" required=""
                                    placeholder="Enter value">{{$aepsslabtime->value ?? ''}}</textarea>
                            </div>
                            <p class="text-muted">Example - 11:00 Am, 2:00 PM</p>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update
                                    Info</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Aeps Settlement Time  -->
        </div>
    </div>
</main>
@endsection
@push('style')
<style>
.action-icon-add {
    list-style: none;
}
</style>
@endpush
@push('script')
<script type="text/javascript">
 $(document).ready(function () {
        $('.actionForm').submit(function(event) {
            var form = $(this);
            var id = form.find('[name="id"]').val();
            form.ajaxSubmit({
                dataType:'json',
                beforeSubmit:function(){
                    form.find('button[type="submit"]').button('loading');
                },
                success:function(data){
                    if(data.status == "success"){
                        if(id == "new"){
                            form[0].reset();
                            $('[name="api_id"]').select2().val(null).trigger('change');
                        }
                        form.find('button[type="submit"]').button('reset');
                        // notify("Task Successfully Completed", 'success');
                        flasher.success("Task Successfully Completed");
                    }else{
                        // notify(data.status, 'warning');
                        flasher.error(data.status);
                    }
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
            return false;
        });

        $("#setupModal").on('hidden.bs.modal', function () {
            $('#setupModal').find('.msg').text("Add");
            $('#setupModal').find('form')[0].reset();
        });

        $('')
    });
</script>
@endpush