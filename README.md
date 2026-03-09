# Assignment Management System

A comprehensive Laravel-based assignment management system with role-based access control for administrators, teachers, and students.

## Features

### Role-Based Access Control
- **Admin**: Manage users, create invitation links, oversee the entire system
- **Teacher**: Create and manage assignments, view student submissions
- **Student**: View assignments, submit work, track progress

### Authentication System
- Secure login with password visibility toggle
- Admin-first registration (only one admin can be created)
- Invitation-based registration for teachers and students
- Password reset functionality via email
- Role-based dashboard redirects

### Invitation System
- Admin creates invitation links by selecting role (teacher/student)
- 30-day validity period for invitation links
- Social media sharing (WhatsApp, Messenger, Telegram, Email)
- Copy-to-clipboard functionality
- Users provide all details when clicking invitation link

### Professional UI
- Role-specific sidebar navigation with color coding:
  - Admin: Purple (#9C27B0)
  - Teacher: Blue (#2196F3)
  - Student: Green (#27ae60)
- Responsive design (mobile-friendly)
- SVG icons throughout
- Active state highlighting

## Tech Stack

- **Framework**: Laravel 12.x
- **Database**: MySQL
- **Frontend**: Blade Templates, Vanilla JavaScript
- **PHP**: 8.2+

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js & NPM (for asset compilation)

### Setup Instructions

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/assignment-management-system.git
cd assignment-management-system
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=assignment_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. Generate application key:
```bash
php artisan key:generate
```

7. Run database migrations:
```bash
php artisan migrate
```

8. (Optional) Seed the database with sample data:
```bash
php artisan db:seed
```

9. Create storage symlink:
```bash
php artisan storage:link
```

10. Compile assets:
```bash
npm run dev
```

11. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## First-Time Setup

1. Navigate to `/register-admin` to create the first admin account
2. Login with admin credentials
3. Create invitation links for teachers and students from the admin dashboard
4. Share invitation links via social media or copy-paste
5. Teachers and students register using the invitation links

## Database Schema

### Users Table
- id, name, email, password, role (admin/teacher/student)

### Students Table
- id, user_id, class, enrollment_number

### Teachers Table
- id, user_id, subject, department

### Assignments Table
- id, title, description, due_date, teacher_id

### Submissions Table
- id, assignment_id, student_id, file_path, submitted_at, grade

### Exams Table
- id, title, description, exam_date, teacher_id

### Exam Results Table
- id, exam_id, student_id, marks, total_marks

### Invitations Table
- id, token, role, invited_by, expires_at, used_at

### Password Resets Table
- email, token, created_at

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── AdminDashboardController.php
│   │   └── InvitationController.php
│   ├── Teacher/
│   │   ├── TeacherDashboardController.php
│   │   └── TeacherAssignmentController.php
│   ├── AuthController.php
│   ├── RegisterController.php
│   ├── PasswordResetController.php
│   ├── DashboardController.php
│   ├── AssignmentController.php
│   └── SubmissionController.php
├── Models/
│   ├── User.php
│   ├── Student.php
│   ├── Teacher.php
│   ├── Assignment.php
│   ├── Submission.php
│   ├── Exam.php
│   ├── ExamResult.php
│   ├── Invitation.php
│   └── PasswordReset.php

resources/views/
├── layouts/
│   ├── admin.blade.php
│   ├── teacher.blade.php
│   └── student.blade.php
├── admin/
│   ├── dashboard.blade.php
│   └── invitations/
├── teacher/
│   ├── dashboard.blade.php
│   └── assignments/
├── assignments/
├── login.blade.php
├── register-admin.blade.php
├── register-invitation.blade.php
├── forgot-password.blade.php
└── reset-password.blade.php
```

## Security

- CSRF protection enabled on all forms
- Password hashing using bcrypt
- Session-based authentication
- Role-based access control middleware
- Invitation token validation
- SQL injection protection via Eloquent ORM

## Troubleshooting

### 419 CSRF Token Error
If you encounter a 419 error when submitting forms, your session may have expired. Refresh the page and try again.

### Session Lifetime
The default session lifetime is 120 minutes. You can adjust this in `.env`:
```env
SESSION_LIFETIME=120
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions, please open an issue on GitHub.
