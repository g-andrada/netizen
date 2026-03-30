<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Merchant extends Model
{
    use HasUuids, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchants';

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
        'name',
        'identifiers',
        'categories',
        'sub_categories',
        'created_by'
    ];

    /**
     * The attributes that are casted
     *
     * @var array<string, string, string, string>
     */
    protected $casts = [
        'identifiers' => 'array',
        'categories' => 'array',
        'sub_categories' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'identifiers' => 'array',
    //         'categories' => 'array',
    //         'sub_categories' => 'array',
    //     ];
    // }

    // protected function name(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => ucwords(strtolower($value)),
    //         set: fn ($value) => ucwords(trim($value)),
    //     );
    // }
}
