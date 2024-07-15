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
                <a id="you_here_sub" href="/subscribtions">Избранные авторы</a>
                <a href="/collections">Коллекции</a>
                <a href="/bougth">Мои покупки</a>
            </div>
        </div>

        <div id="subs_wrapper">
            <div class="subs" v-for="sub in subs">
                <a :href="$router.resolve({name: 'Userpage', params: { id: sub.user_id }}).href"><img :src="'/storage/profile_pics/'+sub.path" alt=""></a>
                <a :href="$router.resolve({name: 'Userpage', params: { id: sub.user_id }}).href"><p>{{ sub.nikname }}</p></a>
            </div>
        </div>
        <div id="no_works_user">
            <img :src="'/storage/imgs/no_users.png'" alt="">
            <p>Тут пока никого нет...</p>
        </div>
    </div>

</template>

<style>



#you_here_sub:hover:after{
    content: '';
    border: 1px solid #bebebe;
    background-color: #bebebe;
}
#you_here_sub:after{
    content: '';
    border: 1px solid #fff;
    border-radius: 20px;
    width: 20px;
    height: 0.5px;
    background-color: #fff;
    position: absolute;
    left: 260px;
    bottom: -3px;
    transition-duration: 0.5s;
}

#subs_wrapper{
    width: 920px;
    margin-left: 380px;
    display: flex;
    flex-wrap: wrap;
    column-gap: 88px;
    margin-top: 40px;
}
.subs{
    display: flex;
    align-items: center;
    font-size: 18px;
    font-weight: 600;
    column-gap: 18px;
    margin-bottom: 20px;
}
.subs a, .subs a:visited{
    color: #1D1A0F;
    text-decoration: none;
}
.subs:hover a{
    text-decoration: underline;
}
    #subs_wrapper a:first-child{
        overflow: hidden;
        width: 80px;
        height: 80px;
        border-radius: 80px;
        display: block;
        margin-top: 9px;
    }
        #subs_wrapper a img{
            max-width: 100%;
            min-height: 100%;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        #subs_wrapper a p{
            max-width: 150px;
            text-overflow: ellipsis;
            overflow: hidden;
        }

</style>

<script>

    export default{
        name: 'Home',
        data(){
            return{
                owner: window.Laravel.user,
                packages: 0,
                subs: {}
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/subs').then(response => {
                this.subs = response.data.data;
                if(this.subs.length <= 0){
                    document.getElementById('no_works_user').style.display = 'block'
                }
            })
            });   
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('api/pakages').then(response => {
                    this.packages = response.data
                })
             });                     
        }, methods: {
            
        },
        beforeRouteEnter(to, from, next) {
            if(!window.Laravel.user ){
                return next("/");
            }
            next();
        }
    }

</script>