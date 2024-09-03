<?php
?>
<div class="table-responsive">
<table class="table table-striped  ">
    <thead style='color:black'>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Acquirer Id</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Acquirer Name</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Api Endpoint</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">S2S Agent</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Status</th>
        <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Action</th>
    </thead>

    <tbody>
        @foreach ($acquirer as $ad)
        <tr>
            <td class="text-xs b-0 text-black" style='color:black'>{{$ad->acquirer_id}}</td>
            <td class="text-xs b-0 text-black" style='color:black'>{{$ad->acquirer_name}}</td>
            <td class="text-xs b-0 text-black" style='color:black'>{{$ad->api_endpoint}}</td>
            <td class="text-xs b-0 text-black" style='color:black'>
            <input type="radio" id="{{$ad->acquirer_id}}" name="s2sAgent" onclick="s2sAgentUpdate(this.value,{{$merchant_id}})" value="{{$ad->acquirer_id}}"  @if($ad->acquirer_id == $s2s_agent_id) checked @endif" >
            </td>
            @if($ad->is_active == 'yes')
            <td class="text-xs b-0 text-black" style='color:black'><span class="badge bg-gradient-success">Active</span>
            </td>
            @else
            <td class="text-xs b-0 text-black" style='color:black'><span
                    class="badge bg-gradient-danger">In-Active</span></td>
            @endif
            <td class="text-xs b-0 " style="color:black"><button type="button" class="btn bg-danger legitRipple btn-xs"
                    onclick="deleteAcquirer(<?= $ad->merchant_acquirer_mapping_id ?>)"> <i
                        class="fa fa-trash text-white"></i></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>