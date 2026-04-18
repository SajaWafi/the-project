<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adding Appointment</title>
    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ===== Page background ===== */
        body {
            background: #edf1f4;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ===== Phone frame ===== */
        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        /* ===== Scrollable content ===== */
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

        /* ===== Status bar ===== */
        .status-bar {
            height: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
        }

        /* ===== Header ===== */
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

      /* 🔹 شكل حقل التاريخ */

.date-section{
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
    border-bottom: none;
    margin-bottom: 14px;
    padding-bottom: 10px;
    padding-top: 10px;
}

/* 🔹 لما تختار التاريخ */
.date-picker:focus {
    background: #f5cfa5;
}

/* 🔹 نخلي الأيقونة أوضح */
.date-picker::-webkit-calendar-picker-indicator {
    filter: invert(48%) sepia(92%) saturate(500%) hue-rotate(10deg);
    cursor: pointer;
}
        /* ===== Form area ===== */
        .form-wrap {
            padding: 0 22px;
        }

        /* ===== Section title inside form ===== */
        .field-title {
            color: #eb9443;
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 6px;
        }

        /* ===== Divider line ===== */
        .field-block {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #efb37f;
        }

        .field-block.no-border {
            border-bottom: none;
            padding-bottom: 0;
        }

        /* ===== Time row ===== */
        .time-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-left: 6px;
        }

        .time-box {
            min-width: 54px;
            font-size: 26px;
            font-weight: 700;
            color: #484848;
            border-bottom: 3px solid #5a5a5a;
            line-height: 1;
            padding-bottom: 2px;
            text-align: center;
        }

        .time-unit {
            min-width: 62px;
        }

        .time-select-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .time-arrow {
            color: #eb9443;
            font-size: 14px;
            margin-top: 6px;
        }

        /* ===== Label above input ===== */
        .sub-label {
            color: #4b4b4b;
            font-size: 14px;
            margin-bottom: 6px;
        }

        /* ===== Select / input common style ===== */
        .select-field,
        .text-field,
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

        .select-field,
        .text-field {
            height: 42px;
        }

        .select-field {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23eb9443' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 38px;
        }

        .text-field::placeholder,
        .textarea-field::placeholder {
            color: #9a8f84;
        }

        /* ===== Textarea ===== */
        .textarea-field {
            min-height: 118px;
            resize: none;
            padding-top: 14px;
            border: 1.5px solid #efc79f;
            background: rgba(255,255,255,0.35);
            font-size: 14px;
            color: #555;
        }

        /* ===== Buttons area ===== */
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

        /* ===== Validation errors ===== */
        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 10px 12px;
            border-radius: 10px;
            margin: 0 22px 12px;
            font-size: 13px;
        }

            .field-block {
        position: relative;
    }

    .suggestions-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 180px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .suggestion-item {
        padding: 10px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background: #f2f2f2;
    }

    </style>
</head>
<body>
    <div class="phone">
        <div class="content">

            <div class="header">
                <div class="page-title">Adding Appointment</div>
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
<div class="date-section">

    <div class="field-title">Select Date</div>

    <!-- 🔥 تقويم -->
    <input type="date"
           id="appointmentDate"
           name="appointment_date"
           value="{{ old('appointment_date') }}"
           class="date-picker">

</div>

                <div class="form-wrap">

                    <div class="field-block">
                        <div class="field-title">Time Since</div>
                        <div class="time-row">
                            <div class="time-select-wrap">
                                <select name="from_hour" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-right:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ old('from_hour', '08') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="from_minute" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-right:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @foreach (['00','15','30','45'] as $minute)
                                        <option value="{{ $minute }}" {{ old('from_minute', '30') == $minute ? 'selected' : '' }}>
                                            {{ $minute }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="from_period" class="select-field" style="width:92px; background:transparent; border-radius:0; padding:0; padding-right:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    <option value="AM" {{ old('from_period', 'AM') == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ old('from_period') == 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field-block">
                        <div class="field-title">Time to</div>
                        <div class="time-row">
                            <div class="time-select-wrap">
                                <select name="to_hour" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-right:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ old('to_hour', '10') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="to_minute" class="select-field" style="width:78px; background:transparent; border-radius:0; padding:0; padding-right:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    @foreach (['00','15','30','45'] as $minute)
                                        <option value="{{ $minute }}" {{ old('to_minute', '00') == $minute ? 'selected' : '' }}>
                                            {{ $minute }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="time-select-wrap">
                                <select name="to_period" class="select-field" style="width:92px; background:transparent; border-radius:0; padding:0; padding-right:20px; font-size:26px; font-weight:700; color:#484848; border-bottom:3px solid #5a5a5a;">
                                    <option value="AM" {{ old('to_period', 'AM') == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ old('to_period') == 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field-block">
                        <div class="field-title">Choose Patient</div>
                        <div class="sub-label">Full Name</div>

                        <input
                            type="text"
                            id="patient_search"
                            class="select-field"
                            placeholder="Type patient name"
                            autocomplete="off"
                            value="{{ old('patient_name') }}"
                        >

                        <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') }}">

                        <div id="patient_suggestions" class="suggestions-box"></div>
                    </div>

                    <div class="field-block">
                        <div class="field-title">Choose Clinic</div>
                        <select name="clinic_name" class="select-field">
                            <option value="">-----------</option>
                            <option value="Main Clinic" {{ old('clinic_name') == 'Main Clinic' ? 'selected' : '' }}>Main Clinic</option>
                            <option value="Neurology Clinic" {{ old('clinic_name') == 'Neurology Clinic' ? 'selected' : '' }}>Neurology Clinic</option>
                            <option value="Child Care Center" {{ old('clinic_name') == 'Child Care Center' ? 'selected' : '' }}>Child Care Center</option>
                        </select>
                    </div>

                    <div class="field-block no-border">
                        <div class="field-title">Add note</div>
                        <textarea name="note" class="textarea-field" placeholder="Enter Your Note Here..">{{ old('note') }}</textarea>
                    </div>

                    <div class="actions">
                        <button type="submit" class="primary-btn">Add new Appointment</button>
                        <a href="{{ route('doctor.appointments') }}" class="secondary-btn" style="display:flex; align-items:center; justify-content:center;">
                            Cancel Adding
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    const patients = [
        { id: 1, name: 'Jane Doe' },
        { id: 2, name: 'Ali Salah' },
        { id: 3, name: 'Hifa Jaber' }
    ];

    const searchInput = document.getElementById('patient_search');
    const patientIdInput = document.getElementById('patient_id');
    const suggestionsBox = document.getElementById('patient_suggestions');

    searchInput.addEventListener('input', function () {
        const value = this.value.toLowerCase().trim();
        suggestionsBox.innerHTML = '';
        patientIdInput.value = '';

        if (value === '') {
            suggestionsBox.style.display = 'none';
            return;
        }

        const filteredPatients = patients.filter(patient =>
            patient.name.toLowerCase().includes(value)
        );

        if (filteredPatients.length === 0) {
            suggestionsBox.style.display = 'none';
            return;
        }

        filteredPatients.forEach(patient => {
            const item = document.createElement('div');
            item.classList.add('suggestion-item');
            item.textContent = patient.name;

            item.addEventListener('click', function () {
                searchInput.value = patient.name;
                patientIdInput.value = patient.id;
                suggestionsBox.style.display = 'none';
            });

            suggestionsBox.appendChild(item);
        });

        suggestionsBox.style.display = 'block';
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.field-block')) {
            suggestionsBox.style.display = 'none';
        }
    });
</script>
</body>
</html>
