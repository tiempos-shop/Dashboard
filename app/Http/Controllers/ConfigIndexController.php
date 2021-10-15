<?php

namespace App\Http\Controllers;

use App\configindex;
use Illuminate\Http\Request;

class ConfigIndexController extends Controller
{
    public function guardarIndex(Request $request)
    {
        $configUnopcion2Imagenes = $request->opcion2Imagenes;
        
        $SubirImg1 = $configUnopcion2Imagenes["SubirImg1"];

        $b64 = $SubirImg1["base64"];
        $b64 = preg_replace('/^data:image\/\w+;base64,/', '', $b64);
        $type = explode(';', $SubirImg1["base64"])[0];
        $type = explode('/', $type)[1];
        $bin = base64_decode($b64, true);

        $configUno = new configindex();

        $filename = "uno".".".$type;

        $configUno->img1 = $filename;
        $configUno->save();

        return 1;
    }
}
