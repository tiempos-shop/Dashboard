<?php

namespace App\Http\Controllers;

use App\configuraciones;
use Illuminate\Http\Request;

class ConfiguracionesController extends Controller
{
 
    private $codigoDiasDHL = "diasdhl";

    public function diasdhl()
    {
        

        $config = configuraciones::where('codigo', $this->codigoDiasDHL)->first();

        if (!isset($config))
        {
            return 0;
        }
        return (int)$config->valor;

    }

    public function guardarDiasDhl(Request $datos)
    {
        if (!isset($datos))
        {
            return response("no se establecio informacion");
        }

        if (!isset($datos->dias))
        {
            return response("no se establecio los dias");
        }

        $config = configuraciones::where('codigo', $this->codigoDiasDHL)->first();

        if (!isset($config))
        {
            return response("no se establecio la configuracion de dias DHL");
        }

        $config->valor = $datos->dias;
        $config->save();

        return 1;

    }

    
}
