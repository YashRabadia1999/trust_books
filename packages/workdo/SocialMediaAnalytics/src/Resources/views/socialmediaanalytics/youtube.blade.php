@extends('layouts.main')
@section('page-title')
{{ __('Youtube') }}
@endsection
@section('page-breadcrumb')
{{ __('Youtube') }}
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('packages/workdo/SocialMediaAnalytics/src/Resources/assets/css/custom.css') }}">
@endpush
@section('content')
<div class="row instagram-card-wrp mb-4"> 
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar bg-danger rounded-1"> 
                        <i class="ti ti-user-plus"></i>
                      </div>
                    <h2 class="mb-0 h5">{{__('Total Subscribers')}}</h2>
                </div>
                <h3 class="mb-0">{{ $subscriberCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar bg-success rounded-1">   
                        <i class="ti ti-thumb-up"></i> 
                     </div>
                    <h2 class="mb-0 h5">{{__('Total Likes')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalLikes }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar bg-warning rounded-1"> 
                        <i class="ti ti-thumb-down"></i>
                    </div>
                    <h2 class="mb-0 h5">{{__('Total DisLikes')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalDislikes }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                <div class="theme-avtar rounded-1"> <i class="ti ti-video"></i>  </div>
                    <h2 class="mb-0 h5">{{__('Total Videos')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalVideos }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                <div class="theme-avtar rounded-1">
                        <i class="ti ti-message-circle"></i>
                     </div>
                    <h2 class="mb-0 h5">{{__('Total Comment')}}</h2>
                </div>
                <h3 class="mb-0">{{$totalComments}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar bg-info rounded-1">
                    <i class="ti ti-eye"></i>
                      </div>
                    <h2 class="mb-0 h5">{{__('Total Views')}}</h2>
                </div>
                <h3 class="mb-0">{{ $totalViews }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Video wise chart')}}</h5>
            </div>
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Like DisLike Chart')}}</h5>
            </div>
            <div class="card-body">
                <div id="likesDislikesChart"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Daily video views')}}</h5>
            </div>
            <div class="card-body">
                <div id="subscribersChart"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Country Subscribers Chart')}}</h5>
            </div>
            <div class="card-body">
                <div id="countrySubscribersChart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Subscribers Gained by Country')}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('Country')}}</th>
                                <th>{{__('Subscribers Gained')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($countryData as $data)
                            <tr>
                                <td>{{ $data['country'] }}</td>
                                <td>{{ $data['subscribers'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 d-flex">
        <div class="card w-100">
            <div class="card-header">
                <h5>{{__('Gender Distribution')}}</h5>
            </div>
            <div class="card-body d-flex justify-content-between flex-column">
                <div class="table-responsive">
                    <table class="table mb-4">
                        <thead>
                            <tr>
                                <th>{{__('Gender')}}</th>
                                <th>{{__('Viewer Percentage')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($genderData as $data)
                            <tr>
                                <td>{{ ucfirst($data['gender']) }}</td>
                                <td>{{ $data['viewerPercentage'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="genderChart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Device Distribution')}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-4">
                        <thead>
                            <tr>
                                <th>{{__('Device')}}</th>
                                <th>{{__('Views')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deviceData as $data)
                            <tr>
                                <td>{{ $data['device'] }}</td>
                                <td>{{ ($data['views']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="deviceChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

<script>
    var videoData = @json($videoData);
    var dates = Object.keys(videoData);
    var commentCounts = Object.values(videoData).map(item => item.commentCount);
    var dislikeCounts = Object.values(videoData).map(item => item.dislikeCount);
    var favoriteCounts = Object.values(videoData).map(item => item.favoriteCount);
    var likeCounts = Object.values(videoData).map(item => item.likeCount);
    var viewCounts = Object.values(videoData).map(item => item.viewCount);

    var options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
                name: 'Comments',
                data: commentCounts
            },
            {
                name: 'Dislikes',
                data: dislikeCounts
            },
            {
                name: 'Favorites',
                data: favoriteCounts
            },
            {
                name: 'Likes',
                data: likeCounts
            },
            {
                name: 'Views',
                data: viewCounts
            }
        ],
        xaxis: {
            categories: dates
        },
        title: {
            text: 'Video Statistics Over Time',
            align: 'center'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

<script>
    const subscribeoption = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Subscribers Gained',
            data: @json($subscribers)
        }],
        xaxis: {
            categories: @json($dates),
            title: {
                text: 'Date'
            }
        },
        yaxis: {
            title: {
                text: 'Subscribers'
            }
        }
    };

    const subscribechart = new ApexCharts(document.querySelector("#subscribersChart"), subscribeoption);
    subscribechart.render();
</script>

<script>
    const likeoptios = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Likes',
            data: @json($likes)
        }, {
            name: 'Dislikes',
            data: @json($dislikes)
        }],
        xaxis: {
            categories: @json($datess),
            title: {
                text: 'Date'
            }
        },
        yaxis: {
            title: {
                text: 'Count'
            }
        },
        colors: ['#00e396', '#ff4560']
    };

    const likechart = new ApexCharts(document.querySelector("#likesDislikesChart"), likeoptios);
    likechart.render();
</script>
<script>
    const suboptions = {
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Subscribers',
            data: @json($subscribers)
        }],
        xaxis: {
            categories: @json($countries),
            title: {
                text: 'Country'
            }
        },
        yaxis: {
            title: {
                text: 'Subscribers Gained'
            }
        },
        colors: ['#008FFB'],
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        }
    };

    const subchart = new ApexCharts(document.querySelector("#countrySubscribersChart"), suboptions);
    subchart.render();
</script>


<script>
    // Prepare the data from PHP for the chart
    var genderData = @json($genderData);

    // Extracting the gender labels and viewer percentages from the $genderData array
    var categories = genderData.map(function(data) {
        return data.gender.charAt(0).toUpperCase() + data.gender.slice(1); // Capitalize the first letter
    });

    var percentages = genderData.map(function(data) {
        return data.viewerPercentage;
    });

    // Options for the Pie Chart
    var genderoptions = {
        chart: {
            type: 'pie',
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: percentages, // The data points (percentages)
        labels: categories, // The labels (gender)
        colors: ['#FF4560', '#00E396'], // Optional, you can change the colors as per your need
        title: {
            text: 'Gender Distribution',
            align: 'center'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    // Create the chart
    var genderchart = new ApexCharts(document.querySelector("#genderChart"), genderoptions);
    genderchart.render();
</script>

<script>
    // Data passed from the controller
    var deviceData = @json($deviceData);

    // Prepare the chart data
    var devices = deviceData.map(function(item) {
        return item.device; // Device name (e.g., Mobile, Tablet, TV, Desktop)
    });

    var views = deviceData.map(function(item) {
        return item.views; // View count for each device type
    });

    // ApexCharts Configuration
    var deviceoptions = {
        chart: {
            type: 'pie',
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: views, // View counts as series data
        labels: devices, // Device names as labels
        title: {
            text: 'Viewer Device Breakdown',
            align: 'center'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var devicechart = new ApexCharts(document.querySelector("#deviceChart"), deviceoptions);
    devicechart.render();
</script>
@endpush