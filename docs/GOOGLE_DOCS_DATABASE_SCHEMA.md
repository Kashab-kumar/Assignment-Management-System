# Assignment Management System - Database Schema (Share Version)

Generated from Laravel migrations in this project.

## 1) Application Tables

| Topic | Field | Type | Key |
|-------|-------|------|-----|
| users | id | bigint | PK |
| users | name | varchar | |
| users | email | varchar | UNIQUE |
| users | role | enum(admin, teacher, student) | DEFAULT: student |
| users | email_verified_at | timestamp | NULLABLE |
| users | password | varchar | |
| users | avatar_path | varchar | NULLABLE |
| users | remember_token | varchar | NULLABLE |
| users | created_at | timestamp | |
| users | updated_at | timestamp | |
| students | id | bigint | PK |
| students | user_id | bigint | FK->users.id, CASCADE |
| students | course_id | bigint | FK->courses.id, SET NULL, NULLABLE |
| students | student_id | varchar | UNIQUE |
| students | name | varchar | |
| students | email | varchar | UNIQUE |
| students | created_at | timestamp | |
| students | updated_at | timestamp | |
| teachers | id | bigint | PK |
| teachers | user_id | bigint | FK->users.id, CASCADE |
| teachers | teacher_id | varchar | UNIQUE |
| teachers | name | varchar | |
| teachers | email | varchar | UNIQUE |
| teachers | subject | varchar | NULLABLE |
| teachers | created_at | timestamp | |
| teachers | updated_at | timestamp | |
| courses | id | bigint | PK |
| courses | name | varchar | |
| courses | code | varchar | UNIQUE |
| courses | category_name | varchar | NULLABLE, INDEXED |
| courses | class_name | varchar | NULLABLE, INDEXED |
| courses | description | text | NULLABLE |
| courses | is_active | boolean | DEFAULT: true |
| courses | created_at | timestamp | |
| courses | updated_at | timestamp | |
| course_teacher | id | bigint | PK |
| course_teacher | course_id | bigint | FK->courses.id, CASCADE |
| course_teacher | teacher_id | bigint | FK->teachers.id, CASCADE |
| course_teacher | created_at | timestamp | |
| course_teacher | updated_at | timestamp | |
| course_teacher | (course_id, teacher_id) | composite | UNIQUE |
| assignments | id | bigint | PK |
| assignments | course_id | bigint | FK->courses.id, SET NULL, NULLABLE |
| assignments | title | varchar | |
| assignments | description | text | |
| assignments | type | varchar | |
| assignments | due_date | date | |
| assignments | max_score | int | DEFAULT: 100 |
| assignments | created_at | timestamp | |
| assignments | updated_at | timestamp | |
| submissions | id | bigint | PK |
| submissions | student_id | bigint | FK->students.id, CASCADE |
| submissions | assignment_id | bigint | FK->assignments.id, CASCADE |
| submissions | content | text | NULLABLE |
| submissions | file_path | varchar | NULLABLE |
| submissions | score | int | NULLABLE |
| submissions | status | enum(pending, graded) | DEFAULT: pending |
| submissions | submitted_at | timestamp | DEFAULT: current |
| submissions | created_at | timestamp | |
| submissions | updated_at | timestamp | |
| exams | id | bigint | PK |
| exams | course_id | bigint | FK->courses.id, SET NULL, NULLABLE |
| exams | type | varchar(20) | DEFAULT: exam |
| exams | title | varchar | |
| exams | description | text | NULLABLE |
| exams | exam_date | date | |
| exams | exam_time | time | NULLABLE |
| exams | duration_minutes | unsigned smallint | DEFAULT: 90 |
| exams | max_score | int | DEFAULT: 100 |
| exams | created_at | timestamp | |
| exams | updated_at | timestamp | |
| exam_questions | id | bigint | PK |
| exam_questions | exam_id | bigint | FK->exams.id, CASCADE |
| exam_questions | question_text | text | |
| exam_questions | question_type | varchar(20) | DEFAULT: short_answer |
| exam_questions | points | unsigned int | DEFAULT: 1 |
| exam_questions | position | unsigned int | DEFAULT: 1 |
| exam_questions | created_at | timestamp | |
| exam_questions | updated_at | timestamp | |
| exam_questions | (exam_id, position) | composite | INDEXED |
| exam_answers | id | bigint | PK |
| exam_answers | exam_id | bigint | FK->exams.id, CASCADE |
| exam_answers | exam_question_id | bigint | FK->exam_questions.id, CASCADE |
| exam_answers | student_id | bigint | FK->students.id, CASCADE |
| exam_answers | answer_text | longtext | |
| exam_answers | created_at | timestamp | |
| exam_answers | updated_at | timestamp | |
| exam_answers | (exam_question_id, student_id) | composite | UNIQUE |
| exam_answers | (exam_id, student_id) | composite | INDEXED |
| exam_results | id | bigint | PK |
| exam_results | student_id | bigint | FK->students.id, CASCADE |
| exam_results | exam_id | bigint | FK->exams.id, CASCADE |
| exam_results | score | int | |
| exam_results | remarks | text | NULLABLE |
| exam_results | created_at | timestamp | |
| exam_results | updated_at | timestamp | |
| invitations | id | bigint | PK |
| invitations | token | varchar | UNIQUE |
| invitations | role | enum(teacher, student) | |
| invitations | course_id | bigint | FK->courses.id, SET NULL, NULLABLE |
| invitations | invited_by | bigint | FK->users.id, CASCADE |
| invitations | used | boolean | DEFAULT: false |
| invitations | max_uses | unsigned int | NULLABLE |
| invitations | uses_count | unsigned int | DEFAULT: 0 |
| invitations | expires_at | timestamp | |
| invitations | created_at | timestamp | |
| invitations | updated_at | timestamp | |
| calendar_events | id | bigint | PK |
| calendar_events | user_id | bigint | FK->users.id, CASCADE |
| calendar_events | course_id | bigint | FK->courses.id, SET NULL, NULLABLE |
| calendar_events | title | varchar | |
| calendar_events | event_type | enum(assignment, quiz, exam, other) | DEFAULT: other |
| calendar_events | event_date | date | |
| calendar_events | description | text | NULLABLE |
| calendar_events | created_at | timestamp | |
| calendar_events | updated_at | timestamp | |
| course_modules | id | bigint | PK |
| course_modules | course_id | bigint | FK->courses.id, CASCADE |
| course_modules | title | varchar | |
| course_modules | description | text | NULLABLE |
| course_modules | position | unsigned int | DEFAULT: 0 |
| course_modules | lesson_count | unsigned int | DEFAULT: 0 |
| course_modules | assignment_count | unsigned int | DEFAULT: 0 |
| course_modules | quiz_count | unsigned int | DEFAULT: 0 |
| course_modules | is_active | boolean | DEFAULT: true |
| course_modules | created_at | timestamp | |
| course_modules | updated_at | timestamp | |
| course_modules | (course_id, position) | composite | INDEXED |
| course_module_items | id | bigint | PK |
| course_module_items | course_module_id | bigint | FK->course_modules.id, CASCADE |
| course_module_items | type | varchar(40) | |
| course_module_items | title | varchar | |
| course_module_items | content | text | NULLABLE |
| course_module_items | position | unsigned int | DEFAULT: 0 |
| course_module_items | created_by | bigint | FK->users.id, SET NULL, NULLABLE |
| course_module_items | is_active | boolean | DEFAULT: true |
| course_module_items | created_at | timestamp | |
| course_module_items | updated_at | timestamp | |
| course_module_items | (course_module_id, type) | composite | INDEXED |
| course_module_items | (course_module_id, position) | composite | INDEXED |

