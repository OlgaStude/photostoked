<template>
    <div class="container">
        <div id="top_collect">
            <div id="collection_go_back">
                <a href="/collections"><img :src="'/storage/imgs/Arrow_back.png'" alt=""></a>
                <a href="/collections">Назад к коллекциям</a>
            </div>
            <h1>{{ collection_name }}</h1>
        </div>
        <div id="material_container">
            
            <div class="material_userpage" v-for="i in index">
                <div class="material_userwrapper">
                    <a  :href="$router.resolve({name: 'Material', params: { id: materials[i-1].material_id }}).href" class="material_blackgr">

                    </a>
                    <button class="remove_from_collection" @click="collection_delete(materials[i-1].material_id)">Удалить из коллекции</button>
                    <div class="collections_user">
                        <select name="" @change="collection_status_change($event)" id="" v-if="materials[i-1].collections.length > 0">
                            <option value="">Ваши коллекции</option>
                            <option :value="[collection.id, materials[i-1].material_id]" v-for="collection in materials[i-1].collections"><p>{{ collection.name }} <span v-if="collection.is_in">(В коллекции)</span></p> </option>
                        </select>
                        <div name="" id="no_collections" v-else>
                            <a href="/collections">Сначала создайте коллекцию</a>
                        </div>
                    </div>
                    <div class="material_raiting">
                        <div class="rating_btn">
                            <img v-if="materials[i-1].is_liked" @click="like(materials[i-1].material_id, materials[i-1].user_id, $event)" :src="'/storage/imgs/like_red.png'" alt="">
                            <img v-else @click="like(materials[i-1].material_id, materials[i-1].user_id, $event)" :src="'/storage/imgs/like_white.png'" alt="">
                        </div>
                        <p>{{ materials[i-1].likes }}</p>
                    </div>
                    <div class="material_username">
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials[i-1].user_id }}).href"><img :src="'/storage/profile_pics/' + materials[i-1].user_path" alt=""></a>
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials[i-1].user_id }}).href"><p>{{ materials[i-1].nikname }}</p></a>
                    </div>
                    
                    <a class="material_userlink" :href="$router.resolve({name: 'Material', params: { id: materials[i-1].material_id }}).href"><img class="material_img" :src="'/storage/approved_materials/' + materials[i-1].material_path" alt=""></a>
                </div>
                
            </div>
    </div>
    <div id="no_works_user">
        <img :src="'/storage/imgs/no_imgs.png'" alt="">
        <p>Тут пока ничего нет...</p>
    </div>
    </div>
</template>


<style>

#top_collect{
    display: flex;
    justify-content: space-between;
    width: 1248px;
    margin: auto;
    margin-top: 30px;
    margin-bottom: 50px;
}
    #top_collect a, #top_collect a:visited{
        color: #1D1A0F;
        text-decoration: none;
        font-size: 18px;
        font-weight: 600;
        display: flex;
    }
    #collection_go_back:hover a{
        text-decoration: underline;
    }
    #top_collect img{
        width: 22px;
        height: 22px;
        margin-right: 10px;
    }
    #top_collect h1{
        font-size: 25px;
        font-weight: 600;
        width: 300px;
        text-align: center;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    #collection_go_back{
        display: flex;
        column-gap: 10px;
        align-items: center;
    }
    .remove_from_collection{
        width: 100%;
        height: 31px;
        font-size: 12px;
        color: #fff;
        background-color: #E85F5F;
        border: 0;
        cursor: pointer;
        transition-duration: 0.5s;
        position: absolute;
        top: -32px;
    }
    .material_userpage:hover .remove_from_collection{
        top: 30px;
    }
    .remove_from_collection:hover{
        color: #e8e8e8;
    }

</style>


<script>
        import axios from 'axios'

    export default{

        name: 'Home',
        data(){
            return{
                materials: null,
                index: null,
                collection_name: ''
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.get('api/collectionitems/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    this.materials = response.data.data;
                    this.index = this.materials.length
                    if(this.index <= 0){
                        document.getElementById('no_works_user').style.display = 'block'
                    }
                })
            });
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.get('api/collectionname/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    this.collection_name = response.data
                })
            });                 
        }, methods: {
            // Удалить из коллекции
            collection_delete(approved_ms_id){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/collectionstatuschange', {
                        collections_id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
                        approved_ms_id: approved_ms_id
                    }).then(response => {
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {   
                            this.$axios.get('api/collectionitems/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                                this.materials = response.data.data;
                                this.index = this.materials.length
                                if(this.index <= 0){
                                    document.getElementById('no_works_user').style.display = 'block'
                                }
                            })
                        });                            
                    })
                });                      
            },
            like(id, user_id, e){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/like', {
                        id: id,
                        user_id: user_id
                    }).then(response => {
                        if(e.target.getAttribute('src') == '/storage/imgs/like_red.png'){
                            e.target.parentElement.nextElementSibling.innerHTML = parseInt(e.target.parentElement.nextElementSibling.innerHTML) - 1
                            e.target.setAttribute('src', '/storage/imgs/like_white.png')
                        }else{
                            e.target.parentElement.nextElementSibling.innerHTML = parseInt(e.target.parentElement.nextElementSibling.innerHTML) + 1
                            e.target.setAttribute('src', '/storage/imgs/like_red.png')
                        }
                        
                    })
                });      
            },
            // Добавить/убрать работу в/из колекции
            collection_status_change(e){
                let values = e.target.value.split(',')
                let collections_id = values[0] 
                let approved_ms_id = values[1] 
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/collectionstatuschange', {
                        collections_id: collections_id,
                        approved_ms_id: approved_ms_id
                    }).then(response => {
                        if(response.data == 'added'){
                            if(e.target.children[e.target.selectedIndex].children[0].children.length <= 0){
                                let span = document.createElement('span')
                                span.innerHTML = " (добавленно)"
                                e.target.children[e.target.selectedIndex].children[0].appendChild(span)
                            }else {
                                e.target.children[e.target.selectedIndex].children[0].children[0].innerHTML = "(добавленно)"
                            }
                        } else {
                            e.target.children[e.target.selectedIndex].children[0].children[0].innerHTML = "(удаленно)"
                            this.$axios.get("/sanctum/csrf-cookie").then((response) => {                      
                                this.$axios.get('api/collectionitems/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                                    this.materials = response.data.data;
                                    this.index = this.materials.length
                                    if(this.index <= 0){
                                        document.getElementById('no_works_user').style.display = 'block'
                                    }
                                })
                            });                             
                        }
                        e.target.selectedIndex = 0
                    })
                });                      
            },
        },
        beforeRouteEnter(to, from, next) {

            // Если пользователь не авторизован
            if(!window.Laravel.user ){
                return next("/");
            }
            // Если это не коллекция пользователя
            let user_belogs_here = ''
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios.get('api/collectioncheck/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    user_belogs_here = response.data
                    if(user_belogs_here == ''){
                        window.location.href = "/"
                    }
                })
            });                 
            next();
        }
    }

</script>