class SecureExamBrowser {
    constructor(options = {}) {
        this.examId = options.examId;
        this.sessionId = options.sessionId;
        this.maxViolations = options.maxViolations || 3;
        this.maxWarnings = options.maxWarnings || 5;
        this.heartbeatInterval = null;
        this.fullscreenInterval = null;
        this.isActive = false;
        this.violations = 0;
        this.warnings = 0;
        this.startTime = null;
        this.duration = null;
        
        this.init();
    }

    async init() {
        try {
            // Request fullscreen
            await this.requestFullscreen();
            
            // Start monitoring
            this.startMonitoring();
            
            // Setup event listeners
            this.setupEventListeners();
            
            // Start heartbeat
            this.startHeartbeat();
            
            this.isActive = true;
            this.showNotification('Secure exam mode activated', 'success');
            
        } catch (error) {
            console.error('Failed to initialize secure exam:', error);
            this.handleFatalError('Failed to initialize secure exam mode');
        }
    }

    async requestFullscreen() {
        const element = document.documentElement;
        
        if (element.requestFullscreen) {
            await element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) {
            await element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            await element.msRequestFullscreen();
        } else {
            throw new Error('Fullscreen not supported');
        }
    }

    startMonitoring() {
        // Monitor fullscreen state
        this.fullscreenInterval = setInterval(() => {
            if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                this.handleViolation('fullscreen_exit');
            }
        }, 1000);

