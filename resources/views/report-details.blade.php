<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report['title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf1f4;
            padding: 40px;
        }

        .box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 24px;
            border-radius: 16px;
        }

        h1 {
            color: #1f5b87;
            margin-bottom: 10px;
        }

        p {
            color: #444;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>{{ $report['title'] }}</h1>
        <p>Here you can show the monthly summary for the child.</p>
    </div>
</body>
</html>