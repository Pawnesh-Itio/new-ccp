@extends('layouts.user_type.auth')
@php
$table = "yes";
@endphp

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Call Back</h6>
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
                                            <!--<a href="https://documenter.getpostman.com/view/17438509/2s93z862jw" target="_blank">https://documenter.getpostman.com/view/17438509/2s93z862jw</a>-->
                                            <!--<a href="https://documenter.getpostman.com/view/31268035/2s9YeD8t2K" target="_blank">https://documenter.getpostman.com/view/31268035/2s9YeD8t2K</a>-->
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