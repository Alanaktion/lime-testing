<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    public function suite()
    {
        return $this->belongsTo(TestSuite::class);
    }

    public function steps()
    {
        return $this->hasMany(TestStep::class);
    }
}
