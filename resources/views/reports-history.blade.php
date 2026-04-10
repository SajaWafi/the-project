<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports History</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #edf1f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .mobile-screen {
            width: 390px;
            height: 844px;
            max-width: 100%;
            max-height: 95vh;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 30px;
            background: #f9f9f9;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
            scrollbar-width: none;
        }

        .mobile-screen::-webkit-scrollbar {
            display: none;
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
            background-size: 165% 100%;
            background-position: left bottom;
            opacity: 0.9;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 16px 14px 24px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 16px;
            min-height: 44px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .toolbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 12px;
        }

        .delete-toggle-btn {
            border: none;
            background: #fff;
            color: #ff5d5d;
            border-radius: 12px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            font-size: 13px;
            font-weight: 700;
        }

        .delete-toggle-btn svg {
            width: 16px;
            height: 16px;
        }

        .reports-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .report-card-wrapper {
            position: relative;
        }

        .report-check {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            accent-color: #2f80ed;
            display: none;
            z-index: 3;
            cursor: pointer;
        }

        .delete-mode .report-check {
            display: block;
        }

        .report-card {
            text-decoration: none;
            background: #fff;
            border-radius: 14px;
            padding: 14px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 12px rgba(0,0,0,0.06);
            transition: 0.2s;
        }

        .delete-mode .report-card {
            padding-left: 40px;
        }

        .report-card.selected {
            outline: 2px solid rgba(47, 128, 237, 0.22);
            background: #f8fbff;
        }

        .report-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .report-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f80ed;
        }

        .report-icon svg {
            width: 18px;
            height: 18px;
        }

        .report-title {
            font-size: 15px;
            font-weight: 500;
            color: #202020;
        }

        .report-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-arrow svg {
            width: 18px;
            height: 18px;
        }

        .delete-actions {
            display: none;
            margin-top: 14px;
        }

        .delete-actions.show {
            display: block;
        }

        .delete-btn {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 14px;
            background: #ff5d5d;
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(255, 93, 93, 0.22);
        }

        .empty-state {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 40px;
        }

        .delete-modal {
            position: fixed;
            inset: 0;
            background: rgba(88, 103, 132, 0.24);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .delete-modal.show {
            display: flex;
        }

        .delete-box {
            width: 100%;
            max-width: 320px;
            background: #f7f7f7;
            border-radius: 26px;
            padding: 26px 20px 20px;
            text-align: center;
            box-shadow: 0 18px 36px rgba(0,0,0,0.14);
        }

        .delete-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .delete-text {
            font-size: 14px;
            color: #667085;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .delete-buttons {
            display: flex;
            gap: 10px;
        }

        .cancel-btn,
        .confirm-btn {
            flex: 1;
            height: 42px;
            border: none;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .cancel-btn {
            background: #c8eee3;
            color: #2f80ed;
        }

        .confirm-btn {
            background: #ff5d5d;
            color: #fff;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">
                <div></div>
                <div class="top-right">
                    <div class="status-icon"></div>
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Reports History</div>

                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            @if(count($reports))
                <div class="toolbar">
                    <button id="deleteToggle" class="delete-toggle-btn" type="button">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Delete</span>
                    </button>
                </div>

                <div class="reports-list" id="reportsList">
                    @foreach($reports as $report)
                        <div class="report-card-wrapper">
                            <input type="checkbox" class="report-check" value="{{ $report['id'] }}">

                            <a href="{{ route('reports.details', $report['id']) }}" class="report-card">
                                <div class="report-left">
                                    <div class="report-icon">
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <rect x="5" y="4" width="14" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                            <path d="M9 9v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            <path d="M12 7v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            <path d="M15 11v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        </svg>
                                    </div>

                                    <div class="report-title">{{ $report['title'] }}</div>
                                </div>

                                <div class="report-arrow">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M9 5L16 12L9 19"
                                              stroke="#e69a4b"
                                              stroke-width="2.2"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="delete-actions" id="deleteActions">
                    <button class="delete-btn" type="button" onclick="openDeleteModal()">
                        Delete Selected
                    </button>
                </div>
            @else
                <div class="empty-state">No reports yet.</div>
            @endif

        </div>
    </div>

    <div class="delete-modal" id="deleteModal" onclick="closeDeleteModal(event)">
        <div class="delete-box">
            <div class="delete-title">Delete Reports</div>
            <div class="delete-text">
                Are you sure you want to delete the selected report(s)?
            </div>

            <div class="delete-buttons">
                <button class="cancel-btn" type="button" onclick="closeDeleteModal()">Cancel</button>
                <button class="confirm-btn" type="button" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const deleteToggle = document.getElementById('deleteToggle');
        const deleteActions = document.getElementById('deleteActions');
        const deleteModal = document.getElementById('deleteModal');
        const reportsList = document.getElementById('reportsList');

        if (deleteToggle) {
            deleteToggle.addEventListener('click', function () {
                document.body.classList.toggle('delete-mode');
                deleteActions.classList.toggle('show');

                if (!document.body.classList.contains('delete-mode')) {
                    document.querySelectorAll('.report-check').forEach(check => {
                        check.checked = false;
                    });

                    document.querySelectorAll('.report-card').forEach(card => {
                        card.classList.remove('selected');
                    });
                }
            });
        }

        document.querySelectorAll('.report-check').forEach(check => {
            check.addEventListener('change', function () {
                const card = this.closest('.report-card-wrapper').querySelector('.report-card');
                if (this.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
        });

        document.querySelectorAll('.report-card').forEach(card => {
            card.addEventListener('click', function (e) {
                if (document.body.classList.contains('delete-mode')) {
                    e.preventDefault();
                    const wrapper = this.closest('.report-card-wrapper');
                    const checkbox = wrapper.querySelector('.report-check');
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('selected', checkbox.checked);
                }
            });
        });

        function openDeleteModal() {
            const checked = document.querySelectorAll('.report-check:checked');
            if (!checked.length) {
                alert('Please select at least one report.');
                return;
            }
            deleteModal.classList.add('show');
        }

        function closeDeleteModal(event) {
            if (event && event.target !== deleteModal) return;
            deleteModal.classList.remove('show');
        }

        function confirmDelete() {
            const selectedIds = Array.from(document.querySelectorAll('.report-check:checked'))
                .map(input => input.value);

            console.log('Delete reports:', selectedIds);

            // هنا لاحقًا تقدر تبعث selectedIds للباك إند
            // مثلاً عن طريق form أو fetch

            closeDeleteModal();

            alert('Selected reports are ready to be deleted.');
        }
    </script>

</body>
</html>