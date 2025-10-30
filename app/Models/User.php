<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Broadcasting\PrivateChannel;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, BroadcastsEvents;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
    
    /**
     * Summary of broadcastOn
     * @param string $event
     * @return PrivateChannel[]
     */
    public function broadcastOn(string $event)
    {
        return match ($event) {
            'created'  => [new PrivateChannel("users")],
            'updated'  => [new PrivateChannel("users")],
            'deleted'  => [new PrivateChannel("users")],
            'trashed'  => [new PrivateChannel("users")],
            'restored' => [new PrivateChannel("users")],
            default => [],
        };
    }

    /**
     * Summary of broadcastAs
     * @param string $event
     * @return array|string
     */
    public function broadcastAs(string $event)
    {
        return match ($event) {
            'created'  => "user.{$event}",
            'updated'  => "user.{$event}",
            'deleted'  => "user.{$event}",
            'trashed'  => "user.{$event}",
            'restored' => "user.{$event}",
            default => [],
        };
    }

    /**
     * Summary of broadcastWith
     * @param string $event
     * @return array{model: User, user: mixed|array{model: User}}
     */
    public function broadcastWith(string $event): array
    {
        return match ($event) {
            'created' => [
                'model' => $this,
                'user' => $this->user
            ],
            default => ['model' => $this],
        };
    }
}
