<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->title }} - Secure Exam</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }
    
    body { 
        font-family: 'Segoe UI', Arial, sans-serif; 
        background: #f8fafc; 
        color: #1f2937; 
        overflow: hidden; 
        user-select: none; 
        -webkit-user-select: none; 
        -moz-user-select: none; 
        -ms-user-select: none; 
    }
    
    .secure-exam-container { 
        height: 100vh; 
        display: flex; 
        flex-direction: column; 
        max-width: 100vw; 
        overflow: hidden; 
    }
    
    .secure-header { 
        background: #ffffff; 
        padding: 15px 20px; 
        border-bottom: 2px solid #7c3aed; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        flex-shrink: 0; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
    }
    
    .exam-title { 
        font-size: 20px; 
        font-weight: 700; 
        color: #1f2937; 
    }
    
    .exam-meta { 
        display: flex; 
        gap: 20px; 
        align-items: center; 
    }
    
    .meta-item { 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        padding: 8px 12px; 
        background: #f1f5f9; 
        border-radius: 6px; 
        font-size: 14px; 
        border: 1px solid #e5e7eb; 
    }
    
    .meta-label { 
        color: #64748b; 
        font-size: 12px; 
    }
    
    .meta-value { 
        font-weight: 600; 
        color: #1f2937; 
    }
    
    .timer { 
        background: #7c3aed; 
        color: #ffffff; 
        padding: 8px 16px; 
        border-radius: 6px; 
        font-weight: 700; 
        font-size: 16px; 
        min-width: 100px; 
        text-align: center; 
    }
    
    .timer.warning { 
        background: #f59e0b; 
        animation: pulse 2s infinite; 
    }
    
    .timer.danger { 
        background: #dc2626; 
        animation: pulse 1s infinite; 
    }
    
    .secure-content { 
        flex: 1; 
        overflow-y: auto; 
        padding: 20px; 
        background: #f8fafc; 
    }
    
    .question-card { 
        background: #ffffff; 
        border: 1px solid #e5e7eb; 
        border-radius: 8px; 
        padding: 20px; 
        margin-bottom: 20px; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.05); 
    }
    
    .question-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 15px; 
        padding-bottom: 10px; 
        border-bottom: 1px solid #e5e7eb; 
    }
    
    .question-number { 
        background: #7c3aed; 
        color: #ffffff; 
        padding: 6px 12px; 
        border-radius: 4px; 
        font-weight: 600; 
        font-size: 14px; 
    }
    
    .question-points { 
        color: #64748b; 
        font-size: 14px; 
    }
    
    .question-text { 
        font-size: 16px; 
        line-height: 1.6; 
        margin-bottom: 15px; 
        color: #1f2937; 
        white-space: pre-wrap; 
    }
    
    .answer-input, .answer-textarea { 
        width: 100%; 
        background: #ffffff; 
        border: 1px solid #d1d5db; 
        border-radius: 6px; 
        color: #1f2937; 
        padding: 12px; 
        font-size: 15px; 
        transition: border-color 0.2s; 
    }
    
    .answer-input:focus, .answer-textarea:focus { 
        outline: none; 
        border-color: #7c3aed; 
        background: #ffffff; 
        box-shadow: 0 0 0 3px rgba(124,58,237,0.1); 
    }
    
    .answer-textarea { 
        min-height: 200px; 
        resize: vertical; 
        font-family: inherit; 
        line-height: 1.6; 
    }
    
    .answer-input { 
        height: 48px; 
    }
    
    .secure-footer { 
        background: #ffffff; 
        padding: 15px 20px; 
        border-top: 1px solid #e5e7eb; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        flex-shrink: 0; 
        box-shadow: 0 -1px 3px rgba(0,0,0,0.1); 
    }
    
    .violation-counters { 
        display: flex; 
        gap: 15px; 
    }
    
    .counter { 
        display: flex; 
        align-items: center; 
        gap: 6px; 
        padding: 6px 10px; 
        border-radius: 4px; 
        font-size: 13px; 
    }
    
    .counter.violations { 
        background: #dc2626; 
        color: #ffffff; 
    }
    
    .counter.warnings { 
        background: #f59e0b; 
        color: #000000; 
    }
    
    .btn-submit { 
        background: #7c3aed; 
        color: #ffffff; 
        border: none; 
        padding: 12px 24px; 
        border-radius: 6px; 
        font-weight: 600; 
        cursor: pointer; 
        transition: background-color 0.2s; 
    }
    
    .btn-submit:hover { 
        background: #6d28d9; 
    }
    
    .btn-submit:disabled { 
        background: #6b7280; 
        cursor: not-allowed; 
    }
    
    .notification { 
        position: fixed; 
        top: 20px; 
        right: 20px; 
        padding: 15px 20px; 
        border-radius: 8px; 
        color: #ffffff; 
        font-weight: 600; 
        z-index: 10000; 
        max-width: 400px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.3); 
    }
    
    .notification.success { background: #22c55e; }
    .notification.warning { background: #f59e0b; }
    .notification.error { background: #dc2626; }
    
    @keyframes pulse { 
        0% { opacity: 1; } 
        50% { opacity: 0.7; } 
        100% { opacity: 1; } 
    }
    
    .loading { 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        height: 100vh; 
        background: #f8fafc; 
        color: #1f2937; 
        font-size: 18px; 
    }

    /* Multiple choice options */
    .mcq-options { display: grid; gap: 10px; margin-top: 12px; }
    .mcq-option { display: flex; align-items: center; gap: 10px; padding: 12px 14px; background: #f8fafc; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.2s; }
    .mcq-option:hover { border-color: #7c3aed; background: #f5f3ff; }
    .mcq-option input[type="radio"] { width: 18px; height: 18px; accent-color: #7c3aed; cursor: pointer; }
    .mcq-option label { cursor: pointer; flex: 1; color: #1f2937; font-size: 15px; }
    .mcq-option.selected { border-color: #7c3aed; background: #ede9fe; }
</style>

<div class="secure-exam-container">
    <!-- Loading Screen -->
    <div id="loading-screen" class="loading">
        <div>
            <h2>Initializing Secure Exam Environment...</h2>
            <p style="margin-top: 10px; opacity: 0.8;">Please wait while we set up your secure exam session.</p>
        </div>
    </div>

    <!-- Main Exam Interface -->
    <div id="exam-interface" style="display: none;">
        <header class="secure-header">
            <div>
                <h1 class="exam-title">{{ $exam->title }}</h1>
                <p style="color: #9ca3af; font-size: 14px; margin-top: 4px;">{{ $exam->course?->name ?? 'General Course' }}</p>
            </div>
            
            <div class="exam-meta">
                <div class="meta-item">
                    <span class="meta-label">Questions:</span>
                    <span class="meta-value">{{ $exam->questions->count() }}</span>
                </div>
                
                <div class="meta-item">
                    <span class="meta-label">Max Score:</span>
                    <span class="meta-value">{{ $exam->max_score }}</span>
                </div>
                
                <div class="timer" id="exam-timer">00:00:00</div>
            </div>
        </header>

        <main class="secure-content">
            <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}">
                @csrf
                
                <div class="question-list">
                    @foreach($exam->questions as $question)
                        @php
                            $savedAnswer = old('answers.' . $question->id, $answers->get($question->id)?->answer_text);
                            $isLongAnswer = $question->question_type === 'long_answer';
                            $isMultipleChoice = $question->question_type === 'multiple_choice';

                            // Parse multiple choice options if available
                            $options = [];
                            if ($isMultipleChoice && !empty($question->answer_key)) {
                                $options = array_map('trim', explode('|', $question->answer_key));
                            }
                        @endphp
                        
                        <div class="question-card">
                            <div class="question-header">
                                <div class="question-number">Question {{ $question->position }}</div>
                                <div class="question-points">{{ $question->points }} point{{ $question->points === 1 ? '' : 's' }} · {{ $isLongAnswer ? 'Long answer' : ($isMultipleChoice ? 'Multiple choice' : 'Short answer') }}</div>
                            </div>
                            
                            <div class="question-text">{{ $question->question_text }}</div>

                            @if($isMultipleChoice && !empty($options))
                                <div class="mcq-options">
                                    @foreach($options as $index => $option)
                                        <label class="mcq-option {{ $savedAnswer === $option ? 'selected' : '' }}">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $option }}"
                                                {{ $savedAnswer === $option ? 'checked' : '' }}
                                                id="answer-{{ $question->id }}-{{ $index }}"
                                            >
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($isLongAnswer)
                                <textarea
                                    name="answers[{{ $question->id }}]"
                                    class="answer-textarea"
                                    placeholder="Type your answer here... (Press Enter for new line)"
                                    id="answer-{{ $question->id }}"
                                >{{ $savedAnswer }}</textarea>
                            @else
                                <input
                                    type="text"
                                    name="answers[{{ $question->id }}]"
                                    class="answer-input"
                                    value="{{ $savedAnswer }}"
                                    placeholder="Type your answer here..."
                                    id="answer-{{ $question->id }}"
                                >
                            @endif
                        </div>
                    @endforeach
                </div>
            </form>
        </main>

        <footer class="secure-footer">
            <div class="violation-counters">
                <div class="counter violations">
                    <span>⚠️ Violations:</span>
                    <span id="violations-count">0</span>
                    <span>/{{ $exam->max_violations }}</span>
                </div>
                
                <div class="counter warnings">
                    <span>⚡ Warnings:</span>
                    <span id="warnings-count">0</span>
                    <span>/{{ $exam->max_warnings }}</span>
                </div>
            </div>
            
            <button type="button" class="btn-submit" id="submit-btn" onclick="submitSecureExam()">
                Submit Exam
            </button>
        </footer>
    </div>
</div>

<script src="{{ asset('js/secure-exam.js') }}"></script>
<script>
    let secureExam = null;
    
    document.addEventListener('DOMContentLoaded', async function() {
        // Show start button instead of auto-starting
        document.getElementById('loading-screen').innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <h2 style="color: #1f2937; margin-bottom: 20px;">Ready to Start Secure Exam</h2>
                <p style="color: #6b7280; margin-bottom: 30px;">Click the button below to start the exam in secure mode. This will enter fullscreen mode.</p>
                <button onclick="startSecureExam()" style="padding: 15px 40px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 18px; font-weight: 600;">
                    Start Exam
                </button>
            </div>
        `;
    });

    async function startSecureExam() {
        try {
            // Start secure exam session
            const response = await fetch('/secure-exam/{{ $exam->id }}/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (!response.ok) {
                let errorMessage = data.error || 'Failed to start exam session';
                if (data.starts_at) {
                    const startDate = new Date(data.starts_at);
                    errorMessage += '. Exam starts at ' + startDate.toLocaleString();
                }
                throw new Error(errorMessage);
            }

            // Initialize secure exam browser
            console.log('Exam duration from DB: {{ $exam->duration_minutes ?? "NULL" }}');
            console.log('Remaining time from server:', data.remaining_time);

            secureExam = new SecureExamBrowser({
                examId: {{ $exam->id }},
                sessionId: data.session_id,
                maxViolations: {{ $exam->max_violations ?? 3 }},
                maxWarnings: {{ $exam->max_warnings ?? 5 }},
                duration: {{ $exam->duration_minutes ?? 0 }}
            });

            // Initialize timer with remaining time from response
            if (data.remaining_time !== undefined) {
                secureExam.updateTimer(data.remaining_time);
            }

            // Hide loading screen and show exam interface
            document.getElementById('loading-screen').style.display = 'none';
            document.getElementById('exam-interface').style.display = 'block';

            // Auto-save answers every 30 seconds
            setInterval(autoSaveAnswers, 30000);

        } catch (error) {
            console.error('Failed to start secure exam:', error);

            // Show error message
            document.getElementById('loading-screen').innerHTML = `
                <div style="text-align: center; color: #dc2626; padding: 40px;">
                    <h2>Failed to Start Secure Exam</h2>
                    <p style="margin-top: 10px; font-size: 16px;">${error.message}</p>
                    <div style="margin-top: 20px;">
                        <button onclick="location.reload()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">
                            Retry
                        </button>
                        <a href="/student/exams" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                            Back to Exams
                        </a>
                    </div>
                </div>
            `;
        }
    }
    
    function autoSaveAnswers() {
        const formData = new FormData(document.getElementById('exam-form'));
        // Implement auto-save logic here
        console.log('Auto-saving answers...');
    }
    
    async function submitSecureExam() {
        if (!secureExam || !secureExam.isActive) {
            return;
        }
        
        if (confirm('Are you sure you want to submit your exam? This action cannot be undone.')) {
            // Submit the form
            document.getElementById('exam-form').submit();
            
            // End secure session
            await secureExam.submitExam();
        }
    }
    
    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
        if (secureExam && secureExam.isActive) {
            e.preventDefault();
            e.returnValue = 'Leaving this page will terminate your exam session. Are you sure?';
            return e.returnValue;
        }
    });
</script>
</body>
</html>
