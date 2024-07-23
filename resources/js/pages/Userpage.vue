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
        <div id="personal">
                <div id="pesonal_user_pfp">
                    <img id="persona_pfp" :src="'/storage/profile_pics/'+owner.path" alt="">
                </div>
                <div id="persona_info">
                    <div id="personal_top_fragment">
                        <p v-if="user.id == owner.id">Это ваша личная страница, {{ owner.nikname }}</p>
                        <p v-else>Это страница {{ owner.nikname }}</p>
                        <a  v-if="user.id == owner.id" href="/edit"><img id="persona_pfp" :src="'/storage/imgs/notepad_icon.png'" alt=""></a>
                        <div id="report_btn" v-if="is_logged && user.id != owner.id">
                            <img @click="report_user" :src="'/storage/imgs/flag.png'" title="Отправить жалобу">
                            <p>Вы отправили жалобу</p>
                        </div>
                    </div>
                    <div id="personal_buttom_fragment">
                        <p>{{ owner.add_info }}</p>
                    </div>
                </div>
            <div id="persona_money" v-if="user.id == owner.id">
                <p v-if="owner.money != 0">На вашем считу сейчас: {{ owner.money }}р,<br>
                    вы можете <span @click='take_money()'>Снять деньги</span></p>
                    <p v-else>На вашем считу сейчас: {{ owner.money }}</p>
                <p id="money_ammount">Покупок осталось: {{ packages }}</p>
            </div>
            <div id="sub_btn" v-else-if="is_logged">
                <button id="subscribed"  v-if="subscribed" @click="subscribe">В избранном</button>
                <button id="not_subscribed" v-else @click="subscribe">Добавить в избранное</button>
            </div>
            
        </div>
        
        <div id="owner_nav" v-if="user.id == owner.id">
            <div id="owner_nav_wrapper">
                <a id="you_here" :href="$router.resolve({name: 'Userpage', params: { id: owner.id }}).href">Мои работы</a>
                <a href="/liked">Понравилось</a>
                <a href="/subscribtions">Избранные авторы</a>
                <a href="/collections">Коллекции</a>
                <a href="/bougth">Мои покупки</a>
            </div>
        </div>
        <div v-else id="empty_nav">

        </div>
        
        <div id="material_container">

            <div class="material_userpage" v-for="i in index">
                <div class="material_userwrapper">
                    <a  :href="$router.resolve({name: 'Material', params: { id: materials[i-1].material_id }}).href" class="material_blackgr">

                    </a>
                    <div class="collections_user" v-if="is_logged">
                        <select name="" @change="collection_status_change($event)" id="" v-if="materials[i-1].collections.length > 0">
                            <option value="">Ваши коллекции</option>
                            <option :value="[collection.id, materials[i-1].material_id]" v-for="collection in materials[i-1].collections"><p>{{ collection.name }} <span v-if="collection.is_in">(В коллекции)</span></p> </option>
                        </select>
                        <div name="" id="no_collections" v-else>
                            <a href="/collections">Сначала создайте коллекцию</a>
                        </div>
                    </div>
                    <div class="material_raiting">
                        <div class="rating_btn" v-if="is_logged">
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
                
                
            </div>
    </div>
    <div id="no_works_user">
        <img :src="'/storage/imgs/no_imgs.png'" alt="">
        <p>Тут пока ничего нет...</p>
    </div>

    </div>
</template>

<style>

#report_btn{
    position: relative;
}
    #report_btn img{
        width: 30px;
        cursor: pointer;
    }
    #report_btn p{
        position: absolute;
        font-size: 16px;
        bottom: 0px;
        right: -670px;
        display: none;
    }

#no_works_user{
    display: none;
    margin: auto;
    width: fit-content;
    margin-top: 50px;
    font-size: 18px;
    text-align: center;
}
#no_works_user img{
    width: 70px;
    margin-bottom: 20px;
}

