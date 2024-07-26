<template>
    <div id="header">
        <div class="nav-bar" v-if="isLogged">
            <div class="header_left">
                <a href="/"><img id="header_logo" :src="'/storage/imgs/logo.png'" alt=""></a>
                <a href="/catalog"><p>Каталог</p></a>
            </div>
            <div class="header_right">
                <a v-if="user.is_admin" href="/admin"><p class="header_p_first-tier">Панель</p></a>
                <a href="#" @click="openform"><p class="header_p_first-tier">Предложить материал</p></a>
                <a href="/pockets"><p class="header_p_first-tier">Приобрести пакеты</p></a>
                <a href="#" @click="logout"><p class="header_p_first-tier">Выйти</p></a>
                <span v-if="messages.length > 0"><img  @click="show_messages = !show_messages" id="header_bell" :src="'/storage/imgs/bell_icon.png'" alt=""></span>
                <img v-else @click="show_messages = !show_messages" id="header_bell" :src="'/storage/imgs/bell_icon.png'" alt="">
                <a id="header_pfp_a" :href="$router.resolve({name: 'Userpage', params: { id: user.id }}).href"><img id="header_pfp" :src="'/storage/profile_pics/'+user.path" alt=""></a>
            </div>
            
            <div id="messages" v-if="show_messages">
                <div class="message" v-for="message in messages">
                    <a class="message_pfp" v-if="message.user_path != 'none'" :href="$router.resolve({name: 'Userpage', params: { id: message.user_id }}).href"><img v-if="message.user_path != 'none'" :src="'/storage/profile_pics/'+message.user_path" alt=""></a>
                    <p>{{ message.text }}</p>
                    <a v-if="message.material_path != 'none'" :href="$router.resolve({name: 'Material', params: { id: message.material_id }}).href"><img class="work_img_msg" :src="'/storage/approved_materials/'+message.material_path" alt=""></a>
                    <img @click="message_delete(message.id)" class="del_btn" :src="'/storage/imgs/close_btn.png'" alt="">
                </div>
                <div id="mesg_empty" v-if="no_messages != ''">
                    <img :src="'/storage/imgs/mail_empty.svg'" alt="">
                    <p>{{ no_messages }}</p>
                </div>
            </div>
            
        </div>
        <div class="nav-bar" v-else>
            <div class="header_left">
                <a href="/"><img id="header_logo" :src="'/storage/imgs/logo.png'" alt=""></a>
                <a href="/catalog"><p>Каталог</p></a>
            </div>
            <div class="header_right">
                <a href="/login"><p class="header_p_first-tier">Войти</p></a>
                <a href="/registration"><p class="header_p_first-tier">Регистрация</p></a>
            </div>
        </div>

         
    </div>
    <form id="send_form" action="" v-if="form_header_is_on">
        <div id="send_form_left">
            <label id="label_form" class="custom-file-upload input-file">
                <input @change="show_name()" class="file_upload"  type="file" id="material" ref="material">
                <span id="file_selected">{{ file_name }}</span>
              </label>
            <p class="form_error">{{ errors.material }}</p>
            <input type="text" name="tags" placeholder="Введите теги через запятую" id="tags_header" v-model="tags">
            <p class="form_error">{{ errors.tags }}</p>
            <button id="send_btn" type="submit" @click="send_material">
                Отправить на прорверку
            </button>
            <button id="close_form_btn" @click="form_header_is_on = false">
                Закрыть
            </button>
            <p id="form_success">{{ success_message }}</p>
        </div>
        <p id="form_info">Ваш материал будет проверен на качество и соответствие правил нашего сайта. Проверка может занять до двух недель, по стечению этого срока или раньше, если ваша работа прошла, вы увидите её отображённой на своей личной странице</p>
    </form>
    <div v-if="form_header_is_on" id="black_screen">

    </div>
    <router-view></router-view>

    <footer>
        <div id="footer_wrap">

            <div id="footer_left">
                <img id="header_logo" :src="'/storage/imgs/logo.png'" alt="">
                <p>Копирайт 2024 Суворина</p>
            </div>
        <div id="footer_right">
                <p><a href="/license">Лицензия</a></p>
                <p><a href="/faq">Вопрос/Ответ</a></p>
        </div>
        </div>
        <div id="footer_bottom">
            <p>PhotoStoked_help@mail.ru</p>
            <p>7 (800) 555-55-55</p>
        </div>
    </footer>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Jura:wght@300..700&display=swap');

