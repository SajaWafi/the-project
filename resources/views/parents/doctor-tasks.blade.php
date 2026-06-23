<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Home Tasks') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background: #ffffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .phone { width: 390px; height: 844px; background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover; border-radius: 22px; overflow: hidden; position: relative; box-shadow: 0 12px 30px rgba(0,0,0,0.35); }
        .content { position: relative; z-index: 2; height: 100%; overflow-y: auto; padding: 8px 14px 30px; }
        .content::-webkit-scrollbar { width: 5px; }
        .content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
        
        .header { position: relative; height: 42px; margin-bottom: 20px; }
        .back-btn { position: absolute; inset-inline-start: 0; background: transparent; border: none; cursor: pointer; color: #10b981; padding: 6px; transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }}; }
        .back-btn svg { width: 26px; height: 26px; }
        .title { font-size: 24px; font-weight: 800; color: #065f46; text-align: center; line-height: 42px; }
        
        .child-info-bar { background: #d1fae5; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        .child-avatar { width: 44px; height: 44px; background: #10b981; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 18px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .child-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .child-details h3 { font-size: 16px; color: #065f46; margin-bottom: 4px; }
        .child-details p { font-size: 12px; color: #6b7280; }

        .progress-container { margin-bottom: 20px; background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #f3f4f6; }
        
        .task-card { background: #fff; border-radius: 16px; padding: 16px; margin-bottom: 15px; border-inline-start: 4px solid #10b981; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .task-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .task-title { font-size: 15px; font-weight: bold; color: #1f2937; }
        .task-body { font-size: 13px; color: #6b7280; line-height: 1.5; margin-bottom: 12px; }
        
        .task-actions { display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e5e7eb; padding-top: 10px; }
        .task-date { font-size: 11px; color: #9ca3af; }
        
        .action-btns { display: flex; align-items: center; gap: 8px; }
        .toggle-label { font-size: 12px; font-weight: bold; color: #4b5563; cursor: pointer; }
        .task-checkbox { width: 18px; height: 18px; cursor: pointer; accent-color: #10b981; }

        /* تنسيق المهام المؤرشفة (بعد 7 أيام) */
        .task-expired { opacity: 0.55; background: #f8fafc; border-inline-start-color: #94a3b8 !important; }
        .task-expired .task-checkbox { cursor: not-allowed; filter: grayscale(100%); }
        .task-expired .toggle-label { cursor: not-allowed; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">
            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div class="title">{{ __('Home Plan') }}</div>
            </div>

            <!-- معلومات الدكتور -->
            <div class="child-info-bar">
                <div class="child-avatar">
                    @if(!empty($doctor->user->profile_image)) 
                        <img src="{{ asset('storage/' . ltrim($doctor->user->profile_image, '/')) }}" alt="Profile Image">
                    @else
                        {{ mb_substr($doctor->user->first_name ?? 'D', 0, 1) }}
                    @endif
                </div>
                <div class="child-details">
                    <h3>{{ __('Dr.') }} {{ $doctor->user->first_name ?? '' }} {{ $doctor->user->last_name ?? '' }}</h3>
                    <p>{{ __('Assigned Home Tasks') }}</p>
                </div>
            </div>

            <!-- اللوجيك الجديد: حساب المهام النشطة فقط -->
            @php
                $now = \Carbon\Carbon::now();
                
                // تصفية المهام لمعرفة "النشطة" فقط (التي لم يمر على إنجازها 7 أيام)
                $activeTasks = $tasks->filter(function($task) use ($now) {
                    if (!$task->is_completed) return true;
                    return $task->updated_at->diffInDays($now) < 7;
                });

                $totalActiveTasks = $activeTasks->count();
                $completedActiveTasks = $activeTasks->where('is_completed', true)->count();
                $progressPercentage = $totalActiveTasks > 0 ? round(($completedActiveTasks / $totalActiveTasks) * 100) : 0;
            @endphp

            <!-- شريط نسبة الإنجاز (يعتمد على النشطة فقط) -->
            <div class="progress-container">
                <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px;">
                    <span>{{ __('Parent Compliance') }}</span>
                    <span style="color: {{ $progressPercentage >= 50 ? '#10b981' : '#f59e0b' }};">{{ $progressPercentage }}%</span>
                </div>
                <div style="width: 100%; background: #e5e7eb; border-radius: 10px; height: 10px; overflow: hidden;">
                    <div style="width: {{ $progressPercentage }}%; background: {{ $progressPercentage >= 50 ? '#10b981' : '#f59e0b' }}; height: 100%; border-radius: 10px; transition: width 0.5s ease;"></div>
                </div>
                <div style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 8px;">
                    {{ $completedActiveTasks }} {{ __('out of') }} {{ $totalActiveTasks }} {{ __('tasks completed') }}
                </div>
            </div>

            <!-- قائمة المهام -->
            @forelse($tasks as $task)
                @php
                    // التحقق إذا كانت المهمة منجزة وفات عليها 7 أيام فأكثر
                    $isExpired = $task->is_completed && $task->updated_at->diffInDays($now) >= 7;
                @endphp

                <div class="task-card {{ $isExpired ? 'task-expired' : '' }}" style="border-inline-start-color: {{ $task->is_completed ? '#10b981' : '#f59e0b' }};">
                    <div class="task-header">
                        <div class="task-title" style="{{ $isExpired ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</div>
                        @if($isExpired)
                            <span style="background: #e2e8f0; color: #64748b; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;"><i class="fas fa-archive"></i> {{ __('Archived') }}</span>
                        @elseif($task->is_completed)
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;"><i class="fas fa-check"></i> {{ __('Done') }}</span>
                        @else
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;"><i class="fas fa-clock"></i> {{ __('Pending') }}</span>
                        @endif
                    </div>
                    
                    <div class="task-body">{{ $task->description ?? __('No additional details provided.') }}</div>

                    <div class="task-actions">
                        <div class="task-date"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($task->created_at)->format('M d, Y') }}</div>
                        
                        @if($isExpired)
                            <!-- مجرد عرض للمهمة المؤرشفة بدون نموذج إرسال (لا يمكن التعديل) -->
                            <div class="action-btns">
                                <label class="toggle-label">{{ __('Completed') }}</label>
                                <input type="checkbox" class="task-checkbox" checked disabled>
                            </div>
                        @else
                            <!-- نموذج التحديث المباشر للمهام النشطة -->
                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="action-btns">
                                @csrf
                                <label for="task-{{ $task->id }}" class="toggle-label">
                                    {{ $task->is_completed ? __('Completed') : __('Mark as Done') }}
                                </label>
                                <input type="checkbox" id="task-{{ $task->id }}" class="task-checkbox" onchange="this.form.submit()" {{ $task->is_completed ? 'checked' : '' }}>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #9ca3af; margin-top: 40px;">
                    <i class="fas fa-tasks" style="font-size: 40px; margin-bottom: 10px; color: #d1d5db;"></i>
                    <p>{{ __('No active home tasks assigned by this doctor.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>