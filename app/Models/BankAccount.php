<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use HasUuids, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_accounts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, string, string, string>
     */
    protected $fillable = [
        'institution',
        'account_number',
        'account_type',
        'account_holder',
        'account_holder_id',
        'balance',
        'ownership_type',
        'joint_id',
        'status',
        'notes',
        'created_by'
    ];

    /**
     * The attributes that are casted
     *
     * @var array<string, string, string, string>
     */
    protected $casts = [
        'account_number' => 'encrypted',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the user that have bank accounts
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
