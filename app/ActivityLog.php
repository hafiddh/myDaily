<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ActivityLog extends Model
{
    protected $table = 'tb_informasi';

    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
