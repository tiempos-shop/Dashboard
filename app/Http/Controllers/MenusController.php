<?php

namespace App\Http\Controllers;

use App\menus;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaMenus = menus::get();

        return $listaMenus;
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
        if (!isset($request))
        {
            return response("no hay datos para continuar",400);
        }

        $idMenu = $request->idMenu;
        $menu = $request->menu;
        
        if (!isset($idMenu))
        {
            return response("no se establecio id menu",400);
        }
        if (!isset($menu))
        {
            return response("no se establecio nombre menun",400);
        }
        if (strlen($menu) == 0)
        {
            return response("debe ingresar un nombre para menu");
        }
        $menuRow = null;

        if($idMenu>0)
        {
            $menuRow = menus::where('idMenu', $idMenu)->first();
        }
        else
        {
            $menuRow = new menus();
        }
        $menuRow->menu = $menu;
        $menuRow->save();

        return $menuRow;

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
