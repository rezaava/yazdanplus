<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\Images;
use App\Models\sms;
use App\Models\UserLog;
use App\Models\ShopUser;



use Laratrust\Traits\HasRolesAndPermissions;



class User extends Authenticatable
{
    use HasRolesAndPermissions, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
     ];
    public function employee_user()
    {
        return $this->hasMany(ShopUser::class, 'user_id');
    }
    public function user_log()
    {
        return $this->hasMany(UserLog::class, 'user_id');
    }

    //karbar va karbar moaref
    public function parent()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }


    public function owner()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function marketer()
    {
        return $this->hasMany(User::class, 'refferer_id');
    }
    public function fullname(){
        return $this->name .' '. $this->family ;
    }





    public function children()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }
    //etmam
    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'transaction_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'order_id');
    }

    public function bankaccount()
    {
        return $this->hasMany(BankAccount::class, 'bankaccount_id');
    }
    public function profile()
    {
        return $this->belongsTo(Images::class, 'profpic_id');
    }
    public function user_from()
    {
        return $this->hasMany(sms::class, 'user_from_id');
    }
    public function user_sms()
    {
        return $this->hasMany(sms::class, 'user_sms_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
    
    
    
    public function toString($auth)
    {
        if ($auth == null){
            $auth = Auth::user();
        }
        
        if($auth->hasRole('admin'))
            return nl2br($this->fullname() . PHP_EOL . $this->mobile);
        else
            return $this->hideMobile($this->mobile, $auth);
    }
}