#personal{
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: fit-content;
    column-gap: 34px;
}
#personal_con{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
    #pesonal_user_pfp{
        overflow: hidden;
        width: 80px;
        height: 80px;
        border-radius: 80px;
    }
        #pesonal_user_pfp img{
            max-width: 100%;
            min-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    #persona_info img{
        width: 23px;
    }
        #personal_top_fragment{
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 11px;
            column-gap: 13px;
            max-width: 626px;
            width: fit-content;
        }
        #personal_top_fragment p{
            text-overflow: ellipsis;
            overflow: hidden;
            text-wrap: nowrap;

        }
        #personal_buttom_fragment{
            max-width: 426px;

        }
    #persona_money{
        width: 251px;
        font-size: 14px;
        text-align: center;
        line-height: 20px;
        margin-left: 20px;
    }
        #persona_money span{
            text-decoration: underline;
        }
        #persona_money span:hover{
            color: #E85F5F;
            cursor: pointer;
        }
        #money_ammount{
            margin-top: 16px;
        }
    #sub_btn{
        margin-top: -20px;
    }
    #sub_btn button{
        width: 193px;
        height: 31px;
        font-size: 14px;
        cursor: pointer;
        transition-duration: 0.5s;
        border-radius: 0;
        border: 1px solid black;
    }
        #sub_btn button:hover{
            background-color: #e8e8e8;
            color: #1D1A0F;
        }
        #not_subscribed{
            color: #fff;
            background-color: #1D1A0F;
        }
        #subscribed{
            color: #1D1A0F;
            background-color: #fff;
        }

    #owner_nav, #empty_nav{
        width: 101vw;
        margin-left: calc(-1 * ((100vw - 1536px + 70px) / 2));
        background-color: #1D1A0F;
        position: relative;
        font-size: 14px;
        padding-top: 7px;
        margin-top: 26px;
    }
    #owner_nav{
        height: 35px;
    }
    #owner_nav_wrapper{
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    #owner_nav_wrapper a, #owner_nav_wrapper a:visited{
        color: white;
        text-decoration: none;
        display: inline-block;
        padding: 0px 17px;
        transition-duration: 0.5s;
    }
    #owner_nav_wrapper a:not(:last-child){
        border-right: 1px solid white;
    }
    #owner_nav_wrapper a:hover{
        color: #bebebe;
    }
    #you_here:hover:after{
        content: '';
        border: 1px solid #bebebe;
        background-color: #bebebe;
    }
    #you_here:after{
        content: '';
        border: 1px solid #fff;
        border-radius: 20px;
        width: 20px;
        height: 0.5px;
        background-color: #fff;
        position: absolute;
        left: 18px;
        bottom: -3px;
        transition-duration: 0.5s;
    }

    #material_container{
        position: relative;
        left: 50%;
        transform: translateX(-45%);
        width: 1248px;
        display: flex;
        flex-wrap: wrap;
        column-gap: 20px;
        margin-top: 30px;
    }
    .material_userpage{
        width: 370px;
        height: 370px;
        margin-bottom: 20px;
    }
    .material_userwrapper{
        width: max-content;
        height: max-content;
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        overflow: hidden;
    }
        .material_blackgr, .collections_user, .material_raiting, .material_username{
            position: absolute;
            transition-duration: 0.5s;
        }
        
        .material_userwrapper:hover .collections_user{
            top: 0px;
        }
        .collections_user{
            width: 100%;
            top: -40px;
        }
        .collections_user select{
            width: 100%;
            height: 30px;
            border-radius: 0px;
            background-color: #fff; 
            font-family: 'inter';
            padding-left: 7px;
        }
        .collections_user option{
            font-family: 'inter';
        }
        .collections_user p{
            width: 100px;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        #no_collections{
            width: 100%;
            background-color: #fff;
            border: 1px solid #1D1A0F;
            font-size: 12px;
        }
            #no_collections a, #no_collections a:visited{
                color: #1D1A0F;
                line-height: 25px;
                padding-left: 7px;
            }
            #no_collections a:hover{
                color: #E85F5F;
            }

        .material_username{
            bottom: -60px;
            left: 10px;
            display: flex;
            align-items: center;
            column-gap: 10px;
            color: #fff;
            width: 60%;
            text-wrap: nowrap;

        }
        .material_userwrapper:hover .material_username{
            bottom: 15px;
        }
            .material_username a:first-child{
                display: block;
                width: 55px;
                height: 55px;
                overflow: hidden;
                border-radius: 50px;
                box-sizing: border-box;
                flex: 0 0 55px;
            }
            .material_username a:nth-child(2){
                text-overflow: ellipsis;
                overflow: hidden;
                max-width: 70%;
            }
                .material_username a img{
                    max-width: 100%;
                    min-height: 100%;
                    position: relative;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }
                .material_username a, .material_username a:visited{
                    color: #fff;
                    text-decoration: none;
                }
                .material_username:hover a{
                    text-decoration: underline;
                }
                .material_username p{
                    width: 100%;
                    text-overflow: ellipsis;
                    overflow: hidden;
                }
        .material_raiting{
            bottom: -60px;
            right: 10px;
            display: flex;
            align-items: center;
            column-gap: 10px;
            color: #fff;
        }
        .material_userwrapper:hover .material_raiting{
            bottom: 15px;
        }
            .material_raiting img{
                cursor: pointer;
                width: 40px;
                height: 40px;
            }
        .material_blackgr{
            display: block;
            height: 0%;
            width: 100%;
            bottom: 0;
            background: linear-gradient(360deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
        }
        .material_userwrapper:hover .material_blackgr{
            height: 100%;
        }

        .material_img{
            max-width: 370px;
            max-height: 370px;
        }



.last_number{
    display: none;
}



</style>

<script>

    export default{
        name: 'Home',
        data(){
            return {
                user: {
                    id: 0
                },
                owner: {},
                materials: null,
                index: null,
                subscribed: false,
                packages: 0,
                is_logged: false,
                login_please: false,
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.get('api/getuser/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    this.owner = response.data
                })
                this.$axios.get('api/subcheck/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    this.subscribed = response.data
                })      
                this.$axios.get('api/usermaterials/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
                    this.materials = response.data.data
                    this.index = this.materials.length
                    if(this.index <= 0){
                        document.getElementById('no_works_user').style.display = 'block'
                    }
                })
                if(window.Laravel.user){
                    this.is_logged = true;
                    
                    this.user = window.Laravel.user
                    this.$axios.get('api/pakages').then(response => {
                        this.packages = response.data
                    })
                }
            });

            window.addEventListener('scroll', this.handleScroll);

        }, methods: {
            report_user(){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {              
                    this.$axios.post('api/reportuser', {
                        id: this.owner.id,
                    }).then(response => {
                        document.querySelector('#report_btn p').style.display = 'inline'
                    })
                });              
            },
            // Переадрессация на страницу банка для снятия средств
            take_money(){
                localStorage.setItem('pocket_price', 'take')
                window.location.href = "/bank"
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
                            e.target.setAttribute('src', '/storage/imgs/like_white.png')
                        }else{
                            e.target.parentElement.nextElementSibling.innerHTML = parseInt(e.target.parentElement.nextElementSibling.innerHTML) + 1
                            e.target.setAttribute('src', '/storage/imgs/like_red.png')
                        }
                    })
                });
            },
            subscribe(e){
                e.preventDefault();
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/follow', {
                        followed_id: this.owner.id,
                    }).then(response => {
                        this.subscribed = !this.subscribed
                    })
                });
            }
        }
    }

</script>