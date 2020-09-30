<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Properties;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    function getAllProperties(Request $request) {
        $properties = Properties::whereNull('deleted_at')->get();
        return response(['status' => 'ok', 'data' => $properties]);
    }

    function newRouter(Request $request) {
        $sapId = $this->uniqueSapId();
        $host = $this->uniqueHostName();
        $mac = $this->uniqueMacAddress();
        $look = $this->uniqueLoopback();

        $properties = new Properties();
        $properties->Sapid = $sapId;
        $properties->Hostname = $host;
        $properties->Loopback = $look;
        $properties->MacAdderss = $mac;
        $properties->save();

        return response(['status' => 'ok', 'data' => ['sapId' => $sapId, 'host' => $host, 'macAddress' => $mac,
            'loopback' => $look]]);
    }

    function updateRouter(Request $request) {
        if($request->input('sapId') && empty($request->input('sapId'))) {
            return response(['status'=>'fail', 'message' => 'Please provide the sapId']);
        }
        if($request->input('hostName') && empty($request->input('hostName'))) {
            return response(['status'=>'fail', 'message' => 'Please provide the sapId']);
        }
        if($request->input('ip') && empty($request->input('ip'))) {
            return response(['status'=>'fail', 'message' => 'Please provide the sapId']);
        }
        if($request->input('macAddress') && empty($request->input('macAddress'))) {
            return response(['status'=>'fail', 'message' => 'Please provide the sapId']);
        }

        $properties = Properties::where('Loopback', $request->input('ip'))->first();
        if($properties) {
            $properties->SapId = $request->input('sapId');
            $properties->Hostname = $request->input('hostName');
            $properties->MacAdderss = $request->input('macAddress');
            $properties->save();

            return response(['status'=>'ok', 'message' => 'Successfully updated the record']);
        }
        return response(['status'=>'ok', 'message' => 'No record found with the given id']);


    }

    public function deleteRouter(Request $request) {
        $properties = Properties::where('Loopback', $request->input('ip'))->first();
        if($properties) {
            $properties->deleted_at = Carbon::now();
            $properties->save();
            return response(['status'=>'ok', 'message' => 'Record deleted successfully.']);
        }
        return response(['status'=>'ok', 'message' => 'No record found with the given id']);

    }



    #------------------------------------------------------------------------------------------------------------------#

//generating the random sap id, i dont know the format
    function uniqueSapId() {
        $sapId = mt_rand(100000000000000000, 999999999999999999);

        $result = \App\Models\Properties::where('Sapid', $sapId)->get();
        if($result->count() > 0) {
            uniqueSapId();
        }

        return $sapId;
    }

//generating the random host name
    function uniqueHostName() {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $hostName = '';
        for ($i = 0; $i < 8; $i++) {
            $hostName .= $characters[rand(0, $charactersLength - 1)];
        }
        $hostName .= '.';

        $host = ['com', 'in', 'eu', 'org'];
        $hK = array_rand($host);
        $hostName .= $host[$hK];

        $result = \App\Models\Properties::where('Hostname', $hostName)->get();
        if($result->count() > 0) {
            uniqueHostName();
        }

        return $hostName;
    }


//generating the random mac address
    function uniqueMacAddress() {
        $macAddress = mt_rand(0, 99) . "-" . mt_rand(0, 99) . "-" . mt_rand(0, 99) . "-" . mt_rand(0, 99). "-" . mt_rand(0, 99). "-" . mt_rand(0, 99);

        $result = \App\Models\Properties::where('MacAdderss', $macAddress)->get();
        if($result->count() > 0) {
            uniqueMacAddress();
        }

        return $macAddress;
    }

//generating the random ipv4
    function uniqueLoopback() {
        $randIP = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);

        $result = \App\Models\Properties::where('Loopback', $randIP)->get();
        if($result->count() > 0) {
            uniqueLoopback();
        }

        return $randIP;
    }

}
