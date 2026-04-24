<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupOrphanedRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-orphaned-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned records from database when users are deleted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up orphaned records...');
        
        // Clean up orphaned student records
        $orphanedStudents = \App\Models\Student::whereDoesntHave('user')->count();
        if ($orphanedStudents > 0) {
            \App\Models\Student::whereDoesntHave('user')->delete();
            $this->info("Deleted {$orphanedStudents} orphaned student records");
        }
        
        // Clean up orphaned teacher records
        $orphanedTeachers = \App\Models\Teacher::whereDoesntHave('user')->count();
        if ($orphanedTeachers > 0) {
            \App\Models\Teacher::whereDoesntHave('user')->delete();
            $this->info("Deleted {$orphanedTeachers} orphaned teacher records");
        }
        
        // Clean up orphaned course modules
        $orphanedModules = \App\Models\CourseModule::whereDoesntHave('teacher')->count();
        if ($orphanedModules > 0) {
            \App\Models\CourseModule::whereDoesntHave('teacher')->delete();
            $this->info("Deleted {$orphanedModules} orphaned course module records");
        }
        
        // Clean up orphaned assignments
        $orphanedAssignments = \App\Models\Assignment::whereDoesntHave('teacher')->count();
        if ($orphanedAssignments > 0) {
            \App\Models\Assignment::whereDoesntHave('teacher')->delete();
            $this->info("Deleted {$orphanedAssignments} orphaned assignment records");
        }
        
        // Clean up orphaned exam results
        $orphanedExamResults = \App\Models\ExamResult::whereDoesntHave('student')->count();
        if ($orphanedExamResults > 0) {
            \App\Models\ExamResult::whereDoesntHave('student')->delete();
            $this->info("Deleted {$orphanedExamResults} orphaned exam result records");
        }
        
        $this->info('Orphaned records cleanup completed!');
        
        return 0;
    }
}
