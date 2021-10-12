@extends('layout')

@section('content')

<div class="p-4" style="width: 70%; margin-left: auto; margin-right: auto; background-color: rgb(240, 240, 240);">
    @if(session('message') != null)
        <span class="alert alert-info mb-2" style="width: 100%; display: block;"> {{ session('message') }} </span>
    @endif

    <h1 style="margin-left: 20px; margin-top: 20px;">Agregar usuario</h1>
    <br>
    <br>
    <form action="{{ url('/usuarios/guardar') }}" method="POST">
        <div style="text-align: left; width: 50%; float: left; background-color: rgb(240, 240, 240);">
            <label style="margin-left: 50px;"><strong>Nombre de Usuario</strong></label>
            <br><br>
            <label style="margin-left: 50px;"><strong>Correo Electrónico </strong></label>
            <br><br>
            <label style="margin-left: 50px;"><strong>Nombre</strong></label>
            <br><br>
            <label style="margin-left: 50px;"><strong>Apellidos</strong></label>
            <br><br>
            <label style="margin-left: 50px;"><strong>Contraseña</strong></label>
            <br><br>
            <label style="margin-left: 50px;"><strong>Telefono</strong></label> 
            <br><br>
            <label style="margin-left: 50px;"><strong>Perfil</strong></label>
            <br><br>
        </div>
        <div class="mb-4" style="text-align: center; width: 50%; float: right; background-color: rgb(240, 240, 240);">
            <input type="text" name="usuario" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="{{ old('usuario') }}" />
            <br><br>
            <input type="text" name="email" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="{{ old('email') }}" />
            <br><br>
            <input type="text" name="nombres" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="{{ old('nombres') }}" />
            <br><br>
            <input type="text" name="apellidos" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="{{ old('apellidos') }}" />
            <br><br>
            <input type="password" name="password" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="{{ old('password') }}" />
            <input type="hidden" name="numeroEmpleado" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="12345" />
            <br><br>
            <input type="text" name="telefono" style="border-radius: 7px; margin-left: 100px; width: 200px;" value="{{ old('telefono') }}" />
            <br><br>
            <select style="margin-left: 50px;" name="IdUsuarioBase" value="{{ old('IdUsuarioBase') }}" >
                <option value="1">ADMINISTRADOR</option>
                <option value="2">USUARIO</option>
            </select>
            <br><br>
        </div>
        <div style="text-align: right; margin-right: 50px;">
            <button class="btn btn-success">Guardar</button>
            <a href="{{ url('/usuarios') }}" class="btn btn-info">Cancelar</a>
        </div>
    </form>
</div>
@endsection