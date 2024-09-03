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
                        <div class="row">
                            <div class="col-sm-10">
                                <!-- Type: toolsHelp Slug: helpList, helpAdd, helpEdit -->
                                <h6 class="card-title">Help Box List
                                    @if(isset($helpList))
                                    @if(!empty($helpList))
                                    <i class="fa fa-question-circle text-capitalize"
                                        data-bs-content="{{$helpList['description']}}"
                                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Top popover" aria-hidden="true">
                                    </i>
                                    @endif
                                    @endif
                                </h6>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" onclick="addhelp()">
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
                                    Add new
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-4">
                        <div class="table-responsive-lg">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Help box
                                            type</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Help box
                                            slug</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">
                                            Description</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Action
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
<!-- Role Modal Start -->
<div id="helpModal" class="modal model-headers-color">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add </span> Help Box</h5>
                @if(isset($helpAdd))
                @if(!empty($helpAdd))
                <i class="fa fa-question-circle text-capitalize add" style="display:none"
                    data-bs-content="{{$helpAdd['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
                @if(isset($helpEdit))
                @if(!empty($helpEdit))
                <i class="fa fa-question-circle text-capitalize edit" style="display:none"
                    data-bs-content="{{$helpEdit['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="helpbox" action="{{route('toolsstore', ['type'=>'help'])}}" method="post">
                <div class="modal-body">
                    <div class="row mb-4">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6">
                            <label>Help Box Type</label>
                            <input type="text" name="type" class="form-control" placeholder="Enter help box type..."
                                required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Help Box Slug</label>
                            <input type="text" name="slug" class="form-control" placeholder="Enter help box slug..."
                                required="">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Help Box Description</label>
                            <textarea type="text" name="description" class="form-control"
                                placeholder="Enter Description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mb-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                        aria-hidden="true">Close</button>
                    <button class="btn btn-primary px-4" type="submit"
                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var url = "{{url('fetch/helpbox/0')}}";
    var onDraw = function() {};
    var options = [{
            "data": "id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.id + `</span>`;
            }
        },
        {
            "data": "type",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.type + `</span>`;
            }
        },
        {
            "data": "slug",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.slug + `</span>`;
            }
        },
        {
            "data": "description",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black; '>` + full.description +
                `</span>`;
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {
                var menu = ``;
                var out = ``;

                menu +=
                    `<a class="dropdown-item" href="javascript:void(0)" onclick="editHelp(this)"><span class="fa fa-pencil"> <i class="fa-solid fa-people-roof"></i></span>Edit</a>`;

                out += `<div class="btn-group dropup" role="group">
                   <button id="btnGroupDrop1" type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                   Action
                   </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        ` + menu + `
                                    
                    </div>
                    </div>`;

                return out;
            }
        },
    ];
    datatableSetup(url, options, onDraw);

    $("#helpbox").validate({
        rules: {
            slug: {
                required: true,
            },
            name: {
                required: true,
            },
        },
        messages: {
            mobile: {
                required: "Please enter role slug",
            },
            name: {
                required: "Please enter role name",
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
            var form = $('#helpbox');
            var id = $('input[name="id"]').val();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button[type="submit"]').button('loading');
                },
                success: function(data) {
                    if (data.status == "success") {
                        if (id == "new") {
                            form[0].reset();
                        }
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

    $("#helpModal").on('hidden.bs.modal', function() {
        $('#helpModal').find('.msg').text("Add");
        $('#helpModal').find('form')[0].reset();
    });

});

function addhelp() {
    $(".add").css("display","block");
    $(".edit").css("display","none");
    $('#helpModal').find('.panel-title').text("Add New Role");
    $('#helpModal').find('input[name="id"]').val(0);
    $('#helpModal').modal('show');
}

function editHelp(ele) {
    var id = $(ele).closest('tr').find('td').eq(0).text();
    var type = $(ele).closest('tr').find('td').eq(1).text();
    var slug = $(ele).closest('tr').find('td').eq(2).text();
    var description = $(ele).closest('tr').find('td').eq(3).text();

    $(".add").css("display","none");
    $(".edit").css("display","block");

    $('#helpModal').find('.msg').text("Edit");
    $('#helpModal').find('input[name="id"]').val(id);
    $('#helpModal').find('input[name="type"]').val(type);
    $('#helpModal').find('input[name="slug"]').val(slug);
    $('#helpModal').find('textarea[name="description"]').val(description);
    $('#helpModal').modal('show');
}
</script>
@endpush