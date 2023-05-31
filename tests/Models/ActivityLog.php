<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 參與活動紀錄表
 */
class ActivityLog extends Model
{
    protected $table = 'activity_log';

    /**
     * @return BelongsTo
     */
    public function creditLog(): BelongsTo
    {
        return $this->belongsTo(CreditLog::class, 'id', 'credit_log_id');
    }
}