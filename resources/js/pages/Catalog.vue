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
        <div id="filter_bar">
            <div @click="filter_open()" id="filter_bar_text">
                <img :src="'/storage/imgs/arrow_down.png'" alt="">
                <p>Фильтрация</p>
            </div>
            <div id="filter_options" v-if="filter_catalog_open">
                <div id="options_wrapper">
                    <div id="format_options">
                        <input type="radio" name="format" v-model="format" value="squere" id="squere">
                        <label for="squere">квадратная</label><br>
                        <input type="radio" name="format" v-model="format" value="horrizontal" id="horrizontal">
                        <label for="horrizontal">горизонтальная</label><br>
                        <input type="radio" name="format" v-model="format" value="vertical" id="vertical">
                        <label for="vertical">вертикальная</label><br>
                    </div>
                    <div id="type_options">
                        <input type="radio" name="type" v-model="type" value="photo" id="photo">
                        <label for="photo">фото</label><br>
                        <input type="radio" name="type" v-model="type" value="illustration" id="illustration">
                        <label for="illustration">иллюстрация</label>
                    </div>
                    <div id="filter_btns">
                        <button id="sort_btn" @click="filter($event)">сортировать</button>
                        <button id="cancel_btn" @click="cancel_filter($event)">сбросить</button>
                    </div>
                </div>
            </div>
            <input id="search_bar" type="text" name="search_bar" @keyup="search($event)" v-model="search_bar">
        </div>
     <div id="tags">
            <div id="tags_wrapper">
                <carousel :items-to-show="7" :wrap-around="true">
                    <slide v-for="tag in tags" :key="tag">
                        <p class="tag"><a @click="tag_search($event)" href="#" class="tag_link">{{ tag.tag_name }}</a></p>
                    </slide>
                
                    <template #addons>
                    <navigation />
                    </template>
                </carousel>
            </div>
        </div>
        <div @click="all_works" id="catalog_back">
            <img :src="'/storage/imgs/Arrow_back.png'" alt="">
            <p>Все работы</p>
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
    <div id="no_works">
        <img :src="'/storage/imgs/no_img.svg'" alt="">
        <p>Ничего не найдено...</p>
    </div>
    </div>
</template>

<style>

#no_works{
    display: none;
    margin: auto;
    width: fit-content;
    margin-top: 50px;
    font-size: 18px;
    text-align: center;
}
#no_works img{
    width: 70px;
    margin-bottom: 20px;
}

#catalog_back{
    display: none;
    align-items: center;
    column-gap: 20px;
    font-size: 20px;
    font-weight: 600;
    cursor: pointer;
    margin-left: 170px;
}
#catalog_back img{
    height: 30px;
}

#filter_bar{
    width: 100vw;
    height: 63px;
    margin-left: calc((100vw - 1536px + 70px)/ -2);
    margin-top: -45px;
    padding: 0px 40px;
    background-color: #1D1A0F;
    display: flex;
    justify-content: space-between;
    overflow: visible;
    position: relative;
}   

