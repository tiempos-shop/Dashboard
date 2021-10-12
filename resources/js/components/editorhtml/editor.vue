<template>
    <div class="container">

        <!-- modal para texto -->
        <button class="btn btn-success" @click="MostrarModal()">Swal Texto</button>        


        <div class="row" id="app">
            <div class="col-md-12 p-4">
                <div >
                    <button class="btn btn-primary btn-sm" @click="AgregarElementoTexto('p')">Agregar Texto</button>
                    <button class="btn btn-primary btn-sm" @click="AgregarElementoTexto('img')">Agregar Imagenes</button>
                </div>
            </div>
            <div class="col-md-8 bg-light" id="contenedor">
                
                <div v-for="(item, index) in elementos" class="bg-white" :key="index" :index="'el' + index" style="min-height: 50px;">
                    <span class="text-dark">{{index}}</span>
                    <div v-if="item.tipo =='p'">
                        <p>{{item.html}}</p>
                    </div>
                </div>
                
            </div>
            <div class="col-md-4" >
                <div v-for="(item, index) in elementos" :key="index" style="height: 100px;">
                    <span>h: {{AltoElemento(0)}}</span>
                    <button class="btn btn-info btn-sm" @click="AgregarElementoTexto('p')">Agregar P</button>
                    <button class="btn btn-primary btn-sm" @click="AgregarElementoTexto('img')">Agregar IMG</button>
                    <button class="btn btn-primary btn-sm" @click="AgregarElementoTexto('youtube')">Agregar Youtube</button>
                    <button class="btn btn-danger btn-sm" @click="Eliminar(index)">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Quill from '../../../../public/js/quill.js';
import  '../../../../public/css/quill.snow.css';

export default {
    data() {
        return {
            elementos:[],
        }
    },
    methods: {
        AgregarElementoTexto(tipo)
        {
            this.elementos.push({id:this.elementos.lenght, html:'hola', tipo:tipo});
        },
        Eliminar(index)
        {
            this.elementos.splice(index, 1);

        },
        AltoElemento(index)
        {
            console.log(index);
            this.$nextTick(()=>{
                var alto = (document.getElementById('el' + index).style.height == null ? '100' : document.getElementById('el' + index).style.height );
                console.log(alto);
            });

    
            return 100;
        },
        async MostrarModal()
        {
            
            var respuestaTexto = this.$swal.fire({
                title : 'Texto',
                html: '<div id="editorTexto"></div>',
                width: '70%'
            });

            var quill = new Quill('#editorTexto', {
                theme: 'snow'
            });

            console.log( await respuestaTexto);
        }
    }
}
</script>