<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workplace</title>
    <style>
        /* ===== Reset عام ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ===== خلفية الصفحة ===== */
        body {
            background: #111;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ===== إطار الموبايل ===== */
        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        /* ===== مساحة المحتوى ===== */
        .content {
            height: 100%;
            overflow-y: auto;
            padding-bottom: 26px;
            position: relative;
        }

        .content::-webkit-scrollbar {
            width: 5px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.12);
            border-radius: 10px;
        }

        /* ===== شريط الحالة ===== */
        .status-bar {
            height: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
            position: relative;
            z-index: 3;
        }

        /* ===== الهيدر ===== */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px 0 10px;
            padding: 0 14px;
            z-index: 3;
        }

        /* ===== زر الرجوع ===== */
        .back-btn {
            position: absolute;
            left: 12px;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        /* ===== عنوان الصفحة ===== */
        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        /* ===== الشعار ===== */
        .logo {
            position: absolute;
            right: 10px;
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

        /* ===== شريط الأيام ===== */
        .days-bar {
            background: #cfd6fb;
            padding: 14px 12px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 18px;
        }

        /* ===== زر اليوم ===== */
        .day-pill {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            color: #2f2f2f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .day-pill input {
            display: none;
        }

        .day-pill.active {
            background: #2f66f3;
            color: #ffffff;
        }

        .day-pill:hover {
            transform: scale(1.04);
        }

        /* ===== نجمة ديكورية ===== */
        .star {
            position: absolute;
            left: 98px;
            top: 182px;
            color: #f0d46a;
            font-size: 30px;
            line-height: 1;
            z-index: 1;
        }

        /* ===== الفورم ===== */
        .form-wrap {
            padding: 0 22px;
            position: relative;
            z-index: 2;
        }

        /* ===== عنوان الحقل ===== */
        .field-title {
            color: #2f66f3;
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        /* ===== كل بلوك ===== */
        .field-block {
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #9eb0ff;
        }

        .field-block.no-border {
            border-bottom: none;
            padding-bottom: 0;
        }

        /* ===== صف الوقت ===== */
        .time-row {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-left: 6px;
        }

        /* ===== اختيار الوقت ===== */
        .time-select-wrap {
            display: inline-flex;
            align-items: center;
            position: relative;
        }

        .time-select {
            border: none;
            outline: none;
            background: transparent;
            font-size: 26px;
            font-weight: 700;
            color: #484848;
            border-bottom: 3px solid #5a5a5a;
            line-height: 1;
            padding: 0 18px 2px 0;
            min-width: 54px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .time-select.period {
            min-width: 70px;
        }

        /* ===== سهم الوقت ===== */
        .time-arrow {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-20%);
            color: #2f66f3;
            font-size: 14px;
            pointer-events: none;
        }

        /* ===== حقل النص ===== */
        .text-input {
            width: 100%;
            height: 40px;
            border: none;
            outline: none;
            background: #cfd6fb;
            border-radius: 14px;
            padding: 0 14px;
            font-size: 15px;
            color: #333;
        }

        .text-input::placeholder {
            color: #6e6e6e;
        }

        /* ===== الأزرار ===== */
        .actions {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .primary-btn,
        .secondary-btn {
            min-width: 170px;
            height: 40px;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .primary-btn {
            background: #2f66f3;
            color: #fff;
        }

        .secondary-btn {
            background: #cfd6fb;
            color: #5f7bf3;
            min-width: 150px;
        }

        /* ===== رسائل النجاح والخطأ ===== */
        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 22px 14px;
            font-size: 13px;
        }

        .success-box {
            background: #e8fff0;
            color: #14804a;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 22px 14px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">

            <div class="header">
                <a href="{{ route('doctor.workplace.timing') }}" class="back-btn">‹</a>

                <div class="page-title">Edit Workplace</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <form action="{{ route('doctor.workplace.update', $workplace['id']) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $selectedDays = old('days', $workplace['days'] ?? []);
                    $weekDays = ['SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI'];
                @endphp

                <div class="days-bar">
                    @foreach($weekDays as $day)
                        <label class="day-pill {{ in_array($day, $selectedDays) ? 'active' : '' }}">
                            <input
                                type="checkbox"
                                name="days[]"
                                value="{{ $day }}"
                                {{ in_array($day, $selectedDays) ? 'checked' : '' }}
                            >
                            <span>{{ $day }}</span>
                        </label>
                    @endforeach
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

                <div class="form-wrap">

                    <div class="field-block">
                        <div class="field-title">Time Since</div>

                        <div class="time-row">
                            <div class="time-select-wrap">
                                <select name="from_hour" class="time-select">
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $hour = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $hour }}"
                                            {{ old('from_hour', str_pad($workplace['from_hour'], 2, '0', STR_PAD_LEFT)) == $hour ? 'selected' : '' }}>
                                            {{ $hour }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="time-arrow">▼</span>
                            </div>

                            <div class="time-select-wrap">
                                <select name="from_minute" class="time-select">
                                    @foreach (['00','15','30','45'] as $minute)
                                        <option value="{{ $minute }}"
                                            {{ old('from_minute', str_pad($workplace['from_minute'], 2, '0', STR_PAD_LEFT)) == $minute ? 'selected' : '' }}>
                                            {{ $minute }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="time-arrow">▼</span>
                            </div>

                            <div class="time-select-wrap">
                                <select name="from_period" class="time-select period">
                                    <option value="AM" {{ old('from_period', $workplace['from_period']) == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ old('from_period', $workplace['from_period']) == 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                                <span class="time-arrow">▼</span>
                            </div>
                        </div>
                    </div>

                    <div class="field-block">
                        <div class="field-title">Time to</div>

                        <div class="time-row">
                            <div class="time-select-wrap">
                                <select name="to_hour" class="time-select">
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $hour = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $hour }}"
                                            {{ old('to_hour', str_pad($workplace['to_hour'], 2, '0', STR_PAD_LEFT)) == $hour ? 'selected' : '' }}>
                                            {{ $hour }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="time-arrow">▼</span>
                            </div>

                            <div class="time-select-wrap">
                                <select name="to_minute" class="time-select">
                                    @foreach (['00','15','30','45'] as $minute)
                                        <option value="{{ $minute }}"
                                            {{ old('to_minute', str_pad($workplace['to_minute'], 2, '0', STR_PAD_LEFT)) == $minute ? 'selected' : '' }}>
                                            {{ $minute }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="time-arrow">▼</span>
                            </div>

                            <div class="time-select-wrap">
                                <select name="to_period" class="time-select period">
                                    <option value="AM" {{ old('to_period', $workplace['to_period']) == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ old('to_period', $workplace['to_period']) == 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                                <span class="time-arrow">▼</span>
                            </div>
                        </div>
                    </div>

                    <div class="field-block no-border">
                        <div class="field-title" style="font-size:16px; color:#555; margin-bottom:8px;">Clinic Name</div>
                        <input
                            type="text"
                            name="place_name"
                            class="text-input"
                            value="{{ old('place_name', $workplace['place_name']) }}"
                            placeholder=""
                        >
                    </div>

                    <div class="actions">
                        <button type="submit" class="primary-btn">Update workplace</button>

                        <a href="{{ route('doctor.workplace.timing') }}" class="secondary-btn">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.day-pill input').forEach(input => {
            input.addEventListener('change', function () {
                this.parentElement.classList.toggle('active', this.checked);
            });
        });
    </script>
</body>
</html>