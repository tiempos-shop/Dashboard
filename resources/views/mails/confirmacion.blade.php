<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Confirmación de Cuenta {{$info->name}}</title>
</head>
<body>
    <div style="color:#40408c; align-content: center; text-align: center;">
        <h2>Hola {{$info->name}}, Bienvenido a TiemposShop</h2>
    </div>
    <div style="margin: 10px;text-align: center;">
        Solo falta que confirmes el correo
    </div>

    <div style="text-align: center">
        Click en el siguiente enlace para confirmar
    </div>

    <div class="">
   

        <a href="{{$info->claveConfirmacion}}" class="href" style="padding:0.5rem;background-color:#222222;color:white;width: 100%;display: inline-block;text-align: center;">
            Confirmar
        </a>

    </div>
    
 
</body>
</html>