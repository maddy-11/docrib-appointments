@extends('doctor.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('doctor.schedule.update') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-6">
                            <div class="form-group">
                                <label>@lang('Slot Type')</label>
                                <select name="slot_type" id="slot-type" class="form-control" required>
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    <option value="1" @selected($doctor->slot_type == 1)>@lang('Serial')</option>
                                    <option value="2" @selected($doctor->slot_type == 2)>@lang('Time')</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>@lang('Available Days')</label>
                                <div class="d-flex gap-2 flex-wrap mb-2" id="weekday-picker">
                                    @php
                                    $weekdays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                                    $selected = $doctor->available_days ? json_decode($doctor->available_days, true) : [];
                                    @endphp
                                    @foreach($weekdays as $day)
                                    <button type="button"
                                    class="btn btn-outline-primary weekday-btn {{ in_array($day, $selected) ? 'active' : '' }}"
                                    data-day="{{ $day }}">
                                    {{ $day }}
                                </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="available_days" id="available_days_input"
                            value="{{ old('available_days', $doctor->available_days ? $doctor->available_days : '[]') }}">
                            <small class="form-text text-muted">@lang('Select the days the doctor is available.')</small>
                        </div>
                    </div>


                    <div class="col-md-3 col-lg-6 start d-none">
                        <div class="form-group">
                            <label>@lang('Start Time')</label>
                            <div class="input-group">
                                <input type="text" name="start_time"
                                value="{{ old('start_time', $doctor->start_time) }}"
                                class="form-control time-picker" autocomplete="off">
                                <span class="input-group-text"><i class="las la-clock"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-6 end d-none">
                        <div class="form-group">
                            <label>@lang('End Time')</label>
                            <div class="input-group">
                                <input type="text" name="end_time"
                                value="{{ old('end_time', $doctor->end_time) }}"
                                class="form-control time-picker" autocomplete="off">
                                <span class="input-group-text"><i class="las la-clock"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 duration d-none">
                        <div class="form-group">
                            <label> @lang('Time Duration')</label>
                            <div class="input-group">
                                <input type="text" name="duration" class="form-control"
                                value="{{ old('duration', $doctor->duration) }}">
                                <span class="input-group-text">@lang('Minutes')</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-12 serial d-none">
                        <div class="form-group">
                            <label> @lang('Maximum Serial')</label>
                            <input type="text" class="form-control" name="max_serial"
                            value="{{ old('max_serial', $doctor->max_serial) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
</div>

<div class="row mb-none-30 mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if ($doctor->slot_type && $doctor->serial_or_slot)
                <h4>@lang('System of Current Schedule')</h4>
                <hr>
                <div class="mt-4">
                    @foreach ($doctor->serial_or_slot as $item)
                    <button type="button" class="btn btn--primary mr-2 mb-2">{{ $item }}</button>
                    @endforeach
                </div>
                @else
                <h5 class="text-center">@lang('You have no schedule')!</h5>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
<link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script-lib')
<script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
<script>
    (function($) {
    'use strict';

    // Weekday toggle logic
    function syncWeekdays() {
        const selected = [];
        $('#weekday-picker .weekday-btn.active').each(function() {
            selected.push($(this).data('day'));
        });
        $('#available_days_input').val(JSON.stringify(selected));
    }

    $('#weekday-picker').on('click', '.weekday-btn', function() {
        $(this).toggleClass('active');
        syncWeekdays();
    });

    // Initialize on load
    syncWeekdays();

    // Existing slot_type logic...
    $('select[name=slot_type]').on('change', function() {
        var type = $(this).val();
        schedule(type);
    })

    var type = $('select[name=slot_type]').val();
    if (type) {
        schedule(type);
    }

    function schedule(type) {
        if (type == 1) {
            $('.duration').addClass('d-none');
            $('.serial').removeClass('d-none');
            $('.start').addClass('d-none');
            $('.end').addClass('d-none');
        } else {
            $('.start').removeClass('d-none');
            $('.end').removeClass('d-none');
            $('.serial').addClass('d-none');
            $('.duration').removeClass('d-none')
        }
    }

    initTimePicker();

    function initTimePicker() {
        var start = new Date();
        start.setHours(9);
        start.setMinutes(0);

        $('.time-picker').datepicker({
            onlyTimepicker: true,
            timepicker: true,
            startDate: start,
            language: 'en',
            minHours: 0,
            maxHours: 23,
        });
    }

})(jQuery);

</script>
@endpush
