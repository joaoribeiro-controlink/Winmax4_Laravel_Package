<?php

namespace Controlink\Winmax4;

use Illuminate\Database\Eloquent\Model;

/**
 * controlink\winmax4\Subfamily
 *
 * @property-read \controlink\winmax4\Family|null $family
 * @property string $Code
 * @property string $Designation
 * @property int $Family_ID
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Subfamily newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subfamily newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subfamily query()
 */
class Subfamily extends Model
{
    protected $table = 'winmax4_subfamilies';

    protected $fillable = [
        'Family_ID',
        'Code',
        'Designation'
    ];

    public function family()
    {
        return $this->hasOne(Family::class);
    }
}
