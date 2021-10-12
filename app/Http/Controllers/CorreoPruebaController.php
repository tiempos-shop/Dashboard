<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class CorreoPruebaController extends Controller
{
    public function prueba(Request $request)
    {
        try {
            //se debe borrar cache para pruebas localhost
            //php artisan config:clear 
            $url_shop = env('URL_SHOP');
            if (strlen($url_shop) == 0)
            {
                return "no se encontro url de shop";
            }
            $info = new \stdClass();

            if (!isset($request->correo))
            {
                $request->correo = "luisricardogz@gmail.com";
            }
            
            $info->name ="prueba";
            $info->server = $url_shop;
            $info->claveConfirmacion = $url_shop."confirmation.php?clave=1234";

            Mail::to($request->correo)->send(new ConfirmacionMail($info));

            return "correo de prueba enviado";
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
        

    }
}
