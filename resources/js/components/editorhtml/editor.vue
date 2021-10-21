<template>
    <div class="container">
    


        <div class="row" id="app">
            <div class="col-md-6 p-4">
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
            <div class="col-md-6">
                <button class="btn btn-primary" @click="GuardarArchive()">GUARDAR</button>
            </div>
            <div class="col-md-8 bg-light text-dark" id="contenedor">
                
                <div v-for="(item, index) in elementos" class="bg-white border p-1" :key="index" :index="'el' + index" >
                    
                    <div :id="'elemento' +  item.id" v-if="!item.eliminar">
                        <div v-if="item.tipo =='p'">
                            <div v-html="item.html"></div>
                            
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
            var resp = await this.MostrarModal();

            if (resp != null)
            {
                //var index = this.elementos.length;
                var index = Math.max.apply(Math, this.elementos.map(function(o) { return o.id; }));
                index = index + 1;
                var minimoAlto = 0;

                this.elementos.push({id:this.elementos.lenght, html:resp, tipo:tipo, eliminar:false});
                this.$nextTick(()=>{
                    var dom = document.getElementById('elemento' + index);
                    minimoAlto = dom.clientHeight;
                    var divborrar = document.getElementById('divborrar'+ index);
                    divborrar.style.minHeight = minimoAlto + 5 + 'px';

                    console.log(dom.clientHeight); 
                })
                
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
            
        }
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