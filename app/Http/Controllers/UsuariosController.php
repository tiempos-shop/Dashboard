<?php

namespace App\Http\Controllers;

use App\usuarios;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = usuarios::get();
        
        return view('usuarios')->with(["usuarios" => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ("usuarioseditar");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario=null;
       
            
        if($request->idusuario > 0)
        {
                $usuario = usuarios::where("IdUsuario", $request->idusuario)->first();
        } 
        else  
        { 
                $usuario = new usuarios();
        }
        
        if(!isset($usuario))
        {
            return response("No se encontró usuario",400);

        }

        $usuario->Nombres = $request->nombres;
        $usuario->Apellidos = $request->apellidos;
        $usuario->NumeroEmpleado = $request->numeroEmpleado;
        $usuario->Usuario = $request->usuario;
        $usuario->Password = $request->password;
        $usuario->CorreoElectronico = $request->email;
        $usuario->FechaCambio = date('Y-m-d');
        $usuario->Telefono = $request->telefono;
        $usuario->IdTipoMovimiento = 1;
        $usuario->IdUsuarioBase = $request->IdUsuarioBase;


        if ($usuario->save()) {
            return redirect('usuarios/')->with('message', 'Operación Exitosa');
        }else{
            return redirect('usuarios/guardar')->with('message', 'Error al crear el usuario')->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = usuarios::where('IdUsuario', $id)->first();

        return view('usuariosedit', ['usuario' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = usuarios::where('IdUsuario', $id)->first();

        return view('usuarioeditar', $usuario);
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
        $usuario = usuarios::find($id);
        if( $usuario->delete() ){
            return redirect('usuarios')->with('message', 'Operación Exitosa');
        }else{
            return redirect('usuarios')->with('message', 'No se pudo eliminar');
        }
    }
}
