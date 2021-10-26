<?php

namespace App\Http\Controllers;

use App\archivo;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $archivoBD = archivo::get();

        return $archivoBD;
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
    public function store(Request $request)
    {
        $datos = $request->elementos;

        foreach ($datos as $info) {
            $archivoBD = null;

            /*se crea un elemento o se busca el actual*/
            if ($info["idGuardado"] == 0)
            {
                $archivoBD = new archivo();
            }
            else
            {
                $archivoBD = archivo::where('id',$info["idGuardado"])->first();
            }

            /*se guardara si es falso*/
            if ($info["eliminar"] == false)
            {
                

                if ($archivoBD == null)
                {
                    return response('no se encontro el registro de archivo anterior', 400);
                }

                if ($info["tipo"] == "img" || $info["tipo"] == "imgprod")
                {
                    $rutaImg =  $info["ruta"];
                    $archivoBD->html = $rutaImg;
                    $archivoBD->rutaserver = $info["rutaserver"];
                    $archivoBD->tipo =  $info["tipo"];  
                }
                if ($info["tipo"] == "p")
                {
                    $archivoBD->html =  $info["html"];
                    $archivoBD->tipo =  $info["tipo"];    
                    $archivoBD->rutaserver = 0;
                }

                if ($info["tipo"] == "sec")
                {
                    $archivoBD->html =  $info["html"];
                    $archivoBD->tipo =  $info["tipo"];
                    $archivoBD->rutaserver = 0;
                }

                $archivoBD->save(); 
            }
            if ($info["eliminar"] == true)
            {
                $archivoBD->delete();
            }
            
            
        }

        return 1;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
