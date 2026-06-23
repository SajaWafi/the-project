<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Medical Report') }} - {{ $linkedChild->name }}</title>

<style>

    body {
        font-family: 'Cairo', sans-serif; /* استخدام الخط العربي */
        color: #333;
        font-size: 14px;
        line-height: 1.6;
    }

    .header {
        text-align: center;
        border-bottom: 2px solid #2c5282;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .header h1 {
        color: #2c5282;
        margin: 0;
        font-size: 24px;
    }

    .header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .info-table th, .info-table td {
        padding: 10px;
        border: 1px solid #e5e7eb;
        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
    }

    .info-table th {
        background-color: #f8fafc;
        color: #1f5b87;
        width: 25%;
    }

    .section-title {
        color: #2c5282;
        font-size: 18px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }

    .note-item {
        background-color: #f8fafc;
        border-left: 4px solid #3a82f6;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 4px;
    }

    html[dir="rtl"] .note-item {
        border-left: none;
        border-right: 4px solid #3a82f6;
    }

    .note-header {
        font-weight: bold;
        color: #1f5b87;
        margin-bottom: 8px;
        font-size: 12px;
        border-bottom: 1px dashed #cbd5e1;
        padding-bottom: 5px;
    }

    .note-text {
        color: #4b5563;
    }

    .footer {
        margin-top: 50px;
        text-align: center;
        font-size: 12px;
        color: #9ca3af;
        border-top: 1px solid #e5e7eb;
        padding-top: 10px;
    }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ __('Medical Report') }}</h1>
        <p>{{ __('Taif Project - Autism Care System') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <th>{{ __('Child Name') }}</th>
            <td>{{ $linkedChild->name ?? __('N/A') }}</td>
            <th>{{ __('Age') }}</th>
            <td>{{ $linkedChild->birth_date ? \Carbon\Carbon::parse($linkedChild->birth_date)->age . ' ' . __('years') : __('N/A') }}</td>
        </tr>
        <tr>
            <th>{{ __('Parent Name') }}</th>
            <td>{{ $parent->user->first_name ?? '' }} {{ $parent->user->last_name ?? '' }}</td>
            <th>{{ __('Autism Level') }}</th>
            <td>{{ __($linkedChild->autism_level ?? 'Not set') }}</td>
        </tr>
        <tr>
            <th>{{ __('Doctor') }}</th>
            <td colspan="3">Dr. {{ $doctor->user->first_name ?? '' }} {{ $doctor->user->last_name ?? '' }}</td>
        </tr>
    </table>

    <div class="section-title">
        {{ __('Clinical Notes & Observations') }}
    </div>

    @forelse($medicalNotes as $note)
        <div class="note-item">
            <div class="note-header">
                {{ __('Date:') }} {{ \Carbon\Carbon::parse($note->created_at)->format('M d, Y - h:i A') }}
            </div>
            <div class="note-text">
                {{ $note->note_text }}
            </div>
        </div>
    @empty
        <p style="color: #6b7280; text-align: center;">{{ __('No clinical notes found for this child yet.') }}</p>
    @endforelse

    <div class="footer">
        {{ __('Generated on:') }} {{ now()->format('M d, Y') }} | {{ __('Taif System') }}
    </div>

</body>
</html>