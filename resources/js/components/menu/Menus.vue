<template>
    <div>
        <h3>Lista de Menu Tienda</h3>

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    
                    <div class="border p-3">
                        <div>Nuevo Menu</div>
                        <input type="text" class="form-control mt-1" v-model="menuRow.menu">
                        <button class="btn btn-primary mt-1"
                            @click="GuardarMenu()"
                            :disabled="status.enviando">AGREGAR</button>
                    </div>

                    <div class="m-4">
                        <div class="mt-4 mb-2">
                            <button class="btn " @click="GuardarLista()" :disabled="status.enviandoInfo" :class="status.guardado ? 'btn-success' : 'btn-primary'">GUARDAR</button>
                        </div>
                        <ul class="list-group">
                            <draggable v-model="menus" group="people" @start="drag=true; " @end="drag=false; Ordenamiento();">
                            <li class="list-group-item" v-for="(menu, index) in menus" :key="index">
                                
                                <div class="d-flex justify-content-between">
                                    <div>{{menu.menu}}</div>
                                    <button class="btn btn-info" @click="menuRow = menu;">Editar</button>
                                </div>
                                

                            </li>
                            </draggable>
                           
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
 import draggable from 'vuedraggable'

export default {
    components: {
        draggable,
    },
    data() {
        return {
            menus:[],
            menuRow:{
                idMenu:0,
                menu:''
            },
            status:{
                enviando:false,
                guardado:false,
                enviandoInfo:false,
            }
        }
    },
    methods: {
        async GuardarLista()
        {
            this.status.enviandoInfo = true;

            const datos = { menus:this.menus };
            await axios.post(this.server + "api/menus/guardarlista", datos)
            .then((resultado) => {
                console.log(resultado.data);
                if (resultado.data == 1)
                {
                    this.status.guardado = true;
                }
            });

            this.status.enviandoInfo = false;
        },
        Ordenamiento()
        {
            var index = 0;
            this.menus.forEach(element => {
                element.orden = index;
                index++;
            });
        },
        async GuardarMenu()
        {
            this.status.enviando = true;
            await axios.post(this.server + 'api/menus', this.menuRow)
            .then((resultado) =>{
                if (resultado.data)
                {
                    if (resultado.data.idMenu && resultado.data.idMenu >0)
                    {
                        this.menuRow.idMenu =0;
                        this.menuRow.menu = "";
                        this.status.enviando = false;
                        this.ObtenerMenus();
                    }
                }
            }).catch((problema) =>{
                this.status.enviando = false;
            })
            this.status.enviando = false;

        },
        async ObtenerMenus()
        {
            await axios.get(this.server + 'api/menus')
            .then((resultado) =>{
                if (resultado.data != null)
                {
                    this.menus = resultado.data;
                }
            }).catch((problema) =>{
                console.log("problemas obtener", problema);
            })
        }
    },
    mounted() {
        this.ObtenerMenus();
    },
}
</script>