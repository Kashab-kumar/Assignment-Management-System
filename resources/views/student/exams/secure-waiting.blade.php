<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->title }} - Waiting Room</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    .waiting-room {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    .waiting-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        text-align: center;
        max-width: 500px;
        width: 90%;
    }

    .exam-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #dc2626, #ef4444);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 36px;
    }

    .exam-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .exam-course {
        color: #6b7280;
        font-size: 16px;
        margin-bottom: 30px;
    }

    .countdown-timer {
        font-size: 48px;
        font-weight: 700;
        color: #dc2626;
        margin: 30px 0;
        font-family: 'Courier New', monospace;
    }

    .start-time {
        font-size: 18px;
        color: #4b5563;
        margin-bottom: 20px;
    }

    .instructions {
        background: #f3f4f6;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        text-align: left;
    }

    .instructions h3 {
        color: #1f2937;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .instructions ul {
        list-style: none;
        padding: 0;
    }

    .instructions li {
        padding: 8px 0;
        color: #4b5563;
        display: flex;
        align-items: center;
    }

    .instructions li:before {
        content: "🔒";
        margin-right: 10px;
        font-size: 16px;
    }

    .refresh-btn {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .refresh-btn:hover {
        transform: translateY(-2px);
    }

    .exit-btn {
        background: #6b7280;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 10px;
        font-size: 14px;
    }

    .exit-btn:hover {
        background: #4b5563;
    }

    .button-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #fef3c7;
        color: #92400e;
        margin-bottom: 20px;
    }
</style>
</head>
<body style="margin: 0; padding: 0; overflow-x: hidden;">

<div class="waiting-room">
    <div class="waiting-container">
        <div class="exam-icon">🔐</div>

        <div class="status-badge">Secure Exam - Waiting Room</div>

        <h1 class="exam-title">{{ $exam->title }}</h1>
        <p class="exam-course">{{ $exam->course?->name ?? 'General Course' }}</p>

        <div class="countdown-timer" id="countdown">
            Loading...
        </div>

        <div class="start-time">
            Exam starts at: {{ $examStartsAt->format('h:i A') }} on {{ $examStartsAt->format('d/m/Y') }}
        </div>

        @if($exam->secure_instructions)
            <div class="instructions">
                <h3>📋 Secure Exam Instructions</h3>
                <div>{{ $exam->secure_instructions }}</div>
            </div>
        @else
            <div class="instructions">
                <h3>📋 Secure Exam Instructions</h3>
                <ul>
                    <li>This exam will run in secure browser mode</li>
                    <li>You must remain in fullscreen mode during the exam</li>
                    <li>Tab switching, right-click, and copy/paste are disabled</li>
                    <li>Any violations will be recorded and may terminate your session</li>
                    <li>Make sure you have a stable internet connection</li>
                    <li>Close all other applications before starting</li>
                </ul>
            </div>
        @endif

        <div class="button-group">
            <button class="refresh-btn" onclick="location.reload()">
                Refresh Status
            </button>
            <a href="{{ route('student.exams.index') }}" class="exit-btn" style="text-decoration: none; display: inline-block;">
                Exit Waiting Room
            </a>
        </div>
    </div>
</div>

<script>
    function stopPageMedia() {
        document.querySelectorAll('audio, video').forEach((media) => {
            try {
                media.pause();
                media.currentTime = 0;
                media.muted = true;
                media.autoplay = false;
            } catch (error) {
                console.warn('Unable to stop media element:', error);
            }
        });
    }

    stopPageMedia();

    function updateCountdown() {
        const startTime = new Date('{{ $examStartsAt->toISOString() }}');
        const now = new Date();
        const diff = startTime - now;

        if (diff <= 0) {
            // Exam has started, redirect to exam
            window.location.href = window.location.href;
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        let timeString = '';
        if (hours > 0) {
            timeString += hours.toString().padStart(2, '0') + ':';
        }
        timeString += minutes.toString().padStart(2, '0') + ':';
        timeString += seconds.toString().padStart(2, '0');

        document.getElementById('countdown').textContent = timeString;
    }

    // Update countdown every second
    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Auto-refresh every 30 seconds
    setInterval(() => {
        window.location.reload();
    }, 30000);
</script>
</body>
</html>
