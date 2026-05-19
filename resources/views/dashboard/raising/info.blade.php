@include('dashboard.attachments.header')


<style>
    .plan_list::-webkit-scrollbar {
        display: none;
    }
</style>


<div class="row pt-2 pb-2">
    <div class="col-8">
        <h4 class="page-title">Raiser</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/login">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/dashboard/raiser">Raiser</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $Raising[0]->first_name }}
                {{ $Raising[0]->last_name }}</li>
        </ol>
    </div>
    <div class="col-4">
        <div class="d-flex justify-content-end">
            <a href="/dashboard/raiser"><button class="btn btn-primary waves-effect waves-light m-1" title="Back To Listing"><i aria-hidden="true"
            class="fa fa-arrow-circle-o-left fa-2x"></i></button></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card rounded-0 mb-0">
                        <div class="card-body px-0">
                            <div class="media mb-2">
                                <div class="media-body align-items-center">
                                    <h5 class="mb-1">{{ $Total }}</h5>
                                    <p class="mb-0">Total Projects</p>
                                </div>
                                <div class="w-icon"><i class="zmdi zmdi-dropbox text-success"></i></div>
                            </div>
                            <div class="progress-wrapper mt-3">
                                <div class="progress mb-0" style="height: 5px;">
                                    <div class="progress-bar gradient-success" role="progressbar" style="width: 100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card rounded-0 mb-0">
                        <div class="card-body px-0">
                            <div class="media mb-2">
                                <div class="media-body align-items-center">
                                    <h5 class="mb-1">{{ $Normal }}</h5>
                                    <p class="mb-0">Normal Projects</p>
                                </div>
                                <div class="w-icon"><i class="zmdi zmdi-format-list-bulleted text-primary"></i></div>
                            </div>
                            <div class="progress-wrapper mt-3">
                                <div class="progress mb-0" style="height: 5px;">
                                    <div class="progress-bar gradient-primary" role="progressbar" style="width: {{ $Normal_Per }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card rounded-0 mb-0">
                        <div class="card-body px-0">
                            <div class="media mb-2">
                                <div class="media-body align-items-center">
                                    <h5 class="mb-1">{{ $Premium }}</h5>
                                    <p class="mb-0">Premium Projects</p>
                                </div>
                                <div class="w-icon"><i class="ti-crown text-warning"></i></div>
                            </div>
                            <div class="progress-wrapper mt-3">
                                <div class="progress mb-0" style="height: 5px;">
                                    <div class="progress-bar gradient-warning" role="progressbar" style="width: {{ $Premium_Per }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card rounded-0 mb-0">
                        <div class="card-body px-0">
                            <div class="media mb-2">
                                <div class="media-body align-items-center">
                                    <h5 class="mb-1">{{ $Active }}</h5>
                                    <p class="mb-0">Active Projects</p>
                                </div>
                                <div class="w-icon"><i class="zmdi zmdi-lock text-danger"></i></div>
                            </div>
                            <div class="progress-wrapper mt-3">
                                <div class="progress mb-0" style="height: 5px;">
                                    <div class="progress-bar gradient-danger" role="progressbar" style="width: {{ $Active_Per }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card rounded-0 mb-0">
                        <div class="card-body px-0">
                            <div class="media mb-2">
                                <div class="media-body align-items-center">
                                    <h5 class="mb-1">{{ $De_Active }}</h5>
                                    <p class="mb-0">De-Active Projects</p>
                                </div>
                                <div class="w-icon"><i class="zmdi zmdi-lock-open text-success"></i></div>
                            </div>
                            <div class="progress-wrapper mt-3">
                                <div class="progress mb-0" style="height: 5px;">
                                    <div class="progress-bar gradient-success" role="progressbar" style="width: {{ $De_Active_Per }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="card rounded-0 mb-0">
                        <div class="card-body px-0">
                            <div class="media mb-2">
                                <div class="media-body align-items-center">
                                    <h5 class="mb-1">{{ $Block }}</h5>
                                    <p class="mb-0">Block Projects</p>
                                </div>
                                <div class="w-icon"><i class="zmdi zmdi-block text-danger"></i></div>
                            </div>
                            <div class="progress-wrapper mt-3">
                                <div class="progress mb-0" style="height: 5px;">
                                    <div class="progress-bar gradient-danger" role="progressbar" style="width: {{ $Block_Per }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            @if ($Raising[0]->profile == null)
                <img src="/user/assets/profile.png"
                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" alt="">
            @else
                <img src="{{ asset('/uploads/raising/profile/' . $Raising[0]->profile) }}"
                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" alt="">
            @endif
            <div class="row justify-content-between">
                <div class="col-lg-8 col-md-8">
                    <div class="row my-1 mx-md-2 mt-4">
                        <div class="col-12">
                            <h6 class="mb-0" id="nationality"></h6>
                        </div>
                        <div class="col-12 mt-2">
                            <h6 class="mb-0">Gender :
                                @if ($Raising[0]->gender == 1)
                                    <span style="color: grey">Male</span>
                                @else
                                    <span style="color: grey">Female</span>
                                @endif
                                &nbsp; | &nbsp; Status :
                                @if ($Raising[0]->status == 1)
                                    <span style="color: rgb(241, 18, 18)">De-Active</span>
                                @else
                                    <span style="color: rgb(24, 136, 24)">Active</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">First Name</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->first_name }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Last Name</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->last_name }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Email</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->email }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Phone</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->phone }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Monthly Earning</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->earning }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Net Worth</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->net_worth }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Passport Number</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->passport }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">National Identification Number</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->identification }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Tax Identification Number</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ $Raising[0]->tin }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Joining Date</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">{{ date('F d Y', strtotime($Raising[0]->date)) }}</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row my-1 mx-md-2">
                        <div class="col-sm-6">
                            <h6 class="mb-0">Current Plan</h6>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0">
                                @if (count($plan) == 0)
                                    No Plan Available
                                @else
                                    @php
                                        if ($plan[0]->type == 1) {
                                            $type = 'Essentials';
                                            $price = 19;
                                        } elseif ($plan[0]->type == 2) {
                                            $type = 'Premium';
                                            $price = 29;
                                        } elseif ($plan[0]->type == 3) {
                                            $type = 'Enterprise';
                                            $price = 39;
                                        } elseif ($plan[0]->type == 4) {
                                            $price = 249;
                                            $type = 'Capital Raise';
                                        }
                                    @endphp
                                    {{ $type }} - ${{ $price }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 mt-5">
                    <div class="card mt-4"
                        style="background: #fff !important; box-shadow: 0 0 0 !important; border: 3px solid #f1f1f1;">
                        <div class="plan_list" style="height:370px; overflow-y:scroll; overflow-x: hidden;">
                            <h3 class="mb-4">Plans</h3>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($plans as $p)
                                @php
                                    if ($p->type == 1) {
                                        $type = 'Essentials';
                                        $price = 19;
                                    } elseif ($p->type == 2) {
                                        $type = 'Premium';
                                        $price = 29;
                                    } elseif ($p->type == 3) {
                                        $type = 'Enterprise';
                                        $price = 39;
                                    } elseif ($p->type == 4) {
                                        $price = 249;
                                        $type = 'Capital Raise';
                                    } elseif ($p->type == 5) {
                                        $price = 0;
                                        $type = 'Free Signup';
                                    }
                                @endphp
                                <div class="row my-1 ">
                                    <div class="col-12 d-flex justify-content-between">
                                        <h6 class="mb-0">{{ $type }} - ${{ $price }}</h6>
                                        @if ($p->expiry >= date('Y-m-d'))
                                            @if ($i == 1)
                                                <span class="badge badge-success">Expire In
                                                    {{ round((strtotime($p->expiry) - time()) / 86400) }} Days</span>
                                            @else
                                                <span class="badge badge-danger">Discontinue</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Expired</span>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">{{ date('F d Y', strtotime($p->date)) }} - TO -
                                            {{ date('F d Y', strtotime($p->expiry)) }}</p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <h3 class="my-4 pl-2">Projects</h3>
                    <div class="table-responsive table-fixed mt-3">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Card Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Views</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @php
                                    $i = 1;
                                    $Content = 'Project';
                                @endphp
                                @forelse($project as $p)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td><img src="{{ asset('/uploads/project/card/' . $p->card) }}"
                                                alt="{{ $Content }} Card Image"></td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->views }}</td>
                                        <td>
                                            @if ($p->status == 0)
                                                <span class="badge badge-success shadow-success m-1">Active</span>
                                            @else
                                                <span class="badge badge-danger shadow-danger m-1">De-Active</span>
                                            @endif

                                            @if ($p->premium == 0)
                                                <span class="badge badge-primary shadow-primary m-1">Normal</span>
                                            @else
                                                <span class="badge badge-warning shadow-warning m-1">Premium</span>
                                            @endif

                                            @if ($p->block == 1)
                                                <span class="badge badge-danger shadow-danger m-1">Block</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->date }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @empty
                                    <td colspan="8" class="text-center">Nothing Found</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.attachments.footer')
