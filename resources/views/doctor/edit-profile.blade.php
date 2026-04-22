<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
            padding: 10px 14px 24px;
            position: relative;
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
            margin-bottom: 10px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        .logo {
            position: absolute;
            right: 0;
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

        .profile-image-wrap {
            position: relative;
            width: fit-content;
            margin: 8px auto 12px;
        }

        .profile-image {
            width: 112px;
            height: 112px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: #d9d9d9;
        }

        .edit-image-btn {
            position: absolute;
            right: -4px;
            bottom: 8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #1d567e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid #fff;
        }

        .form-wrap {
            padding: 0 2px;
        }

        .field {
            margin-bottom: 12px;
        }

        .field label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: #111;
            margin-bottom: 6px;
        }

        .text-input,
        .select-input,
        .textarea-input {
            width: 100%;
            border: none;
            outline: none;
            background: #ccefeb;
            border-radius: 16px;
            color: #333;
        }

        .text-input,
        .select-input {
            height: 42px;
            padding: 0 14px;
            font-size: 15px;
        }

        .select-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23333' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 38px;
        }

        .text-input::placeholder,
        .textarea-input::placeholder {
            color: #6e6e6e;
        }

        .dob-row {
            display: flex;
            gap: 16px;
            align-items: center;
            padding-left: 6px;
            margin-top: 4px;
        }

        .dob-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 20px;
            font-weight: 700;
            color: #3a3a3a;
            border-bottom: 2px solid #4b4b4b;
            line-height: 1;
            padding-bottom: 2px;
        }

        .dob-item select {
            border: none;
            outline: none;
            background: transparent;
            font-size: 20px;
            font-weight: 700;
            color: #3a3a3a;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 14px;
            cursor: pointer;
        }

        .dob-arrow {
            font-size: 12px;
            color: #eb9443;
            margin-top: 4px;
            margin-left: -12px;
            pointer-events: none;
        }

        .textarea-input {
            min-height: 120px;
            resize: none;
            padding: 14px;
            font-size: 14px;
            background: rgba(255,255,255,0.35);
            border: 1.5px solid #a8dfda;
        }

        .submit-btn {
            display: block;
            width: 180px;
            height: 42px;
            margin: 18px auto 0;
            border: none;
            border-radius: 20px;
            background: #2f80ed;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .hidden-file {
            display: none;
        }

        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 13px;
        }

        .success-box {
            background: #dff6dd;
            color: #146c2e;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    @php
        $doctorProfile = $user->doctorProfile ?? null;
        $birthDate = $doctorProfile?->birth_date;
    @endphp

    <div class="phone">
        <div class="content">

            <div class="header">
                <a href="{{ route('doctor.settings') }}" class="back-btn">‹</a>

                <div class="title">Edit Profile</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="form-wrap">
                @if(session('success'))
                    <div class="success-box">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="error-box">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('doctor.edit-profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profile-image-wrap">
                      <img
                    src="{{ !empty($user->profile_image)
                        ? asset('storage/' . $user->profile_image)
                        : asset('images/default-user.png') }}"
                    alt="Doctor"
                    class="profile-image"
                    id="profilePreview"
                >

                        <label for="profileImage" class="edit-image-btn">✎</label>
                        <input type="file" id="profileImage" name="profile_image" class="hidden-file" accept="image/*">
                    </div>

                    <div class="field">
                        <label>full name</label>
                        <input
                            type="text"
                            name="full_name"
                            class="text-input"
                            value="{{ old('full_name', trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))) }}"
                        >
                    </div>

                    <div class="field">
                        <label>Phone Number</label>
                        <input
                            type="text"
                            name="phone"
                            class="text-input"
                            value="{{ old('phone', $user->phone ?? '') }}"
                        >
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            class="text-input"
                            value="{{ old('email', $user->email ?? '') }}"
                        >
                    </div>

                    <div class="field">
                        <label>Gender</label>
                        <select name="gender" class="select-input">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender', $user->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Specialize in</label>
                        <input
                            type="text"
                            name="specialize"
                            class="text-input"
                            value="{{ old('specialize', $doctorProfile?->specialization ?? '') }}"
                        >
                    </div>

                    <div class="field">
                        <label>Date of Birth</label>

                        <div class="dob-row">
                            <div class="dob-item">
                                <select name="birth_day">
                                    <option value=""></option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        @php $day = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $day }}"
                                            {{ old('birth_day', $birthDate ? \Carbon\Carbon::parse($birthDate)->format('d') : '') == $day ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="dob-arrow">▼</span>
                            </div>

                            <div class="dob-item">
                                <select name="birth_month">
                                    <option value=""></option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $month = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $month }}"
                                            {{ old('birth_month', $birthDate ? \Carbon\Carbon::parse($birthDate)->format('m') : '') == $month ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="dob-arrow">▼</span>
                            </div>

                            <div class="dob-item">
                                <select name="birth_year">
                                    <option value=""></option>
                                    @for ($y = date('Y'); $y >= 1950; $y--)
                                        <option value="{{ $y }}"
                                            {{ old('birth_year', $birthDate ? \Carbon\Carbon::parse($birthDate)->format('Y') : '') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="dob-arrow">▼</span>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Bio</label>
                        <textarea
                            name="bio"
                            class="textarea-input"
                            placeholder="Enter Your Bio Here..."
                        >{{ old('bio', $doctorProfile?->bio ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="submit-btn">Accept Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const profileImageInput = document.getElementById('profileImage');
        const profilePreview = document.getElementById('profilePreview');

        profileImageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>