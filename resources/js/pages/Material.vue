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
        <div id="material_info">
            <div id="mat_itself_container">
                <img class="info_material" :src="'/storage/approved_materials/' + material.material_path" alt="">
            </div>
            <div id="mat_info_container">
                <div class="collections_mat" v-if="is_logged">
                    <select name="" @change="collection_status_change($event)" id="" v-if="collections_check">
                        <option value="">Ваши коллекции</option>
                        <option :value="[collection.id, material.material_id]" v-for="collection in material.collections"><p>{{ collection.name }} <span v-if="collection.is_in">(В коллекции)</span></p> </option>
                    </select>
                    <div name="" id="no_collections_mat" v-else>
                        <a href="/collections">Сначала создайте коллекцию</a>
                    </div>
                </div>
                <p id="format_info" v-if="material.format == 'squere'">Квадратное</p>
                <p id="format_info" v-if="material.format == 'vertical'">Вертикальное</p>
                <p id="format_info" v-if="material.format == 'horrizontal'">Горизонтальное</p>
                
                <p v-if="material.type == 'illustration'">Рисунок</p>
                <p v-if="material.type == 'photo'">Фото</p>
                
                <div id="info_mat_bottom">
                    <div v-if="is_bought && is_logged">
                        <button @click="download(0, 0)" class="buy_btn">Скачать</button><br>
                        <span>Вы уже приобрели этот материал</span>
                    </div>
                    <div v-else-if="material.user_id == user_id">
                        <button @click="download(0, 0)" class="buy_btn">Скачать</button><br>
                        <p>Это ваша работа</p>
                    </div>
                    <div v-else-if="packages == 0 && is_logged">
                        <p>У вас пока нет пакетов.</p>
                        <p><a href="/pockets">Приобретите пакет, для скачивания</a></p>
                    </div>
                    <div v-else-if="is_logged">
                        <button @click="download(material.user_id, material.material_id)" class="buy_btn">Скачать</button>
                        <p>У вас осталось {{ packages }} скачиваний</p>
                    </div>
                    <div v-else>
                        <a href="/login">Войдите, чтобы иметь возможность скачивания</a>
                    </div>
                </div>
                
            </div>
            
        </div>
        
        <div id="author_likes">
            <div id="mat_user">
                <a :href="$router.resolve({name: 'Userpage', params: { id: material.user_id }}).href"><img :src="'/storage/profile_pics/' + material.user_path" style="width: 100px;" alt=""></a>
                <a :href="$router.resolve({name: 'Userpage', params: { id: material.user_id }}).href"><p>{{ material.nikname }}</p></a>
            </div>
            
    
            
    
            <div class="material_raiting_sing">
                <div class="rating_btn_sing" v-if="is_logged">
                    <img v-if="material.is_liked" @click="like(material.material_id, material.user_id, $event)" :src="'/storage/imgs/like_red.png'" alt="">
                    <img v-else @click="like(material.material_id, material.user_id, $event)" :src="'/storage/imgs/like.png'" alt="">
                </div>
                <img v-else :src="'/storage/imgs/like.png'" @click="login_please = true" alt="">
                <p>{{ material.likes }}</p>
            </div>
        </div>
        


    </div>
</template>

<style>


