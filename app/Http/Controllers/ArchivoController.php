<?php

namespace App\Http\Controllers;

use App\archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        DB::beginTransaction();

        try {
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
    
                    if ($info["tipo"] == "imgprod")
                    {
                        $rutaImg =  $info["ruta"];
                        $archivoBD->html = $rutaImg;
                        $archivoBD->rutaserver = $info["rutaserver"];
                        $archivoBD->tipo =  $info["tipo"];  
                    }
                    if ($info["tipo"] == "img" & $info["idGuardado"] == 0)
                    {
                        if (!isset($info["base64"]))
                        {
                            return response('no se establecio el archivo imagen a subir', 400);
                        }
    
                        $maxId = archivo::max('id');
    
                        $ubicacion = "img/archive/";
                        $rutaImg =  $info["ruta"];
                        $b64 = $info["base64"];
    
                        $b64 = preg_replace('/^data:image\/\w+;base64,/', '', $b64);
    
                        $type = explode(';', $info["base64"])[0];
                        $type = explode('/', $type)[1];
                        $bin = base64_decode($b64, true);
                        
                        $filename = $maxId.".".$type;
                        $rutaImagen =$ubicacion.$filename;
                        file_put_contents($rutaImagen, $bin);
                        
                        $archivoBD->html = $rutaImagen;
                        $archivoBD->rutaserver = $rutaImagen;
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
                    $archivoBD->estilo = (isset($info["estilo"]) ? $info["estilo"] : "" );
                    $archivoBD->save(); 
                }
                if ($info["eliminar"] == true)
                {
                    $archivoBD->delete();
                }
                
                
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return 0;
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
