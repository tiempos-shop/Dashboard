<?php

namespace App\Http\Controllers;

use App\pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class PrincipalController extends Controller
{
    public function inicio()
    {
        $fechaActual = date("m"); //mes
        $ventasMonedaNacionalMes = pedidos::whereMonth('FechaPedido', $fechaActual)->where('moneda', 'MXN')->sum('total');
        $ventasMonedaDolarMes = pedidos::whereMonth('FechaPedido', $fechaActual)->where('moneda', 'USD')->sum('total');
        $enviosPendientes = pedidos::where('idEstatusPedido', '=', 1)->count();

        /*obtener meses ventas*/
        $mesesGanancia  = DB::select("SELECT MONTH(FechaPedido) as mes, 
            SUM(case WHEN moneda = 'MXN' THEN total ELSE 0 END) as MXN, 
            SUM(case WHEN moneda = 'USD' THEN total ELSE 0 END) as USD 
            FROM pedidos GROUP BY mes");

        $info = new \stdClass();
        $info->ventasMonedaNacionalMes = $ventasMonedaNacionalMes;
        $info->ventasMonedaDolarMes = $ventasMonedaDolarMes;
        $info->enviosPendientes = $enviosPendientes;
        $info->mesesGanancia = $mesesGanancia;
        return response()->json($info);
    }   
}