body{
    overflow-x: hidden;
}

*{
    margin: 0;
    padding: 0;
}

footer{
    width: 100%;
    background-color: #1D1A0F;
    height: 147px;
}
#footer_wrap{
    display: flex;
    justify-content: space-between;
}
    #footer_left, #footer_right{
        display: flex;
        column-gap: 30px;
        margin-right: 30px;
        font-size: 14px;
    }
        #footer_wrap p{
            height: 17px;
            font-weight: 400;
            color: #fff;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }
        footer a, footer a:visited{
            text-decoration: none;
            color: #fff;
        }
        #footer_bottom p{
            font-size: 16px;
            color: #e8e8e8;
            line-height: 25px;
            font-weight: 600;
            text-align: right;
            margin-right: 30px;
        }

.container{
    min-height: 110vh;
    padding-top: 120px;
    padding-left: 34px;
    padding-right: 34px;
    width: 1536px;
    margin: auto;
    position: relative;
  }

#header{
    width: 100vw;
    height: 77px;

    border-bottom: 2px solid #1D1A0F;
    box-shadow: 0px -8px 10px 12px rgba(32, 19, 13, 0.25);
    position: fixed;
    top: 0;
    overflow: visible;
    background-color: #fff;
    z-index: 100;
}
.nav-bar{
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: space-between;
}
    .header_left, .header_right{
        display: flex;
        column-gap: 30px;
        margin-right: 31px;
    }
    #header_logo{
        width: 80px;
        height: 100%;
    }
    .header_left p, .header_right .header_p_first-tier{
        height: 24px;
        font-size: 20px;
        font-weight: 600;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }
        #header a, #header a:visited{
            color: #1D1A0F;
            text-decoration: none;
            transition-duration: 0.3s;
        }
        #header a:hover{
            color: #E85F5F;
        }

    #header_bell{
        height: 40px;
        margin-top: 18px;
        cursor: pointer;
    }
    .header_right span:after{
        content: '';
        border: 1px solid #E85F5F;
        border-radius: 20px;
        width: 10px;
        height: 10px;
        background-color: #E85F5F;
        color: #E85F5F;
        bottom: 15px;
        right: 120px;
        position: absolute;
    }
    #header_pfp_a{
        overflow: hidden;
        width: 55px;
        height: 55px;
        border-radius: 80px;
        display: block;
        margin-top: 9px;
    }
        #header_pfp_a img{
            max-width: 100%;
            min-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    #messages{
        position: absolute;
        top: 77px;
        right: 80px;
        width: 389px;
        max-height: 166px;
        border-width: 0px 1px 1px 1px;
        border-style: solid;
        border-color: #1D1A0F;
        background-color: #fff;
        box-shadow: 0px 6px 6px -1px rgba(7, 73, 57, 0.38);
        padding: 0 6px;
        overflow-y: auto;
    }
        #messeges::-webkit-scrollbar {
            width: 10px;
        }
        #messeges::-webkit-scrollbar-track {
            background: #e8e8e8; 
        }
        #messeges::-webkit-scrollbar-thumb {
            background: #1D1A0F; 
        }
    .message{
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 15px 30px;
        border-bottom: 1px solid #1D1A0F;
        position: relative;
    }
    .message_pfp{
        display: block;
        width: 56px;
        height: 56px;
        border-radius: 30px;
        overflow: hidden;
    }
        .message_pfp img{
            max-width: 100%;
            min-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    .message p{
        font-size: 14px;
        font-weight: 400;
        text-align: center;
        max-width: 202px;
    }
    .work_img_msg{
        max-height: 55px;
        max-width: 55px;
    }

    .del_btn{
        width: 14px;
        height: 14px;
        position: absolute;
        top: 9px;
        right: 16px;
        cursor: pointer;
        display: none;
    }
    .message:hover .del_btn{
        display: block;
    }
    #mesg_empty{
        width: 150px;
        margin: 40px auto;
        text-align: center;
    }
    #mesg_empty img{
        width: 50px;
        margin-bottom: 10px;
    }



    #send_form{
        width: 695px;
        height: 320px;
        background-color: #fff;
        border: 2px solid #1D1A0F;
        position: fixed;
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
        z-index: 101;
        display: flex;
        column-gap: 24px;
        padding-top: 61px;
        padding-left: 64px;
        box-sizing: border-box;
    }
    #send_form_left{
        width: 297px;
    }
    #label_form{
        width: 297px;
        height: 31px;
        border: 1px solid #1D1A0F;
        font-size: 14px;
        font-weight: 600;
        display: block;
        text-align: center;
        padding-top: 5px;
        cursor: pointer;
        transition-duration: 0.5s;
        margin-bottom: 22px;
        font-family: 'inter';
        box-sizing: border-box;
        overflow: hidden;
    }
        #label_form:hover{
            background-color: #e8e8e8;
        }
        #tags_header{
            width: 297px;
            height: 31px;
            margin-bottom: 40px;
            padding-left: 7px;
        }

        #send_btn, #close_form_btn{
            width: 297px;
            height: 31px;
            border: 1px solid black;
            cursor: pointer;
            transition-duration: 0.5s;
        }
        #send_btn{
            color: #fff;
            background-color: #1D1A0F;
            margin-bottom: 12px;
        }
        #send_btn:hover{
            color: #1D1A0F;
            background-color: #e8e8e8;
        }
        #close_form_btn{
            color: #1D1A0F;
            background-color: #fff;
        }
        #close_form_btn:hover{
            color: #1D1A0F;
            background-color: #e8e8e8;
        }
        .form_error{
            font-size: 10px;
            color: #E85F5F;
            line-height: 0px;
            height: 20px;
            margin-top: -15px;
        }
        #form_info{
            width: 267px;
            font-size: 14px;
            text-align: center;
            line-height: 22px;
            margin-top: 15px;
        }
        #form_success{
            text-align: center;
            font-size: 14px;
            margin-top: 5px;
        }

    #black_screen{
        width: 100vw;
        height: 100vh;
        background-color: #1D1A0F;
        opacity: 0.7;
        position: fixed;
        z-index: 100;
    }

