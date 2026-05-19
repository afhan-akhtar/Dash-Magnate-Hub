@include('dashboard.attachments.header')
<style>
    .gradient-yellow {
        background: linear-gradient(45deg, #e6eb31, #9ccd4d) !important;
    }

    .gradient-red {
        background: linear-gradient(45deg, #fd4c06, #ed1515) !important;
    }

    .gradient-green {
        background: linear-gradient(45deg, #35d36e, #9ccd4d) !important;
    }

    .gradient-Danger {
        background: linear-gradient(45deg, #e92b2b, #f91818e6) !important;
    }
</style>
<div class="row mt-3">

    <div class="col-lg-4">
        <div class="card gradient-info" style="border-radius: 15px;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 text-white">Total Earnings</h5>
                    </div>
                    <div class="col">
                        <div class="icon-box float-right rounded-circle bg-light">
                            <i class="zmdi zmdi-balance-wallet text-white" style="margin-top: 12px;"></i>
                        </div>
                    </div>
                </div>
                <h3 class="mt-4 mb-0 text-white">${{ $Total_Earning }}</h3>
                <p class="mb-0 extra-small-font text-white">6.3% Increase</p>
            </div>
            <div class="text-center">
                <img src="/dashboard/assets/images/total_earn.png" style="width:66%">
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-deepblue" style="border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="text-white mb-0">{{ $Total_Plan }} <span class="float-right"><i
                                    class="fa fa-paper-plane-o"></i></span></h5>
                        <div class="progress my-3" style="height:3px;">
                            <div class="progress-bar" style="width:100%"></div>
                        </div>
                        <p class="mb-0 text-white small-font">Total Plans</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-green" style="border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="text-white mb-0">{{ $Active_Plan }} <span class="float-right"><i
                                    class="fa fa-paper-plane-o"></i></span>
                        </h5>
                        <div class="progress my-3" style="height:3px;">
                            <div class="progress-bar" style="width:{{ $Active_Plan_Per }}%"></div>
                        </div>
                        <p class="mb-0 text-white small-font">Active Plans</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-Danger" style="border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="text-white mb-0">{{ $Expire_Plan }} <span class="float-right"><i
                                    class="fa fa-paper-plane-o"></i></span>
                        </h5>
                        <div class="progress my-3" style="height:3px;">
                            <div class="progress-bar" style="width:{{ $Expire_Plan_Per }}%"></div>
                        </div>
                        <p class="mb-0 text-white small-font">Expire Plans</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-primary" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h5 class="mb-0 text-white">{{ $Total_Raising }}</h5>
                                <p class="mb-0 text-white">Total Raiser</p>
                            </div>
                            <div class="w-icon"><i class="fa fa-users text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-info" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h5 class="mb-0 text-white">{{ $Active_Raising }}</h5>
                                <p class="mb-0 text-white">Active Raiser</p>
                            </div>
                            <div class="w-icon"><i class="fa fa-user-o text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-danger" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h5 class="mb-0 text-white">{{ $De_Active_Raising }}</h5>
                                <p class="mb-0 text-white">De-Active Raiser</p>
                            </div>
                            <div class="w-icon"><i class="fa fa-user-times text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-primary" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h5 class="mb-0 text-white">{{ $Total_User }}</h5>
                                <p class="mb-0 text-white">Total Users</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-accounts-alt text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-info" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h5 class="mb-0 text-white">{{ $Active_User }}</h5>
                                <p class="mb-0 text-white">Active Users</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-account-circle text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card gradient-danger" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h5 class="mb-0 text-white">{{ $De_Active_User }}</h5>
                                <p class="mb-0 text-white">De-Active Users</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-accounts text-white"></i></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




<div class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header text-uppercase">Traffic OverView OF {{ date('F') }},{{ date('Y') }}</div>
            <div class="card-body">
                <div id="Traffic_Chart"></div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card rounded-0 mb-0">
                    <div class="card-body px-0">
                        <div class="media mb-2">
                            <div class="media-body align-items-center">
                                <h5 class="mb-1">{{ $Total_Project }}</h5>
                                <p class="mb-0">Total Listings</p>
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
                                <h5 class="mb-1">{{ $Normal_Project }}</h5>
                                <p class="mb-0">Normal Listings</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-format-list-bulleted text-primary"></i></div>
                        </div>
                        <div class="progress-wrapper mt-3">
                            <div class="progress mb-0" style="height: 5px;">
                                <div class="progress-bar gradient-primary" role="progressbar"
                                    style="width: {{ $Normal_Project_Per }}%">
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
                                <h5 class="mb-1">{{ $Premium_Project }}</h5>
                                <p class="mb-0">Premium Listings</p>
                            </div>
                            <div class="w-icon"><i class="ti-crown text-warning"></i></div>
                        </div>
                        <div class="progress-wrapper mt-3">
                            <div class="progress mb-0" style="height: 5px;">
                                <div class="progress-bar gradient-warning" role="progressbar"
                                    style="width: {{ $Premium_Project_Per }}%">
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
                                <h5 class="mb-1">{{ $Active_Project }}</h5>
                                <p class="mb-0">Active Listings</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-lock text-danger"></i></div>
                        </div>
                        <div class="progress-wrapper mt-3">
                            <div class="progress mb-0" style="height: 5px;">
                                <div class="progress-bar gradient-danger" role="progressbar"
                                    style="width: {{ $Active_Project_Per }}%">
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
                                <h5 class="mb-1">{{ $De_Active_Project }}</h5>
                                <p class="mb-0">De-Active Listings</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-lock-open text-success"></i></div>
                        </div>
                        <div class="progress-wrapper mt-3">
                            <div class="progress mb-0" style="height: 5px;">
                                <div class="progress-bar gradient-success" role="progressbar"
                                    style="width: {{ $De_Active_Project_Per }}%">
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
                                <h5 class="mb-1">{{ $Block_Project }}</h5>
                                <p class="mb-0">Block Listings</p>
                            </div>
                            <div class="w-icon"><i class="zmdi zmdi-block text-danger"></i></div>
                        </div>
                        <div class="progress-wrapper mt-3">
                            <div class="progress mb-0" style="height: 5px;">
                                <div class="progress-bar gradient-danger" role="progressbar"
                                    style="width: {{ $Block_Project_Per }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8 col-xl-8">
        <div class="card">
            <div class="card-header text-uppercase">Plans Overview</div>
            <div class="card-body">
                <div id="Plans_Chart"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">
            <div class="card-header">
                Plans Earning
            </div>
            + <div class="row">
                <div class="col-md-6">
                    <div class="card gradient-orange">
                        <div class="card-body">
                            <h5 class="text-white mb-0">${{ $Essentials_Earning }} <span class="float-right"><i
                                        class="fa fa-usd"></i></span>
                            </h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:{{ $Essentials_Earning_Per }}%"></div>
                            </div>
                            <p class="mb-0 text-white small-font">Essentials Plans</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card gradient-ibiza">
                        <div class="card-body">
                            <h5 class="text-white mb-0">${{ $Premium_Earning }} <span class="float-right"><i
                                        class="fa fa-usd"></i></span>
                            </h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:{{ $Premium_Earning_Per }}%"></div>
                            </div>
                            <p class="mb-0 text-white small-font">Enterprise Plans</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card gradient-ohhappiness">
                        <div class="card-body">
                            <h5 class="text-white mb-0">${{ $Enterprise_Earning }} <span class="float-right"><i
                                        class="fa fa-usd"></i></span>
                            </h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:{{ $Enterprise_Earning_Per }}%"></div>
                            </div>
                            <p class="mb-0 text-white small-font">Premium Plans</p>
                        </div>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="card gradient-yellow">
                        <div class="card-body">
                            <h5 class="text-white mb-0">${{ $Capital_Earning }} <span class="float-right"><i
                                        class="fa fa-usd"></i></span>
                            </h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:{{ $Capital_Earning_Per }}%"></div>
                            </div>
                            <p class="mb-0 text-white small-font">Capital Raise</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row my-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Recent Messages
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-items-center">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Contact as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone }}</td>
                            <td>{{ $c->subject }}</td>
                            <td>{{ date('F d Y', strtotime($c->date)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('dashboard.attachments.footer')
<script>
    // chart 1

    var options = {
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
            foreColor: '#4e4e4e',
            toolbar: {
                show: false
            },
            shadow: {
                enabled: false,
                color: '#000',
                top: 3,
                left: 2,
                blur: 3,
                opacity: 1
            },
        },
        stroke: {
            width: 4,
            curve: 'smooth',
        },
        series: [{
            name: 'Traffic',
            data: [@foreach ($Traffic as $T)
                    {{ $T->count }},
                @endforeach],
        }],

        tooltip: {
            enabled: true,
            theme: 'dark',
        },

        xaxis: {
            type: 'datetime',
            categories: [@foreach ($Traffic as $T)
            '{{ date('m/d/Y', strtotime($T->date)) }}',
                @endforeach
            ],
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#00dbde'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        colors: ["#fc00ff"],
        grid: {
            show: true,
            borderColor: 'rgba(66, 59, 116, 0.15)',
        },
        yaxis: {
            min: -10,
            max: 40,
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#Traffic_Chart"),
        options
    );

    chart.render();


    var options = {
        chart: {
            height: 350,
            type: 'bar',
            foreColor: '#4e4e4e',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        grid: {
            show: true,
            borderColor: 'rgba(255, 255, 255, 0.00)',
        },
        series: [{
            name: 'Essentials',
            data: [
                @foreach ($Plans_Overview as $PO)
                    {{ $PO['Essentials'] }},
                @endforeach
            ]
        }, {
            name: 'Premium',
            data: [
                @foreach ($Plans_Overview as $PO)
                    {{ $PO['Premium'] }},
                @endforeach
            ]
        }, {
            name: 'Enterprise',
            data: [
                @foreach ($Plans_Overview as $PO)
                    {{ $PO['Enterprise'] }},
                @endforeach
            ]
        }, {
            name: 'Capital Raise',
            data: [
                @foreach ($Plans_Overview as $PO)
                    {{ $PO['Capital'] }},
                @endforeach
            ]
        }],
        xaxis: {
            categories: [
                @foreach ($Plans_Overview as $PO)
                    '{{ $PO['Date'] }}',
                @endforeach
            ],
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#00c8ff', '#08a50e', '#7f00ff', '#f8952c'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        colors: ["#0072ff", "#cddc35", "#e100ff", '#f8952c'],
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return "Sold " + val
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#Plans_Chart"),
        options
    );

    chart.render();
</script>