#filter_bar_text{
    display: flex;
    column-gap: 15px;
    margin-top: 15px;
    cursor: pointer;
}
    #filter_bar_text img{
        width: 16px;
        height: 9px;
        margin-top: 10px;
        transition: 0.5s;
    }
        .rotete{
            transform: rotate(180deg);
        }
    #filter_bar_text p{
        color: #fff;
        font-size: 24px;
        font-weight: 600;
    }

    #filter_options{
        height: 148px;
        width: 698px;
        background-color: #fff;
        border: 1px solid #1D1A0F;
        box-shadow: 0px 4px 9px 5px rgba(42, 19, 19, 0.25);
        position: absolute;
        padding-top: 27px;
        padding-left: 36px;
        left: 0;
        bottom: -148px;
        z-index: 99;
    }

    #options_wrapper{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #format_options, #type_options{
        width: 275px;
        margin-right: 40px;
    }
    #format_options label, #type_options label{
        font-size: 20px;
        line-height: 29.6px;
        cursor: pointer;
        margin-left: 5px;
    }
    #format_options input, #type_options input{
        margin-bottom: 10px;
        cursor: pointer;
    }
    #filter_btns button{
        height: 31px;
        width: 175px;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition-duration: 0.5s;
    }
        #sort_btn{
            background-color: #1D1A0F;
            margin-bottom: 15px;
        }
        #sort_btn:hover{
            background-color: #e8e8e8;
            border: 1px solid #1D1A0F;
            color: #1D1A0F;
        }
        #cancel_btn{
            background-color: #E85F5F;
            border: 0;
        }

    #search_bar{
        width: 506px;
        height: 35px;
        font-size: 14px;
        padding-left: 10px;
        margin-top: 12px;
    }

    #tags{
        width: 100vw;
        margin-left: calc((100vw - 1536px + 70px)/ -2);
        margin-bottom: 50px;
        height: 56px;
        border-bottom: 1px solid #1D1A0F;
        box-shadow: 0px 1px 12px 5px rgba(55, 30, 30, 0.25);
        overflow: visible;
        padding: 15px 62px 0px 42px;
    }
    .tag{
        width: 130px;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .tag a, .tag a:visited{
        color: #1D1A0F;
        font-size: 18px;
        font-weight: 600;
    }

</style>

<script>
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
    export default{
        name: 'Catalog',
        data(){
            return{
                materials: null,
                tags: null,
                index: null,
                last_id: null,
                user: window.Laravel.user,
                search_bar: '',
                search_word: '',
                current_tag: '',
                format: '',
                type: '',
                filter_catalog_open: false,
                login_please: false,
                stop: false
            }
        }, components: {
            Carousel,
            Slide,
            Pagination,
            Navigation,
        },created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/maindata').then(response => {
                console.log(response.data)
                this.materials = response.data.data;
                this.index = this.materials.length
            })
             });
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/tags').then(response => {
                console.log(response.data)
                this.tags = response.data
            })
             });                 
            window.addEventListener('scroll', this.handleScroll);
        }, methods: {
            filter_open(){
                if(!this.filter_catalog_open){
                    this.filter_catalog_open = true
                    document.querySelector('#filter_bar_text img').classList.add('rotete')
                }else{
                    this.filter_catalog_open = false
                    document.querySelector('#filter_bar_text img').classList.remove('rotete')
                }
                
            },
            collection_status_change(e){
                let values = e.target.value.split(',')
                let collections_id = values[0] 
                let approved_ms_id = values[1] 
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/collectionstatuschange', {
                    collections_id: collections_id,
                    approved_ms_id: approved_ms_id
                 }).then(response => {
                    //  alert(response.data)
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
            if (document.documentElement.scrollTop + window.innerHeight >= document.documentElement.scrollHeight -140) {
                if(!this.stop){
                        document.getElementById('loading').style.display = 'flex'
                    }
                window.removeEventListener('scroll', this.handleScroll)
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/moredata', {
                    id: this.last_id,
                    search_word: this.search_word,
                    tag: this.current_tag,
                    type: this.type,
                    format: this.format
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
            window.addEventListener('scroll', this.handleScroll);
            })
            });                  
            }
            },
            search(e){
                this.stop = false
                if(this.search_bar == ''){
                    this.search_word = ''
                }
                if(e.keyCode == 13){
                    document.getElementById('catalog_back').style.display = 'flex'
                    this.search_word = this.search_bar
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/searchword', {
                        search_word: this.search_word
                    }).then(response => {
                        console.log(response.data)
                        this.materials = response.data.data;
                        this.index = this.materials.length
                            
                    }).catch((err) => {
                        this.materials = null
                        this.index = 0
                        document.getElementById('no_works').style.display = 'block'

                    });
             });                         
                }
            },
            filter(e){
                e.preventDefault()
                this.stop = false
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/filter', {
                    format: this.format,
                    type: this.type,
                    search_word: this.search_word,
                    tag: this.current_tag
                }).then(response => {
                    this.materials = response.data.data;
                    this.index = this.materials.length
                    if(this.index <= 0){
                        document.getElementById('no_works').style.display = 'block'
                    }
                        
                })
             });                     
            },
            cancel_filter(e){
                e.preventDefault()
                this.format = ''
                this.type = ''
                this.stop = false
                document.getElementById('no_works').style.display = 'none'
                if(this.search_word != ''){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/searchword', {
                        search_word: this.search_word
                    }).then(response => {
                        console.log(response.data)
                        this.materials = response.data.data;
                        this.index = this.materials.length
                            
                    })
              });                        
                }else if(this.current_tag != ''){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/searchword', {
                        search_word: this.current_tag
                    }).then(response => {
                        console.log(response.data)
                        this.materials = response.data.data;
                        this.index = this.materials.length
                            
                    })
            });                          
                }else{
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.get('api/maindata').then(response => {
                    console.log(response.data)
                    this.materials = response.data.data;
                    this.index = this.materials.length
                })
             });                     
                }
            },
            tag_search(e){
                e.preventDefault();
                this.stop = false
                document.getElementById('catalog_back').style.display = 'flex'
                this.search_word = '';
                this.search_bar = '';
                this.current_tag = e.target.innerHTML;
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/searchword', {
                    search_word: this.current_tag
                    }).then(response => {
                        console.log(response.data)
                        this.materials = response.data.data;
                        this.index = this.materials.length
                            
                    })
             });                         
                console.log(e.target.innerHTML)
            },
            all_works(){
                this.search_bar = ''
                this.search_word = ''
                this.current_tag = ''
                this.format = ''
                this.type = ''
                this.stop = false
                document.getElementById('catalog_back').style.display = 'none'
                document.getElementById('no_works').style.display = 'none'
             this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                this.$axios.get('api/maindata').then(response => {
                    this.materials = response.data.data;
                    this.index = this.materials.length
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
        }
    }

</script>