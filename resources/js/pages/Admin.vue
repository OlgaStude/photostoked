<template>
    <div class="container">
        <div id="admin_nav">
            <div id="admin_nav_wrapper">
                <a href="/admin">Работы</a>
                <a href="/userdelete">Пользователи</a>
                <a href="/pricemodering">Цены</a>
            </div>
        </div>
        <div class="material_container_admin" v-for="i in index">
            <div class="mat_div">
                <div class="admin_img_cont">
                    <img :src="'/storage/sent_materials/' + materials[i-1].material_path" alt="">
                </div>
                <div class="img_options">
                    <div class="admin_user">
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials[i-1].user_id }}).href"><img :src="'/storage/profile_pics/' + materials[i-1].user_path" alt=""></a>
                        <a :href="$router.resolve({name: 'Userpage', params: { id: materials[i-1].user_id }}).href"><p>{{ materials[i-1].nikname }}</p></a>
                    </div>
                    <button class="down_admin" @click="download(materials[i-1].material_path)">Скачать</button>
                    <button class="del_admin" @click="delete_function(materials[i-1].material_id)">Удалить</button>
                </div>
            </div>
            <div class="desc">
                <label id="label_form_admin" class="custom-file-upload input-file">
                    <input @change="show_name(i)" class="file_upload"  type="file" id="watermarked_version" ref="watermarked_version">
                    <span id="file_selected">{{ file_name[i-1] }}</span>
                </label>
                <p class="admin_error">{{ errors.material[i-1] }}</p>
                <select name="" id="type_select" @change="onChangeType($event, i)">
                    <option value="illustration">Иллюстрация</option>
                    <option value="photo">Фото</option>
                </select>
                <select name="" id="format_select" @change="onChangeFormat($event, i)">
                    <option value="vertical">Вертикальная</option>
                    <option value="horrizontal">Горризонтальная</option>
                    <option value="squere">Квадрат</option>
                </select>
                <input type="text" :value="materials[i-1].tags" ref="tags">
                <p class="admin_error">{{ errors.tags[i-1] }}</p>
                
                <button @click="approve(materials[i-1].material_id, materials[i-1].user_id, i)">Принять и выставить</button>
            </div>
        </div>

    </div>
</template>

<style>

