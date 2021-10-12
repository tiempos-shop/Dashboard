@extends('layout')

@section('content')

    <div>
        @if( session('message') != null )
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
        @endif

    <h1 style="margin-left: 20px; margin-top: 20px;">Roles de Usuario</h1>
    <br>
    <div style="text-align: right; margin-right: 50px;">
        <a href="{{ url('/usuarios/guardar')}}" class="btn btn-primary"><strong>Agregar Usuario</strong></a>
    </div>
    <br>
    <table class="table table-light table-striped" style="width: 100%; text-align: center; background-color: white;">
        <thead class="thead-dark">
            <tr>
                <th style="width:14%;">Usuario</th>
                <th style="width:14%;">E-mail</th>
                <th style="width:14%;">Nombre</th>
                <th style="width:14%;">Apellidos</th>
                <th style="width:14%;">Telefono</th>
                <th style="width:10%;">Perfil</th>
                <th style="width:20%;">Acción</th>
             
            </tr>
        </thead>

        <tbody>
                @foreach ($usuarios as $usuario)
            <tr>
                <td><label type="text" style="height: 35px; width: 100px;"> {{$usuario->Usuario}}</label></td>
                <td><label type="text" style="height: 35px; width: 200px;">{{$usuario->CorreoElectronico}}</label></td>
                <td><label type="text" style="height: 35px; width: 100px;">{{$usuario->Nombres}}</label></td>
                <td><label type="text" style="height: 35px; width: 100px;">{{$usuario->Apellidos}}</label></td>
                <td><label type="text" style="height: 35px; width: 100px;">{{$usuario->Telefono}}</label></td>
                <td><label type="text" style="height: 35px; width: 100px;">{{$usuario->IdUsuarioBase}}</label></td>
                <td><a href="{{ route('usuarios.show', [ 'id' => $usuario->IdUsuario]) }}" class="btn btn-info"><strong>Editar</strong></a>
                <form action="{{ route('usuarios.destroy', $usuario->IdUsuario) }}" method="POST">
                    @csrf
                    @method('DELETE')                 
                    <button type="submit" onclick="return confirm('Are you sure?')" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                </form>
                </td>
            </tr>
                @endforeach
        </tbody>
    </table>
    <br>  

    </div>

@endsection