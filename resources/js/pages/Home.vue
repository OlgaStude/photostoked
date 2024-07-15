<template>
    <div class="container">
        <div id="to_log_div" v-if="login_please">
            <div>
                <p>Только <a href="/login">авторизованный</a> пользователь может оценить работу</p>
                <p>Пожайлуста, <a href="/login">сначала войдите в свою учётную запись</a></p>
                <button @click="login_please = false" id="to_log_close">Закрыть</button>
            </div>
            
        </div>
        <div id="to_log_black" v-if="login_please">

        </div>
        <div id="banner">
            <div id="banner_wrapper">
                <h1>photo<span>Stoked</span></h1>
                <p>Русскоязычный фото маркет</p>
            </div>
        </div>
        <div id="material_pop_container">
            <carousel :items-to-show="2.5" :items-to-scroll="1" :pause-autoplay-on-hover="true" :autoplay="5000" :wrap-around="true" :transition="7000">
            <slide class="material_pop" v-for="i in index_popular">
                <div class="material_popwrapper">
                    <a  :href="$router.resolve({name: 'Material', params: { id: materials_popular[i-1].material_id }}).href" class="material_blackgr">

                    </a>
                    <div class="collections_user" v-if="user != null">
                        <select name="" id="" @change="collection_status_change($event)" v-if="materials_popular[i-1].collections.length > 0">
                            <option value="none">Ваши коллекции</option>
                            <option :value="[collection.id, materials_popular[i-1].material_id]" v-for="collection in materials_popular[i-1].collections"><p>{{ collection.name }} <span v-if="collection.is_in">(В коллекции)</span></p> </option>
                        </select>
                        <div name="" id="no_collections" v-else>
                            <a href="/collections">Сначала создайте коллекцию</a>
                        </div>
                    </div>
                    <div class="material_raiting">
                        <div class="rating_btn" v-if="user != null">
                            <img v-if="materials_popular[i-1].is_liked" @click="like(materials_popular[i-1].material_id, materials_popular[i-1].user_id, $event)" :src="'/storage/imgs/like_red.png'" alt="">
                            <img v-else @click="like(materials_popular[i-1].material_id, materials_popular[i-1].user_id, $event)" :src="'/storage/imgs/like_white.png'" alt="">
                        </div>
                        <img v-else :src="'/storage/imgs/like_white.png'" @click="login_please = true" alt="">
                        <p>{{ materials_popular[i-1].likes }}</p>
                    </div>
                    <div class="material_username">
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials_popular[i-1].user_id }}).href"><img :src="'/storage/profile_pics/' + materials_popular[i-1].user_path" alt=""></a>
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials_popular[i-1].user_id }}).href"><p>{{ materials_popular[i-1].nikname }}</p></a>
                    </div>
                    
                    <a class="material_userlink" :href="$router.resolve({name: 'Material', params: { id: materials_popular[i-1].material_id }}).href"><img class="material_pop_img" :src="'/storage/approved_materials/' + materials_popular[i-1].material_path" alt=""></a>
                </div>
                
                
            </slide>
            </carousel>

        </div>

        <a id="to_catalog_btn" href="/catalog">Перейти в каталог</a>

        <div id="popular_authors">
            <div class="pa_wrapper" v-for='author in authors'>
                <a :href="$router.resolve({name: 'Userpage', params: { id: author.id }}).href"><img :src="'/storage/profile_pics/' + author.path" alt=""></a>
                <a :href="$router.resolve({name: 'Userpage', params: { id: author.id }}).href"><p>{{ author.nikname }}</p></a>
            </div>
        </div>

        <div id="material_container">

            <div class="material_userpage" v-for="i in index">
                <div class="material_userwrapper">
                    <a  :href="$router.resolve({name: 'Material', params: { id: materials[i-1].material_id }}).href" class="material_blackgr">

                    </a>
                    <div class="collections_user" v-if="user != null">
                        <select name="" @change="collection_status_change($event)" id="" v-if="materials[i-1].collections.length > 0">
                            <option value="">Ваши коллекции</option>
                            <option :value="[collection.id, materials[i-1].material_id]" v-for="collection in materials[i-1].collections"><p>{{ collection.name }} <span v-if="collection.is_in">(В коллекции)</span></p> </option>
                        </select>
                        <div name="" id="no_collections" v-else>
                            <a href="/collections">Сначала создайте коллекцию</a>
                        </div>
                    </div>
                    <div class="material_raiting">
                        <div class="rating_btn" v-if="user != null">
                            <img v-if="materials[i-1].is_liked" @click="like(materials[i-1].material_id, materials[i-1].user_id, $event)" :src="'/storage/imgs/like_red.png'" alt="">
                            <img v-else @click="like(materials[i-1].material_id, materials[i-1].user_id, $event)" :src="'/storage/imgs/like_white.png'" alt="">
                        </div>
                        <img v-else :src="'/storage/imgs/like_white.png'" @click="login_please = true" alt="">
                        <p>{{ materials[i-1].likes }}</p>
                    </div>
                    <div class="material_username">
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials[i-1].user_id }}).href"><img :src="'/storage/profile_pics/' + materials[i-1].user_path" alt=""></a>
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials[i-1].user_id }}).href"><p>{{ materials[i-1].nikname }}</p></a>
                    </div>
                    
                    <a class="material_userlink" :href="$router.resolve({name: 'Material', params: { id: materials[i-1].material_id }}).href"><img class="material_img" :src="'/storage/approved_materials/' + materials[i-1].material_path" alt=""></a>
                </div>
                
                
                <p class="last_number">{{ last_id = materials[i-1].material_id }}</p>
            </div>
    </div>
    <div id="loading">
        <img :src="'/storage/imgs/loading.gif'" alt="">
    </div>

    </div>
