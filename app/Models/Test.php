<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $test_suite_id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $steps
 * @property float $sort_order
 * @property string $priority
 * @property-read bool $required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property TestSuite $testSuite
 * @property User $user
 */
class Test extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PRIORITY_OPTIONAL = 'optional';

    public const PRIORITY_NORMAL = 'normal';

    public const PRIORITY_HIGH = 'high';

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'description',
        'steps',
        'sort_order',
        'priority',
    ];

    /** @var array<int, string> */
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

    /**
     * @return Attribute<bool, void>
     */
    protected function required(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->priority !== self::PRIORITY_OPTIONAL,
        );
    }

    /**
     * @return BelongsTo<TestSuite, $this>
     */
    public function testSuite(): BelongsTo
    {
        return $this->belongsTo(TestSuite::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
