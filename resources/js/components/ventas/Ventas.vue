<template>
    <div style="color:black">
        <h3>Ventas</h3>
    
        <table style="width: 100%; text-align: center; background-color: white;" >
            <thead>
                <tr>
                    <td style="width:5%;"> <input type="checkbox"> </td>
                    <th style="width: 5%;">Id Venta</th>
                    <th style="width: 5%;">Id Cliente</th>
                    <th style="width: 20%;">Cliente</th>
                    <th style="width: 15%;">Subtotal</th>
                    <th style="width: 15%;">Total</th>
                    <th style="width: 5%;">Moneda</th>
                    <th style="width: 10%;">Fecha</th>
                    <th style="width: 10%;">Acción</th>
                </tr>
            </thead>
        
        <tbody>
            <tr v-for="(venta, index) in listaVentas" :key="index">
                <td> <input type="checkbox"> </td>
                <td><label>{{venta.IdPedido}}</label></td>
                <td><label>{{venta.IdClientes}}</label></td>
                <td><label>{{venta.nombreCliente}}</label></td>
                <td><label >{{Number(venta.subtotal) | moneda}}</label></td>
                <td><label >{{Number(venta.total) | moneda}}</label></td>
                <td><label >{{venta.moneda}}</label></td>
                <td><label >{{venta.FechaPedido}}</label></td>
                <td>
                    
                    <a :href="server + 'ventas/detalle/' + venta.IdPedido" class="btn btn-sm btn-primary"> <i class="fas fa-eye"></i> ver </a>

                </td>
            </tr>
            

        
        </tbody>
        </table>
    </div>
</template>
<script>
export default {
    data() {
        return {
            listaVentas:[]
        }
    },
    mounted() {
        this.ObtenerVentas();
    },
    methods: {
        async ObtenerVentas()
        {
            await axios.get(this.server + "api/ventas/obtener")
            .then((resultado) =>{
                console.log(resultado.data);
                this.listaVentas = resultado.data;
            })
        }
    },
}
</script>