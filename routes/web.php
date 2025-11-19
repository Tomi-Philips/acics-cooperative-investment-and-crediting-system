<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/testimonial', function () {
    return view('pages.testimonial');
})->name('testimonial');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/cookie-policy', function () {
    return view('pages.cookie');
})->name('cookie_policy');

Route::get('/business-rules', function () {
    return view('pages.business_rules');
})->name('business_rules');

// Temporary route to check user role
Route::get('/check-role', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    return response()->json([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'is_admin' => $user->isAdmin(),
    ]);
})->middleware(['auth'])->name('check-role');

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// ======================================== Authentication Routes
Route::controller(App\Http\Controllers\Auth\AuthController::class)->group(function () {
    // Login routes
    Route::get('/auth/login', 'showLoginForm')->name('login');
    Route::post('/auth/login', 'login');
    Route::post('/auth/logout', 'logout')->name('logout');

    // Password reset routes
    Route::get('/auth/reset', 'showResetRequestForm')->name('reset');
    Route::post('/auth/reset', 'processResetRequest')->name('reset.post');
    Route::get('/auth/set-password', 'showSetPasswordForm')->name('set_password');
    Route::post('/auth/set-password', 'processSetPassword')->name('set_password.post');

    // Membership registration routes
    Route::get('/auth/membership-registration', 'showMembershipRegistrationForm')->name('membership_registration');
    Route::post('/auth/membership-registration', 'processMembershipRegistration')->name('membership_registration.post');
    Route::get('/auth/membership-success', function () {
        return view('auth.membership_success', [
            'reference_number' => session('reference_number', 'N/A')
        ]);
    })->name('membership_success');

    // Application status checking routes
    Route::get('/auth/application-status', [\App\Http\Controllers\Auth\ApplicationStatusController::class, 'showStatusCheckForm'])->name('application.status');
    Route::post('/auth/application-status', [\App\Http\Controllers\Auth\ApplicationStatusController::class, 'checkStatus'])->name('application.status.check');
    Route::get('/auth/application-status/check/{email}', [\App\Http\Controllers\Auth\ApplicationStatusController::class, 'checkStatusByEmail'])->name('application.status.check.email');
});


// ===================== USER ROUTES =====================
Route::prefix('user')->as('user.')->middleware(['auth', 'App\Http\Middleware\CheckRole:member'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Loan Management
    Route::controller(App\Http\Controllers\User\LoanController::class)->group(function () {
        Route::get('/loan_board', 'index')->name('loan_board');
        Route::get('/loan_application', 'showApplicationForm')->name('loan_application');
        Route::post('/loan_application', 'processApplication')->name('loan_application.post');
        Route::get('/loan/{id}', 'show')->name('loan.show');
    });

    // Commodity Management
    Route::controller(App\Http\Controllers\User\CommodityController::class)->group(function () {
        Route::get('/commodity', 'index')->name('commodity'); // User's commodity balance
        Route::get('/commodity/marketplace', 'marketplace')->name('commodity.marketplace'); // Available commodities
        Route::get('/view_commodity/{id}', 'show')->name('view_commodity');
    });

    // Support Tickets
    Route::controller(App\Http\Controllers\User\SupportController::class)->group(function () {
        Route::get('/support', 'index')->name('support');
        Route::get('/ticket/{id}', 'show')->name('support.show');
        Route::post('/ticket', 'store')->name('support.store');
        Route::post('/ticket/{id}/reply', 'reply')->name('support.reply');
        Route::patch('/ticket/{id}/close', 'close')->name('support.close');
    });

    // Saving Withdrawals
    Route::controller(App\Http\Controllers\SavingWithdrawalController::class)->group(function () {
        Route::get('/saving-withdrawals/create', 'create')->name('saving_withdrawals.create');
        Route::post('/saving-withdrawals', 'store')->name('saving_withdrawals.store');
    });

    // Transaction Reports
    Route::controller(App\Http\Controllers\User\TransactionController::class)->group(function () {
        Route::get('/transaction_report', 'index')->name('transaction_report');
        Route::get('/transaction/{id}', 'show')->name('transaction.show');
        Route::get('/transaction_report/pdf', 'generatePdf')->name('transaction_report.pdf');
        Route::get('/transaction_report/excel', 'exportExcel')->name('transaction_report.excel');
    });

    // Profile and Settings
    Route::controller(App\Http\Controllers\User\ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/profile/update', 'updateProfile')->name('profile.update');
        Route::post('/profile/next-of-kin', 'updateNextOfKin')->name('profile.update_next_of_kin');
        Route::post('/settings/password', 'updatePassword')->name('settings.update_password');
        Route::post('/settings/notifications', 'updateNotifications')->name('settings.update_notifications');
    });

    // Redirect old about page to new profile page
    Route::get('/about', function () {
        return redirect()->route('user.profile');
    })->name('about_us');
});
Route::get('/financial-status', [App\Http\Controllers\User\DashboardController::class, 'getFinancialStatus'])->name('financial_status');

