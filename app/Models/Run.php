<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $test_suite_id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property TestSuite $testSuite
 * @property User $user
 * @property \Illuminate\Database\Eloquent\Collection|RunTest[] $runTests
 */
class Run extends Model
{
    use HasFactory;

    /** @var string[] */
    protected $dates = ['completed_at'];

    /**
     * @return BelongsTo<TestSuite, Run>
     */
    public function testSuite(): BelongsTo
    {
        return $this->belongsTo(TestSuite::class);
    }

    /**
     * @return BelongsTo<User, Run>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<RunTest>
     */
    public function runTests(): HasMany
    {
        return $this->hasMany(RunTest::class);
    }
}
