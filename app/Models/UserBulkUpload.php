<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserBulkUpload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_name',
        'file_path',
        'total_records',
        'processed_records',
        'failed_records',
        'description',
        'status',
        'uploaded_by',
        'upload_started_at',
        'upload_completed_at',
        'error_message',
        'processing_summary',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processing_summary' => 'array',
        'upload_started_at' => 'datetime',
        'upload_completed_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Check if the upload was successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed' && $this->failed_records === 0;
    }

    /**
     * Check if the upload has errors.
     */
    public function hasErrors(): bool
    {
        return $this->failed_records > 0 || $this->status === 'failed';
    }

    /**
     * Get the success rate as a percentage.
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_records === 0) {
            return 0;
        }

        return round(($this->processed_records / $this->total_records) * 100, 2);
    }

    /**
     * Get the formatted upload date.
     */
    public function getFormattedUploadDateAttribute(): string
    {
        return $this->created_at->format('M d, Y \a\t g:i A');
    }

    /**
     * Mark the upload as started.
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'processing',
            'upload_started_at' => now(),
        ]);
    }

    /**
     * Mark the upload as completed.
     */
    public function markAsCompleted(array $summary = []): void
    {
        $this->update([
            'status' => 'completed',
            'upload_completed_at' => now(),
            'processing_summary' => $summary,
        ]);
    }

    /**
     * Mark the upload as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'upload_completed_at' => now(),
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Get the status badge class for UI display.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => $this->failed_records > 0
                ? 'bg-yellow-100 text-yellow-800'
                : 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'pending' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the status display text.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'completed' => $this->failed_records > 0
                ? 'Completed with Errors'
                : 'Completed Successfully',
            'failed' => 'Failed',
            'processing' => 'Processing',
            'pending' => 'Pending',
            default => 'Unknown',
        };
    }
}
