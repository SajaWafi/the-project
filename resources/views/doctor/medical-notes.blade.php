<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Medical Notes') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Cairo', Arial, sans-serif; }
        body { background: #ffffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .phone { width: 390px; height: 844px; background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover; border-radius: 22px; overflow: hidden; position: relative; box-shadow: 0 12px 30px rgba(0,0,0,0.35); }
        .content { position: relative; z-index: 2; height: 100%; overflow-y: auto; padding: 8px 14px 30px; }
        .content::-webkit-scrollbar { width: 5px; }
        .content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
        
        .header { position: relative; height: 42px; margin-bottom: 20px; }
        .back-btn { position: absolute; inset-inline-start: 0; background: transparent; border: none; cursor: pointer; color: #2f80ed; padding: 6px; transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }}; }
        .back-btn svg { width: 26px; height: 26px; }
        .title { font-size: 24px; font-weight: 800; color: #1d567e; text-align: center; line-height: 42px; }
        
        .child-info-bar { background: #e0f2fe; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
    
        .child-details h3 { font-size: 16px; color: #1d567e; margin-bottom: 4px; }
        .child-details p { font-size: 13px; color: #6b7280; font-weight: bold; }

        .actions-row { display: flex; gap: 10px; margin-bottom: 20px; }
        .add-note-btn { flex: 1; background: #3a82f6; color: white; border: none; padding: 12px; border-radius: 14px; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 4px 12px rgba(58, 130, 246, 0.3); transition: 0.2s; }
        .pdf-btn { background: #fef3c7; color: #d97706; border: none; border-radius: 14px; width: 48px; display: flex; justify-content: center; align-items: center; text-decoration: none; font-size: 20px; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2); }

        .note-card { background: #fff; border-radius: 16px; padding: 16px; margin-bottom: 15px; border-inline-start: 4px solid #3a82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .note-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; border-bottom: 1px dashed #e5e7eb; padding-bottom: 8px; }
        .note-date { font-size: 14px; font-weight: bold; color: #1d567e; display: flex; align-items: center; gap: 6px; }
        .note-time { font-size: 12px; color: #9ca3af; }
        .note-body { font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 12px; }
        
        .note-actions { display: flex; justify-content: flex-end; gap: 10px; }
        .action-icon { background: none; border: none; font-size: 16px; cursor: pointer; padding: 4px; border-radius: 6px; transition: 0.2s; }
        .edit-icon { color: #3b82f6; }
        .delete-icon { color: #ef4444; }

        /* Modal Styles */
        .custom-modal-overlay { position: absolute; inset: 0; background: rgba(210, 216, 223, 0.82); display: none; align-items: center; justify-content: center; z-index: 100; padding: 22px; }
        .custom-modal-overlay.show { display: flex; }
        .custom-modal-box { width: 100%; max-width: 340px; background: #f7f7f7; border-radius: 24px; padding: 24px; box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12); }
        .custom-modal-title { font-size: 20px; font-weight: 800; margin-bottom: 16px; text-align: center; }
        .note-input { width: 100%; border-radius: 12px; border: 1px solid #cbd5e1; padding: 12px; font-size: 14px; min-height: 120px; margin-bottom: 15px; resize: none; }
        .modal-actions { display: flex; gap: 10px; }
        .modal-btn { flex: 1; height: 42px; border: none; border-radius: 20px; font-weight: bold; cursor: pointer; }
        .btn-cancel { background: #e2e8f0; color: #4b5563; }
        .btn-primary { background: #3a82f6; color: #fff; }
        .btn-danger { background: #ef4444; color: #fff; }
        .child-avatar { 
            width: 44px; height: 44px; background: #3a82f6; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 18px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .child-avatar img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">
            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div class="title">{{ __('Medical Notes') }}</div>
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

            <div class="actions-row">
                <button class="add-note-btn" onclick="openModal('addNoteModal')">
                    + {{ __('Add Clinical Note') }}
                </button>
                
                <a href="{{ route('doctor.parent.export_pdf', $parent['id']) }}" class="pdf-btn" title="{{ __('Download PDF') }}">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>

            @forelse($medicalNotes as $note)
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-date">
                            <i class="far fa-calendar-alt"></i> 
                            {{ \Carbon\Carbon::parse($note->created_at)->format('M d, Y') }}
                        </div>
                        <div class="note-time">
                            <i class="far fa-clock"></i> 
                            {{ \Carbon\Carbon::parse($note->created_at)->format('h:i A') }}
                        </div>
                    </div>
                    
                    <div class="note-body">
                        {{ $note->note_text }}
                    </div>

                    <div class="note-actions">
                        <button class="action-icon edit-icon" onclick="editNote({{ $note->id }}, `{{ $note->note_text }}`, {{ $note->is_shared ? 'true' : 'false' }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-icon delete-icon" onclick="deleteNote({{ $note->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #9ca3af; margin-top: 50px;">
                    <i class="fas fa-clipboard-list" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <p>{{ __('No medical notes found.') }}</p>
                </div>
            @endforelse
        </div>

        <div class="custom-modal-overlay" id="addNoteModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title" style="color: #3a82f6;">{{ __('New Clinical Note') }}</div>
                <form action="{{ route('doctor.notes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="child_id" value="{{ $linkedChild->id }}">
                    <textarea class="note-input" name="note_text" placeholder="{{ __('Type diagnosis here...') }}" required></textarea>
                    
                    <label style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:14px; justify-content:center;">
                        <input type="checkbox" name="is_shared" value="1"> {{ __('Share with parent') }}
                    </label>

                    <div class="modal-actions">
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal('addNoteModal')">{{ __('Cancel') }}</button>
                        <button type="submit" class="modal-btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="custom-modal-overlay" id="editNoteModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title" style="color: #3a82f6;">{{ __('Edit Note') }}</div>
                <form id="editNoteForm" method="POST">
                    @csrf
                    @method('PUT')
                    <textarea class="note-input" name="note_text" id="editNoteText" required></textarea>
                    
                    <label style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:14px; justify-content:center;">
                        <input type="checkbox" name="is_shared" id="editNoteShared" value="1"> {{ __('Share with parent') }}
                    </label>

                    <div class="modal-actions">
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal('editNoteModal')">{{ __('Cancel') }}</button>
                        <button type="submit" class="modal-btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="custom-modal-overlay" id="deleteNoteModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title" style="color: #ef4444;">{{ __('Delete Note') }}</div>
                <p style="margin-bottom: 20px; color: #4b5563; text-align: center;">{{ __('Are you sure you want to delete this note?') }}</p>
                <form id="deleteNoteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-actions">
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal('deleteNoteModal')">{{ __('Cancel') }}</button>
                        <button type="submit" class="modal-btn btn-danger">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }
        
        function editNote(id, text, isShared) {
            document.getElementById('editNoteText').value = text;
            document.getElementById('editNoteShared').checked = isShared;
            document.getElementById('editNoteForm').action = `/doctor/notes/${id}/update`;
            openModal('editNoteModal');
        }

        function deleteNote(id) {
            document.getElementById('deleteNoteForm').action = `/doctor/notes/${id}/delete`;
            openModal('deleteNoteModal');
        }
    </script>
</body>
</html>