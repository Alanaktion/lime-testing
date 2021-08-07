<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
