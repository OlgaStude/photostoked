<template>
    <div class="container">
        <div id="admin_nav">
            <div id="admin_nav_wrapper">
                <a href="/admin">Работы</a>
                <a href="/userdelete">Пользователи</a>
                <a href="/pricemodering">Цены</a>
            </div>
        </div>
        <div id="current_prices" v-for="price in prices">
            <div class="price_mod_wrapper">
                <label :for="price.name">{{price.name}}</label>
                <input type="tel" :value="price.price" :name="price.name" :id="price.name">
                <p class="new_price_error"></p>
                <button class="price_change_btn" @click="price_change(price.id, $event)">Изменить</button>
                <p class="price_ch_suc_msg"></p>
            </div>
        </div>
    </div>
</template>


<style>

#current_prices{
    margin: auto;
    width: fit-content;
}
.price_mod_wrapper:first-child{
    margin-top: 60px;
}
    .price_mod_wrapper{
        display: flex;
        column-gap: 30px;
        margin-bottom: 40px;
        position: relative;
        align-items: center;
    }
    .price_mod_wrapper label{
        width: 70px;
    }
    .price_mod_wrapper input{
        padding-left: 7px;
        height: 31px;
    }

    .price_ch_suc_msg{
        position: absolute;
        right: -140px;
        top: 5px;
    }
    .new_price_error{
        position: absolute;
        color: #E85F5F;
        font-size: 12px;
        top: 40px;
        left: 100px;
    }

    .price_change_btn{
        width: 108px;
        height: 31px;
        background-color: #1D1A0F;
        transition-duration: 0.5s;
        cursor: pointer;
        color: #fff;
        border: 1px solid #1D1A0F;
    }

</style>


<script>

    export default{
        name: 'Home',
        data(){
            return{
                prices: null,
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios.get('api/prices').then(response => {
                    this.prices = response.data;
                })
            });              
        }, methods: {
            price_change(id, e){
                let mesgs = document.querySelectorAll('.price_ch_suc_msg')
                mesgs[0].innerHTML = ''
                mesgs[1].innerHTML = ''
                mesgs[2].innerHTML = ''
                mesgs = document.querySelectorAll('.new_price_error')
                mesgs[0].innerHTML = ''
                mesgs[1].innerHTML = ''
                mesgs[2].innerHTML = ''
                let new_val = e.target.previousElementSibling.previousElementSibling.value
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                    this.$axios.post("api/pricechange", {
                        id: id,
                        new_val: new_val
                    })
                    .then((response) => {
                        e.target.nextElementSibling.innerHTML = 'Цена изменена'
                    })
                    .catch((err) => {
                        if (err.response.data.errors.new_val) {
                            e.target.previousElementSibling.innerHTML = err.response.data.errors.new_val[0];
                        }
                    });
                });                     
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