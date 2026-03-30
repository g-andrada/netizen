<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Paystub extends Model
{
    use HasUuids, SoftDeletes;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paystubs';

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
     * The attributes that are mass assignable.
     *
     * @var array<string, string, string, string>
     */
    protected $fillable = [
        'employer',
        'user_id',
        'pay_period',
        'pay_periods',
        'gross_pay',
        'ytd_gross_pay',
        'net_pay',
        'deductions',
        'deduction_breakdown',
        'ytd_deductions',
        'pay_frequency',
        'pay_date',
    ];

    /**
     * The attributes that are casted
     *
     * @var array<string, string, string, string>
     */
    protected $casts = [
        'gross_pay' => 'decimal:2',
        'ytd_gross_pay' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'deductions' => 'decimal:2',
        'deduction_breakdown' => 'array',
        'ytd_deductions' => 'decimal:2',
        'pay_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the route key for the model.
     */
    // public function getRouteKeyName(): string
    // {
    //     return 'user_id';
    // }

    /**
     * Get the user that have paystubs
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
