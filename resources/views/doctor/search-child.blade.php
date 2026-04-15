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
            padding: 14px;
            height: 100%;
            overflow-y: auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 18px;
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
            width: 40px;
            height: 28px;
        }

        .subtitle {
            text-align: center;
            color: #4d6a79;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .search-wrapper {
            margin-bottom: 18px;
        }

        .search-box {
            height: 48px;
            border-radius: 24px;
            background: rgba(34,193,166,0.22);
            display: flex;
            align-items: center;
            padding: 0 14px;
            gap: 8px;
        }

        .search-icon {
            font-size: 16px;
            color: #1d567e;
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
            color: #6a7c88;
        }

        .search-btn {
            border: none;
            background: #22c1a6;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .helper-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding: 0 4px;
        }

        .helper-text {
            font-size: 12px;
            color: #5f7481;
        }

        .create-link {
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            color: #22c1a6;
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
            background: #a8d3cc;
            border-radius: 22px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
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
            margin-bottom: 4px;
        }

        .child-sub {
            font-size: 13px;
            color: #2f3d46;
            margin-bottom: 2px;
        }

        .badge {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.75);
            color: #1d567e;
            font-size: 11px;
            font-weight: bold;
        }

        .action-side {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .add-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: white;
            color: #1d567e;
            font-size: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }

        .add-btn:hover {
            background: #22c1a6;
            color: white;
            transform: scale(1.05);
        }

        .added-label {
            min-width: 64px;
            text-align: center;
            padding: 6px 8px;
            border-radius: 12px;
            background: #e7f8ef;
            color: #1f8b5c;
            font-size: 11px;
            font-weight: bold;
        }

        .empty-box {
            margin-top: 24px;
            background: rgba(255,255,255,0.75);
            border-radius: 20px;
            padding: 22px 16px;
            text-align: center;
        }

        .empty-box h3 {
            color: #1d567e;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .empty-box p {
            color: #657985;
            font-size: 13px;
            margin-bottom: 14px;
            line-height: 1.5;
        }

        .empty-btn {
            display: inline-block;
            text-decoration: none;
            background: #22c1a6;
            color: white;
            padding: 10px 18px;
            border-radius: 18px;
            font-size: 13px;
            font-weight: bold;
        }

        .spacer {
            height: 18px;
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
                    <span class="search-icon">🔍</span>
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

        <div class="helper-row">
            <div class="helper-text">Make sure you choose the correct child</div>
            <a href="#" class="create-link">+ New child</a>
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
                            Parent ID: {{ $child->parent_id }}
                        </div>

                        <span class="badge">
                            Autism: {{ $child->autism_level ?? 'Not set' }}
                        </span>
                    </div>

                    <div class="action-side">
                        @php
                            $alreadyAdded = false;

                            if (isset($linkedChildIds)) {
                                $alreadyAdded = in_array($child->id, $linkedChildIds);
                            }
                        @endphp

                        @if($alreadyAdded)
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
                <p>
                    We couldn’t find any child with this name.
                    Try another spelling or add a new child later.
                </p>
                <a href="#" class="empty-btn">Create New Child</a>
            </div>
        @else
            <div class="empty-box">
                <h3>Start searching</h3>
                <p>
                    Search by child name to link them to the doctor account.
                </p>
            </div>
        @endif

        <div class="spacer"></div>
    </div>
</div>

</body>
</html>