</template>

<style>

#loading{
    display: none;
    justify-content: center;
    
}
#loading img{
    width: 40px;
    margin-bottom: 20px;
}

#to_log_div{
    position: fixed;
    width: 400px;
    top: 300px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 102;
}
    #to_log_div div:first-child{
        box-shadow: 0px 8px 12px 4px rgba(32, 21, 21, 0.25);
        background-color: #fff;
        border: 1px solid #1D1A0F;
        font-size: 18px;
        text-align: center;
        padding: 20px;
    }
    #to_log_div p{
        margin-bottom: 10px;
    }
    #to_log_div a, #to_log_div a:visited{
        color: #1D1A0F;
    }
    #to_log_div a:hover{
        color: #E85F5F;
    }
    #to_log_div button{
        display: block;
        color: #fff;
        background-color: #1D1A0F;
        width: 205px;
        height: 39px;
        text-align: center;
        text-decoration: none;
        padding: 9px;
        font-size: 18px;
        font-weight: 500;
        transition-duration: 0.5s;
        border: 1px solid #1D1A0F;
        margin: auto;
        margin-top: 20px;
        cursor: pointer;
    }
    #to_log_div button:hover{
        color: #1D1A0F;
        background-color: #e8e8e8;
    }

    #to_log_black{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 101;
        height: 100vh;
        width: 100vw;
        background-color: rgb(29, 26, 15, 0.5);
    }


#material_pop_container{
    width: 1536px;
    margin-left: -34px;
    margin-right: -34px;
}

.carousel{
    text-align: left;
    text-wrap: nowrap;
}

