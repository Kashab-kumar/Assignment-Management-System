  <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileAvatarController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherAssignmentController;
use App\Http\Controllers\Teacher\TeacherSubmissionController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\TeacherGradeController;
use App\Http\Controllers\Teacher\TeacherReportController;
use App\Http\Controllers\Teacher\TeacherCourseController;
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\StudentGradeController;
use App\Http\Controllers\Student\StudentModuleController;
use App\Http\Controllers\Student\StudentRankingController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\SecureExamController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout/{guard}', [AuthController::class, 'logout'])
    ->whereIn('guard', ['admin', 'teacher', 'student'])
    ->name('logout');

// Admin Registration (First time setup)
Route::get('/register/admin', [RegisterController::class, 'showAdminRegister'])->name('register.admin');
Route::post('/register/admin', [RegisterController::class, 'registerAdmin'])->name('register.admin.post');

// Invitation Registration
Route::get('/register/invitation/{token}', [RegisterController::class, 'showInvitationRegister'])->name('register.invitation');
Route::post('/register/invitation/{token}', [RegisterController::class, 'registerInvitation'])->name('register.invitation.post');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');

// Student Routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/student/settings/avatar', [ProfileAvatarController::class, 'update'])->name('student.settings.avatar.update');
    Route::delete('/student/settings/avatar', [ProfileAvatarController::class, 'destroy'])->name('student.settings.avatar.destroy');

    // Student pages
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/settings', [ProfileAvatarController::class, 'studentSettings'])->name('settings');
        Route::get('/calendar', [CalendarController::class, 'studentIndex'])->name('calendar');
        Route::get('/exams', [StudentExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/{exam}', [StudentExamController::class, 'show'])->name('exams.show');
        Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');
        Route::get('/modules', [StudentModuleController::class, 'index'])->name('modules.index');
        Route::get('/modules/{module}', [StudentModuleController::class, 'show'])->name('modules.show');
        Route::get('/grades', [StudentGradeController::class, 'index'])->name('grades.index');
        Route::get('/rankings', [StudentRankingController::class, 'index'])->name('rankings');
        Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
    });
});

// Teacher Routes
Route::middleware(['auth:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [ProfileAvatarController::class, 'teacherSettings'])->name('settings');
    Route::post('/settings/avatar', [ProfileAvatarController::class, 'update'])->name('settings.avatar.update');
    Route::delete('/settings/avatar', [ProfileAvatarController::class, 'destroy'])->name('settings.avatar.destroy');
    Route::get('/calendar', [CalendarController::class, 'teacherIndex'])->name('calendar');
    Route::post('/calendar/events', [CalendarController::class, 'storeTeacherEvent'])->name('calendar.events.store');
    Route::delete('/calendar/events/{event}', [CalendarController::class, 'destroyTeacherEvent'])->name('calendar.events.destroy');

    // Assignments
    Route::get('/assignments', [TeacherAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [TeacherAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [TeacherAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [TeacherAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/submissions/{submission}/grade', [TeacherAssignmentController::class, 'gradeSubmission'])->name('submissions.grade');

    // Submissions
    Route::get('/submissions', [TeacherSubmissionController::class, 'index'])->name('submissions.index');

    // Exams
    Route::get('/exams', [TeacherExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [TeacherExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [TeacherExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [TeacherExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [TeacherExamController::class, 'update'])->name('exams.update');
    Route::get('/exams/{exam}', [TeacherExamController::class, 'show'])->name('exams.show');
    Route::post('/exams/{exam}/results', [TeacherExamController::class, 'upsertResult'])->name('exams.results.upsert');

    // Modules
    Route::get('/modules', [TeacherModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/create', [TeacherModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [TeacherModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}', [TeacherModuleController::class, 'show'])->name('modules.show');
    Route::get('/modules/{module}/edit', [TeacherModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [TeacherModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [TeacherModuleController::class, 'destroy'])->name('modules.destroy');

    // Courses
    Route::get('/courses', [TeacherCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [TeacherCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/modules/{module}', [TeacherCourseController::class, 'showModule'])->name('courses.modules.show');
    Route::post('/courses/{course}/modules/{module}/items', [TeacherCourseController::class, 'storeModuleItem'])->name('courses.modules.items.store');

    // Students / Grades / Reports
    Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');
    Route::post('/students/invitations', [TeacherStudentController::class, 'storeInvitation'])->name('students.invitations.store');
    Route::get('/students/invitations/{invitation}', [TeacherStudentController::class, 'showInvitation'])->name('students.invitations.show');
    Route::get('/grades', [TeacherGradeController::class, 'index'])->name('grades.index');
    Route::get('/reports', [TeacherReportController::class, 'index'])->name('reports.index');
});

// Admin Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [ProfileAvatarController::class, 'adminSettings'])->name('settings');
    Route::post('/settings/avatar', [ProfileAvatarController::class, 'update'])->name('settings.avatar.update');
    Route::delete('/settings/avatar', [ProfileAvatarController::class, 'destroy'])->name('settings.avatar.destroy');

    // Invitations
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Teachers Management
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Students Management
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Courses Management
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/modules', [CourseController::class, 'storeModule'])->name('courses.modules.store');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/academic', [ReportController::class, 'academic'])->name('reports.academic');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// Secure Exam Routes
Route::middleware(['auth:student', 'secure.exam'])->prefix('secure-exam')->name('secure-exam.')->group(function () {
    Route::post('/{exam}/start', [SecureExamController::class, 'startSession'])->name('start');
    Route::post('/{exam}/violation', [SecureExamController::class, 'recordViolation'])->name('violation');
    Route::post('/{exam}/heartbeat', [SecureExamController::class, 'heartbeat'])->name('heartbeat');
    Route::post('/{exam}/end', [SecureExamController::class, 'endSession'])->name('end');
});
