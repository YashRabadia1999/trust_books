@extends('layouts.main')
@section('page-title')
    {{ __('Instagram') }}
@endsection
@section('page-breadcrumb')
    {{ __('Instagram') }}
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
                    <div class="theme-avtar rounded-1 bg-danger">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-messages">
                        <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" /><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" /></svg>
                   </div>
                    <h2 class="mb-0 h5">{{__('Engegment')}}</h2>
                </div>
                <h3 class="mb-0">{{ $totalEngagement }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-success">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users-group">
                            <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </div>
                    <h2 class="mb-0 h5">{{__('Total Followers')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $followerCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-warning"> 
                        <i class="ti ti-message-circle"></i> 
                    </div>
                    <h2 class="mb-0 h5">{{__('Total Comment')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalComments }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                <div class="theme-avtar rounded-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-library-plus">
                            <path d="M7 3m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" /><path d="M4.012 7.26a2.005 2.005 0 0 0 -1.012 1.737v10c0 1.1 .9 2 2 2h10c.75 0 1.158 -.385 1.5 -1" /><path d="M11 10h6" /><path d="M14 7v6" /></svg>
                   </div>
                    <h2 class="mb-0 h5">{{__('Total Post')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalViews }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                <div class="theme-avtar rounded-1">
                        <i class="ti ti-photo"></i>
                     </div>
                    <h2 class="mb-0 h5">{{__('Total Photo')}}</h2>
                </div>
                <h3 class="mb-0">{{$photoCount}}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-info">
                        <i class="ti ti-video"></i>
                      </div>
                    <h2 class="mb-0 h5">{{__('Total Video')}}</h2>
                </div>
                <h3 class="mb-0">{{ $videoCount }}</h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Audiance Growth')}}</h5>
            </div>
            <div class="card-body text-center">
                <div id="followersChart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Posts')}}</h5>
            </div>
            <div class="card-body text-center">
                <div id="postCountChart"></div>
            </div>
        </div>
    </div>  
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Total Instagram Story Insights')}}</h5>
            </div>
            <div class="card-body text-center">
                <div id="story-performance-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12 d-flex">
        <div class="card w-100">
            <div class="card-header">
                <h5>{{__('Total Instagram Story Insights')}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{__('Total Impressions')}}</th>
                            <td>{{ $totals['impressions'] }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Total Reach')}}</th>
                            <td>{{ $totals['reach'] }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Total Taps Forward')}}</th>
                            <td>{{ $totals['taps_forward'] }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Total Taps Back')}}</th>
                            <td>{{ $totals['taps_back'] }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Total Replies')}}</th>
                            <td>{{ $totals['replies'] }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Total Exits')}}</th>
                            <td>{{ $totals['exits'] }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Average Impressions per Story')}}</th>
                            <td>{{ $totals['average_impressions'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row instagram-card-wrp"> 
    <div class="col-md-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex align-items-center justify-content-between gap-2">
                <div class="card-content d-flex gap-2">
                    <div class="theme-avtar bg-danger rounded-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-heart"><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
                    </div>
                    <div class="instagram-card-title">
                        <h4 class="mb-0">{{__('Total Likes')}}</h4>
                        <p class="card-text mb-0 mt-1">{{__('The number of likes on your posts, reels and videos')}}</p>
                    </div>
                </div>
                <h5 class="mb-0">{{ $totalLikes }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex align-items-center justify-content-between gap-2">
                <div class="card-content d-flex gap-2">
                    <div class="theme-avtar bg-success rounded-1">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark"><path d="M18 7v14l-6 -4l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4z" /></svg>
                    </div>
                    <div class="instagram-card-title">
                    <h4 class="mb-0">{{__('Total Saves')}}</h4>
                    <p class="card-text mb-0 mt-1">{{__('The number of saves of your posts, reels and videos')}}</p>
                    </div>
                </div>
                <h5 class="mb-0"> {{ $totalSaves }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex align-items-center justify-content-between gap-2">
                <div class="card-content d-flex gap-2">
                    <div class="theme-avtar bg-warning rounded-1">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-share-3"><path d="M13 4v4c-6.575 1.028 -9.02 6.788 -10 12c-.037 .206 5.384 -5.962 10 -6v4l8 -7l-8 -7z" /></svg>
                    </div>
                    <div class="instagram-card-title">
                    <h4 class="mb-0">{{__('Total Shares')}}</h4>
                    <p class="card-text mb-0 mt-1">{{__('The number of shares of your posts, stories, reels, videos and live videos')}}</p>
                    </div>
                </div>
                <h5 class="mb-0"> {{ $totalshares }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex align-items-center justify-content-between gap-2">
                <div class="card-content d-flex gap-2">
                <div class="theme-avtar rounded-1">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </div>
                    <div class="instagram-card-title">
                    <h5 class="card-title mb-0">{{__('Total Views')}}</h4>
                    <p class="card-text mb-0 mt-1">{{__('The number of times your content (reels, posts, stories) was viewed.')}}</p>
                    </div>
                </div>
                <h5 class="mb-0"> {{ $totalViews }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                <div class="theme-avtar rounded-1">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-progress"><path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969" /><path d="M14 3.223a9.003 9.003 0 0 1 0 17.554" /><path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592" /><path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305" /><path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356" /></svg>
                    </div>
                    <div class="instagram-card-title">
                    <h4 class="mb-0">{{__('Story count')}}</h4>
                    </div>
                </div>
                <h5 class="mb-0">{{$storyCount}}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-info">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-at"><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" /></svg>
                    </div>
                    <div class="instagram-card-title">
                    <h4 class="mb-0">{{__('Total Tags')}}</h4>
                    </div>
                </div>
                <h5 class="mb-0">{{ $totalTags }}</h5>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Engaged Audience by Country')}}</h5>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>{{__('Country')}}</th>
                            <th>{{__('Engaged Audience Count')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($audience as $item)
                            <tr>
                                <td>{{ $item['country'] }}</td>
                                <td>{{ $item['count'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{__('No data available.</td')}}>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script>
    const followers = @json($followers);

    const dates = followers.map(item => item.date);
    const counts = followers.map(item => item.count);

    const options = {
        chart: {
            type: 'line',
            height: 350,
            zoom: {
                enabled: true
            },
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Followers',
            data: counts
        }],
        xaxis: {
            categories: dates,
            title: {
                text: 'Date'
            },
            labels: {
                rotate: -45,
                format: 'yyyy-MM-dd'
            }
        },
        yaxis: {
            title: {
                text: 'Total Followers'
            }
        },
        tooltip: {
            x: {
                format: 'yyyy-MM-dd'
            }
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        const chart = new ApexCharts(document.querySelector("#followersChart"), options);
        chart.render();
    });
</script>

<script>
    const datesasd = @json($dates);  // Dates from the server
    const countsasd = @json($counts);  // Post counts for each date

    const postoption = {
        chart: {
            type: 'area',  // Changed to 'area'
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Post Count',
            data: countsasd
        }],
        xaxis: {
            categories: datesasd,
            title: {
                text: 'Date'
            },
            labels: {
                rotate: -45
            }
        },
        yaxis: {
            title: {
                text: 'Number of Posts'
            },
            labels: {
                formatter: function(value) {
                    return Math.floor(value);
                }
            }
        },
        tooltip: {
            x: {
                format: 'yyyy-MM-dd'
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        const postchart = new ApexCharts(document.querySelector("#postCountChart"), postoption);
        postchart.render();
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var countoption = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                show: false
            }
            },
            series: [{
                name: 'Count',
                data: [
                    {{ $totals['impressions'] }},
                    {{ $totals['reach'] }},
                    {{ $totals['taps_forward'] }},
                    {{ $totals['taps_back'] }},
                    {{ $totals['replies'] }},
                    {{ $totals['exits'] }}
                ]
            }],
            xaxis: {
                categories: [
                    'Impressions', 'Reach', 'Taps Forward', 'Taps Back', 'Replies', 'Exits'
                ]
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                }
            },
            dataLabels: {
                enabled: true
            }
        };

        var countchart = new ApexCharts(document.querySelector("#story-performance-chart"), countoption);
        countchart.render();
    });
</script>
@endpush
