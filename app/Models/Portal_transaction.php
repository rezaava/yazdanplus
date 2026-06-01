<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portal_transaction extends Model
{
    use HasFactory;


    public function calculate_user_fee()
    {
        return floor($this->price * 1 / 100);
    }

    public function get_final_fee()
    {
        return $this->fee + $this->user_fee;
    }

    public function get_status_description()
    {
        switch ($this->status){
            case 0 ;
                return 'cancel';
            case 1 ;
                return 'failed';
            case  2;
                return 'waiting';
            case 3 ;
                return 'successfull';
            default:
                return 'unknown';
        }
    }
    public function get_status_class()
    {
        switch ($this->status){
            case 0:
                return 'text-reset';
            case 1:
                return 'text-danger';
            case 2:
                return 'text-warning';
            case 3:
                return 'text-success';
        }
    }
}
