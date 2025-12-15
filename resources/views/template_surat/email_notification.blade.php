<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>{{ $subject ?? 'Pemberitahuan' }}</title> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #2563eb;
            /* Blue-600 */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 20px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #1d4ed8;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>testing email masuk kah? </h1>
    </div>
    <!-- <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <div class="content">
            @if(isset($greeting))
            <p>Hello, {{ $greeting }}</p>
            @endif

            @if(is_array($content))
            {{-- Handle structured content if passed as array --}}
            @foreach($content as $paragraph)
            <p>{!! $paragraph !!}</p>
            @endforeach
            @else
            {{-- Handle simple string content --}}
            <p>{!! nl2br(e($content)) !!}</p>
            @endif

            @if(isset($actionUrl) && isset($actionText))
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ $actionUrl }}" class="btn">{{ $actionText }}</a>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>{{ config('app.url') }}</p>
        </div>
    </div> -->
</body>

</html>