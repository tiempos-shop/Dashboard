@extends('layout')

@section('content')
<h4>CONFIGURACIÓN IMAGENES PRINCIPAL</h4>


<div style="border: 1px solid black; height: 60%; width: 50%; margin: 0 auto;" class="bg-white">
    <div style="margin-top: 50px; text-align: center;">
      <h3>Opciones de fondo para página de inicio</h3>
      <br><br>
     <img style=" width: 28px;" src="https://img.icons8.com/material-outlined/24/000000/image-gallery.png" />
     <label>Dos imágenes en pantalla dividida</label>
     <label class="switchBtn" style="margin-left: 10px; margin-bottom: -10px;">
      <input type="checkbox">
      <div class="slide round"></div>
    </label>
     <br>
     <img src="https://img.icons8.com/windows/32/000000/add-image.png"/>
     <img style="margin-left: 20px;" src="https://img.icons8.com/windows/32/000000/add-image.png"/>
     <br>
     <br>
     <img style="width: 28px;" src="https://img.icons8.com/windows/32/000000/picture.png"/>
     <label>Una imágen en pantalla completa</label>
     <label class="switchBtn" style="margin-left: 15px; margin-bottom: -10px;">
         <input type="checkbox">
         <div class="slide round"></div>
     </label>
     <br>
     <img  src="https://img.icons8.com/windows/32/000000/add-image.png"/>
     <br>
     <br>
     <img style="width: 28px;" src="https://img.icons8.com/fluency-systems-regular/48/000000/video.png"/>
     <label>Un video en pantalla completa</label>
     <label class="switchBtn" style="margin-left: 35px; margin-bottom: -10px;">
         <input type="checkbox">
         <div class="slide round"></div>
     </label>
     <br>
     <img src="https://img.icons8.com/windows/32/000000/add-image.png"/>
     <br>
     <br>
     </div>
  </div>

  
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