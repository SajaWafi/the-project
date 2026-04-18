<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Child</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.30);
        }

        .content {
            padding: 16px;
            height: 100%;
            overflow-y: auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 12px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            text-decoration: none;
            color: #1d567e;
            font-size: 24px;
            font-weight: bold;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1d567e;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 42px;
            height: 28px;
        }

        .subtitle {
            text-align: center;
            color: #55717f;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .search-wrapper {
            margin-bottom: 16px;
        }

        .search-box {
            height: 50px;
            border-radius: 25px;
            background: rgba(34,193,166,0.20);
            display: flex;
            align-items: center;
            padding: 0 14px;
            gap: 8px;
            border: 1px solid rgba(34,193,166,0.15);
        }

        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-size: 14px;
            color: #333;
        }

        .search-box input::placeholder {
            color: #728590;
        }

        .search-btn {
            border: none;
            background: #22c1a6;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(34,193,166,0.25);
        }

        .top-note {
            font-size: 12px;
            color: #607581;
            margin-bottom: 14px;
            padding-left: 4px;
        }

        .msg {
            padding: 12px;
            border-radius: 14px;
            margin-bottom: 14px;
            font-size: 13px;
        }

        .success {
            background: #dff7ea;
            color: #167a4d;
        }

        .error {
            background: #ffe4e4;
            color: #b52b2b;
        }

        .results-title {
            font-size: 15px;
            font-weight: bold;
            color: #1d567e;
            margin-bottom: 10px;
            padding-left: 4px;
        }

        .child-card {
            background: linear-gradient(180deg, #abd8d0 0%, #9ecac3 100%);
            border-radius: 22px;
            padding: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
        }

        .child-avatar {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: #eef8f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1d567e;
            font-size: 24px;
            flex-shrink: 0;
        }

        .child-info {
            flex: 1;
        }

        .child-name {
            font-weight: bold;
            color: white;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .child-sub {
            font-size: 13px;
            color: #23343d;
            margin-bottom: 3px;
        }

        .badge {
            display: inline-block;
            margin-top: 7px;
            padding: 4px 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.82);
            color: #1d567e;
            font-size: 11px;
            font-weight: bold;
        }

        .action-side {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            background: white;
            color: #1d567e;
            font-size: 22px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s ease;
        }

        .add-btn:hover {
            background: #22c1a6;
            color: white;
            transform: scale(1.06);
        }

        .added-label {
            min-width: 66px;
            text-align: center;
            padding: 7px 10px;
            border-radius: 13px;
            background: #e7f8ef;
            color: #1f8b5c;
            font-size: 11px;
            font-weight: bold;
        }

        .empty-box {
            margin-top: 24px;
            background: rgba(255,255,255,0.78);
            border-radius: 20px;
            padding: 24px 16px;
            text-align: center;
        }

        .empty-box h3 {
            color: #1d567e;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .empty-box p {
            color: #6b7f8a;
            font-size: 13px;
            line-height: 1.5;
        }

        .spacer {
            height: 16px;
        }
    </style>
</head>
<body>

<div class="phone">
    <div class="content">

        <div class="header">
            <a href="{{ route('doctor.parents') }}" class="back-btn">←</a>
            <div class="title">Add Child</div>
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="logo">
        </div>

        <div class="subtitle">
            Search for a child and link them to your account
        </div>

        @if(session('success'))
            <div class="msg success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="msg error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="msg error">
                <ul style="padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="search-wrapper">
            <form action="{{ route('doctor.children.find') }}" method="GET">
                <div class="search-box">
                    <span>🔍</span>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by child name..."
                        value="{{ $search ?? '' }}"
                    >
                    <button type="submit" class="search-btn">Go</button>
                </div>
            </form>
        </div>

        <div class="top-note">
            Check the parent name before adding the child
        </div>

        @if(isset($children) && $children->count() > 0)
            <div class="results-title">Search Results</div>

            @foreach($children as $child)
                <div class="child-card">
                    <div class="child-avatar">
                        <i class="fi fi-sr-user"></i>
                    </div>

                    <div class="child-info">
                        <div class="child-name">{{ $child->name }}</div>

                        <div class="child-sub">
                            Gender: {{ ucfirst($child->gender) }}
                        </div>

                        <div class="child-sub">
                            Birth Date: {{ $child->birth_date }}
                        </div>

                        <div class="child-sub">
                            Parent:
                            {{ $child->parent?->user?->first_name ?? 'No parent name' }}
                        </div>

                        <span class="badge">
                            Autism: {{ $child->autism_level ?? 'Not set' }}
                        </span>
                    </div>

                    <div class="action-side">
                        @if(isset($linkedChildIds) && in_array($child->id, $linkedChildIds))
                            <div class="added-label">Added</div>
                        @else
                            <form action="{{ route('doctor.children.attach', $child->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="add-btn">+</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

        @elseif(request()->has('search'))
            <div class="empty-box">
                <h3>No child found</h3>
                <p>Try another name or spelling.</p>
            </div>
        @else
            <div class="empty-box">
                <h3>Start searching</h3>
                <p>Search by child name to link them to the doctor account.</p>
            </div>
        @endif

        <div class="spacer"></div>
    </div>
</div>

</body>
</html>