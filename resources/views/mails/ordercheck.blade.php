<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Confirmación de Pedido en TiempoShop {{$info->nombre}}</title>
</head>
<body>

    <div style="text-align: center;">  
        <style type="text/css">div.image {max-width: 40px;max-height: 40px;}</style>
        <br><br>
        <h5>Confirmación de Pedido en TiempoShop</h5>
        <h1>¡Agradecemos tu compra!</h1>
        <br>
        <img style="width: 300px; height: 300px;" src="{{url('/').'/images/mail/docok.png'}}" />
        <br><br>
        <h3>Datos del pedido</h3>
        <br>
        <div style="">
            <div>{{url('/').'/images/mail/docok.png'}}</div>
            <div>{{url('/')}}</div>
            <label><strong>No. Pedido:</strong> TS {{$info->IdPedido}}</label>
            <br>
            <label><strong>Fecha:</strong> {{$info->fechaPedido}} </label>
            <br>
            <label><strong>Producto:</strong> </label>
            <br>
            <span>Cartera TACUBAYA</span>
            <br>
            <label><strong>Precio:</strong> {{$info->total}} {{$info->moneda}}</label>
            <br>
            <label><strong>Cantidad:</strong> 1 producto</label>
            <br>
        </div>
    </div>
    
 
</body>
</html>