<template>
    <div class="container">
    


        <div class="row" id="app">
            <div class="col-md-3 p-4">
                <div class="btn-group dropleft p-1"  >
                        <button
                            class="btn bg-white border shadow-sm rounded  p-2 "
                            :id="'dropPrincipalM'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            >
                            <span >
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                        </button>
                        <div class="dropdown-menu p-3" 
                            :aria-labelledby="'dropPrincipalM'">
                            <div class="text-muted">Opciones</div>

                            <button @click="AgregarElementoTexto('p')"
                                class="dropdown-item  mb-2 pr-4 " >
                                <span class="pr-2" style="color:Tomato">
                                    <i class="fas fa-power-off"></i>
                                </span>
                                <strong class="text-center p-0 m-0">Texto</strong>
                                
                            </button>

                            <button   @click="AgregarElementoTexto('img')"
                                class=" btn   mb-2   dropdown-item"  
                                >
                                <span class="pr-2 " style="color:gray">
                                    <i class="fas fa-pause"></i>
                                </span>
                                <strong > 
                                    Imagen
                                </strong>
                            </button>

                            <button @click="AgregarElementoTexto('youtube')"
                                class="dropdown-item mb-1  text-center"  
                                style="color:green"
                                >
                                <span  class="pr-2"> 
                                    <i class="fas fa-chevron-circle-right" >  </i>
                                </span>
                                <strong class="text-dark">Youtube</strong>
                            </button>

                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-file" style="display: contents">
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
                    class="mb-4 btn btn-light"
                    style="margin-top: 5px !important"
                    >
                    <div>Agregar</div>
                    <div class="ml-1">Imagen</div>
                    <i class="fas fa-images"></i>
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary" @click="GuardarArchive()">GUARDAR</button>
            </div>
            <div class="col-md-8 bg-light text-dark" id="contenedor" style="min-height:20px;">
                
                <div v-for="(item, index) in elementos" class="bg-white border p-1" :key="index" :index="'el' + index" >
                    
                    <div :id="'elemento' +  item.id" v-if="!item.eliminar">
                        <div v-if="item.tipo =='p'">
                            <div v-html="item.html"></div>
                            
                        </div>
                        <div v-if="item.tipo == 'img'">
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
                    </div>
                    
                </div>
                
            </div>
            <div class="col-md-4" >
                <div v-for="(item, index) in elementos" :key="index" >
                    <div :id="'divborrar' + item.id" v-if="!item.eliminar">
                        <button class="btn btn-danger" :id="'borrar' + index" :ref="'borrar' + index"  @click="item.eliminar = true;">Borrar</button>
                    </div>
                    
 
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
                        appli.elementos.push({id:index, html:'', tipo:'img', base64:ev.target.result, ruta: '', eliminar:false})
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

            reader.onload = function (event) {
                console.log("onload");

                //generando base64
                appli.elementos.push({id:index, html:'', tipo:'img', base64:event.target.result, ruta: '', eliminar:false});
                
            };

            reader.readAsDataURL(archivo);

           

            console.log("nombre", nombre);
            
        },
        async ObtenerArchive()
        {
            await axios.get(this.server + "api/archivo")
            .then((resultado) => {
                console.log("resultado", resultado.data);
                var datos = resultado.data;

                datos.forEach(element => {
                    this.elementos.push({id: element.id, html:element.html, tipo:element.tipo, eliminar:false})
                });
            })
        },
        async GuardarArchive()
        {
            var html = document.getElementById('contenedor').innerHTML;
            console.log(html);

            var datos = { elementos: this.elementos};
            await axios.post(this.server + "api/archivo",  datos)
            .then((resultado) => {
                console.log("resultado", resultado.data);
            })
        },
        async AgregarElementoTexto(tipo)
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

                    this.elementos.push({id:index, html:resp, tipo:tipo, eliminar:false});
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
        async MostrarModal()
        {
            
            var respuestaTexto =  this.$swal.fire({
                title : 'Texto',
                html: '<div id="editorTexto"></div>',
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
    mounted() {
        this.ObtenerArchive();
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