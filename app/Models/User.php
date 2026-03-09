<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'nama_user',
        'nohp',
        'status',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function penjadwalan()
    {
        return $this->hasMany(Penjadwalan::class, 'id_user', 'id_user');
    }
    public function absensi() {
        return $this->hasMany(Absensi::class, 'id_user', 'id_user');
    }
    public function isActive()
    {
        return $this->status === 'active';
    }
}