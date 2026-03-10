# Fix Missing Student/Teacher Profiles

## Problem
Some users were created without corresponding student or teacher records, causing errors when they try to access their dashboards.

## Solution 1: Manual Database Fix (Quick)

Run these SQL queries in your database to create missing records:

### For Students (user_id 2 in your case)
```sql
-- Check which users need student records
SELECT u.id, u.name, u.email, u.role 
FROM users u 
LEFT JOIN students s ON u.id = s.user_id 
WHERE u.role = 'student' AND s.id IS NULL;

-- Create missing student records
INSERT INTO students (user_id, student_id, name, email, class, created_at, updated_at)
SELECT 
    id,
    CONCAT('STU', LPAD(id, 5, '0')) as student_id,
    name,
    email,
    'Not Assigned' as class,
    NOW(),
    NOW()
FROM users 
WHERE role = 'student' 
AND id NOT IN (SELECT user_id FROM students);
```

### For Teachers
```sql
-- Check which users need teacher records
SELECT u.id, u.name, u.email, u.role 
FROM users u 
LEFT JOIN teachers t ON u.id = t.user_id 
WHERE u.role = 'teacher' AND t.id IS NULL;

-- Create missing teacher records
INSERT INTO teachers (user_id, teacher_id, name, email, subject, created_at, updated_at)
SELECT 
    id,
    CONCAT('TCH', LPAD(id, 5, '0')) as teacher_id,
    name,
    email,
    'Not Assigned' as subject,
    NOW(),
    NOW()
FROM users 
WHERE role = 'teacher' 
AND id NOT IN (SELECT user_id FROM teachers);
```

## Solution 2: Delete and Recreate Users (Clean Slate)

If you want to start fresh:

```sql
-- Delete all non-admin users
DELETE FROM users WHERE role != 'admin';

-- This will cascade delete students, teachers, submissions, etc.
```

Then have users register again using the invitation links.

## Solution 3: Update Existing User

For user ID 2 specifically:

```sql
-- Create student record for user ID 2
INSERT INTO students (user_id, student_id, name, email, class, created_at, updated_at)
VALUES (
    2,
    'STU00002',
    (SELECT name FROM users WHERE id = 2),
    (SELECT email FROM users WHERE id = 2),
    'Class 10A',
    NOW(),
    NOW()
);
```

## Prevention

Going forward, always use the invitation system to create new users:
1. Admin creates invitation link
2. User clicks link and fills in ALL details
3. System creates both user AND student/teacher record

## Verify Fix

After running the SQL, verify with:

```sql
-- Check all users have proper records
SELECT 
    u.id,
    u.name,
    u.role,
    CASE 
        WHEN u.role = 'student' THEN s.student_id
        WHEN u.role = 'teacher' THEN t.teacher_id
        ELSE 'N/A'
    END as profile_id
FROM users u
LEFT JOIN students s ON u.id = s.user_id AND u.role = 'student'
LEFT JOIN teachers t ON u.id = t.user_id AND u.role = 'teacher'
ORDER BY u.id;
```

All students should have a student_id, all teachers should have a teacher_id.
