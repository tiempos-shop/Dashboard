<template>
  <div>
    <div class="row">
      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div
                  class="
                    text-xs
                    font-weight-bold
                    text-primary text-uppercase
                    mb-1
                  "
                >
                  TOTAL VENTAS (Mes)
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{Number(info.ventasMonedaNacionalMes) | moneda}} MXN
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

       <!-- Earnings (Monthly) Card USD -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div
                  class="
                    text-xs
                    font-weight-bold
                    text-primary text-uppercase
                    mb-1
                  "
                >
                  TOTAL VENTAS (Mes)
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{Number(info.ventasMonedaDolarMes) | moneda}} USD
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Pending Requests Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div
                  class="
                    text-xs
                    font-weight-bold
                    text-warning text-uppercase
                    mb-1
                  "
                >
                  Envios DHL Pendientes
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    {{info.enviosPendientes}}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-comments fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- Area Chart -->
     

    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            info:{
                ventasMonedaNacionalMes:0,
                ventasMonedaDolarMes:0,
                enviosPendientes:0
            }
        }
    },
    methods: {
        async ObtenerDatosInicio()
        {
            await axios.get(this.server + 'api/principal/inicio')
            .then((resultado) =>{
                if (resultado.data != null)
                {
                    this.info = resultado.data;
                }
            })
        }
    },
    created() {
        this.ObtenerDatosInicio();
    },
}
</script>