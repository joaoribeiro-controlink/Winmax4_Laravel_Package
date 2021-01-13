<?php

namespace Controlink\Winmax4\Http\Controllers;

use Controlink\Winmax4\Http\Models\Warehouse;
use Illuminate\Http\Request;
use SimpleXMLElement;
use SoapClient;
use Throwable;

class WarehouseController extends Controller
{
    public function get(Request $request){
        $json = json_decode(json_encode($request->json()->all()), FALSE);

        try {
            $client = new SoapClient(config('winmax4.url'));
            $XMLRQString = '<?xml version="1.0" encoding="utf-8"?>'.
                '<x:Winmax4GetWarehousesRQ xmlns:x="urn:Winmax4GetWarehousesRQ">'.
                '   <Warehouses>'.
                '      <Warehouse>'.
                '      <Code>'.$json->code.'</Code>'.
                '      </Warehouse>'.
                '   </Warehouses>'.
                '</x:Winmax4GetWarehousesRQ >';
            $Params=array(
                'CompanyCode' => config('winmax4.company_code'),
                'UserLogin' => config('winmax4.user'),
                'UserPassword' => config('winmax4.password'),
                'Winmax4GetWarehousesRQXML' => $XMLRQString
            );
            $return = $client->GetWarehouses($Params);
            $XMLRSString = new SimpleXMLElement($return->GetWarehousesResult);
            if ($XMLRSString->Code > 0) {
                return response()->json([
                    'Code' => 'W'.$XMLRSString->Code,
                    'Message' => $XMLRSString->Message->__toString()
                ], 400);
            } else {
                //dd($XMLRSString->Warehouses->Warehouse);
                $warehouse = new Warehouse();
                $warehouse->Code = $XMLRSString->Warehouses->Warehouse->Code->__toString();
                $warehouse->Designation = $XMLRSString->Warehouses->Warehouse->Designation->__toString();

                $createdWarehouse = Warehouse::firstOrCreate([
                    'Code' => $warehouse->Code,
                    'Designation' => $warehouse->Designation
                ]);

                return response()->json([
                    'warehouse' => $createdWarehouse,
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