#admin_nav, #empty_nav{
    width: 101vw;
    margin-left: calc(-1 * ((100vw - 1536px + 70px) / 2));
    background-color: #1D1A0F;
    position: relative;
    font-size: 14px;
    padding-top: 7px;
    margin-top: -44px;
}
#admin_nav{
    height: 35px;
}
#admin_nav_wrapper{
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}
#admin_nav_wrapper a, #admin_nav_wrapper a:visited{
    color: white;
    text-decoration: none;
    display: inline-block;
    padding: 0px 17px;
    transition-duration: 0.5s;
}
#admin_nav_wrapper a:not(:last-child){
    border-right: 1px solid white;
}
#admin_nav_wrapper a:hover{
    color: #bebebe;
}
    .material_container_admin{
        display: flex;
        justify-content: space-between;
        padding: 30px;
        border-bottom: 2px solid #1D1A0F;
    }
        .mat_div{
            display: flex;
            column-gap: 30px;
        }
        .admin_img_cont{
            width: 364px;
            height: 330px;
        }
        .admin_img_cont img{
            max-width: 100%;
            max-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    .img_options{
        width: 91px;
    }
    .img_options a:first-child{
        display: block;
        width: 75px;
        height: 75px;
        border-radius: 50px;
        overflow: hidden;
        margin-bottom: 17px;
    }
        .img_options a img{
            max-width: 100%;
            min-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    .img_options p{
        font-size: 18px;
    }
    .img_options a, .img_options a:visited{
        color: #1D1A0F;
        text-decoration: none;
    }
    .admin_user a{
        display: block;
        width: 100px;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }
    .admin_user:hover{
        text-decoration: underline;
    }
    .admin_user p{
        width: 100%;
        text-align: center;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .img_options button{
        height: 31px;
        width: 91px;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        margin-top: 24px;
        border-radius: 0;
        cursor: pointer;
        transition-duration: 0.5s;
    }
        .down_admin{
            background-color: #1D1A0F;
            border: 1px solid #1D1A0F
        }
        .down_admin:hover{
            background-color: #e8e8e8;
            color: #1D1A0F;
        }
        .del_admin{
            background-color: #E85F5F;
            border: 0;
        }

        .desc{
            width: 297px;
            text-align: right;
        }
        #label_form_admin{
            width: 123px;
            height: 31px;
            border: 1px solid #1D1A0F;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            padding-top: 5px;
            cursor: pointer;
            transition-duration: 0.5s;
            margin-bottom: 22px;
            font-family: 'inter';
            box-sizing: border-box;
            overflow: hidden;
        }
            #label_form_admin:hover{
                background-color: #e8e8e8;
            }
        .desc select{
            width: 193px;
            height: 31px;
            padding-left: 7px;
            font-size: 14px;
            background-color: #fff;
            border: 1px solid #1D1A0F;
            margin-bottom: 12px;
        }
        .desc input{
            width: 100%;
            height: 31px;
            padding-left: 7px;
            margin-bottom: 41px;
        }
        .admin_error{
            font-size: 10px;
            color: #E85F5F;
            line-height: 0px;
            height: 20px;
            margin-top: -15px;
        }

        .desc button{
            width: 208px;
            height: 31px;
            background-color: #1D1A0F;
            transition-duration: 0.5s;
            cursor: pointer;
            color: #fff;
            border: 1px solid #1D1A0F;
        }
        .desc button:hover{
            color: #1D1A0F;
            background-color: #e8e8e8;
        }

    


</style>


<script>

    export default{
        name: 'Admin',
        data(){
            return{
                materials: null,
                type: [],
                format: [],
                index: null,
                errors: {
                    tags: [],
                    material: []
                },
                file_name: []
            }
        }, created(){
              this.$axios.get("/sanctum/csrf-cookie").then((response) => {                          
                this.$axios.get('api/getinadmin').then(response => {
                    this.materials = response.data.data;
                    this.index = this.materials.length
                    for(let i =0; i < this.materials.length; i++){
                        this.type[i] = 'illustration'
                        this.format[i] = 'vertical'
                        this.errors.tags[i] = '';
                        this.errors.material[i] = '';
                        this.file_name[i] = 'Материал'
                    }
                })
            });                  
        }, methods: {
            // Отображение имени загруженного файла 
            show_name(i){
                this.file_name[i-1] = this.$refs.watermarked_version[i-1].files[0].name
            },
            download(path){
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                    this.$axios.get('storage/sent_materials/' + path).then(response => {
                        let fileUrl = 'storage/sent_materials/' + path;
                        let fileLink = document.createElement('a');
                        fileLink.href = fileUrl;

                        fileLink.setAttribute('download', path);
                        document.body.appendChild(fileLink)

                        fileLink.click();
                    })
                });                 
            },
            delete_function(id) {
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                    this.$axios.post('api/delete', {
                        id: id
                    }).then((response) => {
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                            this.$axios.get('api/getinadmin').then(response => {
                                this.materials = response.data.data;
                                this.index = this.materials.length
                                document.querySelector('#type_select').value = 'illustration'
                                document.querySelector('#format_select').value = 'vertical'
                                for(let i =0; i < this.materials.length; i++){
                                    this.type[i] = 'illustration'
                                    this.format[i] = 'vertical'
                                    this.errors.tags[i] = '';
                                    this.errors.material[i] = '';
                                    this.file_name[i] = 'Материал'
                                }
                            })
                        });                     
                    })
                });                     
            },
            onChangeType(e, id){
                this.type[id -1] = e.target.value
            },
            onChangeFormat(e, id){
                this.format[id-1] = e.target.value
            },
            approve(material_id, user_id, index){
                
                this.errors = {
                    tags: [],
                    material: []
                }
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {                             
                    this.$axios.post("api/approve",
                    {
                        users_id: user_id,
                        material: this.$refs['watermarked_version'][index-1].files[0],
                        material_id: material_id,
                        format: this.format[index-1],
                        type: this.type[index -1],
                        tags: this.$refs['tags'][index-1].value
                    },
                    {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    }
                    )
                    .then((response) => {
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                            this.$axios.get('api/getinadmin').then(response => {
                                this.materials = response.data.data;
                                this.index = this.materials.length
                                document.querySelector('#type_select').value = 'illustration'
                                document.querySelector('#format_select').value = 'vertical'
                                for(let i =0; i < this.materials.length; i++){
                                    this.type[i] = 'illustration'
                                    this.format[i] = 'vertical'
                                    this.errors.tags[i] = '';
                                    this.errors.material[i] = '';
                                    this.$refs['tags'][index-1].value = null
                                    this.file_name[i] = 'Материал'

                                }
                            })
                        });      
                    })
                    .catch((err) => {
                        if (err.response.data.errors.tags) {
                            this.errors.tags[index-1] = err.response.data.errors.tags[0];
                        }
                        if (err.response.data.errors.material) {
                            this.errors.material[index-1] = err.response.data.errors.material[0];
                        }
                        
                    });
                });                     
            },
            incrimentIndex(){
                this.index++
            }
        },
        beforeRouteEnter(to, from, next) {

            // Если пользователь не авторизован
            if(!window.Laravel.user ){
                return next("/");
            }

            // Если пользователь не администратор
            if(window.Laravel.user.is_admin != 1){
                return next("/");
            }
            next();
        }
    }

</script>