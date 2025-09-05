@extends('admin.layouts.app')

@section('panel')
    @if (@json_decode($general->system_info)->message)
        <div class="row">
            @foreach (json_decode($general->system_info)->message as $msg)
                <div class="col-md-12">
                    <div class="alert border border--primary" role="alert">
                        <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
                        <p class="alert__message">@php echo $msg; @endphp</p>
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget link="{{ route('admin.department.index') }}" icon="las la-layer-group f-size--56"
                title="Total Departments" value="{{ $widget['total_departments'] }}" bg="primary" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget link="{{ route('admin.department.location') }}" icon="las la-street-view f-size--56"
                title="Total Locations" value="{{ $widget['total_locations'] }}" bg="success" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget link="{{ route('admin.appointment.new') }}" icon="lar la-hands-helping f-size--56"
                title="Total New Appointments" value="{{ $widget['total_new_appointments'] }}" bg="red" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget link="{{ route('admin.appointment.done') }}" icon="las la-handshake f-size--56"
                title="Total Done Appointments" value="{{ $widget['total_done_appointments'] }}" bg="12" />
        </div><!-- dashboard-w1 end -->
    </div><!-- row end-->

    <div class="row gy-4 mt-2">
        <div class="col-6">
            <x-widget style="2" link="{{ route('admin.doctor.index') }}" icon="las la-stethoscope"
                title="Total Doctors" value="{{ $widget['total_doctors'] }}" color="success" />
        </div>
        <div class="col-6">
            <x-widget style="2" link="{{ route('admin.staff.index') }}" icon="las la-users" title="Total Staff"
                value="{{ $widget['total_staff'] }}" color="warning" />
        </div>
    </div><!-- row end-->

    <div class="row mb-none-30 mt-30">

        <div class="col mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Monthly Appointments Report') (@lang('30 Days of') {{ now()->format('F') }})</h5>
                    <div id="apex-line"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>

    <script>
        "use strict";


        var options = {
            series: [{
                name: 'Total Deposit',
                data: [
                    @foreach ($months as $month)
                        {{ getAmount(@$depositsMonth->where('months', $month)->first()->depositAmount) }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 450,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
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
            xaxis: {
                categories: @json($months),
            },
            yaxis: {
                title: {
                    text: "{{ __($general->cur_sym) }}",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "{{ __($general->cur_sym) }}" + val + " "
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
        chart.render();





        // apex-line chart
        var options = {
            chart: {
                height: 450,
                type: "area",
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: true
                },
                dropShadow: {
                    enabled: true,
                    enabledSeries: [0],
                    top: -2,
                    left: 0,
                    blur: 10,
                    opacity: 0.08
                },
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: "Total Appointments",
                data: @json($appointmentChart)
            }],
            xaxis: {
                categories: [
                    @foreach ($appointment['date'] as $bookingDate)
                        "{{ $bookingDate }}",
                    @endforeach
                ]
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                },
            },
            grid: {
                xaxis: {
                    lines: {
                        show: true
                    }
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#apex-line"), options);

        chart.render();
    </script>
@endpush
