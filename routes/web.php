<?php

use App\configuraciones;
use App\Http\Controllers\EnviosMovimientoController;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $tiendaAdmin = env("APP_URL");
    $configUrlAdmin = configuraciones::where('idConfiguracion', 1)->first();
    $configUrlAdmin->valor = $tiendaAdmin;
    $configUrlAdmin->save();
    return view('principal');
});

Route::get('/productos', function () {
    return view('productos');
});

/*Ventas*/
Route::get('/ventas', function () {
    return view('ventas/ventas');
});
Route::get('/ventas/detalle/{id}', function () {
    return view('ventas/ventasDetalle');
})->where('id', '[0-9]+');

Route::get('/config/dhl', function () {
    return view('configEnvios');
});


Route::get('/config/principalimagen', function () {
    return view('config/configimagenprincipal');
});

Route::get('/clientes', function () {
    return view('clientes');
});

Route::get('/inicio', function () {
    return view('iniciosesion');
});

Route::post('/inicio', 'LoginController@inicioAdmin');
Route::get('/salir', 'LoginController@salirAdmin');

Route::get('/productos/guardar', function () {
    return view('productosGuardar');
});

Route::get('/productos/{id}', function () {
    return view('productosGuardar');
})->where('id', '[0-9]+');


Route::get('/serviciosdhl', function(){
    return view('serviciosdhl');
});

Route::get('/usuarios', 'UsuariosController@index');

Route::post('/usuarios/guardar', 'UsuariosController@store');

Route::get('/usuarios/guardar', 'UsuariosController@create');

Route::get('/usuarios/{id}', 'UsuariosController@show')->name("usuarios.show");

Route::delete('/usuarios/{id}', 'UsuariosController@destroy')->name("usuarios.destroy");


Route::get('/confirmar/{clave}', function(){
    return view('serviciosdhl');
})->where('clave', '[A-Za-z]+');

Route::get('/serviciosdhl/download/{name}/{ext}/{descargar}/{idpedido}', function($name, $ext, $descargar, $idpedido =0){
    $filename =$name.".".strtolower($ext);

    $file = public_path()."/".$filename;

    //Validar si existe el archivo
    if (file_exists($file) == false)
    {
        $service = new EnviosMovimientoController();
        $movimiento = $service->crearDocumentoPdf($idpedido);

    }

    $headers = array(
        "Content-Type: application/".strtolower($ext),
    );

    if ($descargar == true)
    {
        return Response()->download($file, $filename, $headers);
    }
    else
    {
        return Response()->file($filename, $headers);
        
    }
    
});

///PRUEBAS URL

Route::post('correo/prueba', 'CorreoPruebaController@prueba');

Route::get('/menus', function(){
    return view('menu.menus');
});

Route::get('/editor', function(){
    return view('archive.editor');
});