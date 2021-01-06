<?php

namespace controlink\winmax4\Http\Controllers;

use controlink\winmax4\Article;
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
                'Code' => $XMLRSString->Code,
                'Message' => $XMLRSString->Message
            ], 400);
        } else {
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
                    $articles = [];
                    foreach ($XMLRSString->Articles->Article as $article) {
                        $articleObject = new Article();
                        $articleObject->ArticleCode = $article->ArticleCode ? $article->ArticleCode : null;
                        $articleObject->Designation = $article->Designation ? $article->Designation : null;
                        //$articleObject->Family_ID = $article->Family_ID;
                        $articleObject->ImageHTTPPath = $article->ImageHTTPPath ? $article->ImageHTTPPath : null;
                        $articleObject->DiscountLevel = $article->DiscountLevel ? $article->DiscountLevel : null;
                        $articleObject->SellUnitCode = $article->SellUnitCode ? $article->SellUnitCode : null;
                        $articleObject->SAFTType = $article->SAFTType ? $article->SAFTType : null;
                        $articleObject->LastPurchaseDate = $article->LastPurchaseDate ? $article->LastPurchaseDate : null;
                        $articleObject->LastSellDate = $article->LastSellDate ? $article->LastSellDate : null;
                        $articleObject->CurrencyCode = $article->Prices->Price->CurrencyCode ? $article->Prices->Price->CurrencyCode : null;
                        $articleObject->GroupPrice = $article->Prices->Price->GroupPrice ? $article->Prices->Price->GroupPrice : null;
                        $articleObject->SalesPrice1WithoutTaxesFees = $article->Prices->Price->SalesPrice1WithoutTaxesFees ? $article->Prices->Price->SalesPrice1WithoutTaxesFees : null;
                        $articleObject->SalesPrice2WithoutTaxesFees = $article->Prices->Price->SalesPrice2WithoutTaxesFees ? $article->Prices->Price->SalesPrice2WithoutTaxesFees : null;
                        $articleObject->SalesPrice3WithoutTaxesFees = $article->Prices->Price->SalesPrice3WithoutTaxesFees ? $article->Prices->Price->SalesPrice3WithoutTaxesFees : null;
                        $articleObject->SalesPrice4WithoutTaxesFees = $article->Prices->Price->SalesPrice4WithoutTaxesFees ? $article->Prices->Price->SalesPrice4WithoutTaxesFees : null;
                        $articleObject->SalesPrice5WithoutTaxesFees = $article->Prices->Price->SalesPrice5WithoutTaxesFees ? $article->Prices->Price->SalesPrice5WithoutTaxesFees : null;
                        $articleObject->SalesPrice1WithTaxesFees = $article->Prices->Price->SalesPrice1WithTaxesFees ? $article->Prices->Price->SalesPrice1WithTaxesFees : null;
                        $articleObject->SalesPrice2WithTaxesFees = $article->Prices->Price->SalesPrice2WithTaxesFees ? $article->Prices->Price->SalesPrice2WithTaxesFees : null;
                        $articleObject->SalesPrice3WithTaxesFees = $article->Prices->Price->SalesPrice3WithTaxesFees ? $article->Prices->Price->SalesPrice3WithTaxesFees : null;
                        $articleObject->SalesPrice4WithTaxesFees = $article->Prices->Price->SalesPrice4WithTaxesFees ? $article->Prices->Price->SalesPrice4WithTaxesFees : null;
                        $articleObject->SalesPrice5WithTaxesFees = $article->Prices->Price->SalesPrice5WithTaxesFees ? $article->Prices->Price->SalesPrice5WithTaxesFees : null;
                        $articleObject->SalesPriceExtraWithoutTaxesFees = $article->Prices->Price->SalesPriceExtraWithoutTaxesFees ? $article->Prices->Price->SalesPriceExtraWithoutTaxesFees : null;
                        $articleObject->SalesPriceExtraWithTaxesFees = $article->Prices->Price->SalesPriceExtraWithTaxesFees ? $article->Prices->Price->SalesPriceExtraWithTaxesFees : null;
                        $articleObject->SalesPriceHoldWithoutTaxesFees = $article->Prices->Price->SalesPriceHoldWithoutTaxesFees ? $article->Prices->Price->SalesPriceHoldWithoutTaxesFees : null;
                        $articleObject->SalesPriceHoldWithTaxesFees = $article->Prices->Price->SalesPriceHoldWithTaxesFees ? $article->Prices->Price->SalesPriceHoldWithoutTaxesFees : null;
                        $articleObject->GrossCostPrice = $article->Prices->Price->GrossCostPrice ? $article->Prices->Price->GrossCostPrice : null;
                        $articleObject->NetCostPrice = $article->Prices->Price->NetCostPrice ? $article->Prices->Price->GrossCostPrice : null;
                        //$articleObject->PurchaseTax_ID = $article->PurchaseTax_ID;
                        //$articleObject->SaleTax_ID = $article->SaleTax_ID;


                        $articles[] = $articleObject;
                    }

                    return response()->json([
                        'articles' => $articles,
                        'Code' => $XMLRSString->Code,
                        'Message' => $XMLRSString->Message
                    ], 200);
                }
            }
        }
    }
}
