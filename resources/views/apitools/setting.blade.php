@extends('layouts.user_type.auth')
@php
$table = "yes";
@endphp
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6" style="padding:15px">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-11">
                                <h6 class="card-title">Business Key</h6>
                            </div>
                            <div class="col-sm-1">
                                <!-- Help Box -->
                                <!-- Type: setting, Slug:businessKey, apiDocument -->
                                @if(isset($businessKey))
                                @if(!empty($businessKey))
                                <i class="fa fa-question-circle text-capitalize"
                                    data-bs-content="{{$businessKey['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-border table-hover">
                                <thead style="color:black">
                                    <tr>
                                        <td class="text-uppercase text-xs font-weight-bolder opacity-7 col-sm-3">Ter No
                                            : </td>
                                        <td class='text-xs b-0' style="text-transform: lowercase;">
                                            {{$businessdata->terno}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-uppercase text-xs font-weight-bolder opacity-7 col-sm-3">Public
                                            Key : </td>
                                        <td class='text-xs b-0'>{{$businessdata->public_key}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-uppercase text-xs font-weight-bolder opacity-7 col-sm-3">
                                            KYC-Status : </td>
                                        <td class='text-xs b-0'>
                                            @if($kyc_status=="verified") 
                                            <span class="badge bg-gradient-success">KYC Verified</span>
                                            @endif
                                            @if($kyc_status=="submitted") 
                                            <span class="badge bg-gradient-info">KYC Submitted</span>
                                            @endif
                                            @if($kyc_status=="pending") 
                                            <span class="badge bg-gradient-warning">KYC Pending</span>
                                            @endif
                                            @if($kyc_status=="rejected") 
                                            <span class="badge bg-gradient-danger">KYC Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="padding:15px">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-sm-11">
                                <h6 class="card-title">API Documentation</h6>
                            </div>
                            <div class="col-sm-1">
                                <!-- Help Box -->
                                @if(isset($apiDocument))
                                @if(!empty($apiDocument))
                                <i class="fa fa-question-circle text-capitalize"
                                    data-bs-content="{{$apiDocument['description']}}" data-bs-trigger="hover focus"
                                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                                    aria-hidden="true">
                                </i>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-border table-hover">
                                <thead style="color:black">
                                    <tr>
                                        <td class="text-uppercase text-xs font-weight-bolder opacity-7">Base Url: </td>
                                        <td class='text-xs b-0' style="text-transform: lowercase;">
                                            http://{{$_SERVER['HTTP_HOST']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-uppercase text-xs font-weight-bolder opacity-7">Api Document
                                            Url: </td>
                                        <td class='text-xs b-0' style="text-transform: lowercase;">
                                            <a href="https://documenter.getpostman.com/view/32295715/2sA3Qs9Bv7"
                                                target="_blank">https://documenter.getpostman.com/view/32295715/2sA3Qs9Bv7</a>
                                        </td>
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
@endsection
@push('script')
@endpush