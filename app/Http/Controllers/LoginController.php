<?php

namespace App\Http\Controllers;

use App\clientes;
use App\usuarios;
use App\Mail\ConfirmacionMail;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $data)
    {
        $problemas = ["email"=>"", "password" => "", "redirigir" => "", "sistema" => ""];
        /*Iniciar Sesión */
        $mostrarErrores = false;
        
        try {
            
            $email = "";
            if (!isset($data))
            {
                $problemas["sistema"] = "no hay información de email y contraseña";
                $mostrarErrores = true;
            }
            else
            {
                if (!isset($data->email))
                {
                    $problemas["email"] = "no hay información de email";
                    $mostrarErrores = true;
                }
                if (!isset($data->password))
                {
                    $problemas["password"] = "no hay información de password";
                    $mostrarErrores = true;
                }
              
            }

            if ($mostrarErrores)
            {
                return response($problemas, 400);
            }

            $email = $data->email;
            $password = $data->password;
            

            $datosCliente = clientes::where([
                ['CorreoElectronico', '=', $email],
                ['confirmado', '=', '1']
            ])->first();

            if (!isset($datosCliente))
            {
                return response("usuario o contraseña incorrectos", 400);
            }

            $passwordComparacion = $datosCliente->Password;
            $passwordComparacion = Crypt::decryptString($passwordComparacion);
            
            if ($password != $passwordComparacion)
            {
                return response("usuario o contraseña incorrectos.", 400);
            }

            $idCliente = Crypt::encryptString($datosCliente->IdCliente);
            
            $info = new \stdClass();
            
            $info->idCliente = $idCliente;
            $info->nombre = $datosCliente->Nombre;


            return response()->json($info);
            
        } catch (Exception $th) {
            return $th->getMessage();
        }

        return 0;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //confirmacion de clave


        if(isset($id))
        {
            $estaSinConfirmar = clientes::where([
                ['claveConfirmacion', '=',$id],
                ['confirmado', '=', 0]
            ])->first();

            if (!isset($estaSinConfirmar))
            {
                return response("no existe clave para confirmación", 400);
            }

            $estaSinConfirmar->confirmado = true;
            $estaSinConfirmar->save();
            
            return 1;
        }

        return 0;

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function registrar(Request $data)
    {
        /*registrarse*/
        
        $problemas = ["name" =>"","lastname" =>"","email" =>"","password" =>"","password2" =>"", "newsletter" => "", "sistema" => ""];

        $mostrarErrores = false;

        $url_shop = env('URL_SHOP');
        $url_confirmacion = env('URL_CONFIRMACION');

        if (!isset($url_shop))
        {
            $problemas["sistema"] = "no se encontro configuracion de tienda url";
        }
        

        DB::beginTransaction();

        try {
            if (!isset($data->name))
            {
                $problemas["name"] = "falta nombre";
                $mostrarErrores = true;
            }
            if (!isset($data->lastname))
            {
                $problemas["lastname"] = "falta apellidos";
                $mostrarErrores = true;
            }
            if (!isset($data->email))
            {
                $problemas["email"] = "falta email";
                $mostrarErrores = true;
            }
            if (!isset($data->password))
            {
                $problemas["password"] = "falta contraseña";
                $mostrarErrores = true;
            }
            if (!isset($data->password2))
            {
                $problemas["password2"] = "falta repetir la contraseña";
                $mostrarErrores = true;
            }

            if (!isset($data->newsletter))
            {
                $problemas["newsletter"] = "falta si esta subscrito al newsletter";
                $mostrarErrores = true;
            }

            if ($mostrarErrores)
            {
                return response($problemas, 400);
            }

            $name = $data->name;
            $lastname = $data->lastname;
            $email = $data->email;
            $password = $data->password;
            $password2 = $data->password2;
            $newsletter = $data->newsletter;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $problemas["email"] = "no es un email valido";
                $mostrarErrores = true;
            }

            
            $exiteCorreo = clientes::where([
                ['CorreoElectronico', '=', $email]
            ])->first();
                
            if (isset($exiteCorreo))
            {
                $problemas["email"] = "ya esta registrado este correo";
                $mostrarErrores = true;
            }
            
            if (strlen($password) ==0)
            {
                $problemas["password"] = "debe ingresar contraseña";
                $mostrarErrores = true;
            }
            if (strlen($password2) ==0)
            {
                $problemas["password2"] = "debe ingresar contraseña ";
                $mostrarErrores = true;
            }

            if ($mostrarErrores)
            {
                return response($problemas, 400);
            }

            if ($password != $password2)
            {
                $problemas["password"] = "las contraseñas deben de coincidir";
                $mostrarErrores = true;
            }

            if ($mostrarErrores)
            {
                return response($problemas, 400);
            }


            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('GMT'));

            $password =  Crypt::encryptString($password); //123

            $cliente = new clientes();
            $cliente->CorreoElectronico = $email;
            $cliente->Password = $password;
            $cliente->Nombre = $name;
            $cliente->Apellidos = $lastname;
            $cliente->Vip = false;
            
            $cliente->FechaCambio = $date;
            $cliente->IdTipoMovimiento = 1;
            $cliente->IdUsuarioBase = 1;
            $cliente->confirmado = false;  //confirmae con el correo
            $cliente->newsletter = $newsletter;
            $cliente->claveConfirmacion = "";

            $cliente->save();

            $claveConfirmacion = Hash::make($cliente->IdCliente.$email);

            /*QUITAR CARACTERES*/
            $claveConfirmacion = str_replace("\\", "a", $claveConfirmacion);
            $claveConfirmacion = str_replace("/", "a", $claveConfirmacion);
            
            $cliente->claveConfirmacion = $claveConfirmacion;
            $cliente->save();

            $info = new \stdClass();
            $info->name =$name;
            $info->server = $url_shop;
            $info->claveConfirmacion = $url_confirmacion."confirmation.php?clave=".$claveConfirmacion;

            Mail::to($email)->send(new ConfirmacionMail($info));

            DB::commit();

            return true;    

        } catch (Exception $th) {
            return response($th->getMessage());
            DB::rollBack();
        }

        return false;
        
    }

    public function inicioFacebook(Request $request)
    {
        $url_shop = env('URL_SHOP');

        if (!isset($url_shop))
        {
            $problemas["sistema"] = "no se encontro configuracion de tienda url";
            return response(["request" => false, "message" => $problemas]);
        }   

        try{
            $name = $request->name;
            $lastname = $request->lastname;
            $email = $request->email;
            $password = $request->password;
        
            if (!isset($name))
            {
                $problemas["sistema"] = "no se encontro establecio nombre ";
                return response(["request" => false, "message" => $problemas]);
            }

            

            $exiteCorreo = clientes::where([
                ['CorreoElectronico', '=', $email]
            ])->first();
                
            if (isset($exiteCorreo))
            {
                $idCliente = Crypt::encryptString($exiteCorreo->IdCliente);
                $info = new \stdClass();
                
                $info->idCliente = $idCliente;
                $info->nombre = $exiteCorreo->Nombre;

                return response()->json($info);     
            }

            DB::beginTransaction();
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('GMT'));

            $password =  Crypt::encryptString($password); //123

            $cliente = new clientes();
            $cliente->CorreoElectronico = $email;
            $cliente->Password = $password;
            $cliente->Nombre = $name;
            $cliente->Apellidos = $lastname;
            $cliente->Vip = false;
            
            $cliente->FechaCambio = $date;
            $cliente->IdTipoMovimiento = 1;
            $cliente->IdUsuarioBase = 1;
            $cliente->confirmado = false;  //confirmae con el correo
            $cliente->newsletter = 0;
            $cliente->claveConfirmacion = "";

            $cliente->save();

            DB::commit();

            /*Registro de sesion de usuario*/ 
            $idCliente = Crypt::encryptString($cliente->IdCliente);
            $info = new \stdClass();
            
            $info->idCliente = $idCliente;
            $info->nombre = $cliente->Nombre;
            
            return response()->json($info);

        } catch (Exception $th) {
            return response(["request" => false, "message" => $th->getMessage()]);
            DB::rollBack();
        }

        return response(["request" => false, "message" => "Error interno"]);
    }

    public function inicioAdmin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        if( empty($username) )
            return redirect('/inicio')->with('message', 'Campo usuario vacio')->withInput();

        if( empty($password) )
            return redirect('/inicio')->with('message', 'Campo contraseña vacio')->withInput();

        $usuario = usuarios::where("Usuario", $username)->first();

        if( isset($usuario) ){
            if( $password == $usuario->Password ){
                $auth = ["idusuario" => $usuario["IdUsuario"], "nombre" => $usuario["Nombres"] . " " . $usuario["Apellidos"], "perfil" => $usuario["IdUsuarioBase"]  ];
                setcookie("auth", json_encode($auth), time()+3600);
                return redirect('/');
            }else{
                return redirect('/inicio')->with('message', 'Contraseña Incorrecta')->withInput();
            }
        }else{
            return redirect('/inicio')->with('message', 'No existe el usuario')->withInput();
        }
    }

    public function salirAdmin()
    {
        setcookie("auth", "", time() - 3600);
        unset($_COOKIE['auth']);
        return redirect('/inicio')->with('message', 'Sesion finalizada');
    }
}
