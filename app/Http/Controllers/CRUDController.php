<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CRUDController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','web','2fa','role:admin']);
    }

    public function checkModel(Request $request)
    {

      // return json_encode($data);
      $filename = $request[0].'.json';
      $status = file_exists(resource_path('model_schemas/'.$filename));
      if($status){
        $data = [
          'status' => false,
          'message' => 'Model name already exist',
        ];
        return json_encode($data);
      }else{
        $data = [
          'status' => true,
          'message' => '',
        ];
        return json_encode($data);
      }
    }
}
