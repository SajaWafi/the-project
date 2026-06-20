<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Edit Appointment') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .content {
            height: 100%;
            overflow-y: auto;
            padding-bottom: 28px;
        }

        .content::-webkit-scrollbar {
            width: 5px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.12);
            border-radius: 10px;
        }

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 8px 0 10px;
            padding: 0 12px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
        }

        /* 💡 دعم الاتجاهين RTL/LTR */
        .logo {
            position: absolute;
            inset-inline-end: 10px;
            top: -2px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .date-section {
            padding: 0 22px;
        }

        .date-picker {
            width: 100%;
            height: 50px;
            border-radius: 18px;
            border: none;
            outline: none;
            background: #efdcc2;
            padding: 0 22px;
            font-size: 18px;
            color: #e28c3d;
            font-weight: bold;
            margin-bottom: 14px;
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .date-picker:focus {
            background: #f5cfa5;
        }

        .date-picker::-webkit-calendar-picker-indicator {
            filter: invert(48%) sepia(92%) saturate(500%) hue-rotate(10deg);
            cursor: pointer;
        }

        .form-wrap {
            padding: 0 22px;
        }

        .field-title {
            color: #eb9443;
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 6px;
        }

        .field-block {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #efb37f;
        }

        .field-block.no-border {
            border-bottom: none;
            padding-bottom: 0;
        }

        /* 💡 دعم الاتجاهين RTL/LTR */
        .time-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-inline-start: 6px; 
        }

        .time-select-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .sub-label {
            color: #4b4b4b;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .select-field,
        .textarea-field {
            width: 100%;
            border: none;
            outline: none;
            background: #f7e5cf;
            color: #eb9443;
            font-size: 20px;
            border-radius: 16px;
            padding: 0 14px;
        }

        /* 💡 دعم السهم للاتجاهين */
        .select-field {
            height: 42px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23eb9443' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: {{ app()->getLocale() == 'ar' ? 'left 12px center' : 'right 12px center' }};
            padding-inline-end: 38px;
        }

        .textarea-field {
            min-height: 118px;
            resize: none;
            padding-top: 14px;
            border: 1.5px solid #efc79f;
            background: rgba(255,255,255,0.35);
            font-size: 14px;
            color: #555;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
        }

        .primary-btn,
        .secondary-btn {
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            height: 42px;
            min-width: 210px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .primary-btn {
            background: #eb9a4a;
            color: #fff;
        }

        .secondary-btn {
            background: #f7e5cf;
            color: #eb9443;
            min-width: 200px;
        }

        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 22px 12px;
            font-size: 13px;
        }

        .success-box {
            background: #e8fff0;
            color: #18794e;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 22px 12px;
            font-size: 13px;
        }
        
        /* 💡 زر الرجوع وقلب السهم */
        .back-btn {
            position: absolute;
            inset-inline-start: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
            transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};
        }

        .back-btn svg {
            width: 26px;
            height: 26px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="page-title">{{ __('Edit Appointment') }}</div>
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="success-box">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('doctor.update.appointment', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="date-section">
                    <div class="field-title">{{ __('Select Date') }}</div>

                    <input
                        type="date"
                        id="appointmentDate"
                        name="date"
                        value="{{ old('date', \Carbon\Carbon::parse($appointment->date)->format('Y-m-d')) }}"
                        min="{{ now()->toDateString() }}" 
                        class="date-picker"
                    >
                </div>

                <div class="form-wrap">

                    <div class="field-block">
                        <div class="field-title">{{ __('Time Since') }}</div>
                        <div class="time-row">
                            <div class="time-select-wrap">
                                <select name="from_hour" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-inline-end:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $hour = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $i }}" {{ old('from_hour', $appointment->from_hour) == $i ? 'selected' : '' }}>
                                            {{ $hour }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="from_minute" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-inline-end:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @foreach ([0,15,30,45] as $minute)
                                        <option value="{{ $minute }}" {{ old('from_minute', $appointment->from_minute) == $minute ? 'selected' : '' }}>
                                            {{ str_pad($minute, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="from_period" class="select-field" style="width:92px; background:transparent; border-radius:0; padding:0; padding-inline-end:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    <option value="AM" {{ old('from_period', $appointment->from_period) == 'AM' ? 'selected' : '' }}>{{ __('AM') }}</option>
                                    <option value="PM" {{ old('from_period', $appointment->from_period) == 'PM' ? 'selected' : '' }}>{{ __('PM') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field-block">
                        <div class="field-title">{{ __('Time to') }}</div>
                        <div class="time-row">
                            <div class="time-select-wrap">
                                <select name="to_hour" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-inline-end:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $hour = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $i }}" {{ old('to_hour', $appointment->to_hour) == $i ? 'selected' : '' }}>
                                            {{ $hour }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="to_minute" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-inline-end:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @foreach ([0,15,30,45] as $minute)
                                        <option value="{{ $minute }}" {{ old('to_minute', $appointment->to_minute) == $minute ? 'selected' : '' }}>
                                            {{ str_pad($minute, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="to_period" class="select-field" style="width:92px; background:transparent; border-radius:0; padding:0; padding-inline-end:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    <option value="AM" {{ old('to_period', $appointment->to_period) == 'AM' ? 'selected' : '' }}>{{ __('AM') }}</option>
                                    <option value="PM" {{ old('to_period', $appointment->to_period) == 'PM' ? 'selected' : '' }}>{{ __('PM') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field-block">
                        <div class="field-title">{{ __('Choose Parent') }}</div>
                        <div class="sub-label">{{ __('Full Name') }}</div>

                        <select name="parent_id" id="parent_id" class="select-field">
                            <option value="">{{ __('Select parent') }}</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $appointment->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->user->first_name ?? '' }} {{ $parent->user->last_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-block">
                        <div class="field-title">{{ __('Choose Workplace') }}</div>
                        <div class="sub-label">{{ __('Place Name') }}</div>

                        <select name="workplace_id" id="workplace_id" class="select-field">
                            <option value="">{{ __('Select workplace') }}</option>
                            @foreach($workplaces as $workplace)
                                <option value="{{ $workplace->id }}" {{ old('workplace_id', $appointment->workplace_id ?? '') == $workplace->id ? 'selected' : '' }}>
                                    {{ $workplace->place_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-block no-border">
                        <div class="field-title">{{ __('Add note') }}</div>
                        <textarea name="note" class="textarea-field" placeholder="{{ __('Enter Your Note Here..') }}">{{ old('note', $appointment->note) }}</textarea>
                    </div>

                    <div class="actions">
                        <button type="submit" class="primary-btn">{{ __('Update Appointment') }}</button>

                        <a href="{{ route('doctor.appointments') }}" class="secondary-btn">
                            {{ __('Cancel') }}
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</body>
</html>