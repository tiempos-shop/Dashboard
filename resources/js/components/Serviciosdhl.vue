<template>
   <div class="container">
            <h2>ENVIOS PENDIENTES</h2>
            <div class="row">
                <div class="col-md-12">
                    <h4>POR ATENDER</h4>

                    <ul class="list-group">
                        <li class="list-group-item" v-if="pendientes.length ==0">
                            <div class="text-muted">No hay servicios pendientes de confirmar</div>
                        </li>
                        <li v-for="(servicio, index) in pendientes" :key="index" class="list-group-item text-dark">
                            <div class="row">
                                <div class="col-md-6 row">
                                    <div class="col-md-1">
                                        <strong>#{{servicio.IdPedido}}</strong>
                                    </div>
                                
                                    
                                    <div class="col-md-4 ">
                                        <p class="p-1 m-0">{{servicio.nombre_recibe}}</p>
                                        
                                    </div>
                                    <div class="col-md-1">
                                        <small>CP: </small> {{servicio.CodigoPostal}}
                                    </div>

                                  
                                </div>
                                <div class="col-md-4 row">
                                    <div class="col-md-12">
                                        <span>{{Number(servicio.total) | moneda}}</span>
                                        <span class="pl-1 text-muted">{{servicio.moneda}} TOTAL</span>
                                        <div>
                                            <span>{{Number(servicio.precioEnvio) | moneda}} ENVIO</span>
                                        </div>
                                        <div>
                                            <span>{{Number(servicio.subtotal) | moneda}} SUBTOTAL</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                      <div class="col-md-12">
                                        <button class="btn btn-primary btn-sm" @click="ConfirmarEmpaquetado(index, servicio)">
                                            EMPAQUETAR
                                            <span v-if="servicio.enviandoInfo">
                                                ...
                                            </span>
                                        </button>
                                        <button class="btn btn-warning mt-2">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="p-1 text-danger" v-if="servicio.problemas.length>0">
                                        {{servicio.problemas}}
                                    </div>
                                </div>
                                <div class="col-md-4 p-2 text-primary rounded ">
                                    {{servicio.nombre_cliente}}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 mt-4">
                    ATENDIDOS

                    <ul class="list-group">
                        <li class="list-group-item" v-if="empaquetados.length ==0">
                            <div class="text-muted">No hay servicios Empaquetados</div>
                        </li>
                        <li v-for="(servicio, index) in empaquetados" :key="index" class="list-group-item">
                            <div class="row">
                             
                                <div class="col-md-10 row">
                                    <div class="col-md-2">
                                        <strong>{{servicio.IdPedido}}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span>{{servicio.total | moneda}}</span>
                                        <span class="pl-1 text-muted">{{servicio.moneda}}</span>
                                    </div>
                                    <div class="col-md-4 text-dark">
                                        {{servicio.nombre_recibe}}
                                    </div>
                                    <div class="col-md-1">
                                        {{servicio.CodigoPostal}}
                                    </div>
                                </div>
                            
                                <div class="col-md-2 row">
                                    <div class="col-md-4">
                                        <button class="btn btn-success btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        @click="verArchivo(servicio.filename, servicio.tipo_archivo, servicio.idpedido)">
                                            ARCHIVO PDF
                                        </button>
                                    </div>
                                </div>
                      
                                <div class="col-md-6">
                                    <span class="text-muted ">trackingnumber:</span> <span class="text-monospace">{{servicio.trackingnumber}}</span> 
                                </div>

                                <div class="col-md-4 p-2 border border-info rounded ">
                                    {{servicio.nombre_cliente}}
                                </div>
                                 

                            </div>
                        </li>
                    </ul>
          
           

                </div>
            </div>

    

        </div>
</template>
<script>
export default {
    data() {
        return {
            pendientes:[],
            empaquetados:[],
            status:{
                enviandoInfo: false,
            }
        }
    },
    methods: {
        async ObtenerEnviosPendientes(){
            await axios.get("api/pedido/pendientes")
            .then((resultado) =>{
                console.log("pendientes", resultado.data);
                if (resultado.data != null)
                {
                    var pendientes = resultado.data;

                    pendientes.forEach(element => {
                        element.enviandoInfo = false;
                        element.problemas = "";
                    });

                    this.pendientes=pendientes;
                }
            })
        },
        async ObtenerEnviosEmpaquetados(){
            await axios.get("api/envios_movimiento")
            .then((resultado) =>{
                console.log("movs", resultado.data);
                if (resultado.data != null)
                {
                    
                    this.empaquetados=resultado.data;
                }
            })
        },
        async verArchivo(filename,tipo_archivo, idpedido)
        {
         
            //html: '<iframe src="' + filename+'.'+ tipo_archivo +'" height="800px" width="100%" frameborder="0" ></iframe>',
            this.$swal.fire({
                title : 'PDF DHL ENVIO',
                html: '<iframe src="serviciosdhl/download/' + filename+'/'+ tipo_archivo +'/0/'+ idpedido + '" height="500px" width="100%" frameborder="0" ></iframe>',
                width: '70%'
            });
        },
        async ConfirmarEmpaquetado(index, servicio){
            console.log("index", index);
            console.log("servicio", servicio);
            
            var empaquetar = false;

            await this.$swal.fire({
                title:'Deseas procesar el envio?',
                confirmButtonText : 'Si, Procesar',
                cancelButtonText: 'no, cerrar',
                showCancelButton: true,
            }).then((respuesta)=>{
                empaquetar = respuesta.isConfirmed;                
            });
            
            servicio.enviandoInfo = true;

            if (empaquetar)
            {

                await axios.post("api/envios_movimiento", servicio)
                .then((resultado) => {
                    console.log("resultado", resultado.data);
                    
                    var datos = resultado.data;
                    if (datos>0)
                    {
                        
                        this.pendientes.splice(index,1 );
                        this.ObtenerEnviosEmpaquetados();
                    }
                }).catch((problema)=>{
                    servicio.enviandoInfo = false;
                    if (problema.response.data)
                    {
                        console.log("problema", problema.response.data)
                        servicio.problemas = problema.response.data;
                        console.log(servicio);
                    }
                    else
                    {
                        servicio.problemas = "Ocurrio un problema";
                    }
                    console.log("problema", problema);
                });

            }
            servicio.enviandoInfo = false;

        }
        },
        mounted()
            {
                this.ObtenerEnviosEmpaquetados();
                this.ObtenerEnviosPendientes();
            }
}
</script>