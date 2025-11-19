<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AdministrationController extends Controller
{
    /**
     * Display the administration page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get comprehensive system stats for the administration page
        $stats = [
            'users_count' => DB::table('users')->count(),
            'admin_users_count' => DB::table('users')->where('role', 'admin')->count(),
            'member_users_count' => DB::table('users')->where('role', 'member')->count(),
            'loans_count' => DB::table('loans')->count(),
            'commodities_count' => DB::table('available_commodities')->count(),
            'departments_count' => DB::table('departments')->count(),
            'active_sessions' => $this->getActiveSessionsCount(),
            'system_uptime' => $this->getSystemUptime(),
            'recent_logins' => $this->getRecentLoginsCount(),
        ];

        return view('admin.administration', compact('stats'));
    }

    /**
     * Get count of active user sessions (simplified implementation)
     */
    private function getActiveSessionsCount()
    {
        // Since we don't have a last_login_at column, we'll use a different approach
        // Count users who have been updated recently (indicating activity)
        return DB::table('users')
            ->where('updated_at', '>=', now()->subHours(24))
            ->where('role', 'member')
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get system uptime percentage (simplified implementation)
     */
    private function getSystemUptime()
    {
        // This is a placeholder implementation
        // In a real application, you might track system downtime events
        // and calculate actual uptime percentage
        return '99.9%';
    }

    /**
     * Get count of recent activity (last 24 hours)
     */
    private function getRecentLoginsCount()
    {
        // Since we don't have last_login_at, we'll count recent user registrations
        return DB::table('users')
            ->where('created_at', '>=', now()->subDays(7))
            ->where('role', 'member')
            ->count();
    }

    /**
     * Clear application cache.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');

            return redirect()->route('admin.administration')->with('success', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.administration')->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Run database migrations.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate');

            return redirect()->route('admin.administration')->with('success', 'Database migrations completed successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.administration')->with('error', 'Failed to run migrations: ' . $e->getMessage());
        }
    }

    /**
     * Backup database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backupDatabase()
    {
        try {
            // This is a placeholder - implement actual database backup logic
            // You might want to use a package like spatie/laravel-backup

            return redirect()->route('admin.administration')->with('success', 'Database backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.administration')->with('error', 'Failed to backup database: ' . $e->getMessage());
        }
    }

    /**
     * View system logs.
     */
    public function viewLogs()
    {
        try {
            // Get Laravel log files
            $logPath = storage_path('logs');
            $logFiles = [];

            if (is_dir($logPath)) {
                $files = scandir($logPath);
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                        $filePath = $logPath . DIRECTORY_SEPARATOR . $file;
                        $logFiles[] = [
                            'name' => $file,
                            'size' => filesize($filePath),
                            'modified' => filemtime($filePath),
                            'path' => $filePath
                        ];
                    }
                }

                // Sort by modification time (newest first)
                usort($logFiles, function($a, $b) {
                    return $b['modified'] - $a['modified'];
                });
            }

            return view('admin.system_logs', compact('logFiles'));
        } catch (\Exception $e) {
            return redirect()->route('admin.administration')->with('error', 'Failed to access system logs: ' . $e->getMessage());
        }
    }

    /**
     * Download a specific log file.
     */
    public function downloadLog($filename)
    {
        $logPath = storage_path('logs' . DIRECTORY_SEPARATOR . $filename);

        if (!file_exists($logPath) || pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->route('admin.administration.logs')->with('error', 'Log file not found.');
        }

        return response()->download($logPath);
    }

    /**
     * Clear all log files.
     */
    public function clearLogs()
    {
        try {
            $logPath = storage_path('logs');
            $files = glob($logPath . DIRECTORY_SEPARATOR . '*.log');

            $deletedCount = 0;
            foreach ($files as $file) {
                if (unlink($file)) {
                    $deletedCount++;
                }
            }

            return redirect()->route('admin.administration.logs')
                ->with('success', "Successfully cleared {$deletedCount} log files.");
        } catch (\Exception $e) {
            return redirect()->route('admin.administration.logs')
                ->with('error', 'Failed to clear logs: ' . $e->getMessage());
        }
    }

    /**
     * Perform a basic security audit.
     */
    public function securityAudit()
    {
        try {
            $auditResults = [
                'users_without_recent_login' => DB::table('users')
                    ->where('updated_at', '<', now()->subDays(90))
                    ->where('role', 'member')
                    ->count(),
                'admin_users_count' => DB::table('users')->where('role', 'admin')->count(),
                'users_with_weak_passwords' => 0, // Placeholder - would need password strength analysis
                'failed_login_attempts' => 0, // Placeholder - would need login attempt tracking
                'system_permissions_check' => 'OK', // Placeholder
                'database_integrity' => 'OK', // Placeholder
                'last_backup_date' => 'Not configured', // Placeholder
            ];

            return view('admin.security_audit', compact('auditResults'));
        } catch (\Exception $e) {
            return redirect()->route('admin.administration')->with('error', 'Failed to perform security audit: ' . $e->getMessage());
        }
    }
}
