<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>

@if (\Request::is('rtl'))
<html dir="rtl" lang="ar">
@else
<html lang="en">
@endif

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (env('IS_DEMO'))
    <x-demo-metas></x-demo-metas>
    @endif

    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
    <title>
         Instant Charge Backoffice {{date('Y')}}
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{asset('assets/css/soft-ui-dashboard.css?v=1.0.3')}}" rel="stylesheet" />
    <link id="pagestyle" href="{{asset('assets/css/style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('')}}assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{asset('')}}assets/vendors/select2/select2.min.css">
    <style>
    .select2.select2-container.select2-container {
        width: 100% !important;
    }

    #datatable_wrapper div#datatable_paginate .paginate_button {
        background-color: #fff;
        border: 1px solid #cb0c9f;
        padding: 5px 10px;
        margin: 3px;
        color: #cb0c9f;
        cursor: pointer;
    }

    #datatable_wrapper div#datatable_paginate .paginate_button.current {
        background-color: #cb0c9f;
        color: #fff;
    }

    #loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
    }
    </style>
    @stack('style')
</head>

<body
    class="g-sidenav-show  bg-gray-100 {{ (\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '')) }} ">
    @auth
    @if(!Request::is('dashboard'))
    <div class="page-content">
        <div id="loader-container">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    @endif

    @yield('auth')

    @endauth
    @guest
    @yield('guest')
    @endguest
    <!-- Edit Report Modal -->
    <div id="editModal" class="modal model-headers-color">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Report</h5>
                    @if(isset($payoutEdit))
                    @if(!empty($payoutEdit))
                    <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$payoutEdit['description']}}"
                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-content="Top popover" aria-hidden="true">
                    </i>
                    @endif
                    @endif
                </div>
                <form id="editForm" action="{{route('statementUpdate')}}" method="post">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="pending">Pending</option>
                                    <option value="success">Success</option>
                                    <option value="failed">Failed</option>
                                    <option value="reversed">Reversed</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Ref No</label>
                                <input type="text" name="refno" class="form-control" placeholder="Enter ref no"
                                    required="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label>Txn Id</label>
                                <input type="text" name="txnid" class="form-control" placeholder="Enter txn id"
                                    required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Pay Id</label>
                                <input type="text" name="payid" class="form-control" placeholder="Enter Vle id"
                                    required="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label>Remark</label>
                                <textarea rows="3" name="remark" class="form-control"
                                    placeholder="Enter Remark"></textarea>
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
            </div>
        </div>
    </div>
    <!-- End Edit Report Modal -->
    <!-- Compaint Model -->
    <div id="complaintModal" class="modal model-headers-color">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Complain</h5>
                    @if(isset($paymentComplain))
                    @if(!empty($paymentComplain))
                    <i class="fa fa-question-circle text-capitalize"
                        data-bs-content="{{$paymentComplain['description']}}" data-bs-trigger="hover focus"
                        data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover"
                        aria-hidden="true">
                    </i>
                    @endif
                    @endif
                    @if(isset($payoutComplain))
                    @if(!empty($payoutComplain))
                    <i class="fa fa-question-circle text-capitalize" data-bs-content="{{$payoutComplain['description']}}"
                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-content="Top popover" aria-hidden="true">
                    </i>
                    @endif
                    @endif
                </div>
                <form id="complaintForm" action="{{route('complaintstore')}}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="id" value="new">
                        <input type="hidden" name="product">
                        <input type="hidden" name="transaction_id">

                        <div class="form-group mb-4">
                            <label>Subject</label>
                            <select name="subject" class="form-control">
                                <option value="">Select Subject</option>
                                @foreach ($mydata['complaintsubject'] as $item)
                                <option value="{{$item->id}}">{{$item->subject}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>Description</label>
                            <textarea name="description" cols="30" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                            aria-hidden="true">Close</button>
                        <button class="btn btn-primary px-4" type="submit"
                            data-loading-text="<i class='fa fa-spinner'></i>Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Complaint Model -->
    <!-- Wallet Load Modal -->
    @if (App\Helpers\Permission::hasRole('admin'))
    <div id="walletLoadModal" class="modal fade model-headers-color">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Wallet Load</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>

                </div>
                <form id="walletLoadForm" action="{{route('fundtransaction')}}" method="post">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <input type="hidden" name="type" value="loadwallet">
                            {{ csrf_field() }}
                            <div class="form-group col-md-12">
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
                        <button type="button" class="btn btn-default btn-raised legitRipple" data-bs-dismiss="modal"
                            aria-hidden="true">Close</button>
                        <button class="btn btn-primary btn-raised legitRipple" type="submit"
                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <!-- End Wallet Load Modal -->
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/chartjs.min.js')}}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.form.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    @stack('rtl')
    @stack('dashboard')
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @if (isset($table) && $table == "yes")
    <script src="{{asset('')}}assets/js/data-table.js"></script>
    @endif
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@flasher/flasher@1.2.4/dist/flasher.min.js"></script>
    <script type="text/javascript"
        src="https://login.m2money.in/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    @if (isset($table) && $table == "yes")
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/tables/datatables/datatables.min.js"></script>
    @endif
    <script>
    @if(!Request::is('register') || Request::is('login/forgot-password') || !Request::is('login'))
    @if(isset($table) && $table == "yes")

    function datatableSetup(urls, datas, onDraw = function() {}, ele = "#datatable", element = {}) {
        var options = {
            dom: '<"datatable-scroll"t><"datatable-footer"ip>',
            processing: true,
            serverSide: false,
            ordering: false,
            stateSave: true,
            length: 10,
            columnDefs: [{
                orderable: false,
                width: '130px',
                targets: [0]
            }],
            language: {
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '&rarr;',
                    'previous': '&larr;'
                }
            },
            drawCallback: function() {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
            },
            preDrawCallback: function() {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
            },
            ajax: {
                url: urls,
                type: "post",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.fromdate = $('#searchForm').find('[name="from_date"]').val();
                    d.todate = $('#searchForm').find('[name="to_date"]').val();
                    d.searchtext = $('#searchForm').find('[name="searchtext"]').val();
                    d.agent = $('#searchForm').find('[name="agent"]').val();
                    d.status = $('#searchForm').find('[name="status"]').val();
                    d.product = $('#searchForm').find('[name="product"]').val();
                },
                beforeSend: function() {},
                complete: function() {
                    $('#searchForm').find('button:submit').button('reset');
                    $('#formReset').button('reset');
                },
                error: function(response) {}
            },
            columns: datas
        };

        $.each(element, function(index, val) {
            options[index] = val;
        });

        var DT = $(ele).DataTable(options).on('draw.dt', onDraw);
        return DT;
    }
    @endif
    $(document).ready(function() {
        $(document).ajaxStart(function() {
            $('#loader-container').fadeIn();
        });

        $(document).ajaxComplete(function() {
            $('#loader-container').fadeOut();
        });
        // Load Wallet Handler start
        $("#walletLoadForm").validate({
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
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#walletLoadForm');
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
                            getbalance();
                            form.closest('.modal').modal('hide');
                            // notify("Wallet successfully loaded", 'success');
                            flasher.success("Wallet Successfully Loaded")
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
        // Load Wallet Handler Ends
        // Complaint Functions 
        $("#complaintForm").validate({
            rules: {
                subject: {
                    required: true,
                },
                description: {
                    required: true,
                }
            },
            messages: {
                subject: {
                    required: "Please select subject",
                },
                description: {
                    required: "Please enter your description",
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
                var form = $('#complaintForm');
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
                            flasher.success("Complaint successfully submitted");
                        } else {
                            flasher.error("Somthing went wrong please try agian");
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });
        // EditForm
        $("#editForm").validate({
            rules: {
                status: {
                    required: true,
                },
                txnid: {
                    required: true,
                },
                payid: {
                    required: true,
                },
                refno: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please select status",
                },
                txnid: {
                    required: "Please enter txn id",
                },
                payid: {
                    required: "Please enter payid",
                },
                refno: {
                    required: "Please enter ref no",
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
                var form = $('#editForm');
                var id = form.find('[name="id"]').val();
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        if (data.status == "success") {
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
    });
    // Status
    function status(id, type) {
        $.ajax({
                url: `{{route('statementStatus')}}`,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        buttons: false,
                        text: 'Please wait, we are fetching transaction details',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                },
                data: {
                    'id': id,
                    "type": type
                }
            })
            .done(function(data) {
                if (data.status == "success") {
                    if (data.refno) {
                        var refno = "Operator Refrence is " + data.refno
                    } else {
                        var refno = data.remark;
                    }
                    swal({
                        type: 'success',
                        title: data.status,
                        text: refno,
                        onClose: () => {
                            $('#datatable').dataTable().api().ajax.reload();
                        },
                    });
                } else {
                    swal({
                        type: 'success',
                        title: data.status,
                        text: "Transaction status is " + data.status,
                        onClose: () => {
                            $('#datatable').dataTable().api().ajax.reload();
                        },
                    });
                }
            })
            .fail(function(errors) {
                swal.close();
                // alert(errors.responseJSON.status);
                showError(errors);
            });
    }
    // filter submit
    $('form#searchForm').submit(function() {

        //$('#searchForm').find('button:submit').button('loading');

        var fromdate = $('input[name="from_date"]').val();
        var todate = $('input[name="to_date"]').val();
        // var fromdate =  $('#searchForm').find('input[name="from_date"]').val();
        // var todate =  $('#searchForm').find('input[name="to_date"]').val();
        //console.log(fromdate);
        if (fromdate.length != 0 || todate.length != 0) {
            $('#datatable').dataTable().api().ajax.reload();
        }
        return false;
    });
    $('.mydate').datepicker({
        'autoclose': true,
        'clearBtn': true,
        'todayHighlight': true,
        'format': 'yyyy-mm-dd'
    });

    $('input[name="from_date"]').datepicker("setDate", new Date());
    $('input[name="to_date"]').datepicker('setStartDate', new Date());

    $('input[name="to_date"]').focus(function() {
        if ($('input[name="from_date"]').val().length == 0) {
            $('input[name="to_date"]').datepicker('hide');
            $('input[name="from_date"]').focus();
        }
    });

    $('input[name="from_date"]').datepicker().on('changeDate', function(e) {
        $('input[name="to_date"]').datepicker('setStartDate', $('input[name="from_date"]').val());
        $('input[name="to_date"]').datepicker('setDate', $('input[name="from_date"]').val());
    });


    $('#formReset').click(function() {
        $('form#searchForm')[0].reset();
        $('form#searchForm').find('[name="from_date"]').datepicker().datepicker("setDate",
            new Date());
        $('form#searchForm').find('[name="to_date"]').datepicker().datepicker("setDate", null);
        $('form#searchForm').find('select').select2().val(null).trigger('change')
        $('#formReset').button('loading');
        $('#datatable').dataTable().api().ajax.reload();
    });


    $('select').change(function(event) {
        var ele = $(this);
        if (ele.val() != '') {
            $(this).closest('div.form-group').find('p.error').remove();
        }
    });
    $('.reportExport').click(function() {
        var type = $(this).attr('product');
        var downablebtntype = $(this).attr("downloadfilebtn");
        var fromdate = $('#searchForm').find('[name="from_date"]').val();

        var todate = $('#searchForm').find('[name="to_date"]').val();
        var searchtext = $('#searchForm').find('[name="searchtext"]').val();
        var agent = $('#searchForm').find('[name="agent"]').val();
        var status = $('#searchForm').find('[name="status"]').val();
        var product = $('#searchForm').find('[name="product"]').val();



        window.location.href = "{{ url('statement/export') }}/" + type + "?fromdate=" + fromdate +
            "&todate=" + todate + "&searchtext=" + searchtext + "&agent=" + agent + "&status=" +
            status + "&product=" + product + "&downablebtntype=" + downablebtntype;
    });


    setTimeout(function() {
        sessionOut();
    }, "{{$mydata['sessionOut']}}");

    $(".modal").on('hidden.bs.modal', function() {
        if ($(this).find('form').length) {
            $(this).find('form')[0].reset();
        }

        if ($(this).find('.select').length) {
            $(this).find('.select').val(null).trigger('change');
        }
    });
    // Complaint Function
    function complaint(id, product) {
        $('#complaintModal').find('[name="transaction_id"]').val(id);
        $('#complaintModal').find('[name="product"]').val(product);
        $('#complaintModal').modal('show');
    }
    // Edit report function 
    function editReport(id, refno, txnid, payid, remark, status, actiontype) {
        $('#editModal').find('[name="id"]').val(id);
        $('#editModal').find('[name="status"]').val(status).trigger('change');
        $('#editModal').find('[name="refno"]').val(refno);
        $('#editModal').find('[name="txnid"]').val(txnid);
        if (actiontype == "billpay") {
            $('#editModal').find('[name="payid"]').closest('div.form-group').remove();
        } else {
            $('#editModal').find('[name="payid"]').val(payid);
        }
        $('#editModal').find('[name="remark"]').val(remark);
        $('#editModal').find('[name="actiontype"]').val(actiontype);
        $('#editModal').modal('show');
    }

    // Error function

    function showError(errors, form = "withoutform") {
        if (form != "withoutform") {
            form.find('button[type="submit"]').button('reset');
            $('p.error').remove();
            $('div.alert').remove();
            if (errors.status == 422) {
                $.each(errors.responseJSON.errors, function(index, value) {
                    form.find('[name="' + index + '"]').closest('div.form-group').append('<p class="error">' +
                        value + '</span>');
                });
                form.find('p.error').first().closest('.form-group').find('input').focus();
                setTimeout(function() {
                    form.find('p.error').remove();
                }, 5000);
            } else if (errors.status == 400) {
                if (errors.responseJSON.message) {
                    flasher.error(errors.responseJSON.message);
                } else {
                    flasher.error(errors.responseJSON.status);
                }

                setTimeout(function() {
                    form.find('div.alert').remove();
                }, 10000);
            } else {
                flasher.error(errors.statusText);
            }
        } else {
            if (errors.responseJSON.message) {
                flasher.error(errors.responseJSON.message);

            } else {
                flasher.error(errors.responseJSON.status);
            }
        }
    }
    $(window).load(function() {
        getbalance();
    });
    // Get Balance
    function getbalance() {
        $.ajax({
            url: "{{route('getbalance')}}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(result) {
                $.each(result, function(index, value) {
                    $('.' + index).text(value);
                });
            }
        });
    }
    @endif
    </script>

    @stack('script')

</body>

</html>