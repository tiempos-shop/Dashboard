<template>
    <div>
        <div class="container">

            <h3>Detalle de la venta</h3>

            <div class="container mt-2  p-4 shadow-sm border bg-white mb-4 text-dark">
                <div class="row">
                    <div class="col-md-2">Pedido: #<label>{{venta.IdPedido}}</label></div>
                    
                    <div class="col-md-8"><strong>{{venta.nombreCliente}}</strong></div>
                    <div class="col-md-2"><label >{{venta.FechaPedido}}</label></div>
                </div>

                <div class="row border-bottom">
                    <div class="col-md-3"> <label >Subtotal {{Number(venta.subtotal) | moneda}}</label></div>
                    <div class="col-md-3"><label >Envio {{Number(venta.precioEnvio) | moneda}}</label></div>
                    <div class="col-md-3"><label >Total {{Number(venta.total) | moneda}}</label></div>
                    <div class="col-md-2"><label >Moneda {{venta.moneda}}</label></div>
                    
                </div>
                
            </div>

            <ul class="list-group" id="tablaproductos" >
                <li class="list-group-item columnas p-1">
                    <div class="row">
                        <div class="col-md-1">
                        ID
                        </div>
                        <div class="col-md-2">
                            <span >SKU</span>
                        </div>
                        <div class="col-md-4">
                            Nombre
                        </div>
                        <div class="col-md-2">
                            Cantidad
                        </div>
                        <div class="col-md-1">
                            costo
                        </div>
                        <div class="col-md-1">
                            precio
                        </div>
               
                    </div>
                </li>
            
                <li class="list-group-item p-2 text-dark"  v-for="(producto, index) in ListaProductos" :key="index">
                    <div class="row"    >
                        <div class="col-md-1">
                        <span class="text-muted">{{producto.idProducto}}</span>
                        </div>
                        <div class="col-md-2">
                            <strong class="text-info text-monospace">{{producto.sku}}</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>{{producto.nombre}}</strong>
                            
                        </div>
                        <div class="col-md-2 text-center">
                            <span
                                
                                class="text-muted">{{producto.Cantidad}}</span>
                        </div>
                        <div class="col-md-1">
                            <strong class="text-monospace">{{producto.costo | moneda}}</strong>
                        </div>
                        <div class="col-md-1">
                            <strong class="text-monospace">{{producto.precio | moneda}}</strong>
                        </div>
                      
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            venta:{
                IdPedido:0, IdCliente:0, nombreCliente:'', 
                subtotal:0, total:0, moneda:'',
                FechaPedido:''},
            ListaProductos:[],
            idVenta:0,
        }
    },
    mounted() {
        this.ObtenerVenta();
    },
    methods: {
        async ObtenerVenta()
        {
            var idVenta = document.getElementById('idVenta').value;
            
            console.log("idventa", idVenta);

            await axios.get(this.server + "api/ventas/porId/" + idVenta)
            .then((resultado) => {
                console.log("resultado", resultado);
                this.venta = resultado.data;
                this.ObtenerDetalle(idVenta);
            })
        },
        async ObtenerDetalle(idVenta)
        {
            await axios.get(this.server + "api/ventas/detalle/" + idVenta)
            .then((resultado) => {
                console.log("detalle", resultado.data);
                this.ListaProductos = resultado.data;
            })
        }
    },
}
</script>