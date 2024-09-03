<!-- Navbar -->
 @php
 $currencyDetails = \App\Helpers\Permission::getCurrency(\Auth::user()->id);
 @endphp
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    <?php $CompanyData = \App\Helpers\Permission::getCompanyDetails(\Auth::user()->company_id); ?> navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                    {{ str_replace('-', ' ', Request::path()) }}</li>
                <li>
                    @if (App\Helpers\Permission::hasRole('admin'))
                    <a href="javascript:void(0)" style="padding: 13px;"><a href="#"
                            class="btn btn-outline-primary load-wallet-btn text-center" data-bs-toggle="modal"
                            data-bs-target="#walletLoadModal"><span class="wallet-load-btn-txt"><i class="fa fa-solid fa-wallet"></i> Load
                            Wallet</a></span>
                        @endif
                </li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <!-- <div class="nav-item d-flex align-self-end">
                <a href="https://www.creative-tim.com/product/soft-ui-dashboard-laravel" target="_blank" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                    Download
                </a>
            </div> -->
            <div class="ms-md-3 pe-md-3 d-flex align-items-center">
            </div>
            <div class=" ms-md-3 pe-md-3 d-flex align-items-center">
                <span><i class="fa fa-solid fa-wallet"></i> Wallet : @php if(!empty($currencyDetails)){ echo $currencyDetails->symbol ; } @endphp</span>
                <span  class="mainwallet">{{ number_format(\Auth::user()->mainwallet)}}</span>
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a data-bs-toggle="dropdown" id="dropdownMenuButton" href="#" aria-expanded="false"
                        class="dropdown-toggle nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">{{ \Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu profile" style=" position:absolute;left:82%;top:35px"
                        aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{route('profile')}}">
                                <span> User Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{ url('logout') }}">
                                <span> Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <!-- <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li> -->
                <!-- <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
                </a>
                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                        <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New message</span> from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            13 minutes ago
                        </p>
                        </div>
                    </div>
                    </a>
                </li>
                <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                        <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New album</span> by Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            1 day
                        </p>
                        </div>
                    </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                        <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>credit-card</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                </g>
                                </g>
                            </g>
                            </g>
                        </svg>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                            Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            2 days
                        </p>
                        </div>
                    </div>
                    </a>
                </li>
                </ul>
            </li> -->
            </ul>
        </div>
    </div>
</nav>
<!-- Filters -->
@if (!Request::is('dashboard') && !Request::is('resources/companydata') && !Request::is('test-form')  && !Request::is('user-profile') &&
!Request::is('statement/cosmosonboarding') && !Request::is('profile/*')&& !Request::is('upi/upicollect')&&
!Request::is('payout')&& !Request::is('qrcode') && !Request::is('recharge/*')&& !Request::is('upi') &&
!Request::is('billpay/*') && !Request::is('pancard/*') && !Request::is('member/*/create') && !Request::is('profile') &&
!Request::is('profile/*') && !Request::is('dmt') && !Request::is('resources/companyprofile') && !Request::is('resources/companyprofile/*')  && !Request::is('aeps/*')
&& !Request::is('developer/*') && !Request::is('resources/commission') && !Request::is('setup/portalsetting'))
<form class="forms-sample" id="searchForm" style="margin-left:40px;margin-right:40px;margin-bottom:0px">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card ">
                <div class="card-header" style="height:80px">
                    <div class="row">
                        <!-- filter heading -->
                        <div class="col-sm-4">
                            <h6>Search</h6>
                        </div>
                        <!-- filter buttons -->
                        <div class="col-sm-8">
                            <!-- Filter -->
                            <ul class="filter-btn">
                                <li>
                                    <button type="submit" class="btn btn-secondary fl-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-search fl-svg">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                        SEARCH
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-primary fl-btn" id="formReset">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-refresh-ccw fl-svg">
                                            <polyline points="1 4 1 10 7 10"></polyline>
                                            <polyline points="23 20 23 14 17 14"></polyline>
                                            <path
                                                d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                            </path>
                                        </svg>
                                        REFRESH
                                    </button>
                                </li>
                                <!-- export -->
                                @if(isset($export))
                                <li>
                                    <button type="button" downloadfilebtn="excel" class="reportExport btn btn-success fl-btn"
                                        product="{{ $export ?? '' }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Excel">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-download-cloud fl-svg">
                                            <polyline points="8 17 12 21 16 17"></polyline>
                                            <line x1="12" y1="12" x2="12" y2="21"></line>
                                            <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29">
                                            </path>
                                        </svg>
                                        Export
                                    </button>

                                </li>
                                <li>
                                    <button type="button" downloadfilebtn="pdf" class="reportExport btn  btn-success fl-btn"
                                        product="{{ $export ?? '' }}" data-bs-toggle="tooltip" data-bs-placement="top" title="PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-download-cloud fl-svg">
                                            <polyline points="8 17 12 21 16 17"></polyline>
                                            <line x1="12" y1="12" x2="12" y2="21"></line>
                                            <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29">
                                            </path>
                                        </svg>
                                        PDF
                                    </button>
                                </li>
                                <li>
                                    <button type="button" downloadfilebtn="word" class="reportExport btn btn-success fl-btn"
                                        product="{{ $export ?? '' }}"data-bs-toggle="tooltip" data-bs-placement="top" title="Word">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-download-cloud fl-svg">
                                            <polyline points="8 17 12 21 16 17"></polyline>
                                            <line x1="12" y1="12" x2="12" y2="21"></line>
                                            <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29">
                                            </path>
                                        </svg>
                                        Word
                                    </button>
                                </li>
                                @endif
                                <!-- end export -->
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(isset($mystatus))
                    <input type="hidden" name="status" value="{{$mystatus}}">
                    @endif
                    <div class="row mb-3">
                        <div class="col-lg-2">
                            <label class="form-label fw-bold">Start Date</label>

                            <div class="input-group flatpickr" id="">
                                <input type="text" class="form-control mydate" placeholder="Select date"
                                    name="from_date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-bold">End Date</label>
                            <div class="input-group flatpickr" id="">
                                <input type="text" name="to_date" class="form-control mydate" placeholder="Select date"
                                    data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-bold">Search Value</label>
                            <input type="text" name="searchtext" class="form-control" placeholder="Search Value" />
                        </div>
                        @if(isset($agentfilter) && $agentfilter!='hide')
                        <div class="col-lg-2">
                            <label class="form-label fw-bold">Agent Id / Parent Id</label>
                            <input type="text" name="agent" class="form-control" placeholder="Agent Id / Parent Id">
                        </div>
                        @endif
                        @if(isset($status))
                        <div class="col-lg-2">
                            <label for="status" class="form-label fw-bold">Select</label>
                            <select class="form-select select" name="status" id="status">
                                <option selected="" disabled="">Select {{$status['type'] ?? ''}} Status</option>
                                <option value="">Select {{$status['type'] ?? ''}} Status</option>
                                @if (isset($status['data']) && sizeOf($status['data']) > 0)
                                @foreach ($status['data'] as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
<!-- End Filters -->
<!-- End Navbar -->