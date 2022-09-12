<?php

namespace Sadguru\SGEntryPass;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];
}
