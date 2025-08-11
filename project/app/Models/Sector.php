<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectors';
    protected $guarded = false;


    /** Пользователи, относящиеся к сектору/отделу
     * @return BelongsToMany
     */
    public function users(): belongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /** Объявления, созданные для отдела
     * @return HasMany
     */
        public function infos(): HasMany
    {
        return $this->hasMany(Info::class)->orderBy('created_at', 'desc');
    }

    /** Непрочитанные сообщения авторизованного пользователя
     * @return HasMany
     */
    public function unreadInfos(): HasMany
    {
        return $this->hasMany(InfoUser::class)->where('user_id', Auth::id())->where('is_read', false);
    }
}
