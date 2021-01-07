<?php


namespace controlink\winmax4\Http\Controllers;

use controlink\winmax4\Family;
use controlink\winmax4\Subfamily;
use SimpleXMLElement;
use SoapClient;

class FamilyController extends Controller
{
    public function get()
    {
        $client = new SoapClient(config('winmax4.url'));
        $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>' .
            '<x:Winmax4GetFamiliesRQ xmlns:x="urn:Winmax4GetFamiliesRQ">' .
            '   <GetSubFamilies />' .
            '</x:Winmax4GetFamiliesRQ >';
        $Params = array(
            'CompanyCode' => config('winmax4.company_code'),
            'UserLogin' => config('winmax4.user'),
            'UserPassword' => config('winmax4.password'),
            'Winmax4GetFamiliesRQXML' => $XMLRQString
        );
        $return = $client->GetFamilies($Params);
        $XMLRSString = new SimpleXMLElement($return->GetFamiliesResult);
        if ($XMLRSString->Code > 0){
            return response()->json([
                'Code' => $XMLRSString->Code,
                'Message' => $XMLRSString->Message
            ], 400);
        }else {
            if ($XMLRSString->Code > 0) {
                return response()->json([
                    'Code' => $XMLRSString->Code,
                    'Message' => $XMLRSString->Message
                ], 400);
            } else {
                for ($i = 1; $i <= $XMLRSString->Filter->TotalPages; $i++) {
                    $client = new SoapClient(config('winmax4.url'));
                    $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>' .
                        '<x:Winmax4GetFamiliesRQ xmlns:x="urn:Winmax4GetFamiliesRQ">' .
                        '   <GetSubFamilies />' .
                        '</x:Winmax4GetFamiliesRQ >';
                    $Params = array(
                        'CompanyCode' => config('winmax4.company_code'),
                        'UserLogin' => config('winmax4.user'),
                        'UserPassword' => config('winmax4.password'),
                        'Winmax4GetFamiliesRQXML' => $XMLRQString
                    );
                    $return = $client->GetFamilies($Params);
                    $XMLRSString = new SimpleXMLElement($return->GetFamiliesResult);
                    if ($XMLRSString->Code > 0){
                        return response()->json([
                            'Code' => $XMLRSString->Code,
                            'Message' => $XMLRSString->Message
                        ], 400);
                    } else {
                        $families = [];
                        $subfamilies = [];
                        //dd(count($XMLRSString->Families->Family));
                        foreach ($XMLRSString->Families->Family as $family){
                            $familyObject = new Family();
                            $familyObject->Code = $family->Code ? $family->Code : null;
                            $familyObject->Designation = $family->Designation ? $family->Designation : null;
                            $CreatedFamily = Family::firstOrCreate([
                                'Code' => $family->Code,
                                'Designation' => $family->Designation
                            ]);
                            if($family->SubFamilies->SubFamily){
                                foreach ($family->SubFamilies->SubFamily as $SubFamily){
                                    $subfamilyObject = new Subfamily();
                                    $subfamilyObject->Family_ID = $CreatedFamily->id;
                                    $subfamilyObject->Code = $SubFamily->Code->__toString() ? $SubFamily->Code->__toString() : null;
                                    $subfamilyObject->Designation = $SubFamily->Designation->__toString() ? $SubFamily->Designation->__toString() : null;
                                    $subfamilies[] = $subfamilyObject;
                                }
                            }else{
                                $subfamilies = null;
                            }

                            $families[] =  (object) [
                                'family' => $familyObject,
                                'subfamily' => $subfamilies
                            ];

                        }

                        return response()->json([
                            'families' => $families,
                        ], 200);
                    }
                }
            }
        }
    }

    public function saveSubFamilies(){
        $response = $this->get();

        foreach ($response->getData()->families as $family){
            if($family->subfamily){
                foreach ($family->subfamily as $subfamily) {
                    try {
                        $test = Subfamily::firstOrCreate([
                            'Family_ID' => $subfamily->Family_ID,
                            'Code' => $subfamily->Code,
                            'Designation' => $subfamily->Designation
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return response()->json([
                            'Code' => $ex->getCode(),
                            'Message' => $ex->getMessage()
                        ], 500);
                    }
                }
            }
        }

        return response()->json([
            'Code' => 200,
            'Message' => 'All SubFamilies have been saved!'
        ], 200);

    }
}
