<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;


    protected $hidden = [
        'meta',
        'uploader',
        'deleted_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'streams' => 'array',
        'meta' => 'array',
    ];

    public function evidence()
    {
        return $this->belongsTo('App\Models\Evidence', 'url', 'evidence_url');
    }
}
