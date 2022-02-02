<?php

namespace App\Models\User;

use App\Models\Article\Article;
use App\Models\Article\ArticleAuthor\ArticleAuthor;
use App\Models\Conference\Conference;
use App\Models\Direction\Direction;
use App\Models\User\Grant\Grant;
use App\Models\User\Premium\Premium;
use App\Models\User\Project\Project;
use App\Models\User\Work\Work;
use BlackParadise\LaravelAdmin\Core\PathManager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'avatar',
        'country_id',
        'city_id',
        'direction_id',
        'phone',
        'site',
        'email',
        'file',
        'desc',
        'password',
    ];

    protected $appends = [
        'avatar_url',
        'is_premium',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $createWithRel = [
        'roles_id' => [
            'required' => true,
            'type' => 'integer',
        ],
    ];

    /**
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ?
            (new PathManager())->getFile($this->avatar, 'user_avatar')
            :
            (new PathManager())->getDefaultPicture();
    }

    /**
     * @return HasMany
     */
    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    /**
     * @return Collection
     */
    public function forSelect(): Collection
    {
        $collection = $this->newQuery()->get();
        return $collection->pluck('full_name', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'students', 'user_id', 'student_id');
    }

    /**
     * @return BelongsToMany
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'students', 'student_id', 'user_id');
    }

    /**
     * @return HasMany
     */
    public function grants(): HasMany
    {
        return $this->hasMany(Grant::class);
    }

    /**
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return BelongsToMany
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class)
            ->using(ArticleAuthor::class)
            ->withPivot(['approved']);
    }

    /**
     * @return HasMany
     */
    public function premiums(): HasMany
    {
        return $this->hasMany(Premium::class);
    }

    /**
     * @return string|false
     */
    public function getIsPremiumAttribute()
    {
        $now = Carbon::now()->format('Y-m-d');
        $prem = $this->premiums()
            ->where('start','<=',Carbon::now()->format('Y-m-d'))
            ->where('finish','>=',$now)
            ->get('finish');
        if ($prem->count() > 0) {
            return Carbon::parse($prem->first()->finish)->format('d-m-Y');
        }
        return false;
    }

    /**
     * @return HasMany
     */
    public function conferenceAuthor(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    /**
     * @return BelongsTo|null
     */
    public function direction(): ?BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }
}
