<?php

namespace controlink\winmax4;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 *
 * @package controlink\winmax4
 * @property string $ArticleCode
 * @property string $Designation
 * @property int $family_id
 * @property string $ImageHTTPPath
 * @property int $DiscountLevel
 * @property string $SellUnitCode
 * @property string $SAFTType
 * @property \Illuminate\Support\Carbon $LastPurchaseDate
 * @property \Illuminate\Support\Carbon $LastSellDate
 * @property string $CurrencyCode
 * @property string $GroupPrice
 * @property double $SalesPrice1WithoutTaxesFees
 * @property double $SalesPrice2WithoutTaxesFees
 * @property double $SalesPrice3WithoutTaxesFees
 * @property double $SalesPrice4WithoutTaxesFees
 * @property double $SalesPrice5WithoutTaxesFees
 * @property double $SalesPrice1WithTaxesFees
 * @property double $SalesPrice2WithTaxesFees
 * @property double $SalesPrice3WithTaxesFees
 * @property double $SalesPrice4WithTaxesFees
 * @property double $SalesPrice5WithTaxesFees
 * @property double $SalesPriceExtraWithoutTaxesFees
 * @property double $SalesPriceExtraWithTaxesFees
 * @property double $SalesPriceHoldWithoutTaxesFees
 * @property double $SalesPriceHoldWithTaxesFees
 * @property double $GrossCostPrice
 * @property double $NetCostPrice
 * @property int $PurchaseTax_ID
 * @property int $SaleTax_ID
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \controlink\winmax4\Family|null $family
 * @property-read \controlink\winmax4\Tax|null $purchase_tax
 * @property-read \controlink\winmax4\Tax|null $sale_tax
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @mixin \Eloquent
 */

class Article extends Model
{
    protected $table = 'winmax4_articles';

    protected $fillable = [
        'ArticleCode',
        'Designation',
        'Family_ID',
        'ImageHTTPPath',
        'DiscountLevel',
        'SellUnitCode',
        'SAFTType',
        'LastPurchaseDate',
        'LastSellDate',
        'CurrencyCode',
        'GroupPrice',
        'SalesPrice1WithoutTaxesFees',
        'SalesPrice2WithoutTaxesFees',
        'SalesPrice3WithoutTaxesFees',
        'SalesPrice4WithoutTaxesFees',
        'SalesPrice5WithoutTaxesFees',
        'SalesPrice1WithTaxesFees',
        'SalesPrice2WithTaxesFees',
        'SalesPrice3WithTaxesFees',
        'SalesPrice4WithTaxesFees',
        'SalesPrice5WithTaxesFees',
        'SalesPriceExtraWithoutTaxesFees',
        'SalesPriceExtraWithTaxesFees',
        'SalesPriceHoldWithoutTaxesFees',
        'SalesPriceHoldWithTaxesFees',
        'GrossCostPrice',
        'NetCostPrice',
        'PurchaseTax_ID',
        'SaleTax_ID',
    ];

    public function family()
    {
        return $this->hasOne(Family::class, 'Family_ID');
    }

    public function purchase_tax()
    {
        return $this->hasOne(Tax::class, 'PurchaseTax_ID');
    }

    public function sale_tax()
    {
        return $this->hasOne(Tax::class, 'SaleTax_ID');
    }
}
