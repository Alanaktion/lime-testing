<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'steps' => 'array',
    ];

    public function suite()
    {
        return $this->belongsTo(TestSuite::class);
    }
}
