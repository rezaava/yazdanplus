<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    public function user_log()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ActionText()
    {
        switch ($this->action) {
            case 1:
                return 'ورود';
            case 2:
                return 'خروج';
        }
    }
    public function ActionTextClass()
    {
        switch ($this->action) {
            case 1:
                return 'text-success';
            case 2:
                return 'text-danger';
        }
    }
}
