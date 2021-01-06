<?php

namespace controlink\winmax4\Http\Controllers;

use SoapClient;

class ArticleController extends Controller
{
    public function get()
    {

        dd(config('winmax4.url'));
        $client = new SoapClient(config('winmax4.url'));
        $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>' .
            '<x:Winmax4ArticlesRQ xmlns:x="urn:Winmax4ArticlesRQ">' .
            '   <GetPrices />' .
            '   <GetStocks />' .
            '   <GetTaxes />' .
            '</x:Winmax4ArticlesRQ>';
        $Params = array(
            'CompanyCode' => config('winmax4.company_code'),
            'UserLogin' => config('winmax4.user'),
            'UserPassword' => config('winmax4.password'),
            'Winmax4ArticleRQXML' => $XMLRQString
        );
        $return = $client->GetArticles($Params);
        $XMLRSString = new SimpleXMLElement($return->GetArticlesResult);
        if ($XMLRSString->Code > 0)
            echo '</br>Error: ' . $XMLRSString->Code . " " . $XMLRSString->Message;
        else
            foreach ($XMLRSString->Articles->Article as $article) {
                dd($article);
                echo '</br>Code: <b>' . $article->ArticleCode . '</b>';
                echo '</br>Designation: ' . $article->Designation;
            }


    }
}
