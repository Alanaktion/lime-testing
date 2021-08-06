<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSuite extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
