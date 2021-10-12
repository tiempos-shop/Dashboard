@extends('layout')

@section('content')
    
    <input type="hidden" value="{{ request()->id }}" id="idProducto">
    <productosguardar></productosguardar>
@endsection