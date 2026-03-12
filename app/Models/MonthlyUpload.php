<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MonthlyUpload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year',
        'month',
        'upload_type',
        'file_name',
        'file_path',
        'total_records',
        'processed_records',
        'failed_records',
        'update_fields',
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
        'update_fields' => 'array',
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
     * Check if a monthly upload already exists for the given year, month, and type.
     */
    public static function existsForMonth(int $year, int $month, string $uploadType = 'monthly_contributions'): bool
    {
        return self::where('year', $year)
            ->where('month', $month)
            ->where('upload_type', $uploadType)
            ->exists();
    }

    /**
     * Get the latest upload for a specific month and type.
     */
    public static function getForMonth(int $year, int $month, string $uploadType = 'monthly_contributions'): ?self
    {
        return self::where('year', $year)
            ->where('month', $month)
            ->where('upload_type', $uploadType)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Get the month name.
     */
    public function getMonthNameAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F');
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
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
     * Check if the upload has been reversed.
     */
    public function isReversed(): bool
    {
        return $this->status === 'reversed';
    }

    /**
     * Check if the upload can be reversed.
     */
    public function canBeReversed(): bool
    {
        // Only completed uploads can be reversed
        if ($this->status !== 'completed') {
            return false;
        }

        // Only the most recent upload (by absolute creation order) can be reversed
        $latestUpload = self::orderBy('id', 'desc')
            ->first();

        return $latestUpload && $latestUpload->id === $this->id;
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
}
