<template>
    <div class="container">
        <h3>Lista de Productos</h3>
        <div class="container mb-4 d-flex flex-row-reverse">
            <a :href="server + 'productos/guardar'" class="btn btn-primary" >
                Agregar Producto
            </a>
        </div>
         <ul class="list-group" id="tablaproductos" >
            <li class="list-group-item columnas p-1">
                <div class="row">
                    <div class="col-md-1">
                    ID
                    </div>
                    <div class="col-md-2">
                        <span >SKU</span>
                    </div>
                    <div class="col-md-4">
                        Nombre
                    </div>
                    <div class="col-md-2">
                        Inventario
                    </div>
                    <div class="col-md-1">
                        costo
                    </div>
                     <div class="col-md-1">
                        precio
                    </div>
                    <div class="col-md-1">
                        Acción
                    </div>
                </div>
            </li>
          
            <li class="list-group-item p-2"  v-for="(producto, index) in ListaProductos" :key="index">
                <div class="row"    >
                    <div class="col-md-1">
                    <span class="text-muted">{{producto.idProducto}}</span>
                    </div>
                    <div class="col-md-2">
                        <strong class="text-info text-monospace">{{producto.sku}}</strong>
                    </div>
                    <div class="col-md-4">
                        <strong>{{producto.nombre}}</strong>
                        
                    </div>
                    <div class="col-md-2">
                        <span
                            
                            class="text-muted">{{producto.inventario}}</span>
                    </div>
                    <div class="col-md-1">
                        <strong class="text-monospace">{{producto.costo | moneda}}</strong>
                    </div>
                     <div class="col-md-1">
                        <strong class="text-monospace">{{producto.precio | moneda}}</strong>
                    </div>
                    <div class="col-md-1">
                        <a :href="server + 'productos/' + producto.idProducto" class="btn btn-primary btn-sm">
                            EDITAR
                        </a>
                    </div>
                </div>
            </li>
        </ul>

    </div>
</template>
<script>
export default {
    data() {
        return {
            ListaProductos:[]
        }
    },
    mounted() {
        this.ObtenerProductos();
    },
    methods: {
        
        async ObtenerProductos()
        {
            await axios.get("api/productos")
            .then((resultado) =>{
                console.log("prodcutos", resultado.data);
                if (resultado.data != null)
                {
                    
                    this.ListaProductos=resultado.data;
                }
            })
        }
    },
}
</script>