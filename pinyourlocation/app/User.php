<?php namespace App;

use Esensi\Model\Contracts\ValidatingModelInterface;
use Esensi\Model\Traits\ValidatingModelTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Acoustep\EntrustGui\Contracts\HashMethodInterface;
use Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, ValidatingModelInterface, HashMethodInterface
{
  use Authenticatable, CanResetPassword, ValidatingModelTrait, EntrustUserTrait;

    protected $throwValidationExceptions = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','token'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $hashable = ['password'];

    protected $rulesets = [

        'creating' => [
            'email'      => 'required|email|unique:users',
            'password'   => 'required',
        ],

        'updating' => [
            'email'      => 'required|email|unique:users',
            'password'   => '',
        ],
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'lastrun'
    ];

    public function entrustPasswordHash() 
    {
        $this->password = Hash::make($this->password);
        $this->save();
    }

    public function verification_codes()
    {
        return $this->hasMany('App\VerificationCode');
    }

    public function pinned_locations()
    {
        return $this->hasMany('App\PinnedLocation');
    }
    public function followers()
    {
        return $this->belongsToMany('App\User', 'followers', 'follow_id', 'user_id')->withTimestamps();
    }
    public function following()
    {
        return $this->belongsToMany('App\User', 'followers', 'user_id', 'follow_id')->withTimestamps();
    }
}
