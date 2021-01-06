<?php

namespace controlink\winmax4\Http\Controllers;

use SoapClient;

class ArticleController extends Controller
{
    public function get()
    {

        $client = new SoapClient("http://winmax4.controlink.pt/winmax4/webservices/generic.asmx?wsdl");
        $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>' .
            '<x:Winmax4ArticlesRQ xmlns:x="urn:Winmax4ArticlesRQ">' .
            '   <GetPrices />' .
            '   <GetStocks />' .
            '   <GetTaxes />' .
            '</x:Winmax4ArticlesRQ>';
        $Params = array(
            'CompanyCode' => '1',
            'UserLogin' => 'WS',
            'UserPassword' => 'Clink2015!',
            'Winmax4ArticleRQXML' => $XMLRQString
        );
        $return = $client->GetArticles($Params);
        $XMLRSString = new SimpleXMLElement($return->GetArticlesResult);
        if ($XMLRSString->Code > 0)
            echo '</br>Error: ' . $XMLRSString->Code . " " . $XMLRSString->Message;
        else
            foreach ($XMLRSString->Articles->Article as $article) {
                echo '</br>Code: <b>' . $article->ArticleCode . '</b>';
                echo '</br>Designation: ' . $article->Designation;
            }


    }
}
