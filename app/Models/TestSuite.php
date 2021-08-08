<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestSuite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function runs()
    {
        return $this->hasMany(Run::class);
    }

    public function latestRun()
    {
        return $this->hasOne(Run::class)->ofMany([
            'completed_at' => 'max',
        ], function ($query) {
            $query->whereNotNull('completed_at');
        });
    }
}
