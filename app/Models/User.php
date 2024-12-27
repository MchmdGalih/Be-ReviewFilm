<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->generateOtp();
        });
    }


    public function generateOtp()
    {

        do {
            $otpCode = mt_rand(100000, 999999);
            $validUnique = Otpcode::where('otp', $otpCode)->first();
        } while ($validUnique);

        $now = Carbon::now();
        $otp_code = Otpcode::updateOrCreate([
            'user_id' => $this->id
        ], [
            'otp' => $otpCode,
            'expired_at' => $now->addMinutes(5)
        ]);
    }

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

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function otp_code()
    {
        return $this->hasOne(OtpCode::class, 'user_id');
    }
}
