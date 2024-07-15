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
                <a href="/collections">Коллекции</a>
                <a id="you_here_brought" href="/bougth">Мои покупки</a>
            </div>
        </div>

        <p id="disclaimer">Данная страница содержит все приобретённые вами материалы. Наличие метериала в данном списке является подтверждением вашего права на его использование</p>
        
        <div id="material_container">

            <div class="material_userpage" v-for="i in index">
                <div class="material_userwrapper">
                    <a  :href="$router.resolve({name: 'Material', params: { id: materials[i-1].material_id }}).href" class="material_blackgr">

                    </a>
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
                
                
                <p class="last_number">{{ last_id = materials[i-1].material_id }}</p>
            </div>
    </div>
    <div id="no_works_user">
        <img :src="'/storage/imgs/no_imgs.png'" alt="">
        <p>Тут пока ничего нет...</p>
    </div>

    </div>
</template>

<style>



#you_here_brought:hover:after{
    content: '';
    border: 1px solid #bebebe;
    background-color: #bebebe;
}
#you_here_brought:after{
    content: '';
    border: 1px solid #fff;
    border-radius: 20px;
    width: 20px;
    height: 0.5px;
    background-color: #fff;
    position: absolute;
    left: 537px;
    bottom: -3px;
    transition-duration: 0.5s;
}

#disclaimer{
    line-height: 30px;
    font-size: 16px;
    text-align: center;
    width: 1248px;
    margin: 26px auto;
}

</style>

<script>

    export default{
        name: 'Home',
        data(){
            return{
                materials: null,
                index: null,
                owner: window.Laravel.user,
                packages: 0,
            }
        }, created(){
             this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
            this.$axios.get('api/mybought').then(response => {
                this.materials = response.data.data;
                this.index = this.materials.length
                if(this.index <= 0){
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
        },
        beforeRouteEnter(to, from, next) {
            if(!window.Laravel.user ){
                return next("/");
            }
            next();
        }
    }

</script>