// Export route moved to admin group

/// ===================== ADMIN ROUTES =====================
Route::prefix('admin')->as('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');



    // Users Management
    Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
        Route::get('/users/add', 'create')->name('users.add');
        Route::get('/users/add-finances', 'createFinances')->name('users.add_finances');
        Route::post('/users/add-finances', 'storeFinances')->name('users.store_finances');
        Route::get('/users/bulk-upload', 'createBulkUpload')->name('users.bulk_upload');
        Route::post('/users/bulk-upload', 'bulkUpload')->name('users.bulk_upload.store');
        Route::post('/users/add', 'store')->name('users.store');
        Route::get('/users/all', 'index')->name('users.all');
        Route::get('/users/view/{id}', 'show')->name('users.view');
        Route::get('/users/edit/{id}', 'edit')->name('users.edit');
        Route::put('/users/edit/{id}', 'update')->name('users.update');
        Route::get('/users/edit-finances/{id}', 'editFinances')->name('users.edit_finances');
        Route::put('/users/edit-finances/{id}', 'updateFinances')->name('users.update_finances');
        Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        Route::get('/users/bulk-upload/validation-errors/{session_id}', 'showValidationErrors')->name('users.bulk_upload_validation_errors');
        Route::post('/users/bulk-upload/process', 'processBulkUpload')->name('users.bulk_upload.process');
        Route::get('/users/bulk-upload/details/{id}', 'viewBulkUploadDetails')->name('users.bulk_upload_details');
        Route::get('/users/download-template', 'downloadTemplate')->name('users.download_template');
        Route::get('/users/export-financial-data', 'exportFinancialData')->name('users.export_financial_data');
    });

    // Manual Transactions (temporarily without CSRF for testing)
    Route::controller(App\Http\Controllers\Admin\ManualTransactionController::class)->group(function () {
        Route::get('/manual-transactions', 'index')->name('manual_transactions.index');
        Route::get('/manual-transactions/create', 'create')->name('manual_transactions.create');
        Route::post('/manual-transactions', 'store')->name('manual_transactions.store');
        Route::get('/manual-transactions/bulk', 'bulkCreate')->name('manual_transactions.bulk');
        Route::post('/manual-transactions/bulk', 'bulkStore')->name('manual_transactions.bulk_store')->withoutMiddleware(['App\Http\Middleware\VerifyCsrfToken']);
        Route::get('/manual-transactions/template', 'downloadTemplate')->name('manual_transactions.template');
        Route::get('/manual-transactions/search-members', 'searchMembers')->name('manual_transactions.search_members');
    });

    // Pending Memberships
    Route::controller(App\Http\Controllers\Admin\PendingMembershipController::class)->group(function () {
        Route::get('/pending-memberships', 'index')->name('pending-memberships');
        Route::get('/pending-memberships/{id}', 'show')->name('pending-memberships.view');
        Route::post('/pending-memberships/{id}/verify', 'verify')->name('pending-memberships.verify');
        Route::post('/pending-memberships/{id}/approve', 'approve')->name('pending-memberships.approve');
        Route::post('/pending-memberships/{id}/reject', 'reject')->name('pending-memberships.reject');
    });

    // Departments
    Route::controller(App\Http\Controllers\Admin\DepartmentController::class)->group(function () {
        Route::get('/departments', 'index')->name('departments.all');
        Route::get('/departments/add', 'create')->name('departments.add');
        Route::get('/departments/download-template', 'downloadTemplate')->name('departments.download_template');
        Route::post('/departments', 'store')->name('departments.store');
        Route::post('/departments/bulk-upload', 'bulkUpload')->name('departments.bulk_upload');
        Route::get('/departments/{id}', 'show')->name('departments.view');
        Route::get('/departments/{id}/edit', 'edit')->name('departments.edit');
        Route::put('/departments/{id}', 'update')->name('departments.update');
        Route::delete('/departments/{id}', 'destroy')->name('departments.destroy');
    });

    // Loan Management
    Route::controller(App\Http\Controllers\Admin\LoanController::class)->group(function () {
        Route::get('/loans', 'index')->name('loans.index');
        // Redirect legacy Add Loan page to Manual Transactions create
        Route::get('/loans/create', function() {
            return redirect()->route('admin.manual_transactions.create');
        })->name('loans.create');
        Route::post('/loans', 'store')->name('loans.store');
        Route::get('/loans/approval', 'pendingApprovals')->name('loans.approval');
        Route::get('/loans/pending-approvals', 'pendingApprovals')->name('loans.pending-approvals');
        Route::get('/loans/calculator', 'calculator')->name('loans.calculator');
        Route::post('/loans/{loan}/approve', 'approve')->name('loans.approve');
        Route::post('/loans/{loan}/reject', 'reject')->name('loans.reject');
        Route::get('/loans/{loan}', 'show')->name('loans.show');
    });

    // Commodity Management
    Route::resource('commodities', App\Http\Controllers\Admin\CommodityController::class);

    // Saving Withdrawals
    Route::controller(App\Http\Controllers\SavingWithdrawalController::class)->group(function () {
        Route::get('/saving-withdrawals', 'index')->name('saving_withdrawals.index');
        Route::put('/saving-withdrawals/{savingWithdrawal}', 'update')->name('saving_withdrawals.update');
    });



    // Support Tickets
    Route::controller(App\Http\Controllers\Admin\SupportTicketController::class)->group(function () {
        Route::get('/tickets/open', 'openTickets')->name('tickets.open');
        Route::get('/tickets/closed', 'closedTickets')->name('tickets.closed');
        Route::get('/tickets/{id}/reply', 'showReply')->name('tickets.show_reply');
        Route::post('/tickets/{id}/close', 'closeTicket')->name('tickets.close');
        Route::post('/tickets/{id}/reopen', 'reopenTicket')->name('tickets.reopen');
        Route::post('/tickets/{id}/reply', 'storeReply')->name('tickets.store_reply');
    });

    // Reports
    Route::view('/reports', 'admin.report')->name('reports');

    // System Users
    Route::view('/system-users', 'admin.system_user')->name('system_users');

    // Settings route removed

    // Admin Profile
    Route::controller(App\Http\Controllers\Admin\ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::get('/profile/change-password', 'showChangePasswordForm')->name('profile.change_password');
        Route::put('/profile/password', 'updatePassword')->name('profile.update_password');
    });

    // Business Rules
    Route::controller(App\Http\Controllers\Admin\BusinessRulesController::class)->group(function () {
        Route::get('/business-rules', 'index')->name('business_rules');
        Route::post('/business-rules', 'store')->name('business_rules.store');
        Route::put('/business-rules/{id}', 'update')->name('business_rules.update');
        Route::delete('/business-rules/{id}', 'destroy')->name('business_rules.destroy');
    });

    // Administration
    Route::controller(App\Http\Controllers\Admin\AdministrationController::class)->group(function () {
        Route::get('/administration', 'index')->name('administration');
        Route::post('/administration/clear-cache', 'clearCache')->name('administration.clear_cache');
        Route::post('/administration/run-migrations', 'runMigrations')->name('administration.run_migrations');
        Route::post('/administration/backup-database', 'backupDatabase')->name('administration.backup_database');
        Route::get('/administration/logs', 'viewLogs')->name('administration.logs');
        Route::get('/administration/logs/download/{filename}', 'downloadLog')->name('administration.logs.download');
        Route::delete('/administration/logs/clear', 'clearLogs')->name('administration.logs.clear');
        Route::get('/administration/security-audit', 'securityAudit')->name('administration.security_audit');
    });

    // Mail previews
    Route::get('/mail-previews', [App\Http\Controllers\PreviewController::class, 'index'])->name('mail_previews');
    Route::get('/mail-previews/{filename}', [App\Http\Controllers\PreviewController::class, 'show'])->name('preview.show');

    // System Users
    Route::controller(App\Http\Controllers\Admin\SystemUserController::class)->group(function () {
        Route::get('/system-users', 'index')->name('system_users');
        Route::post('/system-users', 'store')->name('system_users.store');
        Route::get('/system-users/{id}/edit', 'edit')->name('system_users.edit');
        Route::put('/system-users/{id}', 'update')->name('system_users.update');
        Route::post('/system-users/{id}/reset-password', 'resetPassword')->name('system_users.reset_password');
        Route::delete('/system-users/{id}', 'destroy')->name('system_users.destroy');
    });

    // Support Tickets (Alternative Controller - Renamed to avoid conflicts)
    Route::controller(App\Http\Controllers\Admin\TicketController::class)->group(function () {
        Route::get('/tickets/alt/open', 'openTickets')->name('tickets.alt.open');
        Route::get('/tickets/alt/closed', 'closedTickets')->name('tickets.alt.closed');
        Route::get('/tickets/alt/reply/{id}', 'showForReply')->name('tickets.alt.show_reply');
        Route::post('/tickets/alt/reply/{id}', 'storeReply')->name('tickets.alt.store_reply');
        Route::post('/tickets/alt/close/{id}', 'closeTicket')->name('tickets.alt.close');
        Route::post('/tickets/alt/reopen/{id}', 'reopenTicket')->name('tickets.alt.reopen');
    });

    // Transactions
    Route::controller(App\Http\Controllers\Admin\TransactionController::class)->group(function () {
        Route::get('/transactions', 'index')->name('transactions');
        Route::get('/transactions/create', 'create')->name('transactions.create');
        Route::post('/transactions', 'store')->name('transactions.store');
        Route::get('/transactions/{id}', 'show')->name('transactions.show');
        Route::post('/transactions/report', 'generateReport')->name('transactions.report');
    });

    // Manual Transactions
    Route::controller(App\Http\Controllers\Admin\ManualTransactionController::class)->group(function () {
        Route::get('/manual-transactions', 'index')->name('manual_transactions.index');
        Route::get('/manual-transactions/create', 'create')->name('manual_transactions.create');
        Route::post('/manual-transactions', 'store')->name('manual_transactions.store');
        Route::get('/manual-transactions/bulk', 'bulkCreate')->name('manual_transactions.bulk');
        Route::post('/manual-transactions/bulk', 'bulkStore')->name('manual_transactions.bulk_store');
        Route::get('/manual-transactions/template', 'downloadTemplate')->name('manual_transactions.template');
        Route::get('/manual-transactions/search-members', 'searchMembers')->name('manual_transactions.search_members');
        Route::get('/manual-transactions/member-details/{userId}', 'getMemberDetails')->name('manual_transactions.member_details');
    });

    // Reports
    Route::controller(App\Http\Controllers\Admin\ReportController::class)->group(function () {
        Route::get('/reports', 'index')->name('reports');
        Route::get('/reports/users', 'users')->name('reports.users');
        Route::get('/reports/loans', 'loans')->name('reports.loans');
        Route::get('/reports/transactions', 'transactions')->name('reports.transactions');
        Route::get('/reports/export', 'export')->name('reports.export');
    });

    // Departments routes are defined above with the controller

    // FAQs
    Route::resource('faqs', App\Http\Controllers\Admin\FaqController::class);
    Route::resource('events', App\Http\Controllers\Admin\EventController::class);
    Route::post('faqs/categories', [App\Http\Controllers\Admin\FaqController::class, 'storeCategory'])->name('faqs.storeCategory');
    // Bulk Updates
    Route::get('/bulk-updates', 'App\Http\Controllers\Admin\BulkUpdateController@index')->name('bulk_updates');
    Route::post('/bulk-updates/upload', 'App\Http\Controllers\Admin\BulkUpdateController@upload')->name('bulk_updates.upload');
    Route::post('/bulk-updates/process', 'App\Http\Controllers\Admin\BulkUpdateController@process')->name('bulk_updates.process');
    Route::get('/bulk-updates/template', 'App\Http\Controllers\Admin\BulkUpdateController@downloadTemplate')->name('bulk_updates.template');
    Route::get('/bulk-updates/success/{upload}', 'App\Http\Controllers\Admin\BulkUpdateController@showSuccess')->name('bulk_updates.success');
    Route::get('/bulk-updates/transactions/{upload}', 'App\Http\Controllers\Admin\BulkUpdateController@showTransactions')->name('bulk_updates.transactions');
    Route::get('/bulk-updates/integrity/{upload}', 'App\Http\Controllers\Admin\BulkUpdateController@verifyIntegrity')->name('bulk_updates.integrity');
    Route::get('/bulk-updates/details/{id}', 'App\Http\Controllers\Admin\BulkUpdateController@viewDetails')->name('bulk_updates.details');
    Route::delete('/bulk-updates/{upload}/reverse', 'App\Http\Controllers\Admin\BulkUpdateController@reverseUpload')->name('bulk_updates.reverse');

    // Simple bulk upload test
    Route::match(['GET', 'POST'], '/simple-bulk-test', 'App\Http\Controllers\Admin\SimpleBulkController@testUpload')->name('admin.simple_bulk_test');
});
