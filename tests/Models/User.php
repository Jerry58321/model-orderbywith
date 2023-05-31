<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 使用者表
 */
class User extends Model
{
    protected $table = 'user';

    /**
     * @return HasMany
     */
    public function activityLog(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}