<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class sms extends Model
{
    use HasFactory;
    public function user_from()
    {
        return $this->belongsTo(User::class, 'user_from_id');
    }
    public function user_to()
    {
        return $this->belongsTo(User::class, 'user_to_id');
    }
    public function SmsType(){
        switch ($this->type) {
            case 0:
                return "سیستم";
            case 1:
                return "ورود";
            case 2:
                return "خرید";
        }
    }
    public function SmsTypeSms(){
        switch ($this->type) {
            case 0:
                return "tetx-info";
            case 1:
                return "text-warning";
            case 2:
                return "text-success";
        }
    }

}
