<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $run_id
 * @property int $test_id
 * @property string $result
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Run $run
 * @property Test $test
 */
class RunTest extends Model
{
    use HasFactory;

    public const RESULT_PASS = 'pass';

    public const RESULT_FAIL = 'fail';

    public const RESULT_SKIP = 'skip';

    /**
     * @return BelongsTo<Run, RunTest>
     */
    public function run(): BelongsTo
    {
        return $this->belongsTo(Run::class);
    }

    /**
     * @return BelongsTo<Test, RunTest>
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
