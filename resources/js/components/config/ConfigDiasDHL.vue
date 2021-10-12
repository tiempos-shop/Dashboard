<template>
    <div>
        <h4>Config DHL</h4>

        <div class="text-center" v-if="status.cargando">
            <i class="fas fa-circle-notch"  
                                 spin/> 
            <span>Obteniendo datos</span>
        </div>
        <div style="border: 1px solid black; height: 50%; width: 50%; margin: 0 auto;" class="bg-white" v-if="!status.cargando">
            <div style=" margin-top: 50px; text-align: center;" >
                <h3>Agregar días extra para entrega DHL</h3>
            <br><br>
                <label>Número de días extra</label>
                <input type="number" style="width: 50px; text-align: center;" v-model="dias">
            <br>
            <br>
                <label>Número de días actuales: {{diasText}} días</label>
            <br><br>
                <button class="btn btn-primary" 
                    :disabled="status.enviando"
                    @click="GuardarConfiguracion()">Agregar
                    <span v-if="status.enviando"></span>
                </button>

                <div class="container mt-3">
                    <div class="bg-success p-2 text-white" v-if="status.guardado"> 
                        Configuración guardada
                    </div>

                    <div class="bg-danger p-2  text-white" v-if="errores.sistema.length>0">
                        {{errores.sistema}}
                    </div>
                </div>
            <br><br><br>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            dias:'',
            diasText:'',
            status:{
                enviando:false,
                guardado:false,
                cargando:true,
            },
            errores:{
                sistema:''
            }
        }
    },
    mounted() {
        this.ObtenerConfiguracion();
    },
    methods: {
        async ObtenerConfiguracion()
        {
            this.sistema = "";
            this.status.cargando = true;
            
            await axios.get(this.server + "api/config/diasdhl")
            .then((resultado) => {
                console.log(resultado.data);
                if (!isNaN(resultado.data))
                {
                    this.diasText = resultado.data;
                }
                else
                {
                    this.errores.sistema = "No se obtuvo la configuración de días";
                }
            })
            this.status.cargando = false;
        },
        async GuardarConfiguracion()
        {
            this.status.guardado = false;
            if (!this.ValidarDatos())
            {
                return;
            }
            this.errores.sistema = "";
            
            this.status.enviando = true;
            const datos = { dias: this.dias};
            await axios.post(this.server + "api/config/diasdhl", datos)
            .then((resultado) => {
                console.log(resultado.data);
                if (resultado.data == 1 )
                {
                    this.status.guardado = true;
                    this.diasText = this.dias;
                }
                else
                {
                    this.errores.sistema = "No se actualizo la configuración";
                }
            })
            this.status.enviando = false;
        },
        ValidarDatos()
        {
            var esValido = true;
            this.errores.sistema ="";

            if (isNaN(this.dias))
            {
                this.errores.sistema = "debe ingresar un valor número de días";
                
            }
            if (this.dias.length ==0)
            {
                this.errores.sistema = "debe ingresar un valor número de días";
            }
            if (Number(this.dias)>3)
            {
                this.errores.sistema = "Debe ser un número menor a 3";
            }

            if (Number(this.dias)<0)
            {
                this.errores.sistema = "Debe ser un número mayor a 0";
            }

            if (this.errores.sistema.length>0)
            {
                esValido = false;
            }

            return esValido;
        }
    },
}
</script>