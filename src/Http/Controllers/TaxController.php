<?php

namespace Controlink\Winmax4\Http\Controllers;

use Controlink\Winmax4\Tax;
use SimpleXMLElement;
use SoapClient;
use Throwable;

class TaxController extends Controller
{
    public function get(){
        try {
            $client = new SoapClient(config('winmax4.url'));
            $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>'.
                '<x:Winmax4GetTaxesRQ xmlns:x="urn:Winmax4GetTaxesRQ">'.
                '</x:Winmax4GetTaxesRQ >';
            $Params=array(
                'CompanyCode' => config('winmax4.company_code'),
                'UserLogin' => config('winmax4.user'),
                'UserPassword' => config('winmax4.password'),
                'Winmax4GetTaxesRQXML'=> $XMLRQString
            );
            $return = $client->GetTaxes($Params);
            $XMLRSString = new SimpleXMLElement($return->GetTaxesResult);
            if ($XMLRSString->Code > 0) {
                return response()->json([
                    'Code' => $XMLRSString->Code,
                    'Message' => $XMLRSString->Message
                ], 400);
            } else {
                $taxfees = [];
                foreach ($XMLRSString->Taxes->TaxFee->TaxFeeRates->TaxFeeRate as $taxfee){
                    $Tax = new Tax();
                    $Tax->vat = $taxfee->Percentage->__toString();
                    $createdTax = Tax::firstOrCreate([
                        'vat' => $Tax->vat
                    ]);

                    $taxfees[] = $Tax;
                }

                return response()->json([
                    'Taxes' => $taxfees,
                ], 200);
            }
        }catch (\SoapFault $e) {
            if (!config('winmax4.url') || $e->faultcode == "WSDL") {
                return response()->json([
                    'ErrorCode' => 1,
                    'Message' => trans('winmax4::error.url')
                ], 400);

                if(!config('winmax4.user')){
                    return response()->json([
                        'ErrorCode' => 1,
                        'Message' => trans('winmax4::error.user')
                    ], 400);
                }

                if(!config('winmax4.password')){
                    return response()->json([
                        'ErrorCode' => 1,
                        'Message' => trans('winmax4::error.password')
                    ], 400);
                }

                if(!config('winmax4.company_code')){
                    return response()->json([
                        'ErrorCode' => 1,
                        'Message' => trans('winmax4::error.companycode')
                    ], 400);
                }
            }
        }
    }
}