.material_pop{
    margin-bottom: 20px;
}
    .material_popwrapper{
        width: max-content;
        height: max-content;
        position: relative;
        overflow: hidden;

    }
    .material_popwrapper:hover .collections_user{
        top: 0px;
    }
    .material_popwrapper:hover .material_username{
        bottom: 15px;
    }
    .material_popwrapper:hover .material_raiting{
        bottom: 15px;
    }
    .material_popwrapper:hover .material_blackgr{
        height: 100%;
    }
    .material_pop_img{
        max-width: 583px;
        max-height: 482px;
    }
    #banner{
        width: 101vw;
        height: 185px;
        background-color: #1D1A0F;
        margin-left: calc((100vw - 1536px + 70px)/ -2);
        margin-top: -50px;
        margin-bottom: 41px;
        box-shadow: 0px 8px 12px 4px rgba(32, 21, 21, 0.25);
        padding-top: 40px;
    }
        #banner_wrapper{
            width: max-content;
            margin-top: 47px;
            margin: auto;
            cursor: default;
        }
            #banner h1{
                font-family: 'Jura';
                text-align: center;
                font-size: 60px;
                color: #fff;
                font-weight: 500;
            }
            #banner span{
                font-family: 'Jura';
                color: #E85F5F;
                font-weight: 600;
            }
            #banner p{
                color: #fff;
                font-size: 14px;
                text-align: center;
            }

            #to_catalog_btn, #to_catalog_btn:visited{
                display: block;
                color: #fff;
                background-color: #1D1A0F;
                width: 205px;
                height: 39px;
                text-align: center;
                text-decoration: none;
                padding: 9px;
                font-size: 18px;
                font-weight: 500;
                transition-duration: 0.5s;
                border: 1px solid #1D1A0F;
                margin: 20px auto;
            }
            #to_catalog_btn:hover{
                color: #1D1A0F;
                background-color: #e8e8e8;
            }

            #popular_authors{
                display: flex;
                justify-content: space-around;
                padding-top: 20px;
                margin-top: 30px;
                margin-bottom: 50px;
                height: 150px;
                width: 101vw;
                margin-left: calc((100vw - 1536px + 70px)/-2);
                background-color: #1D1A0F;
                color: #fff;
            }
            .pa_wrapper{
                max-width: 100px;
            }
            .pa_wrapper p{
                width: 100%;
                text-align: center;
                text-overflow: ellipsis;
                overflow: hidden;
                margin-top: 10px;
            }
            .pa_wrapper a:first-child{
                display: block;
                width: 80px;
                height: 80px;
                border-radius: 50px;
                overflow: hidden;
                position: relative;
                left: 50%;
                transform: translateX(-50%);
            }
            .pa_wrapper a, .pa_wrapper a:visited{
                color: #fff;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
            }
            .pa_wrapper:hover a{
                text-decoration: underline;
            }
            .pa_wrapper:hover a:first-child{
                outline: 1px solid #fff;
            }
            .pa_wrapper img{
                max-width: 100%;
                min-height: 100%;
                position: relative;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

.last_number{
    display: none;
}



</style>


<script>
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
    export default{
        name: 'Home',
        data(){
            return {
                materials: null,
                materials_popular: null,
                index: null,
                index_popular: null,
                last_id: null,
                user: window.Laravel.user,
                authors: null,
                login_please: false,
                stop: false
            }
        },
        components: {
            Carousel,
            Slide,
            Pagination,
            Navigation,
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/maindata').then(response => {
                this.materials = response.data.data;
                
                this.index = this.materials.length
            })
            });      
             this.$axios.get("/sanctum/csrf-cookie").then((response) => {           
            this.$axios.get('api/maindatapopular').then(response => {
                console.log(response.data.data)
                this.materials_popular = response.data.data;
                this.index_popular = this.materials_popular.length
            })
            });      
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/author_').then(response => {
                this.authors = response.data.data;
            })
             });                 
            window.addEventListener('scroll', this.handleScroll);
        }, methods: {
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
                     }
                     e.target.selectedIndex = 0
                })
              });                    
            },
            handleScroll() {
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight)
                if (document.documentElement.scrollTop + window.innerHeight >= document.documentElement.scrollHeight - 140) {
                    console.log('sees')
                    if(!this.stop){
                        document.getElementById('loading').style.display = 'flex'
                    }
                window.removeEventListener('scroll', this.handleScroll)
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/moredata', {
                    id: this.last_id
                 }).then(response => {
                     document.getElementById('loading').style.display = 'none'
                     if(response.data.data.length > 0){

                         for (let i = 0; i < response.data.data.length; i++) {
                             this.materials.push(response.data.data[i]);
                            }
                            this.index = this.materials.length
                            window.addEventListener('scroll', this.handleScroll);
                        }else{
                            this.stop = true
                        }
            })
             });                 
            }
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
        }
    }

</script>
