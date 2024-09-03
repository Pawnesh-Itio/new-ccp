<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-bottom nav-justified no-margin">
        @foreach ($commission as $key => $value)
            <li class="{{($key == 'payout') ? 'active' : ''}}"><a href="#{{$key}}" data-bs-toggle="tab" class="legitRipple" aria-expanded="true">{{($key == 'payout') ? 'Payout' : 'Payin'}}</a>&nbsp;|&nbsp;</li>
        @endforeach
    </ul>

    <div class="tab-content">
        @if(isset($mydata['schememanager']) && $mydata['schememanager']->value == "admin")
            @foreach ($commission as $key => $value)
                <div class="tab-pane {{($key == 'payout') ? 'active' : ''}}" id="{{$key}}">
                    <table class="table table-striped align-items-center">
                        <thead style="color:black">
                                <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Provider</th>
                                <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Type</th>
                                @if(App\Helpers\Permission::hasRole(['admin']))
                                <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Charges</th>
                                @endif
                        </thead>

                        <tbody>
                            @foreach ($value as $comm)
                                <tr>
                                    <td class='text-xs b-0' style='color:black'>{{ucfirst($comm->provider->name)}}</td>
                                    <td class='text-xs b-0' style='color:black'>{{ucfirst($comm->type)}}</td>
                                    @if(App\Helpers\Permission::hasRole(['admin']))
                                    <th class='text-xs b-0' style='color:black'>{{ucfirst($comm->apiuser)}}</th>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            @foreach ($commission as $key => $value)
                <div class="tab-pane {{($key == 'mobile') ? 'active' : ''}}" id="{{$key}}">
                    <table class="table table-bordered" cellspacing="0" style="width:100%">
                        <thead>
                                <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Provider</th>
                                <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Type</th>
                                <th class="text-uppercase  text-xs font-weight-bolder opacity-7 ps-2">Value</th>
                        </thead>

                        <tbody>
                            @foreach ($value as $comm)
                                <tr>
                                    <td class="text-xs b-0">{{ucfirst($comm->provider->name)}}</td>
                                    <td class="text-xs b-0">{{ucfirst($comm->type)}}</td>
                                    <td class="text-xs b-0">{{$comm->value}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif
    </div>
</div>