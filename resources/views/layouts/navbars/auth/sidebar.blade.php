<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <?php $CompanyData = \App\Helpers\Permission::getCompanyDetails(\Auth::user()->company_id); ?>
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center navbar-brand " href="{{ route('dashboard') }}">
            @if(!empty($CompanyData ))
            @if(!empty($CompanyData->logo))
            <img src="{{asset('assets/img/logos/')}}/{{$CompanyData->logo}}" class="navbar-brand-img" alt="Logo">
            @else
            <img src="{{asset('assets/img/logos/logo.jpg')}}" class="navbar-brand-img" width="100px" height="100px"  alt="Logo" >
            @endif
            @else
            <img src="{{asset('assets/img/logos/logo.jpg')}}" class="navbar-brand-img h-200"
                alt="...">
            @endif
            <!-- <span class="ms-3 font-weight-bold">Xyeso</span> -->
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- Main Start -->
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Main</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }} button"
                    href="{{ url('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>shop </title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(0.000000, 148.000000)">
                                            <path class="color-background opacity-6"
                                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            @if ((\App\Helpers\Permission::can(['company_manager', 'change_company_profile'])) ||
            (\App\Helpers\Permission::hasNotRole('retailer') && isset($mydata['schememanager']) &&
            $mydata['schememanager']->value == "all"))
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['resources/companyprofile','resources/companydata','resources/company','resources/scheme']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-gears text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Resources</span>
                </a>
                <ul class="dropdown-menu cus-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    @if (\App\Helpers\Permission::can('company_manager'))
                    <li>
                        <a class="dropdown-item border-radius-md " href="{{route('resource', ['type' => 'company'])}}">
                            <div class="icon " style="margin-left:5%"><i class="fa fa-building-o"
                                    aria-hidden="true"></i></i>
                            </div>
                            <span style="margin-left:20px">Company Manager</span>
                        </a>
                    </li>
                    @endif
                    @if (\App\Helpers\Permission::can('change_company_profile'))
                    <li>
                        <a class="dropdown-item border-radius-md " href="{{route('resource', ['type' => 'companyprofile'])}}">
                            <div class="icon " style="margin-left:5%"><i class="fa fa-regular fa-user-circle-o "></i>
                            </div>
                            <span style="margin-left:20px">Company Profile</span>
                        </a>
                    </li>
                    @endif
                    @if (\App\Helpers\Permission::hasRole(['admin']))
                    <li>
                        <a class="dropdown-item border-radius-md " href="{{route('resource', ['type' => 'companydata'])}}">
                            <div class="icon " style="margin-left:5%"><i class="fa fa-building-o"
                                    aria-hidden="true"></i>
                            </div>
                            <span style="margin-left:20px">Company Data</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md " href="{{route('resource', ['type' => 'scheme'])}}">
                            <div class="icon " style="margin-left:5%"><i class="fa fa-regular fa-user-circle-o "></i>
                            </div>
                            <span style="margin-left:20px">Scheme Manager</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            <!-- Main End -->
            <!-- Member start -->
            @if (App\Helpers\Permission::can(['view_whitelable', 'view_md', 'view_distributor', 'view_retailer',
            'view_apiuser', 'view_other', 'view_kycpending', 'view_kycsubmitted', 'view_kycrejected']) &&
            App\Helpers\Permission::hasRole('admin'))
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Member Information</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['member/reseller','member/apiuser']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users-line text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Member</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    @if(App\Helpers\Permission::hasRole(['admin']))
                    <!-- <li>
                        <a class="dropdown-item border-radius-md" href="{{route('member', ['type' => 'reseller'])}}">
                            <div class="icon" style="margin-left:5%">
                                <i class="fa fa-solid fa-users"></i>
                            </div>
                            <span style="margin-left:20px">
                                Reseller
                            </span>
                        </a>
                    </li> -->
                    @endif
                    @if (App\Helpers\Permission::hasRole(['admin']))
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('member', ['type' => 'apiuser'])}}">
                            <div class="icon" style="margin-left:5%">
                                <i class="fa fa-solid fa-store"></i>
                            </div>
                            <span style="margin-left:20px">
                                Merchant
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Member End -->
            <!-- Agent Start -->
            @if(App\Helpers\Permission::hasRole('admin'))
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['statement/openacquiringid','statement/cosmosid']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-user-tie text-dark side-icon" ></i>
                    </div>
                    <span class="nav-link-text ms-1">Agent List</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('setup' , ['type' => 'acquirer'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Acquirer</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a class="dropdown-item border-radius-md"
                            href="{{route('statement', ['type' => 'openacquiringid'])}}">
                            <div class="icon" style="margin-left:5%">
                            <i class="fa fa-solid fa-user-tie "></i>
                            </div>
                            <span style="margin-left:20px">
                                Acquirer Agent
                            </span>
                        </a>
                    </li> -->
                    <!-- <li>
                        <a class="dropdown-item border-radius-md" href="{{route('statement', ['type' => 'cosmosid'])}}">
                            <div class="icon" style="margin-left:5%">
                            <i class="fa fa-solid fa-user-tie"></i>
                            </div>
                            <span style="margin-left:20px">
                                COSMOS Agent
                            </span>
                        </a>
                    </li> -->
                </ul>
            </li>
            @endif
            <!-- Agent End -->
            <!-- Fund Start -->
            @if(App\Helpers\Permission::can('fund_request') && App\Helpers\Permission::hasRole(['admin']))
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Fund</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['statement/cosmosonboarding','fund/tr']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-money-bill text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Fund</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    @if (App\Helpers\Permission::can(['fund_transfer', 'fund_return']) && App\Helpers\Permission::hasRole(['admin']))
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('fund', ['type' => 'tr'])}}">
                            <div class="icon" style="margin-left:5%">
                                <i class="fa fa-exchange" aria-hidden="true"></i>
                            </div>
                            <span style="margin-left:20px">
                                Transfer/Return
                            </span>
                        </a>
                    </li>
                    @endif
                    <!-- @if (App\Helpers\Permission::hasNotRole('admin') && App\Helpers\Permission::can('fund_request'))
                    <li>
                        <a class="dropdown-item border-radius-md"
                            href="{{route('statement', ['type' => 'cosmosonboarding'])}}">
                            <div class="icon" style="margin-left:5%">
                                <i class="fa fa-solid fa-briefcase"></i>
                            </div>
                            <span style="margin-left:20px">
                                Onboarding-Cosmos
                            </span>
                        </a>
                    </li>
                    @endif -->
                </ul>
            </li>
            @endif
            @if (App\Helpers\Permission::hasRole(['apiuser','reseller']) && App\Helpers\Permission::can('fund_request') )
            <!-- <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is('fund/payout') ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-comment-dollar text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Payout</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('fund', ['type' => 'payout'])}}">
                            <div class="icon" style="margin-left:5%">
                                <i class="fa fa-solid fa-hand-point-up"></i>
                            </div>
                            <span style="margin-left:20px"> Request</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            @endif
            <!-- Fund End -->
            <!-- Report Start -->
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reports</h6>
            </li>
            @if(App\Helpers\Permission::can('money_statement'))
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['statement/upi','statement/payouts','complaint'] ) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-landmark-dome text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Transaction History</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    @if(App\Helpers\Permission::can('money_statement'))
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('statement',['type'=>'upi'])}}">
                            <div class="icon" style="margin-left:6%">
                                <i class="fa fa-money-check-alt"></i>
                            </div>
                            <span style="margin-left:20px">
                                Payment Statement
                            </span>
                        </a>
                    </li>
                    <!-- <li><a class="dropdown-item border-radius-md" href="{{route('statement', ['type' => 'payouts'])}}">
                            <div class="icon" style="margin-left:6%">
                                <i class="fa fa-money-check-alt"></i>
                            </div>
                            <span style="margin-left:20px">
                                Payout Statement
                            </span>
                        </a>
                    </li> -->
                    @endif
                    @if(App\Helpers\Permission::hasRole('admin'))
                    <li><a class="dropdown-item border-radius-md" href="{{route('complaint')}}">
                            <div class="icon" style="margin-left:6%">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <span style="margin-left:20px">
                                Complaints List
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if (\App\Helpers\Permission::can(['account_statement', 'awallet_statement']))
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is('statement/account') ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-receipt text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Account Statement</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('statement', ['type' => 'account'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-wallet"></i></div>
                            <span style="margin-left:20px">Main Wallet</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <!-- Settings -->
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Settings</h6>
            </li>
            @if (App\Helpers\Permission::hasRole(['apiuser','reseller']) && \App\Helpers\Permission::can(['account_statement', 'apiuser_acc_manager']))
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['developer/api/setting','developer/api/document']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-screwdriver-wrench text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">API Setting</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('apisetup', ['type' => 'setting'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-screwdriver-wrench"></i></div>
                            <span style="margin-left:20px">KEY & DOCUMENT</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a class="dropdown-item border-radius-md" href="{{route('apisetup', ['type' => 'document'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-screwdriver-wrench"></i></div>
                            <span style="margin-left:20px">API Documents</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            @endif
            @if (App\Helpers\Permission::hasRole('admin'))
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['developer/api/setting','developer/api/document','tools/roles','tools/permissions','tools/helpbox']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-universal-access text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Roles & Permissions</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('tools' , ['type' => 'roles'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Roles</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('tools' , ['type' => 'permissions'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Permission</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('tools' , ['type' => 'helpbox'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Help Box</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Setup -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle {{ (Request::is(['setup/api','setup/operator','setup/portalsetting']) ? 'active' : '') }} button"
                    data-bs-toggle="dropdown" id="dropdownMenuButton" href="#"
                    aria-expanded="{{ (Request::is('dashboard') ? 'true' : 'false') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-solid fa-universal-access text-dark side-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">Setup</span>
                </a>
                <ul class="dropdown-menu" style="margin-top:0%; margin-left:5%;margin-right:5%;"
                    aria-labelledby="dropdownMenuButton">
                    <!-- <li>
                        <a class="dropdown-item border-radius-md" href="{{route('setup' , ['type' => 'acquirer'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Acquirer</span>
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('setup' , ['type' => 'api'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Api</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('setup' , ['type' => 'operator'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Operator</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md" href="{{route('setup' , ['type' => 'portalsetting'])}}">
                            <div class="icon" style="margin-left:5%;"><i class="fa fa-solid fa-universal-access"></i></div>
                            <span style="margin-left:20px">Portal Setting</span>
                        </a>
                    </li>
                </ul>
            </li>
            <br>
            <!-- End Setup -->
            @endif
        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
        <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
            <div class="full-background"
                style="background-image: url('{{asset('assets/img/curved-images/white-curved.jpeg')}}')">
            </div>
            <div class="card-body text-start p-3 w-100">
                <div
                    class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                    <i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true"
                        id="sidenavCardIcon"></i>
                </div>
                <div class="docs-info">
                    <h6 class="text-white up mb-0">Need help?</h6>
                    <p class="text-xs font-weight-bold">Please check our docs</p>
                    <a href="https://documenter.getpostman.com/view/32295715/2sA3Qs9Bv7" target="_blank"
                        class="btn btn-white btn-sm w-100 mb-0">Documentation</a>
                </div>
            </div>
        </div>
    </div>
</aside>