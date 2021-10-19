<template>
    <div class="container row " style="color: #333333;">
        <div class="col-md-12">
            <div class="container  d-flex flex-row-reverse">
                <button class="btn " :class="status.guardado ? 'btn-success' : 'btn-primary'"  @click="GuardarProducto()"
                 :disabled="status.enviandoInfo">GUARDAR</button>
            </div>
        </div>
        <div class="col-md-12">
            <div class="btn bg-danger text-white p-2" v-if="errores.sistema.length > 0">
                {{errores.sistema}}
            </div>
        </div>

        <div class="col-md-9">
            <h5>Producto</h5>
            <div class="container p-3 mb-4  bg-white border">
          
                <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="espanol-tab" data-toggle="tab" href="#espanol" role="tab" aria-controls="espanol" aria-selected="true">espanol</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">english</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="espanol" role="tabpanel" aria-labelledby="espanol-tab">
                         <div class="row">

                            <div class="col-md-12 mt-2">
                                <div>Nombre</div>
                                <input class="form-control" type="text" v-model="producto.nombre">
                                <div class="btn  text-white p-1" :style="peligro" v-if="errores.nombre.length > 0">
                                    {{errores.nombre}}
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div>Descripcion</div>
                                <div id="editor">
                                    <p></p>
                                </div>
                                <div class="btn  text-white p-1" :style="peligro" v-if="errores.descripcion.length > 0">
                                    {{errores.descripcion}}
                                </div>

                            </div>



                        </div>
                    </div>
                    <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div>Nombre</div>
                                <input class="form-control" type="text" v-model="english.nombre">
                                <div class="btn  text-white p-1" :style="peligro" v-if="errores.nombre.length > 0">
                                    {{errores.nombre}}
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div>Descripcion</div>
                                <div id="editorEnglish">
                                    <p></p>
                                </div>
                                <div class="btn  text-white p-1" :style="peligro" v-if="errores.descripcion.length > 0">
                                    {{errores.descripcion}}
                                </div>

                            </div>



                            </div>
                        </div>
                </div>

                <div class="col-md-12 mt-2">
                    <div>Color | Opción</div>
                    <input class="form-control" type="text" v-model="producto.color">
                    <div class="btn  text-white p-1" :style="peligro" v-if="errores.color.length > 0">
                        {{errores.color}}
                    </div>
                </div>
            </div>



             <div class="container p-3 mb-4 border bg-white row" >
                <div class="col-md-12"><strong>Imagenes</strong></div>
                <div class="col-md-12">{{nombreReferenciaImg}}</div>
                    <div class="d-flex container mb-4  col-md-6"
                        v-for="(archivoImg, index) in imagenFilesRenderizados"
                        :key="index  + 'IMG'">

                        <img id="imagenProducto"
                            class="img-thumbnail"
                            style="width:90%"
                            v-bind:src="archivoImg.idImagen > 0 ? archivoImg.ruta : archivoImg.base64"
                            alt="imagen de producto" />
                        <div class="">
                            <button

                            class="btn-danger btn-sm m-0"
                            @click="EliminarImagen(index)">X</button>
                        </div>

                    </div>
                 <div class="">
                    <div class="input-group mb-1" >

                        <div class="custom-file" style="display: contents;">
                            <input
                                type="file"
                                multiple="multiple"
                                @change="AgregarImagen()"
                                id="SubirArchivo"
                                accept="image/x-png,image/jpeg" class="custom-file-input mb-2 mt-4 "
                                style="width:1%"
                                placeholder="Imagen producto" ref="imgProducto" >
                                <label for="SubirArchivo" class="mb-4 btn btn-light mt-4" style="margin-top:3rem !important">

                                    <div>Agregar</div>
                                    <div class="ml-1">Imagen</div>
                                    <i class="fas fa-images"></i>
                                </label>
                        </div>

                    </div>

                    <div class="btn  text-white p-1" :style="peligro" v-if="errores.imagenes.length > 0">
                        {{errores.imagenes}}
                    </div>

                 </div>
             </div>

            <div class="container p-3 mb-4 border bg-white">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div> <strong>Precio</strong> </div>

                    </div>
                    <div class="col-md-6 mt-2">
                        <div>Precio de Descuento</div>
                        <input class="form-control" type="text" v-model="producto.precio">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.precio.length > 0">
                            {{errores.precio}}
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div>Precio</div>
                        <input class="form-control" type="text" v-model="producto.precioComparativo">
                    </div>
                    <div class="col-md-6 mt-2">
                        <div>Costo por Articulo</div>
                        <input class="form-control" type="text" v-model="producto.costo">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.costo.length > 0">
                            {{errores.costo}}
                        </div>
                    </div>
                </div>

            </div>

            <div class="container p-3 mb-4 border bg-white">
                <div class="row">
                    <div class="col-md-12 mt-2">

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="manejaraTallas" v-model="producto.manejaraTallas"
                                @click="producto.manejaInventario = false;">
                            <label class="form-check-label" for="manejaraTallas"><strong>¿Manejará tallas?</strong></label>
                        </div>
                    </div>

                    <div class="col-md-8" v-if="producto.manejaraTallas">
                        <input type="text" class="form-control" v-model="varianteTexto">
                    </div>
                    <div class="col-md-4" v-if="producto.manejaraTallas">
                        <button class="btn btn-primary" @click="AgregarVariante()">Agregar</button>
                    </div>

                    <div class="col-md-8  mt-2" v-if="producto.manejaraTallas">
                        <div class="text-muted mb-2">Lista de Tallas</div>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">Talla</div>
                                    <div class="col-md-3">Inventario</div>
                                    <div class="col-md-2">Acción</div>
                                </div>
                            </li>
                            <li class="list-group-item "
                                v-for="(detalle, index) in varianteDetalles" :key="index"
                                :style="detalle.eliminar ?  'padding: 0 !important;' : '' ">
                                <div class="row" v-if="!detalle.eliminar">
                                    <input type="text" class="form-control mr-1 col-md-4" v-model="detalle.valor">
                                    <input type="number" class="form-control mr-1 col-md-3" v-model="detalle.inventario">
                                    <button class="btn btn-danger col-md-3" @click="detalle.eliminar = true;">Eliminar</button>
                                </div>

                            </li>
                        </ul>
                    </div>


                    <div class="btn  text-white p-1" :style="peligro" v-if="errores.variantes.length > 0">
                        {{errores.variantes}}
                    </div>

                </div>
            </div>

            <div class="container p-3 mb-4 border bg-white">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div> <strong>Inventario</strong> </div>

                    </div>
                    <div class="col-md-6 mt-2">
                        <div>SKU (código de artículo)</div>
                        <input class="form-control" type="text" v-model="producto.sku">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.sku.length > 0">
                            {{errores.sku}}
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div>Código de barras (ISBN, UPC, GTIN, etc.)</div>
                        <input class="form-control" type="text" v-model="producto.codigoBarras" >

                    </div>
                    <div class="col-md-6 mt-2">

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="rastrearCheck" v-model="producto.manejaInventario"
                            @click="producto.inventario = 0; producto.manejaraTallas = false;">
                            <label class="form-check-label" for="rastrearCheck">¿Manejará Inventario?</label>
                        </div>

                    </div>
                    <div class="col-md-12" v-if="!producto.manejaraTallas">
                        <div> <strong>Cantidad | Inventario</strong> </div>
                        <div>
                            <div>Disponible</div>
                            <input class="form-control w-50" type="number" v-model="producto.inventario"
                                :readonly="!producto.manejaInventario">
                            <div class="btn  text-white p-1" :style="peligro" v-if="errores.inventario.length > 0">
                                {{errores.inventario}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="container p-3 mb-4 border bg-white">
                <div class="row">

                    <div class="col-md-12 mt-2">
                        <div> <strong>Enviós</strong> </div>

                    </div>
                    <div class="col-md-3 mt-2 ">
                        <div>peso</div>
                        <div class="d-flex">
                            <input class="form-control" type="text" v-model="producto.peso">
                            <span class="ml-2">Kg</span>

                        </div>

                    </div>
                     <div class="col-md-3 mt-2 ">
                        <div>alto</div>
                        <div class="d-flex">
                            <input class="form-control" type="text" v-model="producto.alto">
                            <span class="ml-2">cm</span>

                        </div>

                    </div>
                    <div class="col-md-3 mt-2 ">
                        <div>ancho</div>
                        <div class="d-flex">
                            <input class="form-control" type="text" v-model="producto.ancho">
                            <span class="ml-2">cm</span>

                        </div>

                    </div>
                     <div class="col-md-3 mt-2 ">
                        <div>largo</div>
                        <div class="d-flex">
                            <input class="form-control" type="text" v-model="producto.largo">
                            <span class="ml-2">cm</span>

                        </div>

                    </div>
                </div>

                <div class="row">


                    <div class="col-md-3 mt-2 ">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.peso.length > 0">
                            {{errores.peso}}
                        </div>

                    </div>
                     <div class="col-md-3 mt-2 ">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.alto.length > 0">
                            {{errores.alto}}
                        </div>

                    </div>
                    <div class="col-md-3 mt-2 ">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.ancho.length > 0">
                            {{errores.ancho}}
                        </div>

                    </div>
                     <div class="col-md-3 mt-2 ">
                        <div class="btn  text-white p-1" :style="peligro" v-if="errores.largo.length > 0">
                            {{errores.largo}}
                        </div>

                    </div>
                </div>

            </div>


            <div class="container p-3 mb-4 border bg-white">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div> <strong>Guia de tallas (img)</strong> </div>

                    </div>
                    <div class="col-md-10">
                        <div class="d-flex container mb-4"
                            v-if="rutaGuiaTallas.ruta.length > 0 || rutaGuiaTallas.base64.length > 0 ">

                            <img id="imagenTalla"
                                class="img-thumbnail"
                                style="width:90%"
                                v-bind:src="rutaGuiaTallas.ruta.length > 0 ? rutaGuiaTallas.ruta : rutaGuiaTallas.base64"
                                alt="imagen de tallas" />
                            <div class="">
                           
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="">
                            <div class="input-group mb-1" >

                                <div class="custom-file" style="display: contents;">
                                    <input
                                        type="file"

                                        @change="AgregarImagenGuiaTallas()"
                                        id="SubirGuia"
                                        accept="image/x-png,image/jpeg" class="custom-file-input mb-2 mt-4 "
                                        style="width:1%"
                                        placeholder="Imagen guia de tallas" ref="imgGuiaTallas" >
                                        <label for="SubirGuia" class="mb-4 btn btn-light mt-4" style="margin-top:3rem !important">

                                            <div>Agregar</div>
                                            <div class="ml-1">Imagen</div>
                                            <i class="fas fa-images"></i>
                                        </label>
                                </div>

                            </div>

                            <div class="btn  text-white p-1" :style="peligro" v-if="errores.imagenes.length > 0">
                                {{errores.imagenes}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="contianer p-2">
                opciones


                <div class="container bg-white border rounded pb-3 mt-3 pt-2">
                    <strong class="mb-4 mt-3">Organización</strong>
                    <div>
                        tipo de producto
                        <input type="text" class="form-control" v-model="producto.tipo">
                    </div>

                    <div class="mt-3">
                        <strong>Etiquetas</strong>
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="container bg-white border rounded pb-3 mt-3 pt-2">
                    <strong class="mb-4 mt-3">Menu</strong>
                    <div>
                        ver en el menu:
                        <select class="form-select form-select-sm combo" aria-label="Estado" v-model="producto.idMenu">
                        <option :value="opcion.idMenu" v-for="(opcion, index) in menus" :key="index">
                            {{opcion.menu}}
                        </option>
                        
                        
                    </select>
                    </div>
                    
                  
                </div>
            </div>

        </div>


    </div>
</template>
<script>
import 'medium-editor/dist/css/medium-editor.css'
import 'vuejs-medium-editor/src/themes/default.css'
import 'highlight.js/styles/ocean.css';
import Quill from '../../../public/js/quill.js';
import  '../../../public/css/quill.snow.css';



export default {
    data() {
        return {
            producto:{
                sku:'',
                nombre:'',
                descripcion:'',
                color:'',
                precio:'',
                precioComparativo:'',
                costo:'',
                codigoBarras:'',
                peso:'',
                idProducto:0,
                inventario: '',
                manejaInventario: false,
                manejaraTallas : false,
                ancho:'',
                alto:'',
                largo:'',
                idColecion:0,
                coleccion:'',
                idTipo:0,
                tipo:'',
                idMenu:0

            },
            rutaGuiaTallas: {base64 :'', ruta:''},
            english:{
                idProductoIdioma:0,
                nombre:'',
                descripcion:'',
            },
            nombreReferenciaImg:'',
            imagenFilesRenderizados:[],
            imagenTallaRenderizados:null,
            variantes:[],
            variante: { idVariante:1, nombre:'Tallas'},
            varianteDetalles:[],
            varianteTexto:'',
            imagenData:[],
            imagenTallas:[],
            content: "",
            peligro:'background-color: #dd796f !important;',
            options: {
            },
            errores:{
                sistema:"",
                sku:'',
                nombre:'',
                descripcion:'',
                imagenes:'',
                color:'',
                precio:'',
                precioComparativo:'',
                costo:'',
                codigoBarras:'',
                peso:'',
                idProducto:0,
                inventario: '',
                manejaInventario: '',
                manejaraTallas:'',
                ancho:'',
                alto:'',
                largo:'',
                variantes:''
            },
            status:{
                guardado:false,
                enviandoInfo:false
            },
            menus:[]
        }
    },

    methods: {
        async ObtenerMenus()
        {
            await axios.get(this.server + "api/menus")
            .then((resultado) => {
                if (resultado.data && resultado.data != null) 
                {
                    this.menus = resultado.data;
                }
            })
        },
        AgregarVariante()
        {
            if (this.varianteTexto.trim().length >0)
            {
                this.varianteDetalles.push({idProductoVarianteDetalle:0, idVariante:1, valor: this.varianteTexto, eliminar: false});
            }
        },
        EliminarImagen(index)
        {
            this.imagenData.splice(index, 1);
            this.imagenFilesRenderizados.splice(index, 1);
            console.log("index", index);
        },
        AgregarImagenGuiaTallas()
        {
            console.clear();


            var index = this.imagenTallas.length;


            this.imagenTallas.push({
                "file" : this.$refs.imgGuiaTallas.files[0],
                "result": null,
                "ruta": "#",
                "index":index
            });


            var nombreExtencion = this.$refs.imgGuiaTallas.files[0].name;



            var reader = new FileReader();
            var appli = this;

            reader.onload = function (event) {
                console.log("onload");

                //generando base64
                appli.rutaGuiaTallas.ruta = "";
                appli.rutaGuiaTallas.base64 = event.target.result;
            }



            reader.readAsDataURL(this.imagenTallas[0].file);

        },
        AgregarImagen()
        {
            console.clear();


            var index = this.imagenData.length;
            var pocisionIndexInicial = index;

            var listaImgs =this.$refs.imgProducto.files;

            for (let position = 0; position < listaImgs.length; position++) {


                this.imagenData.push({
                    "file" : this.$refs.imgProducto.files[position],
                    "result": null,
                    "ruta": "#",
                    "index":index
                });
                index = this.imagenData.length;
            }

            /*
            this.imagenData.push({
                "file" : this.$refs.imgProducto.files[0],
                "result": null,
                "ruta": "#",
                "index":index
            });
            */

            var nombreExtencion = this.$refs.imgProducto.files[0].name;
            this.nombreReferenciaImg = nombreExtencion;

            console.log("imagenData", this.imagenData);

            for (pocisionIndexInicial; pocisionIndexInicial < index; pocisionIndexInicial++) {

                var reader = new FileReader();
                var appli = this;

                reader.onload = function (event) {
                    appli.imagenFilesRenderizados.push({
                        "idImagen":0,
                        "base64":event.target.result,
                        "nombreExtencion": nombreExtencion,
                        "index":pocisionIndexInicial
                    });
                }
                console.log("index", pocisionIndexInicial);



                reader.readAsDataURL(this.imagenData[pocisionIndexInicial].file);

            }

        },
        Obtenerhtml()
        {
            var info = document.getElementById('editor').firstChild.innerHTML;

            this.producto.descripcion = info;
                this.english.descripcion = document.getElementById('editorEnglish').firstChild.innerHTML;

        },
        LimpiarErrores()
        {
            this.errores.sistema="";
            this.errores.sku='';
            this.errores.nombre='';
            this.errores.descripcion='';
            this.errores.imagenes='';
            this.errores.color='';
            this.errores.precio='';
            this.errores.precioComparativo='';
            this.errores.costo='';
            this.errores.codigoBarras='';
            this.errores.peso='';
            this.errores.idProducto=0;
            this.errores.inventario= '';
            this.errores.manejaInventario= '';
            this.errores.ancho='';
            this.errores.alto='';
            this.errores.largo='';
            this.errores.variantes='';
            this.errores.manejaraTallas='';
        },
        ValidarProducto()
        {
            this.LimpiarErrores();

            var EsValido = true;

            if (this.producto.nombre.trim().length == 0)
            {
                this.errores.nombre = "no se ingreso el nombre";
                EsValido = false;
            }
            if (this.producto.color.trim().length == 0)
            {
                this.errores.color = "no se ingreso el color";
                EsValido = false;
            }
            if (this.producto.descripcion.trim().length <= 11)
            {
                this.errores.descripcion = "no se ingreso la descripcion";
                EsValido = false;
            }
            console.log(this.producto.descripcion.length);
            if (this.imagenFilesRenderizados.length != 4)
            {
                this.errores.imagenes = "se deben ingresar 4 imagenes";
                EsValido = false;
            }
            if (this.producto.precio.trim().length == 0 || isNaN(this.producto.precio))
            {
                this.errores.precio = "no se ingreso el precio";
                EsValido = false;
            }


            if (this.producto.costo.trim().length == 0 || isNaN(this.producto.costo))
            {
                this.errores.costo = "no se ingreso el costo";
                EsValido = false;
            }

            if (this.producto.sku.trim().length == 0)
            {
                this.errores.sku = "no se ingreso el SKU";
                EsValido = false;
            }

            if (this.producto.manejaInventario)
            {
                if (this.producto.inventario.length == 0 || isNaN(this.producto.inventario))
                {
                    this.errores.inventario = "debe ingresar un inventario";
                    EsValido = false;
                }
            }

            if (this.producto.manejaraTallas)
            {
                console.log("this.producto.manejaraTallas", this.producto.manejaraTallas);
                console.log("this.varianteDetalles.length", this.varianteDetalles.length);
                if (this.varianteDetalles.length == 0)
                {
                    this.errores.variantes = "debe ingresar variantes de talla";
                    EsValido = false;
                }
                else
                {
                    this.varianteDetalles.forEach(element => {
                        if (element.valor.length == 0)
                        {
                            this.errores.variantes = "debe ingresar un nombre de la talla";
                            EsValido = false;
                        }
                    });
                }



            }

            if (this.producto.peso.length == 0 || isNaN(this.producto.peso))
            {
                this.errores.peso = "debe ingresar un peso";
                EsValido = false;
            }

            if (this.producto.ancho.length == 0 || isNaN(this.producto.ancho))
            {
                this.errores.ancho = "debe ingresar un ancho";
                EsValido = false;
            }

            if (this.producto.alto.length == 0 || isNaN(this.producto.alto))
            {
                this.errores.alto = "debe ingresar un alto";
                EsValido = false;
            }

            if (this.producto.largo.length == 0 || isNaN(this.producto.largo))
            {
                this.errores.largo = "debe ingresar un largo";
                EsValido = false;
            }
            return EsValido;
        },
        async GuardarProducto()
        {
            this.status.enviandoInfo = true;

            try {
                this.Obtenerhtml();

                //aqui validar productos por la descripcion html
                if (!this.ValidarProducto())
                {
                    this.status.enviandoInfo = false;
                    this.errores.sistema = "valide la información, faltan datos";
                    return;
                }

                const datos = {"producto": this.producto,  "imagenes" : this.imagenFilesRenderizados,
                "english" : this.english, "rutaGuiaTallas" : this.rutaGuiaTallas,
                "varianteDetalles" : this.varianteDetalles };

                console.log(datos);


                await axios.post(this.server + "api/productos", datos)
                .then((respuesta) => {
                    console.log("respuesta", respuesta.data);

                    if (respuesta.data > 0)
                    {
                        this.status.guardado = true;
                        var servidor = this.server;
                        setTimeout(() => {
                            window.location.href = servidor + "productos";
                        }, 200);
                    }
                    else
                    {
                        this.errores.sistema = respuesta.data;
                    }
                })
            } catch (error) {
                console.log("detalle en el guardado: " + error);
            }

            this.status.enviandoInfo = false;
        }
    },
    async mounted() {
        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        new Quill('#editorEnglish', {
            theme: 'snow'
        });
        this.ObtenerMenus();
        var inputProducto = document.getElementById('idProducto');
        if (inputProducto != null)
        {
            this.producto.idProducto =  Number(inputProducto.value);
            if (this.producto.idProducto >0)
            {
                await axios.get(this.server +"api/productos/" + this.producto.idProducto)
                .then((resultado) =>{
                    console.log("resultado", resultado.data.producto);
                    this.producto = resultado.data.producto;

                    //general propiedades rutatalla
                    this.rutaGuiaTallas.ruta = resultado.data.producto.rutaGuiaTallas;
                    this.rutaGuiaTallas.base64 = "";

                    this.imagenFilesRenderizados =resultado.data.imagenes;
                    this.varianteDetalles =  resultado.data.variantes;

                    if (resultado.data.english)
                    {
                        this.english = resultado.data.english;
                    }
                    if (this.producto.descripcion)
                    {
                        document.getElementById('editor').firstChild.innerHTML = this.producto.descripcion;
                    }

                    if (this.english.descripcion)
                    {
                        document.getElementById('editorEnglish').firstChild.innerHTML = this.english.descripcion;
                    }

                })
            }

        }
    },
}
</script>
<style >
    .combo {
        background-color: white;
        border: 1px solid #8080804f;
        width: 100%;
    }
    .ql-container {
        height: auto !important;
    }
</style>
