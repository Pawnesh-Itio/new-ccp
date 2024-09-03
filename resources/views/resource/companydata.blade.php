@extends('layouts.user_type.auth')

@php
$table = "yes";
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
                                <!-- Type:resourceCompanydata slug: companydata_list,companydata_add,companydata_edit -->
                                <h6 class="card-title">Company Data Manager
                                    @if(isset($companydata_list))
                                    @if(!empty($companydata_list))
                                    <i class="fa fa-question-circle text-capitalize"
                                        data-bs-content="{{$companydata_list['description']}}"
                                        data-bs-trigger="hover focus" data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="Top popover" aria-hidden="true">
                                    </i>
                                    @endif
                                    @endif
                                </h6>

                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" onclick="addSetup()">
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
                                </button>
                            </div>
                        </div>
                    </div> 
                    <div class="card-body mb-4">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped align-items-center">
                                <thead style="color:black">
                                    <tr>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">#</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Title</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Slug</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">
                                            Description</th>
                                        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Type
                                        </th>
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
<!--Add Modal start -->
<div id="addModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Company</h5>
                @if(isset($companydata_add))
                @if(!empty($companydata_add))
                <i class="fa fa-question-circle text-capitalize" style="float:right"
                    data-bs-content="{{$companydata_add['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="addManager" action="{{route('resourceupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id">
                        <input type="hidden" name="actiontype" value="companydata">
                        <div class="form-group mb-2 col-md-12">
                            <label>Type</label>
                            <select name="type" class="form-control" onchange="companyShow(this)">
                                <option value="">Choose Any One Option</option>
                                <option value="E_ALL">ALL</option>
                                <option value="SINGLE">Single</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 col-md-12" id="companyselect" style="display:none">
                            <label>Choose a Company</label>
                            <select name="company_id" class="form-control">
                                <option value="">Choose Any One Option</option>
                                @foreach($company as $cd)
                                <option value="{{$cd->id}}">{{$cd->companyname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Slug</label>
                            <select name="slug" class="form-control">
                                <option value="">Choose Any One Option</option>
                                <option value="c_news">News</option>
                                <option value="c_notice">Notice</option>
                                <option value="c_support">Support Details</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="title" required="">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Description</label>
                            <textarea name="description" id="add-description-editor" class="form-control"
                                placeholder="Description..."></textarea>
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
<!-- End add Modal -->
<!--Setup Modal start -->
<div id="setupModal" class="modal fade model-headers-color" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span class="msg">Add</span> Company</h5>
                @if(isset($companydata_edit))
                @if(!empty($companydata_edit))
                <i class="fa fa-question-circle text-capitalize" style="float:right"
                    data-bs-content="{{$companydata_edit['description']}}" data-bs-trigger="hover focus"
                    data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" aria-hidden="true">
                </i>
                @endif
                @endif
            </div>
            <form id="setupManager" action="{{route('resourceupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="id">
                        <input type="hidden" name="actiontype" value="companydata">
                        <div class="form-group mb-2 col-md-12">
                            <label>Slug</label>
                            <input type="text" name="slug" class="form-control" placeholder="Slug" required="">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Type</label>
                            <input type="text" name="type" class="form-control" placeholder="Type" required="">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Title" required="">
                        </div>
                        <div class="form-group mb-2 col-md-12">
                            <label>Description</label>
                            <textarea name="description" id="description-editor" class="form-control"
                                placeholder="Description..."></textarea>
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
<!-- End Setup Modal -->
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
$(document).ready(function() {
    var url = "{{route('companydata')}}";
    var onDraw = function() {

    };
    var options = [{
            "data": "id",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + data + `</span>`;
            }
        },
        {
            "data": "title",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.title + `</span>`;
            }
        },
        {
            "data": "slug",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.slug + `</span>`;
            }
        },
        {
            "data": "website",
            render: function(data, type, full, meta) {
                return `<span class='text-xs b-0' style='color:black'>` + full.description + `</span>`;
            }
        },
        {
            "data": "name",
            render: function(data, type, full, meta) {
                if (full.type == 'SINGLE') {
                    return `<span class='text-xs b-0' style='color:black'>` + full.type + `|` + full
                        .companyname + `</span>`;
                } else {
                    return `<span class='text-xs b-0' style='color:black'>` + full.type + `</span>`;
                }
            }
        },
        {
            "data": "action",
            render: function(data, type, full, meta) {

                var out = `<a href="javascript:void(0)" class='text-xs b-0' onclick="editSetup('` + full
                    .id + `', '` + full.title + `', '` + full.slug + `', '` + full.description
                    .replaceAll("'", "\\'") + `', '` + full.type + `')">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                       </svg>Edit Company Data                                      
                                    </a>`;
                return out;
            }
        },
    ];
    datatableSetup(url, options, onDraw);

    $("#setupManager").validate({
        rules: {
            title: {
                required: true,
            }
        },
        messages: {
            title: {
                required: "Please enter Title",
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
            var form = $('#setupManager');
            var id = form.find('[name="id"]').val();
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
                        // notify("", 'success');
                        flasher.success("Task Successfully Completed");
                        $('#setupModal').modal('hide');
                        $('#addModal').modal('hide');
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

    // Add Handler
    $("#addManager").validate({
        rules: {
            title: {
                required: true,
            }
        },
        messages: {
            title: {
                required: "Please enter Title",
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
            var form = $('#addManager');
            var id = form.find('[name="id"]').val();
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
                        // notify("", 'success');
                        flasher.success("Task Successfully Completed");
                        $('#addModal').modal('hide');
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
    // End Add Handler

    $("#setupModal").on('hidden.bs.modal', function() {
        $('#setupModal').find('.msg').text("Add");
        $('#setupModal').find('form')[0].reset();
    });

});

function addSetup() {
    $('#addModal').find('.msg').text("Add");
    $('#addModal').find('input[name="id"]').val("0");
    $('#addModal').modal('show');
}

function editSetup(id, title, slug, description, type) {
    $('#setupModal').find('.msg').text("Edit");
    $('#setupModal').find('input[name="id"]').val(id);
    $('#setupModal').find('input[name="title"]').val(title);
    $('#setupModal').find('input[name="slug"]').val(slug).prop('readonly', true);
    $('#setupModal').find('input[name="type"]').val(type).prop('readonly', true);
    $('#setupModal').find('description-editor').val(description);
    $('#setupModal').modal('show');
}

function companyShow(type) {
    var value = type.value;
    if (value == 'SINGLE') {
        document.getElementById("companyselect").style.display = "block";
        $('#addModal').find('select[name="company_id"]').prop('disabled', false);
    } else {
        $('#addModal').find('select[name="company_id"]').prop('disabled', true);
        document.getElementById("companyselect").style.display = "none";
    }
}
</script>
<script>
ClassicEditor.create(document.querySelector('#description-editor'))
    .catch(error => {
        console.error(error);
    });
</script>
<script>
ClassicEditor.create(document.querySelector('#add-description-editor'))
    .catch(error => {
        console.error(error);
    });
</script>
@endpush