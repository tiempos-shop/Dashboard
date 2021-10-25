<template>
    <div class="container">
        
        <modal-imagenes @cerrar="status.verModalImagenes = false" @imagen="AgregarElementoTexto('imgprod', $event)" v-if="status.verModalImagenes"></modal-imagenes>

        <div v-if="status.cargandoInfo">
            <i class="fas fa-circle-notch fa-spin" spin />
            <span class="ml-2">Cargando configuración</span>
        </div>

        <div class="row" v-if="!status.cargandoInfo">
            
            <div class="col-md-12">
                <small class="text-muted">Seleccione una opcion para agregar</small>
            </div>
            <div class="row col-md-8" >
                <div class="col-md-2">
                    <button @click="AgregarElementoTexto('p')"
                        class="btn bg-white " >
                        <span class="pr-2" style="color:Tomato">
                            <i class="fas fa-italic"></i>
                        </span>
                        <strong class="text-center p-0 m-0">Texto</strong>
                        
                    </button>
                </div>
                <div class="col-md-2">
                    <button @click="AgregarElementoTexto('youtube')"
                        class="btn bg-white"  
                        style="color:green"
                        >
                        <span  class="pr-2"> 
                            <i class="fas fa-video" >  </i>
                        </span>
                        <strong class="text-dark">Youtube</strong>
                    </button>
                </div>
                <div class="col-md-2">
                    <div class="custom-file bg-white" style="display: contents">
                        <input
                        type="file"
                        @change="AgregarImagen('SubirImg1')"
                        id="SubirImg1"
                        accept="image/x-png,image/jpeg"
                        class="custom-file-input mb-2 bg-white"
                        style="width: 1%"
                        placeholder="Imagen guia de tallas"
                        ref="SubirImg1"
                        />
                        <label
                        for="SubirImg1"
                        class=" btn bg-white"
                        >
                        <div class="ml-1"><i class="fas fa-images"></i> <span class="ml-1 text-dark">Imagen</span></div>
                        
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn bg-white" @click="status.verModalImagenes = true">Imagenes Productos</button>
                </div>
                <div class="col-md-3">
                    <button class="btn bg-white">
                        Texto Sección
                    </button>
                </div>
            </div>
            <div class="col-md-8 p-2 row">
                <input type="text" class="form-control col-md-8" v-model="seccion" v-on:input="seccion =seccion.toUpperCase()" placeholder="Agregar Texto de Sección">
                <button class="btn btn-primary"  @click="AgregarSeccion()">Agregar</button>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary btn-block" @click="GuardarArchive()"
                    :disabled="status.guardandoInfo" :class="status.guardado ? 'btn-success' : 'btn-primary'"
                    >GUARDAR</button>

                <div v-if="errores.sistema.length > 0" class="text-danger p-1">
                    {{errores.sistema}}
                </div>
            </div>
            <div class="col-md-2">
                <div id="t" style="position: fixed;top: 50vh;">
                    <div v-for="(item, index) in elementos" class="bg-white  p-1" :key="index" :index="'men' + index" >
                        <div v-if="item.tipo == 'sec'">
                            <span style="border-top: 1px solid black; border-bottom: 1px solid black;">{{item.html}}</span>
                        </div>
                    </div>                 
                </div>
   
            </div>
            <div class="col-md-8 bg-light text-dark mt-4" id="contenedor" style="min-height:20px;">
                
                <div v-for="(item, index) in elementos" class="bg-white  p-1" :key="index" :index="'el' + index" >
                    
                    <div :id="'elemento' +  item.id" v-if="!item.eliminar">
                        <div v-if="item.tipo =='p'">
                            <div v-html="item.html"></div>
                            
                        </div>
                        <div v-if="item.tipo == 'img' || item.tipo == 'imgprod'">
                            <img
                                class="img-thumbnail"
                                v-if="
                                item.ruta.length > 0 ||
                                item.base64.length > 0
                                "
                                style="width: 90%"
                                v-bind:src="
                                item.ruta.length > 0
                                    ? item.ruta
                                    : item.base64
                                "
                                alt="imagen" />
                        </div>

                        <div v-if="item.tipo == 'sec'">
                            <span style="border-top: 1px solid black; border-bottom: 1px solid black;">{{item.html}}</span>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            <div class="col-md-2 mt-4" >
                <div v-for="(item, index) in elementos" :key="index" >
                    <div :id="'divborrar' + item.id" v-if="!item.eliminar">
                        <button class="btn btn-primary p-1 m-0" :id="'editar' + item.id" :ref="'editar' + index"  @click="EditarElemento(item.id, index)">Editar</button>
                        <button class="btn btn-danger p-1 m-0" :id="'borrar' + item.id" :ref="'borrar' + index"  @click="item.eliminar = true;">Borrar</button>
                    </div>
                    
 
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Quill from '../../../../public/js/quill.js';
import  '../../../../public/css/quill.snow.css';
import ModalImagenes from './ModalImagenes.vue';

