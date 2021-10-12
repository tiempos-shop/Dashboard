@extends('layout')

@section('content')
    <input type="hidden" id="idVenta" value="{{ request()->id }}">
    
    <ventasdetalle></ventasdetalle>
    
@endsection