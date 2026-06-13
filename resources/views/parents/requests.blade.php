<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctors - Requests & Notifications</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #ffffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.phone {
    width: 100%;
    max-width: 360px;
    max-height: 800px;
    background: url('{{ asset('pics/bg.png') }}') no-repeat;
    background-position: left;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 12px 30px rgba(0,0,0,0.35);
}

.content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 16px 14px;
}

.back-btn {
    position: absolute;
    left: 12px;
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #1d567e;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
}

.back-btn svg {
    width: 22px;
    height: 22px;
    display: block;
}

.title {
    font-size: 28px;
    font-weight: 800;
    color: #1f567f;
}

.logo {
    position: absolute;
    right: 12px;
    width: 38px;
    height: 38px;
    object-fit: contain;
}

/* المربع الأصفر الأصلي (للمواعيد) */
.notice-box {
    margin: 0 12px 10px;
    background: #fff4d9;
    border: 1px solid #f0cf7a;
    color: #8a5a00;
    border-radius: 14px;
    padding: 12px;
    font-size: 13px;
    line-height: 1.4;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.notice-title {
    font-weight: 700;
    margin-bottom: 4px;
}

/* المربع الجديد (لإشعارات الرسائل) */
.chat-notice-box {
    margin: 0 12px 10px;
    background: #eef2f6; /* أزرق فاتح جداً */
    border: 1px solid #cdd8e4;
    color: #2c3e50;
    border-radius: 14px;
    padding: 12px;
    font-size: 13px;
    line-height: 1.4;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.chat-notice-icon {
    flex-shrink: 0;
    margin-top: 2px;
}

.chat-notice-content {
    flex: 1;
}

.chat-notice-title {
    font-weight: 700;
    margin-bottom: 4px;
    color: #1f567f;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-notice-time {
    font-size: 11px;
    font-weight: normal;
    color: #7f8c8d;
}

.list {
    padding: 10px;
    overflow-y: auto;
    height: 580px;
}

.card {
    background: #C3EDE5;
    border-radius: 20px;
    padding: 14px;
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.card img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
}

.card-info {
    margin-left: 12px;
    flex: 1;
}

.name {
    font-weight: 700;
    color: #1f567f;
}

.specialty {
    font-size: 14px;
    color: #333;
}

.actions {
    margin-top: 8px;
    display: flex;
    gap: 10px;
}

.btn-icon {
    width: 70px;
    height: 36px;
    border-radius: 25px;
    background: white;
    border: none;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    color: #000000;
    font-size: 12px;
    cursor: pointer;
}
</style>
</head>

<body>

    <div class="phone">
        <div class="content">
            
            <div class="header">
                <a href="{{ url()->previous() }}" class="back-btn" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19"
                              stroke="currentColor"
                              stroke-width="2.2"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="title">Request</div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            </div>

         @foreach($appointmentNotices as $appNotice)
    @php
        // نجيبوا بيانات الدكتور بناءً على related_id الموجود في الإشعار
        $doctor = \App\Models\DoctorProfile::find($appNotice->related_id);
        $doctorName = $doctor ? ($doctor->user->first_name . ' ' . $doctor->user->last_name) : 'Doctor';
    @endphp

    <div class="notice-box" style="margin: 0 12px 10px; background: #fff4d9; border: 1px solid #f0cf7a; color: #8a5a00; border-radius: 14px; padding: 12px; font-size: 13px; line-height: 1.4; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <div class="notice-title" style="font-weight: 700; margin-bottom: 4px; display: flex; justify-content: space-between; align-items: center;">
            
            <span>Appointment Updated by Dr. {{ $doctorName }} ⚠️</span>
            
            <span style="font-size: 11px; font-weight: normal; color: #b37400;">
                {{ $appNotice->created_at->diffForHumans() }}
            </span>
        </div>
        <div>{{ $appNotice->message }}</div>
    </div>
@endforeach

            <div class="list">
                
                @foreach($notifications as $relatedId => $doctorMessages)
                    @if($relatedId && $doctorMessages->isNotEmpty())
                        @php
                            // نجيبوا بيانات أحدث رسالة في المجموعة
                            $latestMsg = $doctorMessages->first();
                        @endphp
                        
                        <a href="{{ route('chat', $relatedId) }}" class="notice-box" style="background: #eef2f6; border: 1px solid #cdd8e4; color: #1f567f; margin-bottom: 12px; border-radius: 20px; padding: 14px; font-size: 13px; line-height: 1.4; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; align-items: flex-start; gap: 12px; text-decoration: none;">
                            
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1f567f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top: 2px;">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            
                            <div style="flex: 1;">
                                <div style="font-weight: 700; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                                    <span>
                                        {{ $latestMsg->title }} 
                                        @if($doctorMessages->count() > 1)
                                            <span style="background: #1f567f; color: #fff; border-radius: 50%; padding: 2px 6px; font-size: 10px; margin-left: 4px;">{{ $doctorMessages->count() }}</span>
                                        @endif
                                    </span>
                                    <span style="font-size: 11px; font-weight: normal; color: #7f8c8d;">
                                        {{ $latestMsg->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    @foreach($doctorMessages->take(3) as $msg)
                                        <div style="background: #ffffff; padding: 6px 10px; border-radius: 8px; border: 1px solid #e1e8ed; color: #2c3e50; font-size: 12px;">
                                            {{ $msg->message }}
                                        </div>
                                    @endforeach
                                    
                                    @if($doctorMessages->count() > 3)
                                        <div style="font-size: 10px; color: #7f8c8d; text-align: center; margin-top: 2px;">+ {{ $doctorMessages->count() - 3 }} more...</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach

                @forelse($requests as $request)
                    <div class="card">
                        @php
                            $profileImage = $request->doctor?->user?->profile_image;
                            $doctorImage = $profileImage ? asset('storage/' . $profileImage) : asset('images/default-user.png');
                        @endphp
                        
                        <img src="{{ $doctorImage }}" alt="Doctor Image">

                        <div class="card-info">
                            <div class="name">
                                Dr. {{ $request->doctor?->user?->first_name ?? 'طبيب' }} {{ $request->doctor?->user?->last_name ?? '' }}
                            </div>

                            @if($request->status == 'pending')
                                <div class="specialty" style="color: #1f567f;">Is sending request</div>
                                
                                <div class="actions">
                                    <form action="{{ route('requests.accept', $request->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn-icon" type="submit" style="background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9;">accept</button>
                                    </form>

                                    <form action="{{ route('requests.reject', $request->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn-icon" type="submit" style="background: #ffebee; color: #c62828; border: 1px solid #ffcdd2;">reject</button>
                                    </form>
                                </div>

                            @elseif($request->status == 'accepted')
                                <div class="specialty" style="color: #2e7d32; font-weight: bold;">Already Linked ✔️</div>
                                
                            @elseif($request->status == 'rejected')
                                <div class="specialty" style="color: #c62828;">Request Rejected ❌</div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-info">
                            <div class="name">No requests</div>
                            <div class="specialty">There are no pending requests right now</div>
                        </div>
                    </div>
                @endforelse

            </div> </div> </div> </body>
</html>