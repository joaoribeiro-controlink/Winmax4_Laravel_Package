<?php

namespace Controlink\Winmax4\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * controlink\winmax4\Tax
 *
 * @property int $vat
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tax query()
 * @mixin \Eloquent
 */
class Tax extends Model
{
    protected $table = 'winmax4_taxes';

    protected $fillable = [
        'vat'
    ];
}
