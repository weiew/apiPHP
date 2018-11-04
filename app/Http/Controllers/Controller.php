<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function output($data,$code = 200,$message="success")
    {
        $output['data'] = $data;
        $output['code'] = $code;
        $output['message'] = $message;

        return response()->json($output, $code);
    }
}