        // Monitor visibility changes (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.handleViolation('tab_switch');
            }
        });

        // Monitor window focus/blur
        window.addEventListener('blur', () => {
            this.handleViolation('tab_switch');
        });

        window.addEventListener('focus', () => {
            // Window regained focus
        });
    }

    setupEventListeners() {
        // Prevent right-click
        document.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            this.handleViolation('right_click');
            return false;
        });

        // Prevent copy/paste/cut
        document.addEventListener('copy', (e) => {
            e.preventDefault();
            this.handleViolation('copy_attempt');
            return false;
        });

        document.addEventListener('paste', (e) => {
            e.preventDefault();
            this.handleViolation('copy_attempt');
            return false;
        });

        document.addEventListener('cut', (e) => {
            e.preventDefault();
            this.handleViolation('copy_attempt');
            return false;
        });

        // Prevent keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P, Ctrl+R, F5, Alt+Tab
            const forbiddenKeys = [
                123, // F12
                116, // F5
                82,  // R (Ctrl+R)
                85,  // U (Ctrl+U)
                83,  // S (Ctrl+S)
                80,  // P (Ctrl+P)
                73,  // I (Ctrl+Shift+I)
                74,  // J (Ctrl+Shift+J)
            ];

            if (forbiddenKeys.includes(e.keyCode)) {
                if (e.ctrlKey || e.shiftKey || e.altKey) {
                    e.preventDefault();
                    this.handleViolation('keyboard_shortcut', { key: e.key, ctrlKey: e.ctrlKey, shiftKey: e.shiftKey, altKey: e.altKey });
                    return false;
                }
            }

            // Prevent Alt+Tab
            if (e.altKey && e.keyCode === 9) {
                e.preventDefault();
                this.handleViolation('keyboard_shortcut', { key: 'Alt+Tab' });
                return false;
            }
        });

        // Prevent text selection
        document.addEventListener('selectstart', (e) => {
            e.preventDefault();
            return false;
        });

        // Prevent drag and drop
        document.addEventListener('dragstart', (e) => {
            e.preventDefault();
            return false;
        });

        // Monitor dev tools
        let devtools = { open: false, orientation: null };
        const threshold = 160;

        setInterval(() => {
            if (window.outerHeight - window.innerHeight > threshold || 
                window.outerWidth - window.innerWidth > threshold) {
                if (!devtools.open) {
                    devtools.open = true;
                    this.handleViolation('dev_tools_open');
                }
            } else {
                devtools.open = false;
            }
        }, 500);

        // Prevent back button
        window.addEventListener('popstate', (e) => {
            e.preventDefault();
            history.pushState(null, null, location.href);
            this.handleViolation('navigation_attempt');
            return false;
        });

        // Prevent page unload
        window.addEventListener('beforeunload', (e) => {
            e.preventDefault();
            e.returnValue = 'Leaving this page will terminate your exam session. Are you sure?';
            return e.returnValue;
        });
    }

    startHeartbeat() {
        this.heartbeatInterval = setInterval(async () => {
            try {
                const response = await fetch(`/secure-exam/${this.examId}/heartbeat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.terminated) {
                        this.handleTermination(data.reason || 'Exam session terminated');
                    } else {
                        throw new Error(data.error || 'Heartbeat failed');
                    }
                } else {
                    // Update remaining time display
                    this.updateTimer(data.remaining_time);
                    this.updateViolationCounters(data.violations, data.warnings);
                }

            } catch (error) {
                console.error('Heartbeat failed:', error);
                // Try to reconnect
                setTimeout(() => this.startHeartbeat(), 5000);
            }
        }, 30000); // Every 30 seconds
    }

    async handleViolation(type, details = {}) {
        try {
            const response = await fetch(`/secure-exam/${this.examId}/violation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    details: details
                })
            });

            const data = await response.json();

            if (data.terminated) {
                this.handleTermination(data.reason);
            } else {
                this.violations = data.violations;
                this.warnings = data.warnings;
                this.updateViolationCounters(data.violations, data.warnings);
                
                const message = this.getViolationMessage(type);
                const level = data.violations >= this.maxViolations - 1 ? 'error' : 'warning';
                this.showNotification(message, level);
            }

        } catch (error) {
            console.error('Failed to record violation:', error);
        }
    }

    getViolationMessage(type) {
        const messages = {
            'tab_switch': 'Tab switching detected! Please stay on the exam page.',
            'fullscreen_exit': 'Fullscreen mode exited! Please return to fullscreen.',
            'copy_attempt': 'Copy/paste attempt detected! This is not allowed.',
            'right_click': 'Right-click disabled! This action is not allowed.',
            'keyboard_shortcut': 'Keyboard shortcut detected! This action is not allowed.',
            'dev_tools_open': 'Developer tools detected! Please close them immediately.',
            'navigation_attempt': 'Navigation attempt detected! Please stay on the exam page.'
        };

        return messages[type] || 'Violation detected! Please follow exam rules.';
    }

    handleTermination(reason) {
        this.isActive = false;
        this.cleanup();
        
        // Show termination message
        document.body.innerHTML = `
            <div style="
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background: #1a1a1a;
                color: white;
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 20px;
            ">
                <div>
                    <h1 style="color: #ff4444; margin-bottom: 20px;">Exam Session Terminated</h1>
                    <p style="font-size: 18px; margin-bottom: 10px;">Reason: ${reason}</p>
                    <p style="font-size: 14px; opacity: 0.8;">Your exam session has been terminated due to policy violations.</p>
                    <p style="font-size: 14px; opacity: 0.8; margin-top: 20px;">Please contact your instructor for assistance.</p>
                </div>
            </div>
        `;

        // Prevent any further interaction
        document.addEventListener('click', (e) => e.preventDefault());
        document.addEventListener('keydown', (e) => e.preventDefault());
    }

    handleFatalError(message) {
        document.body.innerHTML = `
            <div style="
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background: #1a1a1a;
                color: white;
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 20px;
            ">
                <div>
                    <h1 style="color: #ff4444; margin-bottom: 20px;">Secure Exam Error</h1>
                    <p style="font-size: 18px; margin-bottom: 10px;">${message}</p>
                    <p style="font-size: 14px; opacity: 0.8;">Please refresh the page and try again.</p>
                </div>
            </div>
        `;
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            z-index: 10000;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;

        switch (type) {
            case 'success':
                notification.style.background = '#22c55e';
                break;
            case 'warning':
                notification.style.background = '#f59e0b';
                break;
            case 'error':
                notification.style.background = '#ef4444';
                break;
            default:
                notification.style.background = '#3b82f6';
        }

        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    updateTimer(remainingSeconds) {
        const timerElement = document.getElementById('exam-timer');
        if (timerElement && remainingSeconds !== null) {
            const hours = Math.floor(remainingSeconds / 3600);
            const minutes = Math.floor((remainingSeconds % 3600) / 60);
            const seconds = remainingSeconds % 60;
            
            timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (remainingSeconds < 300) { // Less than 5 minutes
                timerElement.style.color = '#ef4444';
            }
        }
    }

    updateViolationCounters(violations, warnings) {
        const violationsElement = document.getElementById('violations-count');
        const warningsElement = document.getElementById('warnings-count');
        
        if (violationsElement) violationsElement.textContent = violations;
        if (warningsElement) warningsElement.textContent = warnings;
    }

    async submitExam() {
        if (!this.isActive) return;

        try {
            const response = await fetch(`/secure-exam/${this.examId}/end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (response.ok) {
                this.isActive = false;
                this.cleanup();
                // Redirect to exam results page
                window.location.href = `/student/exams/${this.examId}`;
            }

        } catch (error) {
            console.error('Failed to submit exam:', error);
        }
    }

    cleanup() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
        }
        
        if (this.fullscreenInterval) {
            clearInterval(this.fullscreenInterval);
        }

        // Remove event listeners
        document.removeEventListener('visibilitychange', this.handleViolation);
        window.removeEventListener('blur', this.handleViolation);
        window.removeEventListener('focus', this.handleViolation);
        window.removeEventListener('beforeunload', this.handleViolation);
    }

    destroy() {
        this.cleanup();
        this.isActive = false;
    }
}

// Export for use in blade templates
window.SecureExamBrowser = SecureExamBrowser;
