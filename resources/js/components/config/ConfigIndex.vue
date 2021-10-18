<template>
  <div>
    <div
      style="border: 1px solid black; width: 80%; margin: 0 auto"
      class="bg-white"
    >
      <div v-if="status.cargandoInfo">
        <i class="fas fa-circle-notch fa-spin" spin />
        <span class="ml-2">Cargando configuración</span>
      </div>
      <div style="margin-top: 50px; text-align: center" v-if="!status.cargandoInfo">
        <h3>Opciones de fondo para página de inicio</h3>

        <div class="custom-control custom-switch">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch1"
            v-model="opcion2Imagenes.activo"
            @change="opcion1Imagen.activo = false"
          />
          <label class="custom-control-label" for="customSwitch1"
            >Dos imágenes en pantalla dividida</label
          >
        </div>

        <div>
          <div class="custom-file" style="display: contents">
            <input
              type="file"
              @change="AgregarImagenUno('SubirImg1')"
              id="SubirImg1"
              accept="image/x-png,image/jpeg"
              class="custom-file-input mb-2"
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

          <div class="custom-file" style="display: contents">
            <input
              type="file"
              @change="AgregarImagenUno('SubirImg2')"
              id="SubirImg2"
              accept="image/x-png,image/jpeg"
              class="custom-file-input mb-2"
              style="width: 1%"
              placeholder="Imagen guia de tallas"
              ref="SubirImg2"
            />
            <label
              for="SubirImg2"
              class="mb-4 btn btn-light"
              style="margin-top: 5px !important"
            >
              <div>Agregar</div>
              <div class="ml-1">Imagen 2</div>
              <i class="fas fa-images"></i>
            </label>
          </div>

          <div>
            <div class="d-flex container mb-4">
              <img
                id="SubirImg1"
                class="img-thumbnail"
                v-if="
                  opcion2Imagenes.SubirImg1.ruta.length > 0 ||
                  opcion2Imagenes.SubirImg1.base64.length > 0
                "
                style="width: 50%"
                v-bind:src="
                  opcion2Imagenes.SubirImg1.ruta.length > 0
                    ? opcion2Imagenes.SubirImg1.ruta
                    : opcion2Imagenes.SubirImg1.base64
                "
                alt="imagen"
              />

              <img
                id="SubirImg2"
                class="img-thumbnail"
                v-if="
                  opcion2Imagenes.SubirImg2.ruta.length > 0 ||
                  opcion2Imagenes.SubirImg2.base64.length > 0
                "
                style="width: 50%"
                v-bind:src="
                  opcion2Imagenes.SubirImg2.ruta.length > 0
                    ? opcion2Imagenes.SubirImg2.ruta
                    : opcion2Imagenes.SubirImg2.base64
                "
                alt="imagen"
              />
            </div>
          </div>
        </div>

        <div class="custom-control custom-switch">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch2"
            v-model="opcion1Imagen.activo"
            @change="opcion2Imagenes.activo = false"
          />
          <label class="custom-control-label" for="customSwitch2"
            >Una imágen en pantalla completa</label
          >
        </div>

        <div>
          <div class="custom-file" style="display: contents">
            <input
              type="file"
              @change="AgregarImagenOpcionUno('SubirImg3')"
              id="SubirImg3"
              accept="image/x-png,image/jpeg"
              class="custom-file-input mb-2"
              style="width: 1%"
              placeholder="Imagen guia de tallas"
              ref="SubirImg3"
            />
            <label
              for="SubirImg3"
              class="mb-4 btn btn-light"
              style="margin-top: 5px !important"
            >
              <div>Agregar</div>
              <div class="ml-1">Imagen</div>
              <i class="fas fa-images"></i>
            </label>
          </div>

          <div>
            <img
              class="img-thumbnail"
              v-if="
                opcion1Imagen.SubirImg3.ruta.length > 0 ||
                opcion1Imagen.SubirImg3.base64.length > 0
              "
              style="width: 50%"
              v-bind:src="
                opcion1Imagen.SubirImg3.ruta.length > 0
                  ? opcion1Imagen.SubirImg3.ruta
                  : opcion1Imagen.SubirImg3.base64
              "
              alt="imagen"
            />
          </div>
        </div>

   
      </div>

      <div class="m-2">
        <button class="btn btn-primary" @click="Guardar()">
          <span v-if="status.enviandoInfo">
            <i class="fas fa-circle-notch fa-spin" spin />
          </span>
          <span v-else> GUARDAR </span>
        </button>
      </div>
      <div v-if="status.guardado" class="bg-success p-2 text-white">
        Guardado!
      </div>
	  <div v-if="errores.sistema.length>0" class="bg-danger p-2 text-white">
        {{errores.sistema}}
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      opcion2Imagenes: {
        SubirImg1: {
          base64: "",
          extension: "",
          ruta: "",
        },
        SubirImg2: {
          base64: "",
          extension: "",
          ruta: "",
        },
        activo: false,
      },
      opcion1Imagen: {
        SubirImg3: {
          base64: "",
          extension: "",
          ruta: "",
        },
        activo: false,
      },
	  opcionYoutube:{
		  activo:false,
		  url:'',
		  valido:false
	  },
      status: {
        enviandoInfo: false,
        guardado: false,
        cargandoInfo:false,
      },
	  errores:{
		  sistema:'',
	  }
    };
  },
  methods: {
    async Obtener() {
      this.status.cargandoInfo =true;
      await axios
        .get(this.server + "api/configindex/obtener")
        .then((resultado) => {
          console.log("resultado", resultado.data);

          var configuraciones = resultado.data.configuraciones;
          var appUrl = resultado.data.appUrl;

          var config1 = configuraciones.find((opcion) => opcion.idConfig == 1);
          if (config1 != null) {
            this.opcion2Imagenes.SubirImg1.ruta = appUrl + config1.img1;
            this.opcion2Imagenes.SubirImg2.ruta = appUrl + config1.img2;
            this.opcion2Imagenes.activo =  config1.activo;
          }

          var config2 = configuraciones.find((opcion) => opcion.idConfig == 2);
          if (config2 != null) {
            this.opcion1Imagen.SubirImg3.ruta = appUrl + config2.img1;
            this.opcion1Imagen.activo =  config2.activo;
          }
        });

        this.status.cargandoInfo =false;
    },
    async Guardar() {

		/*validar*/
		this.errores.sistema = "";
		if (!this.opcion2Imagenes.activo && !this.opcion1Imagen.activo)
		{
			this.errores.sistema = "Debe estar activo un opcion";
			return;
		}

      	this.status.guardado = false;
      	this.status.enviandoInfo = true;

		const datos = {
			opcion2Imagenes: this.opcion2Imagenes,
			opcion1Imagen: this.opcion1Imagen,
		};
		await axios
			.post(this.server + "api/configindex/guardar", datos)
			.then((resultado) => {
			console.log("resul", resultado.data);
			if (resultado.data == 1) {
				this.status.guardado = true;
			}
			});

		this.status.enviandoInfo = false;
    },
    AgregarUrlYoutube()
    {
      var esValido = true;
      if (this.opcionYoutube.url.length>0)
      {
        esValido = false;
      }
      if (this.opcionYoutube.url.indexOf("https://www.youtube.com") != 0){
        esValido = false;
      }

    },
    AgregarImagenUno(nombre) {
      var archivo = null;
      var nombreExtencion = null;

      archivo = this.$refs[nombre].files[0];
      nombreExtencion = this.$refs[nombre].files[0].name;

      var reader = new FileReader();
      var appli = this;

      reader.onload = function (event) {
        console.log("onload");

        //generando base64
        appli.opcion2Imagenes[nombre].ruta = "";
        appli.opcion2Imagenes[nombre].base64 = event.target.result;

        //appli.opcion2Imagenes.SubirImg1.base64 = event.target.result;
      };

      console.log("nombre", nombre);

      reader.readAsDataURL(archivo);
    },
    AgregarImagenOpcionUno(nombre) {
      var archivo = null;
      var nombreExtencion = null;

      archivo = this.$refs[nombre].files[0];
      nombreExtencion = this.$refs[nombre].files[0].name;

      var reader = new FileReader();
      var appli = this;

      reader.onload = function (event) {
        console.log("onload");

        //generando base64
        appli.opcion1Imagen[nombre].ruta = "";
        appli.opcion1Imagen[nombre].base64 = event.target.result;

        //appli.opcion2Imagenes.SubirImg1.base64 = event.target.result;
      };

      console.log("nombre", nombre);

      reader.readAsDataURL(archivo);
    },
  },
  mounted() {
    this.Obtener();
  },
};
</script>