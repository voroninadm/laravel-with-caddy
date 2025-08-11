<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Info extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['is_read', 'author_name'];

    // Метод boot вызывается при инициализации модели
    protected static function boot()
    {
        parent::boot();

        // Событие "creating" срабатывает перед созданием записи - добавляем id создателя
        static::creating(function ($info) {
            if (Auth::check()) {
                $info->user_id = Auth::id();
                $info->is_done = false;
            }
        });
    }

    /** Сектор, для которого создано объявление
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /** Автор объявления
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Связанная с объявлением запись в таблице InfoUser */
    public function relatedInfo(): HasOne
    {
        return $this->hasOne(InfoUser::class);
    }

    /** Добавляем атрибут is_read к объявлению из связанной таблицы
     * @return bool
     */
    public function getIsReadAttribute(): bool
    {
        return (bool) optional($this->hasOne(InfoUser::class)->where('user_id', Auth::id())->first())->is_read;
    }

    public function getAuthorNameAttribute()
    {
        return $this->author->name ?? null;
    }

}
