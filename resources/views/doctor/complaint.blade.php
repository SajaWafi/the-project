<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Complaints</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --taif-blue: #1f5b87;
            --taif-orange: #f6ad55;
            --page-bg: #f4f7fc;
            --text-dark: #2d3748;
            --text-muted: #718096;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--page-bg);
            font-family: 'Public Sans', Arial, sans-serif;
            min-height: 100vh;
        }

        .mobile-container {
            position: relative;
            width: 380px;
            height: 680px;
            max-width: 95%;
            background-image: url("{{ asset('images/bg.png') }}");
            background-size: cover;
            background-position: center;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 20px 10px;
        }

        .back-arrow {
            color: #0ea5e9;
            font-size: 18px;
            text-decoration: none;
        }

        .page-title {
            color: var(--taif-blue);
            font-size: 28px;
            font-weight: 800 ;
            margin: 0;
        }

        .header-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .complaint-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .instruction-text {
            color: var(--text-muted);
            font-size: 13px;
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--taif-blue);
            font-size: 13px;
            font-weight: 700;
        }

        .form-select, .form-textarea, .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #edf2f7;
            border-radius: 14px;
            font-size: 14px;
            color: var(--text-dark);
            padding: 12px 15px;
            outline: none;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-select, .form-input {
            height: 50px;
        }

        .form-select {
            cursor: pointer;
        }

        .form-textarea {
            height: 140px;
            resize: none;
            line-height: 1.5;
        }

        .form-select:focus, .form-textarea:focus, .form-input:focus {
            background: white;
            border-color: #0ea5e9;
        }

        /* الحقل المخفي لاسم ولي الأمر */
        #parent-select-group {
            display: none;
        }

        .submit-btn {
           width: 100%;
            height: 52px;
            background: var(--taif-orange);
            color: #fff;
            border: none;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 20px;
            box-shadow: 0 6px 18px rgba(47,128,237,0.3);
            transition: 0.2s;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="mobile-container">
        <div class="mobile-header">
            <a href="javascript:history.back()" class="back-arrow">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h4 class="page-title">Complaints</h4>
            <img class="header-logo" src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
        </div>

        <div class="complaint-body">
            <p class="instruction-text">
                Need help or want to report an issue? Please provide details below for our admin team.
            </p>

            <form action="{{ route('doctor.complaints.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Issue Category</label>
                    <select name="category" id="complaint-category" class="form-select" required>
                        <option value="" disabled selected>Choose a category</option>
                        <option value="System Error / Bug">System Error or Bug (خلل في النظام)</option>
                        <option value="Technical Issue">Technical Issue (مشكلة تقنية بالحساب)</option>
                        <option value="Parent Dispute">Issue regarding a Parent (مشكلة مع ولي أمر)</option>
                        <option value="General Suggestion">General Suggestion (اقتراح عام)</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group" id="parent-select-group">
                    <label>Parent Name or ID</label>
                    <input 
                        type="text" 
                        name="parent_info" 
                        class="form-input" 
                        placeholder="Enter parent's name or ID..."
                    >
                </div>

                <div class="form-group">
                    <label>Details</label>
                    <textarea 
                        name="message" 
                        class="form-textarea" 
                        placeholder="Describe your issue or feedback in detail..." 
                        required
                    ></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    Submit Report
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('complaint-category').addEventListener('change', function() {
            var parentGroup = document.getElementById('parent-select-group');
            if (this.value === 'Parent Dispute') {
                parentGroup.style.display = 'block';
            } else {
                parentGroup.style.display = 'none';
            }
        });
    </script>

</body>
</html>