<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Home Tasks') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Cairo', Arial, sans-serif; }
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
        
        .add-task-btn { width: 100%; background: #10b981; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: 0.2s; margin-bottom: 20px; }
        
        .task-card { background: #fff; border-radius: 16px; padding: 16px; margin-bottom: 15px; border-inline-start: 4px solid #10b981; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .task-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .task-title { font-size: 15px; font-weight: bold; color: #1f2937; }
        .task-body { font-size: 13px; color: #6b7280; line-height: 1.5; margin-bottom: 12px; }
        
        .task-actions { display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px dashed #e5e7eb; padding-top: 10px; }
        .task-date { font-size: 11px; color: #9ca3af; }
        .action-btns { display: flex; gap: 10px; }
        .action-icon { background: none; border: none; font-size: 15px; cursor: pointer; padding: 4px; transition: 0.2s; }
        .edit-icon { color: #3b82f6; }
        .delete-icon { color: #ef4444; }

        /* Modal Styles */
        .custom-modal-overlay { position: absolute; inset: 0; background: rgba(210, 216, 223, 0.82); display: none; align-items: center; justify-content: center; z-index: 100; padding: 22px; }
        .custom-modal-overlay.show { display: flex; }
        .custom-modal-box { width: 100%; max-width: 340px; background: #f7f7f7; border-radius: 24px; padding: 24px; box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12); }
        .custom-modal-title { font-size: 20px; font-weight: 800; margin-bottom: 16px; text-align: center; }
        .note-input { width: 100%; border-radius: 12px; border: 1px solid #cbd5e1; padding: 12px; font-size: 14px; margin-bottom: 15px; resize: none; }
        .modal-actions { display: flex; gap: 10px; }
        .modal-btn { flex: 1; height: 42px; border: none; border-radius: 20px; font-weight: bold; cursor: pointer; }
        .btn-cancel { background: #e2e8f0; color: #4b5563; }
        .btn-primary { background: #10b981; color: #fff; }
        .btn-danger { background: #ef4444; color: #fff; }
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

            <div class="child-info-bar">
                <div class="child-avatar">
                    @if(!empty($parent->user->profile_image)) 
                        <img src="{{ asset('storage/' . ltrim($parent->user->profile_image, '/')) }}" alt="Profile Image">
                    @else
                        {{ mb_substr($linkedChild->name ?? 'C', 0, 1) }}
                    @endif
                </div>
                <div class="child-details">
                    <h3>{{ $linkedChild->name ?? __('Unknown') }}</h3>
                    <p>{{ __('Parent:') }} {{ $parent->user->first_name ?? '' }} {{ $parent->user->last_name ?? '' }}</p>
                </div>
            </div>

            @php
                $totalTasks = $homeTasks ? $homeTasks->count() : 0;
                $completedTasks = $homeTasks ? $homeTasks->where('is_completed', true)->count() : 0;
                $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            @endphp

            <div class="progress-container">
                <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px;">
                    <span>{{ __('Parent Compliance') }}</span>
                    <span style="color: {{ $progressPercentage >= 50 ? '#10b981' : '#f59e0b' }};">{{ $progressPercentage }}%</span>
                </div>
                <div style="width: 100%; background: #e5e7eb; border-radius: 10px; height: 10px; overflow: hidden;">
                    <div style="width: {{ $progressPercentage }}%; background: {{ $progressPercentage >= 50 ? '#10b981' : '#f59e0b' }}; height: 100%; border-radius: 10px; transition: width 0.5s ease;"></div>
                </div>
                <div style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 8px;">
                    {{ $completedTasks }} {{ __('out of') }} {{ $totalTasks }} {{ __('tasks completed') }}
                </div>
            </div>

            <button class="add-task-btn" onclick="openModal('addTaskModal')">
                + {{ __('Assign New Home Task') }}
            </button>

            @forelse($homeTasks as $task)
                <div class="task-card" style="border-inline-start-color: {{ $task->is_completed ? '#10b981' : '#f59e0b' }};">
                    <div class="task-header">
                        <div class="task-title">{{ $task->title }}</div>
                        @if($task->is_completed)
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;"><i class="fas fa-check"></i> {{ __('Done') }}</span>
                        @else
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;"><i class="fas fa-clock"></i> {{ __('Pending') }}</span>
                        @endif
                    </div>
                    
                    <div class="task-body">{{ $task->description ?? __('No additional details provided.') }}</div>

                    <div class="task-actions">
                        <div class="task-date"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($task->created_at)->format('M d, Y') }}</div>
                        <div class="action-btns">
                            <button class="action-icon edit-icon" onclick="editTask({{ $task->id }}, `{{ $task->title }}`, `{{ $task->description }}`)"><i class="fas fa-edit"></i></button>
                            <button class="action-icon delete-icon" onclick="deleteTask({{ $task->id }})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #9ca3af; margin-top: 40px;">
                    <i class="fas fa-tasks" style="font-size: 40px; margin-bottom: 10px; color: #d1d5db;"></i>
                    <p>{{ __('No active home tasks for this child.') }}</p>
                </div>
            @endforelse
        </div>

        <div class="custom-modal-overlay" id="addTaskModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title" style="color: #10b981;">{{ __('New Home Task') }}</div>
                <form action="{{ route('doctor.tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="child_id" value="{{ $linkedChild->id }}">
                    <input type="text" name="title" class="note-input" placeholder="{{ __('Task Title...') }}" required>
                    <textarea class="note-input" name="description" placeholder="{{ __('Task Instructions...') }}" style="min-height: 100px;"></textarea>
                    <div class="modal-actions">
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal('addTaskModal')">{{ __('Cancel') }}</button>
                        <button type="submit" class="modal-btn btn-primary">{{ __('Assign') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="custom-modal-overlay" id="editTaskModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title" style="color: #3b82f6;">{{ __('Edit Task') }}</div>
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" id="editTaskTitle" class="note-input" required>
                    <textarea class="note-input" name="description" id="editTaskDesc" style="min-height: 100px;"></textarea>
                    <div class="modal-actions">
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal('editTaskModal')">{{ __('Cancel') }}</button>
                        <button type="submit" class="modal-btn btn-primary" style="background:#3b82f6;">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="custom-modal-overlay" id="deleteTaskModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title" style="color: #ef4444;">{{ __('Delete Task') }}</div>
                <p style="margin-bottom: 20px; color: #4b5563; font-size: 15px;">{{ __('Are you sure you want to delete this task?') }}</p>
                <form id="deleteTaskForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-actions">
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal('deleteTaskModal')">{{ __('Cancel') }}</button>
                        <button type="submit" class="modal-btn btn-danger">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }
        
        function editTask(id, title, desc) {
            document.getElementById('editTaskTitle').value = title;
            document.getElementById('editTaskDesc').value = desc !== 'null' ? desc : '';
            document.getElementById('editTaskForm').action = `/doctor/tasks/${id}/update`;
            openModal('editTaskModal');
        }

        function deleteTask(id) {
            document.getElementById('deleteTaskForm').action = `/doctor/tasks/${id}/delete`;
            openModal('deleteTaskModal');
        }
    </script>
</body>
</html>