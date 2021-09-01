<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function testSuite()
    {
        return $this->belongsTo(TestSuite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function runTests()
    {
        return $this->hasMany(RunTest::class);
    }
}