## 2) Framework/System Tables (Laravel)

| Topic | Field | Type | Key |
|-------|-------|------|-----|
| password_reset_tokens | email | varchar | PK |
| password_reset_tokens | token | varchar | |
| password_reset_tokens | created_at | timestamp | NULLABLE |
| sessions | id | varchar | PK |
| sessions | user_id | bigint | NULLABLE, INDEXED |
| sessions | ip_address | varchar(45) | NULLABLE |
| sessions | user_agent | text | NULLABLE |
| sessions | payload | longtext | |
| sessions | last_activity | int | INDEXED |
| cache | key | varchar | PK |
| cache | value | mediumtext | |
| cache | expiration | int | INDEXED |
| cache_locks | key | varchar | PK |
| cache_locks | owner | varchar | |
| cache_locks | expiration | int | INDEXED |
| jobs | id | bigint | PK |
| jobs | queue | varchar | INDEXED |
| jobs | payload | longtext | |
| jobs | attempts | unsigned tinyint | |
| jobs | reserved_at | unsigned int | NULLABLE |
| jobs | available_at | unsigned int | |
| jobs | created_at | unsigned int | |
| job_batches | id | varchar | PK |
| job_batches | name | varchar | |
| job_batches | total_jobs | int | |
| job_batches | pending_jobs | int | |
| job_batches | failed_jobs | int | |
| job_batches | failed_job_ids | longtext | |
| job_batches | options | mediumtext | NULLABLE |
| job_batches | cancelled_at | int | NULLABLE |
| job_batches | created_at | int | |
| job_batches | finished_at | int | NULLABLE |
| failed_jobs | id | bigint | PK |
| failed_jobs | uuid | varchar | UNIQUE |
| failed_jobs | connection | text | |
| failed_jobs | queue | text | |
| failed_jobs | payload | longtext | |
| failed_jobs | exception | longtext | |
| failed_jobs | failed_at | timestamp | |
| password_resets | id | bigint | PK |
| password_resets | email | varchar | INDEXED |
| password_resets | token | varchar | |
| password_resets | created_at | timestamp | NULLABLE |

## 3) Key Relationships (Quick View)
- users 1-1 students
- users 1-1 teachers
- courses 1-many students
- courses many-many teachers (via course_teacher)
- courses 1-many assignments
- courses 1-many exams
- students 1-many submissions
- assignments 1-many submissions
- students 1-many exam_results
- exams 1-many exam_results
- exams 1-many exam_questions
- exam_questions 1-many exam_answers
- students 1-many exam_answers
- courses 1-many course_modules
- course_modules 1-many course_module_items

## 4) Important Note
- The Student model includes a class field, but current migration files do not create a students.class column. If needed, add a migration for this column before deployment.
