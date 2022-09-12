<?php

namespace Sadguru\SGEntryPass;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserLoginToken extends \Illuminate\Database\Eloquent\Model
{

    use SoftDeletes;

    protected $fillable = [
        'status',
        'email',
        'user_id',
        'token',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

}