</style>

<script>
 export default{
        name: 'App',
        data(){
            return{
                isLogged: false,
                form_header_is_on: false,
                tags: null,
                user: window.Laravel.user,
                errors: {
                    tags: null,
                    material: null
                },
                success_message: null,
                messages: null,
                show_messages: false,
                messages_exists: false,
                no_messages: '',
                file_name: 'Загрузите фото',
            }
        },
        created(){
            if(window.Laravel.isLogged){
                this.isLogged = true
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                    this.$axios.get('api/messages').then(response => {
                        this.messages = response.data.data
                        if(this.messages.length > 0){
                            this.messages_exists = true
                        } else{
                            this.no_messages = 'Уведомлений нет'
                        }
                    })
                });                                      
            }
        }, methods: {
            openform(e){
                e.preventDefault()
                this.form_header_is_on = true
            },
            // Показать имя загруженного файла
            show_name(){
                this.file_name = this.$refs.material.files[0].name
            },
            message_delete(id){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                    this.$axios.post('api/deletemessage', {
                        id: id
                    }).then(response => {
                        this.$axios.get('api/messages').then(response => {
                            this.messages = response.data.data
                            if(this.messages.length == 0){
                                this.messages_exists = false
                                this.no_messages = 'Уведомлений нет'
                            }
                        })
                    }).catch(error => {

                    })
                });                                                      
            },
            logout (e) {
                e.preventDefault();
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/logout').then(response => {
                        window.location.href = '/';
                    }).catch(error => {
                        
                    })
                });  
            },
            send_material(e){
                e.preventDefault();
                this.errors = {
                    tags: null,
                    material: null
                }
                this.success_message = ''
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios
                    .request({
                        url: "http://127.0.0.1:8000/api/sendingmaterial",
                        method: "POST",
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        data: {
                            tags: this.tags,
                            material: this.$refs.material.files[0],
                        }
                    }).then(response => {
                            this.success_message = response.data.message;
                            this.tags = ''
                    }).catch(err => {
                        if (err.response.data.errors.tags) {
                            this.errors.tags = err.response.data.errors.tags[0];
                        }
                        if (err.response.data.errors.material) {
                            this.errors.material = err.response.data.errors.material[0];
                        }
                    })
                });                  
            }

        },
    }
</script>