#material_info{
    display: flex;
    column-gap: 20px;
}
    #mat_itself_container{
        width: 900px;
        height: 640px;
        position: relative;
        margin-left: 20px;
    }
    .info_material{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        max-width: 100%;
        max-height: 100%;
        margin-right: 56px;
    }

    #mat_info_container{
        font-size: 20px;
        font-weight: 600;
        position: relative;
        margin-left: 20px;
        width: 500px;
    }
        #format_info{
            margin-top: 20px;
            margin-bottom: 15px;
        }

        #info_mat_bottom{
            position: absolute;
            bottom: 0;
            right: 0;
            text-align: right;
        }
        #info_mat_bottom a,#info_mat_bottom a:visited{
            color: black;
        }
        #info_mat_bottom a:hover{
            color: #E85F5F;
        }

        #author_likes{
            width: 900px;
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            margin-bottom: 50px;
            align-items: center;
        }
        #mat_user{
            display: flex;
            column-gap: 25px;
            align-items: center;
            text-wrap: nowrap;

        }
        #mat_user a, #mat_user a:visited{
            color: #1D1A0F;
            text-decoration: none;
            font-size: 20px;
            font-weight: 600;
        }
        #mat_user a:first-child{
            display: block;
            width: 70px;
            height: 70px;
            overflow: hidden;
            border-radius: 50px;
        }
            #mat_user a img{
                max-width: 100%;
                min-height: 100%;
                position: relative;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            #mat_user p{
                width: 300px;
                text-overflow: ellipsis;
                overflow: hidden;
            }

        .material_raiting_sing{
            display: flex;
            column-gap: 10px;
            height: 44px;
            align-items: center;
        }
            .material_raiting_sing p{
                font-weight: 600;
            }
        .material_raiting_sing img{
            cursor: pointer;
        }

        .collections_mat{
            width: 100%;
        }
        .collections_mat select{
            width: 100%;
            height: 30px;
            border-radius: 0px;
            background-color: #fff; 
            font-family: 'inter';
            padding-left: 7px;
        }
        .collections_mat option{
            font-family: 'inter';
        }

        #no_collections_mat{
            width: 100%;
            background-color: #fff;
            font-size: 16px;
        }
            #no_collections_mat a, #no_collections_mat a:visited{
                color: #1D1A0F;
                line-height: 25px;
            }
            #no_collections_mat a:hover{
                color: #E85F5F;
            }

            .buy_btn{
                width: 119px;
                height: 37px;
                background-color: #1D1A0F;
                color: #fff;
                font-size: 20px;
                font-weight: 600;
                cursor: pointer;
                transition-duration: 0.5s;
                margin-bottom: 20px;
            }
            .buy_btn:hover{
                border: 1px solid #1D1A0F;
                background-color: #e8e8e8;
                color: #1D1A0F;
            }

</style>

<script>

    export default{
        name: 'Home',
        data(){
            return{
                material: [],
                packages: 0,
                is_bought: false, 
                user_id: 0,
                is_logged: false,
                collections_check: false,
                login_please: false
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.get('api/material/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    this.material = response.data.data[0];
                    if(this.material.collections.length > 0){
                        this.collections_check = true
                    }
                })
                // Загрузка данных, если пользователь авторизован
                if(window.Laravel.user){
                    this.is_logged = true
                    this.$axios.get('api/pakages').then(response => {
                        this.packages = response.data
                    })
                    this.user_id = window.Laravel.user.id
                    this.$axios.get('api/isbought/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                        this.is_bought = response.data;
                    })
                }
            });                     
        }, methods: {
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
                        }
                        e.target.selectedIndex = 0
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
                            e.target.setAttribute('src', '/storage/imgs/like.png')
                        }else{
                            e.target.parentElement.nextElementSibling.innerHTML = parseInt(e.target.parentElement.nextElementSibling.innerHTML) + 1
                            e.target.setAttribute('src', '/storage/imgs/like_red.png')
                        }
                        
                    })
                });
        
            },
            download(user_id, material_id){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/downloadmaterial', {
                        user_id: user_id,
                        material_id: material_id
                    }).then(response => {
                            this.$axios.get('storage/original_imgs/' + this.material.original_path).then(response => {
                            let fileUrl = 'storage/original_imgs/' + this.material.original_path;
                            let fileLink = document.createElement('a');
                            fileLink.href = fileUrl;
                            
                            fileLink.setAttribute('download', this.material.original_path);
                            document.body.appendChild(fileLink)
                            
                            fileLink.click();
                        })
                        
                        this.is_bought = true
                    })
                });
            }
        }
    }

</script>