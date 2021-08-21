<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Test extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PRIORITY_OPTIONAL = 'optional';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';

    protected $fillable = [
        'name',
        'description',
        'steps',
        'sort_order',
        'priority',
    ];

    protected $appends = [
        'required',
    ];

    public static function booted()
    {
        self::creating(function (Test $test) {
            if ($test->user_id === null && Auth::check()) {
                $test->user_id = Auth::user()->id;
            }
        });
    }

    public function getRequiredAttribute(): bool
    {
        return $this->priority !== self::PRIORITY_OPTIONAL;
    }

    public function testSuite()
    {
        return $this->belongsTo(TestSuite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
