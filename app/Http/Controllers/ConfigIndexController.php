<?php

namespace App\Http\Controllers;

use App\configindex;
use App\configuraciones;
use Illuminate\Http\Request;

class ConfigIndexController extends Controller
{
    public function guardarIndex(Request $request)
    {
        $configUnopcion2Imagenes = $request->opcion2Imagenes;
        
        $SubirImg1 = $configUnopcion2Imagenes["SubirImg1"];
        $SubirImg2 = $configUnopcion2Imagenes["SubirImg2"];
        $prefijoOpcion1 = "prefopcion1";

        /*ubicacion para archivos config*/
        $ubicacionUno = "img/config/";
        
        /*obteniendo el row de la config 1*/
        $configUno = configindex::where('idConfig', 1)->first();

        if (isset($SubirImg1["base64"]))
        {
            /*para img 1 */
            $b64 = $SubirImg1["base64"];
            $b64 = preg_replace('/^data:image\/\w+;base64,/', '', $b64);
            $type = explode(';', $SubirImg1["base64"])[0];
            $type = explode('/', $type)[1];
            $bin = base64_decode($b64, true);
            
            $filename1 = $prefijoOpcion1."uno".".".$type;
            file_put_contents($ubicacionUno.$filename1, $bin);

            /*estableciendo el nombre del archivo*/
            $configUno->img1 = $ubicacionUno.$filename1;
        }
        
        if(isset($SubirImg2["base64"]))
        {
            /*para img 2 */
            $b64 = $SubirImg2["base64"];
            $b64 = preg_replace('/^data:image\/\w+;base64,/', '', $b64);
            $type = explode(';', $SubirImg2["base64"])[0];
            $type = explode('/', $type)[1];
            $bin = base64_decode($b64, true);

            $filename2 =  $prefijoOpcion1."dos".".".$type;
            file_put_contents($ubicacionUno.$filename2, $bin);
            $configUno->img2 = $ubicacionUno.$filename2;
        }
        
        $configUno->activo = true;
        $configUno->esYoutube = false;
        $configUno->save();



        return 1;
    }

    public function obtener()
    {
        $configuraciones = configindex::get();
        $appUrl = env("APP_URL");

        $appUrl = $appUrl."/";

        $datos = new \stdClass();
        $datos->appUrl = $appUrl;
        $datos->configuraciones = $configuraciones;

        return response()->json($datos);
    }
}