export default {
    data() {
        return {
            elementos:[],
            status:{
                cargandoInfo:true,
                verModalImagenes:false,
                enviandoInfo:false,
                guardado:false,
                guardandoInfo:false
            },
            seccion:'',
            errores:{
                sistema:'',
            }
        }
    },
    components : {
        ModalImagenes
    },
    methods: {
        AgregarSeccion()
        {
            if (this.seccion.length == 0)
            {
                return;
            }
            var id = Math.max.apply(Math, this.elementos.map(function(o) { return o.id; }));
            id = id + 1;
            this.elementos.push( {id:id, html:this.seccion, tipo:'sec', eliminar:false, idGuardado:id});
        },
        async EditarElemento(id, indexElemento)
        {
            var elemento = this.elementos.find((ele) => ele.id == id);
            var tipo = null;
            if (elemento != null)
            {
                tipo = elemento.tipo;
            }

            if (tipo == "p")
            {
                var resp = await this.MostrarModal(elemento.html);

                if (resp != null)
                {
                    //var index = this.elementos.length;
                    var index = id;
                    var minimoAlto = 0;
                    var registro = {id:id, html:resp, tipo:'p', eliminar:false, idGuardado:id};
                    this.$set(this.elementos, indexElemento, registro);
                    
                    this.$nextTick(()=>{
                        var dom = document.getElementById('elemento' + index);
                        minimoAlto = dom.clientHeight;
                        var divborrar = document.getElementById('divborrar'+ index);
                        divborrar.style.minHeight = minimoAlto + 5 + 'px';

                        console.log(dom.clientHeight); 
                    });
                    
                }
            }
        },
        async AgregarImagen(nombre)
        {
            var archivo = null;
            var nombreExtencion = null;

            archivo = this.$refs[nombre].files[0];
            nombreExtencion = this.$refs[nombre].files[0].name;

            var reader = new FileReader();
            var appli = this;

            var index = Math.max.apply(Math, this.elementos.map(function(o) { return o.id; }));
            index = index + 1;

            function getBase64(file) {
                const reader = new FileReader()
                return new Promise(resolve => {
                reader.onload = ev => {
                    resolve(
                        appli.elementos.push({id:index, html:'', tipo:'img', base64:ev.target.result, ruta: '', rutaserver:'', eliminar:false, idGuardado : 0})
                    )
                }
                reader.readAsDataURL(file)
                })
            }

            const promises = []

            // loop through fileList with for loop
            
            promises.push(getBase64(this.$refs[nombre].files[0]))
            

            // array with base64 strings
            await Promise.all(promises);
            var minimoAlto = 0;
            var dom = document.getElementById('elemento' + index);
            minimoAlto = dom.clientHeight;
            var divborrar = document.getElementById('divborrar'+ index);
            divborrar.style.minHeight = minimoAlto + 9 + 'px';

            console.log(dom.clientHeight); 

            return 1;
            
        },
        async ObtenerArchive()
        {
            this.status.cargandoInfo = true;

            await axios.get(this.server + "api/archivo")
            .then((resultado) => {
                console.log("resultado", resultado.data);
                var datos = resultado.data;
                var minimoAlto = 0;
                datos.forEach(element => {

                    if (element.tipo =="p")
                    {
                        this.elementos.push({id: element.id, html:element.html, tipo:element.tipo, eliminar:false, idGuardado : element.id});
                    }
                    
                    if (element.tipo == "img")
                    {
                        this.elementos.push({id: element.id, ruta:element.html, rutaserver:element.rutaserver, tipo:element.tipo, eliminar:false, idGuardado : element.id});
                    }
                    
                });

                
            });

            

            this.status.cargandoInfo = false;
        },
        async GuardarArchive()
        {
            this.status.guardandoInfo = true;

            var html = document.getElementById('contenedor').innerHTML;
            console.log(html);

            var datos = { elementos: this.elementos};
            await axios.post(this.server + "api/archivo",  datos)
            .then((resultado) => {
                console.log("resultado", resultado.data);
                if (resultado.data == 1)
                {
                    this.status.guardado = true;
                }
            }).catch((problema) => {
                console.log(problema);
                this.errores.sistema = false;
            });

            this.status.guardandoInfo = false;
            
        },
        async AgregarElementoTexto(tipo, imagen)
        {
            if (tipo == "p")
            {
                var resp = await this.MostrarModal();

                if (resp != null)
                {
                    //var index = this.elementos.length;
                    var index = Math.max.apply(Math, this.elementos.map(function(o) { return o.id; }));
                    index = index + 1;
                    var minimoAlto = 0;

                    this.elementos.push({id:index, html:resp, tipo:tipo, eliminar:false, idGuardado:0});
                    this.$nextTick(()=>{
                        var dom = document.getElementById('elemento' + index);
                        minimoAlto = dom.clientHeight;
                        var divborrar = document.getElementById('divborrar'+ index);
                        divborrar.style.minHeight = minimoAlto + 5 + 'px';

                        console.log(dom.clientHeight); 
                    });
                    
                }
            }

            if (tipo == "img")
            {

            }

            if (tipo == "imgprod")
            {
                var index = Math.max.apply(Math, this.elementos.map(function(o) { return o.id; }));
                index = index + 1;

                this.elementos.push({id:index, html:'', tipo:tipo, base64:'', ruta: imagen.ruta , rutaserver: imagen.rutaserver, eliminar:false, idGuardado:0});
                this.status.verModalImagenes = false;
                var minimoAlto = 0;
                this.$nextTick(()=>{
                    var dom = document.getElementById('elemento' + index);
                    minimoAlto = dom.clientHeight;
                    var divborrar = document.getElementById('divborrar'+ index);
                    divborrar.style.minHeight = minimoAlto + 5 + 'px';

                    console.log(dom.clientHeight); 
                });
            }
            
            
        },
        Eliminar(index, id)
        {
            var elemento = this.elementos.find((ele) => ele.id == id);
            console.log(elemento);
            if (elemento != null)
            {
                elemento.eliminar = true;
            }
            
            //this.elementos.splice(index, 1);

        },
        AltoElemento(index)
        {
            setTimeout(function(){   }, 3000); 
            console.log(index);
            this.$nextTick(()=>{
                if (document.getElementById('el' + index) != null)
                {
                    var alto = (document.getElementById('el' + index).style.height == null ? '100' : document.getElementById('el' + index).style.height );
                    console.log(alto);
                }
                
            });

            return 100;
        },
        async MostrarModal(cuerpoHtml = "")
        {
            
            var respuestaTexto =  this.$swal.fire({
                title : 'Texto',
                html: '<div id="editorTexto">' + cuerpoHtml + '</div>',
                width: '70%'
            });

            var quill = new Quill('#editorTexto', {
                theme: 'snow'
            });
            await respuestaTexto;
            var htmlTexto = document.getElementById('editorTexto').firstChild.innerHTML;
            
            if (htmlTexto != null)
            {
                return htmlTexto;
            } 

            return null;
            
        },
        
    },
    async mounted() {
        await this.ObtenerArchive();
        var minimoAlto = 0;
        this.$nextTick(()=>{
            this.elementos.forEach(element => {
                console.log('elemento' +  element.id)
                var dom = document.getElementById('elemento' + element.id);
                console.log("docum", dom);
                minimoAlto = dom.clientHeight;
                var divborrar = document.getElementById('divborrar'+ element.id);
                divborrar.style.minHeight = minimoAlto + 5 + 'px';
            });
        });
    },
}
</script>

<style >
    p {
        margin: 0 !important;
    }
    ol {
        margin: 0 !important;
    }
</style>