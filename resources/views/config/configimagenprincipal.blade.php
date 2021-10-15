@extends('layout')

@section('content')
<h4>CONFIGURACIÓN IMAGENES PRINCIPAL</h4>

<configindex />

  
@endsection

<style>

    button{
        text-decoration: none;
        padding: 10px;
        font-weight: 300;
        font-size: 15px;
        color: #ffffff;
        background-color: #000000;
        border-radius: 8px;
        border: 1px solid black;
        margin-left: 155px;
      }
    
      .switchBtn {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 28px;
    }
    .switchBtn input {display:none;}
    .slide {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        padding: 8px;
        color: #fff;
    }
    .slide:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 30px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slide {
        background-color: #177fc4;
        padding-left: 40px;
    }
    input:focus + .slide {
        box-shadow: 0 0 1px #01aeed;
    }
    input:checked + .slide:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        left: -20px;
    }
    .slide.round {
        border-radius: 34px;
    }
    .slide.round:before {
        border-radius: 50%;
    }
    </style>