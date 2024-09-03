<table class="table table-striped ">
    <thead style='color:black'>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Id</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Field Name</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Field Label</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Field Type</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">is_active</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Action</th>
    </thead>

    <tbody>
        @foreach ($acquirer_fields as $ad)
        <tr>
            <td class="text-xs b-0 text-black" style="color:black">
                {{$ad->field_id}}
                <!-- Status Update -->
                <div class="switch-checkboxinp">
                    <label class="switch">
                        <input id="acquirerFieldStatus" onclick="fieldStatusUpdate(this)" type="checkbox"
                            @if($ad->is_active =='yes') checked @endif value="{{$ad->field_id}}">
                        <span class="slider round"></span>
                    </label>
                </div>
                <!-- End Status Update -->
            </td>
            <td class="text-xs b-0 text-black" style='color:black'>{{$ad->field_name}}</td>
            <td class="text-xs b-0 text-black" style='color:black'>{{$ad->field_label}}</td>
            <td class="text-xs b-0 text-black" style='color:black'>{{$ad->field_type}}</td>
            @if($ad->is_active == 'yes')
            <td class="text-xs b-0 text-black" style='color:black'><span class="badge bg-gradient-success">Active</span>
            </td>
            @else
            <td class="text-xs b-0 text-black" style='color:black'><span
                    class="badge bg-gradient-danger">In-Active</span></td>
            @endif
            <td class="text-xs b-0 " style="color:black">
            <button type="button"  class="btn btn-sm btn-icon btn-primary" onclick="editfieldSetup('<?= $acquirer_id ?>','<?= $ad->field_id ?>','<?= $ad->field_name ?>','<?= $ad->field_label ?>','<?= $ad->field_type ?>')"> Edit</button>
            <button type="button" class="btn bg-danger legitRipple btn-xs"
                    onclick="deleteAcquirerField(<?= $ad->field_id ?>)"> <i
                        class="fa fa-trash text-white"></i></button>
            </td>

            @endforeach
    </tbody>
</table> 