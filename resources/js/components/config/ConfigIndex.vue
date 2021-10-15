<template>
  <div>
    <div
      style="border: 1px solid black; width: 80%; margin: 0 auto"
      class="bg-white"
    >
      <div style="margin-top: 50px; text-align: center">
        <h3>Opciones de fondo para página de inicio</h3>
        <br /><br />

        <div class="custom-control custom-switch">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch1"
          />
          <label class="custom-control-label" for="customSwitch1"
            >Dos imágenes en pantalla dividida</label
          >
        </div>
        <br />
        <div>
            <div class="custom-file" style="display: contents;">
                <input
                    type="file"

                    @change="AgregarImagenUno('SubirImg1')"
                    id="SubirImg1"
                    accept="image/x-png,image/jpeg" class="custom-file-input mb-2 mt-4 "
                    style="width:1%"
                    placeholder="Imagen guia de tallas" ref="SubirImg1" >
                    <label for="SubirImg1" class="mb-4 btn btn-light mt-4" style="margin-top:3rem !important">

                        <div>Agregar</div>
                        <div class="ml-1">Imagen</div>
                        <i class="fas fa-images"></i>
                    </label>
            </div>

            <div class="custom-file" style="display: contents;">
                <input
                    type="file"

                    @change="AgregarImagenUno('SubirImg2')"
                    id="SubirImg2"
                    accept="image/x-png,image/jpeg" class="custom-file-input mb-2 mt-4 "
                    style="width:1%"
                    placeholder="Imagen guia de tallas" ref="SubirImg2" >
                    <label for="SubirImg2" class="mb-4 btn btn-light mt-4" style="margin-top:3rem !important">

                        <div>Agregar</div>
                        <div class="ml-1">Imagen 2</div>
                        <i class="fas fa-images"></i>
                    </label>
            </div>

            <div>
                <div class="d-flex container mb-4"
                    >

                    <img id="SubirImg1"
                        class="img-thumbnail"
                        v-if="opcion2Imagenes.SubirImg1.ruta.length > 0 || opcion2Imagenes.SubirImg1.base64.length > 0 "
                        style="width:50%"
                        v-bind:src="opcion2Imagenes.SubirImg1.ruta.length > 0 ? opcion2Imagenes.SubirImg1.ruta : opcion2Imagenes.SubirImg1.base64"
                        alt="imagen" />

                    <img id="SubirImg2"
                        class="img-thumbnail"
                        v-if="opcion2Imagenes.SubirImg2.ruta.length > 0 || opcion2Imagenes.SubirImg2.base64.length > 0 "
                        style="width:50%"
                        v-bind:src="opcion2Imagenes.SubirImg2.ruta.length > 0 ? opcion2Imagenes.SubirImg2.ruta : opcion2Imagenes.SubirImg2.base64"
                        alt="imagen" />
            

                </div>

            </div>
       
        </div>

        <br />
        <br />

        <div class="custom-control custom-switch">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch2"
          />
          <label class="custom-control-label" for="customSwitch2"
            >Una imágen en pantalla completa</label
          >
        </div>
        <br />
        <div>
          <button class="btn btn-light m-2">
            <i class="fas fa-image"></i>
          </button>
        </div>
        <br />
        <br />

        <div class="custom-control custom-switch">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch3"
          />
          <label class="custom-control-label" for="customSwitch3"
            >Un video en pantalla completa</label
          >
        </div>

        <br />
        <div>
          <button class="btn btn-light m-2">
            <i class="fas fa-video"></i>
          </button>
        </div>
        <br />
        <br />
      </div>

      <div class="m-2">
          <button class="btn btn-primary" @click="Guardar()">GUARDAR</button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    data() {
        return {
            opcion2Imagenes:{
                SubirImg1:{
                    base64:'',
                    extension:'',
                    ruta:''
                },
                SubirImg2:{
                    base64:'',
                    extension:'',
                    ruta:''
                },
            }
        }
    },
    methods: {
        async Guardar()
        {
            await axios.post(this.server + "api/configindex/guardar", this.opcion2Imagenes)
            .then((resultado) =>{
                console.log("resul", resultado);
            })
        },
        AgregarImagenUno(nombre)
        {
            
            var archivo = null;
            var nombreExtencion = null;

            archivo = this.$refs[nombre].files[0];
            nombreExtencion = this.$refs[nombre].files[0].name;


            var reader = new FileReader();
            var appli = this;
            
            reader.onload = function (event) {
                console.log("onload");

                //generando base64
                //appli.opcion2Imagenes.SubirImg1.ruta = "";
                appli.opcion2Imagenes[nombre].base64 = event.target.result;
          
                //appli.opcion2Imagenes.SubirImg1.base64 = event.target.result;
            }

            console.log("nombre", nombre);

            reader.readAsDataURL(archivo);
        }
    },
}
</script>