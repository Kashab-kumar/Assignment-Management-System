-- Fix Missing Student and Teacher Records
-- Run this in phpMyAdmin SQL tab

-- 1. Check which users are missing their profile records
SELECT 
    u.id,
    u.name,
    u.email,
    u.role,
    CASE 
        WHEN u.role = 'student' AND s.id IS NULL THEN 'MISSING STUDENT RECORD'
        WHEN u.role = 'teacher' AND t.id IS NULL THEN 'MISSING TEACHER RECORD'
        ELSE 'OK'
    END as status
FROM users u
LEFT JOIN students s ON u.id = s.user_id AND u.role = 'student'
LEFT JOIN teachers t ON u.id = t.user_id AND u.role = 'teacher'
WHERE u.role != 'admin';

-- 2. Create missing STUDENT records
INSERT INTO students (user_id, student_id, name, email, class, created_at, updated_at)
SELECT 
    u.id,
    CONCAT('STU', LPAD(u.id, 5, '0')) as student_id,
    u.name,
    u.email,
    'Not Assigned' as class,
    NOW(),
    NOW()
FROM users u
WHERE u.role = 'student' 
AND u.id NOT IN (SELECT user_id FROM students);

-- 3. Create missing TEACHER records
INSERT INTO teachers (user_id, teacher_id, name, email, subject, created_at, updated_at)
SELECT 
    u.id,
    CONCAT('TCH', LPAD(u.id, 5, '0')) as teacher_id,
    u.name,
    u.email,
    'Not Assigned' as subject,
    NOW(),
    NOW()
FROM users u
WHERE u.role = 'teacher' 
AND u.id NOT IN (SELECT user_id FROM teachers);

-- 4. Verify all users now have proper records
SELECT 
    u.id,
    u.name,
    u.email,
    u.role,
    COALESCE(s.student_id, t.teacher_id, 'N/A') as profile_id,
    CASE 
        WHEN u.role = 'student' AND s.id IS NOT NULL THEN '✓ OK'
        WHEN u.role = 'teacher' AND t.id IS NOT NULL THEN '✓ OK'
        WHEN u.role = 'admin' THEN '✓ OK (Admin)'
        ELSE '✗ STILL MISSING'
    END as status
FROM users u
LEFT JOIN students s ON u.id = s.user_id
LEFT JOIN teachers t ON u.id = t.user_id
ORDER BY u.id;
