<?php

use App\Http\Controllers\EnviosController;
use App\Http\Controllers\EnviosMovimientoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['web']], function () {
    // your routes here
    /*Sesion de carrito*/
    
});



Route::group(['middleware' => ['cors']], function () {
    //Rutas a las que se permitirá acceso
    Route::resource('/envios_movimiento', 'EnviosMovimientoController');
    Route::resource('/direcciones', 'DireccionesController');
    Route::post('/direccion/porcliente/', 'DireccionesController@porcliente');

    Route::resource('/clientes', 'ClientesController');

    Route::resource('/pedidos', 'PedidosController');

    Route::resource('/productos', 'ProductosController');
    Route::get('/productostienda', 'ProductosController@productostienda');
    Route::post('/productostiendabusqueda', 'ProductosController@productostiendabusqueda');


    Route::get('pedido/porcliente/{idcliente}', 'PedidosController@porcliente')->where('idcliente', '[0-9]+');
    Route::get('pedido/pendientes', 'PedidosController@pendientes');

    Route::get('envios_mov/pdfPorMovimiento/{idmovimiento}', 'PedidosController@porcliente')->where('idcliente', '[0-9]+');

    Route::post('/envios_mov/cotizar', 'EnviosMovimientoController@cotizar');
    Route::get('envios_mov/pdfPorMovimiento/{idmovimiento}', 'EnviosMovimientoController@pdfPorMovimiento')->where('idmovimiento', '[0-9]+');


    Route::get('/envio', 'EnviosController@index')->name('home');

    /*Configuraciones */
    Route::post('config/diasdhl', 'ConfiguracionesController@guardarDiasDhl');
    Route::get('config/diasdhl', 'ConfiguracionesController@diasdhl');

    Route::get('configindex/guardar', 'ConfigIndexController@guardarIndex');

    /*Ventas */
    Route::get('ventas/obtener', 'PedidosController@ventasObtener');
    Route::get('ventas/porId/{idVenta}', 'PedidosController@ventasPorId')->where('idVenta', '[0-9]+');
    Route::get('ventas/detalle/{idVenta}', 'PedidosController@ventasObtenerDetalle')->where('idVenta', '[0-9]+');


    Route::resource('/encarrito', 'EnCarritoController');
    Route::post('/encarrito/cantidad', 'EnCarritoController@cantidad');
    Route::get('/encarritolocal/{idProducto}', 'EnCarritoController@buscarInfo')->where('idProducto', '[0-9]+');

    Route::resource('/cargainicial', 'CargaInicialController');

    Route::resource('login', 'LoginController');

    Route::post('login/registrar', 'LoginController@registrar');
    Route::post('login/facebook', 'LoginController@inicioFacebook');

    Route::resource('menus', 'MenusController');

    Route::get('principal/inicio', 'PrincipalController@inicio');
});





