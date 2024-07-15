<template>
    <div class="container">
        <div id="admin_nav">
            <div id="admin_nav_wrapper">
                <a href="/admin">Работы</a>
                <a href="/userdelete">Пользователи</a>
                <a href="/pricemodering">Цены</a>
            </div>
        </div>
        <div id="admin_container">

            <div id="admin_del_wrapper">
                <input type="text" id="user_search" @keypress="make_search" v-model="user_name" placeholder="Найти пользователя">
                <button id="find_user_btn" @click="find_user()">Сбросить</button>
                <div class="admin_del" v-for="person in people">
                    <div class="person_admin">
                        <a :href="$router.resolve({name: 'Userpage', params: { id: person.id }}).href"><img :src="'/storage/profile_pics/'+person.path" alt=""></a>
                        <a :href="$router.resolve({name: 'Userpage', params: { id: person.id }}).href"><p>{{ person.nikname }}</p></a>
                    </div>
                    <div class="buttons_user_del">
                        <span class="warning_succes"></span>
                        <button class="user_delete_full" @click="delete_person(person.id)">Удалить</button>
                        <button class="user_delete_" @click="switch_btn($event)">Удалить</button>
                        <button class="user_warning" @click="send_message(person.id, $event)">Предупреждение</button>
                    </div>
                </div>
                <div id="no_works_user">
                    <img :src="'/storage/imgs/no_users.png'" alt="">
                    <p>Никто не найден...</p>
                </div>
            </div>
            <div id="reports">
                <div class="report_wrap" v-for="report in reported">
                    <div class="person_report">
                        <a :href="$router.resolve({name: 'Userpage', params: { id: report.reported_id }}).href"><img :src="'/storage/profile_pics/'+report.path" alt=""></a>
                        <button @click="delete_report(report.report_id)" class="report_del_btn">Удалить жалобу</button>
                    </div>
                    <p>{{ report.reported_nik }}</p>
                    <p>от <a :href="$router.resolve({name: 'Userpage', params: { id: report.reporter_id }}).href">{{ report.reporter_nik }}</a></p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

#reports{
    margin-top: 80px;
}
.report_wrap{
    box-sizing: border-box;
    width: 400px;
    margin-bottom: 20px;
}
.report_wrap p a, .report_wrap p a:visited{
    
    color: #1D1A0F;
}
.report_wrap button{
    background-color: #E85F5F;
    border: 0;
    cursor: pointer;
    color: #fff;
    width: 158px;
    height: 31px;
}
.person_report{
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.report_wrap p{
    width: 100%;
    line-break: anywhere;
    margin-top: 10px;
}

#admin_container{
    display: flex;
    column-gap: 100px;
}

.warning_succes{
    font-size: 14px;
    line-height: 31px;
}

.user_delete_full{
    display: none;
}

#admin_del_wrapper{
    width: 950px;
    position: relative;
    right: 0;
    column-gap: 88px;
    padding-bottom: 40px;
    margin-top: 30px;
}
.admin_del{
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    margin-top: 30px;
}
    .person_admin{
        display: flex;
        align-items: center;
        column-gap: 18px;
    }
.admin_del a, .admin_del a:visited, .person_report a, .person_report a:visited{
    color: #1D1A0F;
    text-decoration: none;
}
.admin_del:hover a, .person_report a:hover{
    text-decoration: underline;
}
    #admin_del_wrapper a:first-child, .person_report a:first-child{
        overflow: hidden;
        width: 80px;
        height: 80px;
        border-radius: 80px;
        display: block;
        margin-top: 9px;
    }
        #admin_del_wrapper a img, .person_report a img{
            max-width: 100%;
            min-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        #admin_del_wrapper a p{
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .buttons_user_del{
            display: flex;
            column-gap: 20px;
        }

        .user_warning{
            width: 208px;
            height: 31px;
            background-color: #1D1A0F;
            transition-duration: 0.5s;
            cursor: pointer;
            color: #fff;
            border: 1px solid #1D1A0F;
        }
        .user_delete_, .user_delete_full{
            background-color: #E85F5F;
            border: 0;
            cursor: pointer;
            color: #fff;
            width: 108px;
            height: 31px;
        }

        #find_user_btn{
            width: 108px;
            height: 31px;
            background-color: #1D1A0F;
            transition-duration: 0.5s;
            cursor: pointer;
            color: #fff;
            border: 1px solid #1D1A0F;   
        }
        #user_search{
            padding-left: 7px;
            height: 31px;
            width: 400px;
            margin-right: 20px;
        }

</style>

<script>

    export default{
        name: 'Home',
        data(){
            return{
                people: [],
                real_btn: false,
                user_name: '',
                reported: null
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/allpeople').then(response => {
                this.people = response.data;
            })
            });      
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/reportedusers').then(response => {
                this.reported = response.data.data;
            })
                      });          
        }, methods: {
            delete_report(id){
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/deletereport', {
                    id: id
                 }).then(response => {
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.get('api/reportedusers').then(response => {
                    this.reported = response.data.data;
                })
                     });               
                }).catch(error => {

                })
                       });             
            },
            send_message(id, e){
                let spans = document.querySelectorAll('.warning_succes')
                spans.forEach(element => {
                    element.innerHTML = ''
                });
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/warning', {
                    id: id
                 }).then(response => {
                    e.target.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML = 'Предупреждение отправлено!'
                }).catch(error => {

                })
                     });               
            },
            switch_btn(e){
                let spans = document.querySelectorAll('.warning_succes')
                spans.forEach(element => {
                    element.innerHTML = ''
                });
                e.target.style.display = 'none'
                e.target.previousElementSibling.style.display = 'block'
                e.target.previousElementSibling.previousElementSibling.innerHTML = "Повторно нажмите для подтверждения"
            },
            delete_person(id){
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/deleteperson', {
                    id: id
                 }).then(response => {
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.get('api/allpeople').then(response => {
                        this.people = response.data;
                        console.log(this.people)
                    })
                     });                   
                }).catch(error => {

                })
                    });
            },
            find_user(){
                this.user_name = ''
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.post('api/usersearch', {
                        search_word: ''
                    }).then(response => {
                        this.people = response.data;
                        document.getElementById('no_works_user').style.display = 'none'
                        
                    }).catch(error => {

                    })
                    });
            },
            make_search(e){
                if(e.keyCode == 13){
                    document.getElementById('no_works_user').style.display = 'none'
                        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post('api/usersearch', {
                        search_word: this.user_name
                    }).then(response => {
                        this.people = response.data;
                        if(this.people.length <= 0){
                            document.getElementById('no_works_user').style.display = 'block'
                        }
                    }).catch(error => {
                    })
                    });
                }

            }
        },
        beforeRouteEnter(to, from, next) {
            if(!window.Laravel.user ){
                return next("/");
            }
            if(window.Laravel.user.is_admin != 1){
                return next("/");
            }
            next();
        }
    }

</script>