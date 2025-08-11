<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    protected $table = 'users';
    protected $appends = ['unreadedMessagesCount'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'login',
        'job',
        'infos_permissions'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /** Секторы/отделы, к которым имеет отношение пользователь
     * @return BelongsToMany
     */
    public function sectors(): belongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }


    /** Объявления, созданные пользователем
     * @return HasMany
     */
    public function postedInfos(): hasMany
    {
        return $this->hasMany(Info::class);
    }


    /** Все доступные юзеру отделы со всеми сообщениями этих отделов
     * @return BelongsToMany
     */
    public function sectorsInfos(): BelongsToMany
    {
        return $this->sectors()->with('infos')->withCount('infos');
    }


    /** Общий счетчик всех доступных юзеру непрочитанных сообщений
     * @return int
     */
    public function getUnreadedMessagesCountAttribute(): int
    {
        return $this->hasMany(InfoUser::class)->where('user_id', Auth::id())->where('is_read', false)->count();
    }
}
