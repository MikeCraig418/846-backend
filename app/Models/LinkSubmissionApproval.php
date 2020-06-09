<?php

namespace App\Models;

use App\Traits\Uuids;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkSubmissionApproval extends Model
{
    use Uuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [

    ];

    protected $casts = [
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function link_submission() {
        return $this->belongsTo(LinkSubmission::class);
    }
}
