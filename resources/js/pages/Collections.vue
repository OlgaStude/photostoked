<template>
    <div class="container">
        <div id="personal">
            <div id="pesonal_user_pfp">
                <img id="persona_pfp" :src="'/storage/profile_pics/'+owner.path" alt="">
            </div>
            <div id="persona_info">
                <div id="personal_top_fragment">
                    <p>Это ваша личная страница, {{ owner.nikname }}</p>
                    <a  href="/edit"><img id="persona_pfp" :src="'/storage/imgs/notepad_icon.png'" alt=""></a>
                </div>
                <div id="personal_buttom_fragment">
                    <p>{{ owner.add_info }}</p>
                </div>
            </div>
        <div id="persona_money">
            <p v-if="owner.money != 0">На вашем считу сейчас: {{ owner.money }}р,<br>
                вы можете <span @click='take_money()'>Снять деньги</span></p>
                <p v-else>На вашем считу сейчас: {{ owner.money }}</p>
            <p id="money_ammount">Покупок осталось: {{ packages }}</p>
        </div>
    </div>
    
    <div id="owner_nav">
        <div id="owner_nav_wrapper">
            <a :href="$router.resolve({name: 'Userpage', params: { id: owner.id }}).href">Мои работы</a>
            <a href="/liked">Понравилось</a>
            <a href="/subscribtions">Избранные авторы</a>
            <a id="you_here_collections" href="/collections">Коллекции</a>
            <a href="/bougth">Мои покупки</a>
        </div>
    </div>
        
    <div id="add_collection">
        <span id="collection_name_error">{{ errors.name }}</span>
        <input id="collection_input" type="text" v-model="collection_name">
        <button type="submit" @click="craete_new" id="create_btn">Создать</button>
    </div>

    <div id="collections">
        <div class="collections_div" v-for="item in items">
            <div v-if="item.path == ''" class="no_cover"><a :href="$router.resolve({name: 'Collection_page', params: { id: item.id }}).href">Пустая коллекция</a></div>
            <div v-else class="cover"><a :href="$router.resolve({name: 'Collection_page', params: { id: item.id }}).href"><img class="collection_cover_img" :src="'/storage/approved_materials/' + item.path" alt=""></a></div>
            <div class="collection_bottom_part">
                <a :href="$router.resolve({name: 'Collection_page', params: { id: item.id }}).href"><p>{{ item.name }}</p></a>
                <p class="collection_del_btn" @click="delete_collection(item.id)">-</p>
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



#you_here_collections:hover:after{
    content: '';
    border: 1px solid #bebebe;
    background-color: #bebebe;
}
#you_here_collections:after{
    content: '';
    border: 1px solid #fff;
    border-radius: 20px;
    width: 20px;
    height: 0.5px;
    background-color: #fff;
    position: absolute;
    left: 427px;
    bottom: -3px;
    transition-duration: 0.5s;
}

#add_collection{
    width: fit-content;
    margin: auto;
    margin-top: 20px;
    position: relative;
    overflow: visible;
}

    #collection_input{
        width: 250px;
        height: 31px;
        border: 1px solid #1D1A0F;
        font-size: 14px;
        padding-left: 7px;
        margin-right: 27px;
    }
    #create_btn{
        width: 130px;
        height: 31px;
        background-color: #1D1A0F;
        color: #fff;
        font-size: 14px;
        cursor: pointer;
        transition-duration: 0.5s;
        border: 0;
    }
    #create_btn:hover{
        color: #1D1A0F;
        background-color: #bebebe;
        border: 1px solid #1D1A0F;
    }
    #collection_name_error{
        position: absolute;
        font-size: 14px;
        color: #E85F5F;
        bottom: -20px;
    }

    #collections{
        display: flex;
        flex-wrap: wrap;
        width: 1047px;
        margin: auto;
        column-gap: 100px;
        padding-left: 60px;
        margin-top: 50px;
        margin-bottom: 50px;
    }
        .collections_div{
            margin-bottom: 20px;
        }
            .collections_div:hover .collection_del_btn{
                opacity: 1;
            }
        .collection_bottom_part{
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
            .collection_bottom_part a,.collection_bottom_part a:visited{
                color: #1D1A0F;
                text-decoration: none;
                font-size: 16px;
                font-weight: 500;
                margin-left: 5px;
                
            }
            .collection_bottom_part a p{
                text-overflow: ellipsis;
                max-width: 180px;
                overflow: hidden;
            }
            .collection_bottom_part a:hover{
                text-decoration: underline;
            }
            .collection_del_btn{
                opacity: 0;
                border: 1px solid #1D1A0F;
                height: 100%;
                width: 25px;
                text-align: center;
                cursor: pointer;
                transition-duration: 0.5s;
            }
            .collection_del_btn:hover{
                border-color: #bebebe;
                color: #bebebe;
            }

            .no_cover a, .no_cover a:visited{
                cursor: pointer;
                display: block;
                width: 249px;
                height: 270px;
                position: relative;
                margin: 0;
                text-align: center;
                padding-top: 120px;
                font-size: 20px;
                text-decoration: none;
                color: #1D1A0F;
                background-color: rgb(29, 26, 15, 0.3);
                outline: 2px solid rgb(29, 26, 15, 0.5);
            }
            .no_cover a:hover{
                outline: 2px solid #1D1A0F;
            }

            .cover{
                cursor: pointer;
                width: 249px;
                height: 270px;
                background-color: #e8e8e8;
                outline: 1px solid #1D1A0F;
                transition-duration: 0.5s;
                transition-property: background-color;
                overflow: hidden;
            }
            .cover:hover{
                background-color: #bdbdbd;
                outline: 2px solid #1D1A0F;
            }
            .collection_cover_img{
                max-width: 100%;
                max-height: 100%;
                position: relative;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

</style>

<script>

    export default{
        name: 'Home',
        data(){
            return{
                user: window.Laravel.user,
                collection_name: '',
                errors: {
                    name: ''
                },
                owner: window.Laravel.user,
                packages: 0,
                items: {}
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.get('api/getuserscollections').then(response => {
                    this.items = response.data;
                    if(this.items.length <= 0){
                        document.getElementById('no_works_user').style.display = 'block'
                    }
                })
                this.$axios.get('api/pakages').then(response => {
                    this.packages = response.data
                })
            });      
        }, methods: {
            craete_new(e){
                e.preventDefault();
                this.errors = {
                    name: ''
                }
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {           
                    this.$axios.post('api/create_collection', {
                        name: this.collection_name
                    }).then(response => {
                        this.$axios.get('api/getuserscollections').then(response => {
                            this.items = response.data;
                            document.getElementById('no_works_user').style.display = 'none'
                        })
                    }).catch((err) => {
                        if (err.response.data.errors.name) {
                            this.errors.name = err.response.data.errors.name[0];
                        }
                    });
                });                     
            },
            delete_collection(item_id){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/delete_collection', {
                        id: item_id
                    }).then(response => {
                        this.$axios.get('api/getuserscollections').then(response => {
                            this.items = response.data;
                            if(this.items.length <= 0){
                                document.getElementById('no_works_user').style.display = 'block'
                            }
                        })
                    })
                });                     
            }
        },
        beforeRouteEnter(to, from, next) {
            // Если пользователь не авторизован
            if(!window.Laravel.user ){
                return next("/");
            }
            next();
        }
    }

</script>