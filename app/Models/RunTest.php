<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function run()
    {
        return $this->belongsTo(Run::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
