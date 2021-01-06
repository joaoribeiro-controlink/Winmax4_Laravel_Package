<?php

namespace controlink\winmax4;

use Illuminate\Database\Eloquent\Model;

/**
 * controlink\winmax4\Family
 *
 * @property string $Code
 * @property string $Designation
 * @method static \Illuminate\Database\Eloquent\Builder|Family newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Family newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Family query()
 * @mixin \Eloquent
 */
class Family extends Model
{
    protected $table = 'winmax4_families';

    protected $fillable = [
        'Code',
        'Designation'
    ];
}
