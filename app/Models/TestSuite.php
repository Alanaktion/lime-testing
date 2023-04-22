<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property Team $team
 * @property \Illuminate\Database\Eloquent\Collection|Test[] $tests
 * @property \Illuminate\Database\Eloquent\Collection|Run[] $runs
 * @property Run $latestRun
 */
class TestSuite extends Model
{
    use HasFactory;
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = ['name', 'description'];

    /**
     * @return BelongsTo<Team, TestSuite>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return HasMany<Test>
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    /**
     * @return HasMany<Run>
     */
    public function runs(): HasMany
    {
        return $this->hasMany(Run::class);
    }

    /**
     * @return HasOne<Run>
     */
    public function latestRun(): HasOne
    {
        return $this->hasOne(Run::class)->ofMany([
            'completed_at' => 'max',
        ], function ($query) {
            $query->whereNotNull('completed_at');
        });
    }
}
