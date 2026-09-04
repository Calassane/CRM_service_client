<?php

namespace App\Models;

use App\Enums\CallDirection;
use App\Enums\CallReason;
use App\Enums\CallStatus;
use Database\Factories\CustomerCallFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerCall extends Model
{
    /** @use HasFactory<CustomerCallFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'reservation_id',
        'agent_id',
        'direction',
        'reason',
        'started_at',
        'duration_seconds',
        'status',
        'notes',
    ];

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<Reservation, $this>
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'direction' => CallDirection::class,
            'reason' => CallReason::class,
            'started_at' => 'immutable_datetime',
            'duration_seconds' => 'integer',
            'status' => CallStatus::class,
        ];
    }
}
