# Secure Exam Browser System

This document explains how to use the Secure Exam Browser functionality in the Assignment Management System.

## Overview

The Secure Exam Browser system provides a controlled environment for online exams that prevents cheating and ensures exam integrity. It includes:

- **Exact time-based exam start**: Exams begin precisely at the scheduled time
- **Fullscreen enforcement**: Students must remain in fullscreen mode
- **Tab switching prevention**: Students cannot switch browser tabs
- **Activity monitoring**: All violations are logged and tracked
- **Automatic termination**: Sessions are terminated when limits are exceeded

## For Teachers

### Creating a Secure Exam

1. Navigate to **Teacher Dashboard → Exams → Create Exam**
2. Fill in the basic exam details (title, description, date, time, duration)
3. **Enable Secure Exam Mode** by checking the checkbox
4. Configure secure options:
   - **Secure Instructions**: Custom instructions for students (optional)
   - **Maximum Violations**: Number of violations allowed before termination (default: 3)
   - **Maximum Warnings**: Number of warnings allowed before termination (default: 5)
5. Add exam questions as usual
6. Click "Create Exam"

### What Monitors Are Active

When secure mode is enabled, the system monitors:

- **Tab switching**: Switching away from the exam page
- **Fullscreen exit**: Leaving fullscreen mode
- **Right-click**: Disabling context menu
- **Copy/Paste**: Preventing text copying and pasting
- **Keyboard shortcuts**: Blocking F12, Ctrl+Shift+I, Ctrl+U, etc.
- **Developer tools**: Detecting when dev tools are opened
- **Navigation attempts**: Preventing back button navigation

### Viewing Exam Sessions

Teachers can view exam sessions and violations in the exam management interface. Each student's session includes:

- Start and end times
- Number of violations and warnings
- Detailed violation log with timestamps
- Termination reason (if applicable)

## For Students

### Taking a Secure Exam

1. Navigate to the exam page before the scheduled start time
2. You will see a **waiting room** with a countdown timer
3. Review the secure exam instructions
4. When the exam starts, you'll be prompted to enter fullscreen mode
5. The exam interface will load with:
   - Timer showing remaining time
   - Violation and warning counters
   - Exam questions

### Rules During Secure Exams

- **Must remain in fullscreen mode**
- **Cannot switch browser tabs**
- **Cannot use right-click menu**
- **Cannot copy/paste text**
- **Cannot use keyboard shortcuts**
- **Cannot open developer tools**

### Violation System

- **Violations**: Serious breaches (tab switching, fullscreen exit)
- **Warnings**: Minor breaches (right-click, keyboard shortcuts)
- **Termination**: Session ends when limits are exceeded
- **Grace Period**: Students get warnings before termination

## Technical Implementation

### Database Tables

- **exams**: Added secure_mode, secure_instructions, max_violations, max_warnings
- **exam_sessions**: Tracks individual student exam sessions with violation logs

### Key Components

1. **SecureExamMiddleware**: Validates exam access and manages sessions
2. **SecureExamController**: Handles API endpoints for session management
3. **SecureExamBrowser (JavaScript)**: Client-side monitoring and enforcement
4. **ExamSession Model**: Manages session data and violation tracking

### Security Features

- **Content Security Policy**: Prevents external resource loading
- **Security Headers**: X-Frame-Options, X-Content-Type-Options, etc.
- **Event Monitoring**: Tracks all user interactions
- **Heartbeat System**: Regular server communication
- **Auto-save**: Periodic saving of exam answers

## Browser Compatibility

The secure exam system works with modern browsers that support:

- Fullscreen API
- Visibility API
- Modern JavaScript features

**Recommended browsers:**
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## Troubleshooting

### Common Issues

1. **Fullscreen not working**: Check browser permissions and ensure no popups are blocked
2. **Exam not starting**: Verify system time and exam schedule
3. **Session terminated**: Check violation limits and network connection
4. **Timer issues**: Ensure stable internet connection

### Best Practices

- Test the system before actual exams
- Ensure students have compatible browsers
- Provide clear instructions to students
- Monitor exam sessions during the exam
- Have a backup plan for technical issues

## Limitations

- Cannot prevent physical cheating (notes, other devices)
- Requires modern browser support
- Depends on stable internet connection
- May not work on some mobile devices

## Future Enhancements

Potential improvements for future versions:

- Webcam monitoring
- Screen recording
- IP address restrictions
- Biometric verification
- Mobile app support
- Offline mode capability
