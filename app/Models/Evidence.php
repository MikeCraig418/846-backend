<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidence extends Model
{

    use Uuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'video' => 'array',
    ];

    public function incident()
    {
        return $this->belongsTo('App\Models\Incident');
    }

    public function video()
    {
        return $this->hasMany('App\Models\Video');
    }
}
