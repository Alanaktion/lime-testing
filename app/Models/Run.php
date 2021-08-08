<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    use HasFactory;

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