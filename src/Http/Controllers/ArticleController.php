<?php

namespace Controlink\Winmax4\Http\Controllers;

use Controlink\Winmax4\Http\Models\Article;
use Controlink\Winmax4\Http\Models\Family;
use Controlink\Winmax4\Http\Models\Subfamily;
use Controlink\Winmax4\Http\Models\Tax;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleXMLElement;
use SoapClient;

class ArticleController extends Controller
{
    public function get()
    {
        $client = new SoapClient(config('winmax4.url'));
        $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>' .
            '<x:Winmax4ArticlesRQ xmlns:x="urn:Winmax4ArticlesRQ">' .
            '   <GetPrices />' .
            '   <GetStocks />' .
            '   <GetTaxes />' .
            '   <Filter>' .
            '       <PageSize>50</PageSize>' .
            '   </Filter>' .
            '</x:Winmax4ArticlesRQ>';
        $Params = array(
            'CompanyCode' => config('winmax4.company_code'),
            'UserLogin' => config('winmax4.user'),
            'UserPassword' => config('winmax4.password'),
            'Winmax4ArticleRQXML' => $XMLRQString
        );
        $return = $client->GetArticles($Params);
        $XMLRSString = new SimpleXMLElement($return->GetArticlesResult);
        if ($XMLRSString->Code > 0) {
            return response()->json([
                'Code' => 'W'.$XMLRSString->Code,
                'Message' => $XMLRSString->Message
            ], 400);
        } else {
            $articles = [];

            for ($i = 0; $i <= $XMLRSString->Filter->TotalPages; $i++) {
                $client = new SoapClient(config('winmax4.url'));
                $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>' .
                    '<x:Winmax4ArticlesRQ xmlns:x="urn:Winmax4ArticlesRQ">' .
                    '   <GetPrices />' .
                    '   <GetStocks />' .
                    '   <GetTaxes />' .
                    '   <Filter>' .
                    '       <PageSize>50</PageSize>' .
                    '       <PageNumber>'.$i.'</PageNumber>' .
                    '   </Filter>' .
                    '</x:Winmax4ArticlesRQ>';
                $Params = array(
                    'CompanyCode' => config('winmax4.company_code'),
                    'UserLogin' => config('winmax4.user'),
                    'UserPassword' => config('winmax4.password'),
                    'Winmax4ArticleRQXML' => $XMLRQString
                );
                $return = $client->GetArticles($Params);
                $XMLRSString = new SimpleXMLElement($return->GetArticlesResult);
                if ($XMLRSString->Code > 0) {
                    return response()->json([
                        'Code' => $XMLRSString->Code,
                        'Message' => $XMLRSString->Message
                    ], 400);
                } else {
                    foreach ($XMLRSString->Articles->Article as $article) {
                        if(isset($article->PurchaseTaxFees->TaxFee->Percentage)){
                            $PurchaseTax = Tax::where('vat', $article->PurchaseTaxFees->TaxFee->Percentage->__toString())->first();
                        }

                        if(isset($article->SaleTaxFees->TaxFee->Percentage)){
                            $SaleTax = Tax::where('vat', $article->SaleTaxFees->TaxFee->Percentage->__toString())->first();
                        }

                        foreach ($article->Stocks->Stock as $stock){
                            $client = new Client();
                            $response = $client->post(route('getWarehouse'), [
                                'json' => [
                                    'code' => $stock->WarehouseCode
                                ]
                            ]);


                        }
                        die();



                        $Family = Family::where('Code', $article->FamilyCode)->first();
                        $SubFamily = Subfamily::where('Code', $article->SubFamilyCode)->where('Family_ID', $Family->id)->first();

                        $articleObject = new Article();
                        $articleObject->ArticleCode = $article->ArticleCode ? $article->ArticleCode->__toString() : null;
                        $articleObject->Designation = $article->Designation ? $article->Designation->__toString() : null;
                        $articleObject->Family_ID = $Family->id;
                        $articleObject->SubFamily_ID = isset($SubFamily->id) ? $SubFamily->id : null;
                        $articleObject->ImageHTTPPath = $article->ImageHTTPPath ? $article->ImageHTTPPath->__toString() : null;
                        $articleObject->DiscountLevel = $article->DiscountLevel ? $article->DiscountLevel->__toString() : null;
                        $articleObject->SellUnitCode = $article->SellUnitCode ? $article->SellUnitCode->__toString() : null;
                        $articleObject->SAFTType = $article->SAFTType ? $article->SAFTType->__toString() : null;
                        $articleObject->LastPurchaseDate = $article->LastPurchaseDate ? $article->LastPurchaseDate->__toString() : null;
                        $articleObject->LastSellDate = $article->LastSellDate ? $article->LastSellDate->__toString() : null;
                        $articleObject->CurrencyCode = $article->Prices->Price->CurrencyCode ? $article->Prices->Price->CurrencyCode->__toString() : null;
                        $articleObject->GroupPrice = $article->Prices->Price->GroupPrice ? $article->Prices->Price->GroupPrice->__toString() : null;
                        $articleObject->SalesPrice1WithoutTaxesFees = $article->Prices->Price->SalesPrice1WithoutTaxesFees ? $article->Prices->Price->SalesPrice1WithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPrice2WithoutTaxesFees = $article->Prices->Price->SalesPrice2WithoutTaxesFees ? $article->Prices->Price->SalesPrice2WithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPrice3WithoutTaxesFees = $article->Prices->Price->SalesPrice3WithoutTaxesFees ? $article->Prices->Price->SalesPrice3WithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPrice4WithoutTaxesFees = $article->Prices->Price->SalesPrice4WithoutTaxesFees ? $article->Prices->Price->SalesPrice4WithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPrice5WithoutTaxesFees = $article->Prices->Price->SalesPrice5WithoutTaxesFees ? $article->Prices->Price->SalesPrice5WithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPrice1WithTaxesFees = $article->Prices->Price->SalesPrice1WithTaxesFees ? $article->Prices->Price->SalesPrice1WithTaxesFees->__toString() : null;
                        $articleObject->SalesPrice2WithTaxesFees = $article->Prices->Price->SalesPrice2WithTaxesFees ? $article->Prices->Price->SalesPrice2WithTaxesFees->__toString() : null;
                        $articleObject->SalesPrice3WithTaxesFees = $article->Prices->Price->SalesPrice3WithTaxesFees ? $article->Prices->Price->SalesPrice3WithTaxesFees->__toString() : null;
                        $articleObject->SalesPrice4WithTaxesFees = $article->Prices->Price->SalesPrice4WithTaxesFees ? $article->Prices->Price->SalesPrice4WithTaxesFees->__toString() : null;
                        $articleObject->SalesPrice5WithTaxesFees = $article->Prices->Price->SalesPrice5WithTaxesFees ? $article->Prices->Price->SalesPrice5WithTaxesFees->__toString() : null;
                        $articleObject->SalesPriceExtraWithoutTaxesFees = $article->Prices->Price->SalesPriceExtraWithoutTaxesFees ? $article->Prices->Price->SalesPriceExtraWithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPriceExtraWithTaxesFees = $article->Prices->Price->SalesPriceExtraWithTaxesFees ? $article->Prices->Price->SalesPriceExtraWithTaxesFees->__toString() : null;
                        $articleObject->SalesPriceHoldWithoutTaxesFees = $article->Prices->Price->SalesPriceHoldWithoutTaxesFees ? $article->Prices->Price->SalesPriceHoldWithoutTaxesFees->__toString() : null;
                        $articleObject->SalesPriceHoldWithTaxesFees = $article->Prices->Price->SalesPriceHoldWithTaxesFees ? $article->Prices->Price->SalesPriceHoldWithoutTaxesFees->__toString() : null;
                        $articleObject->GrossCostPrice = $article->Prices->Price->GrossCostPrice ? $article->Prices->Price->GrossCostPrice->__toString() : null;
                        $articleObject->NetCostPrice = $article->Prices->Price->NetCostPrice ? $article->Prices->Price->GrossCostPrice->__toString() : null;
                        $articleObject->PurchaseTax_ID = $PurchaseTax->id;
                        $articleObject->SaleTax_ID = $SaleTax->id;

                        $createdArticle = Article::updateOrCreate(
                            ['ArticleCode' => $articleObject->ArticleCode],[
                            'Designation' => $articleObject->Designation,
                            'Family_ID' => $articleObject->Family_ID,
                            'SubFamily_ID' => $articleObject->SubFamily_ID,
                            'ImageHTTPPath' => $articleObject->ImageHTTPPath,
                            'DiscountLevel' => $articleObject->DiscountLevel,
                            'SellUnitCode' => $articleObject->SellUnitCode,
                            'SAFTType' => $articleObject->SAFTType,
                            'LastPurchaseDate' => $articleObject->LastPurchaseDate,
                            'LastSellDate' => $articleObject->LastSellDate,
                            'CurrencyCode' => $articleObject->CurrencyCode,
                            'GroupPrice' => $articleObject->GroupPrice,
                            'SalesPrice1WithoutTaxesFees' => $articleObject->SalesPrice1WithoutTaxesFees,
                            'SalesPrice2WithoutTaxesFees' => $articleObject->SalesPrice2WithoutTaxesFees,
                            'SalesPrice3WithoutTaxesFees' => $articleObject->SalesPrice3WithoutTaxesFees,
                            'SalesPrice4WithoutTaxesFees' => $articleObject->SalesPrice4WithoutTaxesFees,
                            'SalesPrice5WithoutTaxesFees' => $articleObject->SalesPrice5WithoutTaxesFees,
                            'SalesPrice1WithTaxesFees' => $articleObject->SalesPrice1WithTaxesFees,
                            'SalesPrice2WithTaxesFees' => $articleObject->SalesPrice2WithTaxesFees,
                            'SalesPrice3WithTaxesFees' => $articleObject->SalesPrice3WithTaxesFees,
                            'SalesPrice4WithTaxesFees' => $articleObject->SalesPrice4WithTaxesFees,
                            'SalesPrice5WithTaxesFees' => $articleObject->SalesPrice5WithTaxesFees,
                            'SalesPriceExtraWithoutTaxesFees' => $articleObject->SalesPriceExtraWithoutTaxesFees,
                            'SalesPriceExtraWithTaxesFees' => $articleObject->SalesPriceExtraWithTaxesFees,
                            'SalesPriceHoldWithoutTaxesFees' => $articleObject->SalesPriceHoldWithoutTaxesFees,
                            'SalesPriceHoldWithTaxesFees' => $articleObject->SalesPriceHoldWithTaxesFees,
                            'GrossCostPrice' => $articleObject->GrossCostPrice,
                            'NetCostPrice' => $articleObject->NetCostPrice,
                            'PurchaseTax_ID' => $articleObject->PurchaseTax_ID,
                            'SaleTax_ID' => $articleObject->SaleTax_ID,
                        ]);

                        $articles[] = $articleObject;
                    }
                }
            }
            return response()->json([
                'articles' => $articles,
            ], 200);
        }
    }

    public function create(Request $request){
        $json = json_decode(json_encode($request->json()->all()), FALSE);

    }
}
