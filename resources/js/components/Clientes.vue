<template>
    <div class="container">
        <h3>Lista de Clientes</h3>

         <ul class="list-group" id="tablaclientes" >
            <li class="list-group-item columnas p-1">
                <div class="row">
                    <div class="col-md-1">
                    ID
                    </div>
                    <div class="col-md-5">
                        <span >NOMBRE</span>
                    </div>
                    <div class="col-md-4">
                        CORREO
                    </div>
                    <div class="col-md-2">
                        VIP
                    </div>
                    
                </div>
            </li>
          
            <li class="list-group-item p-2"  v-for="(cliente, index) in Listaclientes" :key="index">
                <div class="row"    >
                    <div class="col-md-1">
                    <span class="text-muted">{{cliente.IdCliente}}</span>
                    </div>
                    <div class="col-md-5">
                        <strong class="text-dark text-monospace">{{cliente.Nombre}} {{cliente.Apellidos}}</strong>
                    </div>
                    <div class="col-md-4">
                        <strong>{{cliente.CorreoElectronico}}</strong>
                        
                    </div>
                    <div class="col-md-2">
                        <span
                            class="text-muted">{{cliente.Vip}}</span>
                    </div>
                 
                </div>
            </li>
        </ul>

    </div>
</template>
<script>
export default {
    data() {
        return {
            Listaclientes:[]
        }
    },
    mounted() {
        this.Obtenerclientes();
    },
    methods: {
        async Obtenerclientes()
        {
            await axios.get("api/clientes")
            .then((resultado) =>{
                console.log("clientes", resultado.data);
                if (resultado.data != null)
                {
                    
                    this.Listaclientes=resultado.data;
                }
            })
        }
    },
}
</script>