<template>
    <div id="videocomponente" class="container-fluid">
        <div class="container">
             <input type="file" 
                    @change="ArchivoSeleccionado" 
                    accept="image/x-png,image/jpeg"
                    class="form-control" ref="archivoOc"  placeholder="Archivo OC">
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            archivoVideo:'',
        }
    },
    methods: {

        ValorProgreso(evt){
            if (evt.lengthComputable) {
                var percent = Math.round(evt.loaded * 100 / evt.total);
                console.log(percent);
                //document.getElementById('progress').innerHTML = percent.toFixed(2) + '%';// Establecer el porcentaje de visualización de progreso
                //document.getElementById('progress').style.width = percent.toFixed(2) + '%';// Establecer el ancho de la barra de progreso completado
            }
            else {
                console.log("problema");
                //document.getElementById('progress').innerHTML = 'unable to compute';
            }
        },

        async SubirVideo()
        {
            var formData = new FormData();

            await axios.post(this.server +  "api/videos", formData)
                .then((resultado) => {
                    console.log(resultado.data);
                })
        },

        ArchivoSeleccionado: function(event)
        {
            
            this.archivoVideo = this.$refs.archivoOc.files[0];
            console.log("files", this.archivoVideo);
            
        }

    },
}
</script>