<?php

namespace Controlink\Winmax4\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model of the table Warehouses.
 *
 * @property string $Code
 * @property string $Designation
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    protected $table = 'winmax4_warehouses';

    protected $fillable = [
        'Code',
        'Designation'
    ];
}
