<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LinkSubmissionScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->limit(200);
    }

    public function remove(Builder $builder, Model $model){}
}