<script>
    Nationalities();

    function Nationalities() {
        Nationalities = ["Select Nationality", "American", "British", "Canadian", "Australian", "French", "German",
            "Italian", "Japanese",
            "Chinese", "Indian", "Russian", "Mexican", "Spanish", "Brazilian", "South Korean", "South African",
            "Swedish", "Swiss", "Turkish", "Argentinian", "Austrian", "Belgian", "Chilean", "Colombian", "Danish",
            "Dutch", "Egyptian", "Finnish", "Greek", "Hungarian", "Irish", "Israeli", "Jamaican", "Kenyan",
            "Malaysian", "New Zealander", "Norwegian", "Polish", "Portuguese", "Romanian", "Saudi Arabian",
            "Singaporean", "Thai", "Ukrainian", "Vietnamese", "Afghan", "Albanian", "Algerian", "Angolan",
            "Armenian", "Azerbaijani", "Bahraini", "Bangladeshi", "Barbadian", "Belarusian", "Belizean", "Beninese",
            "Bolivian", "Bosnian", "Botswanan", "Bulgarian", "Burkinabe", "Burmese", "Burundian", "Cambodian",
            "Cameroonian", "Central African", "Chadian", "Comoran", "Congolese", "Costa Rican", "Croatian", "Cuban",
            "Cypriot", "Czech", "Djiboutian", "Dominican", "Ecuadorian", "Equatorial Guinean", "Eritrean",
            "Estonian", "Ethiopian", "Fijian", "Gabonese", "Gambian", "Georgian", "Ghanaian", "Guatemalan",
            "Guinean", "Guyanese", "Haitian", "Honduran", "Icelandic", "Indonesian", "Iranian", "Iraqi", "Ivorian",
            "Jordanian", "Kazakhstani", "Kiribati", "Kuwaiti", "Kyrgyz", "Laotian", "Latvian", "Lebanese",
            "Liberian", "Libyan", "Liechtensteiner", "Lithuanian", "Luxembourger", "Macedonian", "Madagascan",
            "Malawian", "Maldivian", "Malian", "Maltese", "Mauritanian", "Mauritian", "Moldovan", "Mongolian",
            "Montenegrin", "Moroccan", "Mozambican", "Namibian", "Nauruan", "Nepalese", "Nicaraguan", "Nigerien",
            "North Korean", "Omani", "Pakistani", "Palauan", "Panamanian", "Papua New Guinean", "Paraguayan",
            "Peruvian", "Philippine", "Qatari", "Rwandan", "Saint Lucian", "Salvadoran", "Samoan", "San Marinese",
            "Sao Tomean", "Senegalese", "Serbian", "Seychellois", "Sierra Leonean", "Slovak", "Slovenian",
            "Solomon Islander", "Somali", "Sudanese", "Surinamer", "Swazi", "Syrian", "Tajik", "Tanzanian",
            "Togolese", "Tongan", "Trinidadian or Tobagonian", "Tunisian", "Tuvaluan", "Ugandan", "Uruguayan",
            "Uzbekistani", "Venezuelan", "Yemenite", "Zambian", "Zimbabwean"
        ];
        for (let i = 1; i < Nationalities.length; i++) {
            if (i == '<?php echo $Raising[0]->nationality; ?>') {
                $("#nationality").text(Nationalities[i]);
            }
        }
    }
</script>
