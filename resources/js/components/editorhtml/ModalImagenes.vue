<template>
    <div>
        <div class="modal-backdrop show" @click="$emit('cerrar')"></div>
        <transition name="modalImagenes">
            
        <div class="modal modal-mask " :class="claseComponente"  >
            <div 
                class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"   
                style="max-width: 850px;"
                role="document">
                <div class="modal-content p-2 m-0" style="height: 85%;">
                       
                    <div class="modal-body p-0 m-0 ">
                        <div class="row m-2">
                            <div class=" container mb-4  col-md-4"
                            v-for="(archivoImg, index) in imagenes"
                            :key="index  + 'IMG'">

                            <img id="imagenProducto"
                                class="img-thumbnail"
                                style="width:90%; cursor:pointer"
                                @click="AgregarImagen(archivoImg)"
                                v-bind:src="archivoImg.ruta"
                                alt="imagen de producto" />


                        </div>
                        </div>
                        
                    </div>

                    <div class="modal-footer m-0 p-0">
                        <button class="btn btn-light" @click="$emit('cerrar')">cerrar</button>
                    </div>
                </div>
            </div>

            
        </div>
        </transition>
    </div>
</template>
<script>


export default {
    data() {
        return {
            claseComponente:'fade',
            imagenes:[],
        }
    },
    watch:{
    },
    methods: {
        ObtenerImagenes()
        {
            axios.get(this.server + "api/imagenesproductos")
            .then((resultado) =>{
                this.imagenes = resultado.data;
                console.log(resultado.data);
            })   
        },
        AgregarImagen(imagen)
        {
            this.$emit('imagen', imagen);
        }
    },
    mounted() {
        let app = this;
        setTimeout(function() { app.claseComponente = 'fade show'; }, 100);
        this.ObtenerImagenes();
    },

}
</script>

<style >
    .show {
        display: block;
    }
</style>