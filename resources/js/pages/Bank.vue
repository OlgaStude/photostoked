<template>
    <div class="container">
        <p id="buying_tittle" v-if="size != ''">Купить пакет на {{ size }} руб.</p>
        <p id="buying_tittle" v-else>Снять деньги со счёта</p>

        <div id="buying_wrapper">
            <select name="" id="buying_select">
                <option value="">Сбербанк</option>
                <option value="">ГазПром Банк</option>
                <option value="">Российский Банк</option>
            </select>
            <div id="buying_second_row">
                <input class="no_error" id="card" type="tel" placeholder="XXXX XXXX XXXX XXXX" @keypress="space" maxlength="19" v-model="card">
                <input class="no_error" id="month" type="text" maxlength="2" placeholder="MM" v-model="month">
                <span id="slash">/</span>
                <input class="no_error" id="year" type="text" maxlength="2" placeholder="YY" v-model="year">
                <p v-if="errors.card != null" class="buy_error_t">{{ errors.card }}</p>
                <p v-if="errors.month != null" class="buy_error_t b_err_right_t">{{ errors.month }}</p>
                <p v-if="errors.year != null" class="buy_error_t b_err_right_t">{{ errors.year }}</p>
            </div>
            <div id="buying_last_row">
                <input class="no_error" id="name" @input="upper_case()" type="text" placeholder="Имя на карте" v-model="name">
                
                <div id="text">
                    <span>Три цифры на обороте</span>
                </div>
                <input class="no_error" id="cvc" type="text" maxlength="3" placeholder="CVC" v-model="cvc">
                
            </div>
            <p class="buy_error">{{ errors.name }}</p>
            <p class="buy_error b_err_right">{{ errors.cvc }}</p>
            <button type="submit" @click="buy">отправить</button>
        </div>
        <p id="buy_success">{{ success_msg }}</p>
    </div>
</template>

<style>

.error_input{
    border: 1px solid rgb(232, 82, 82);
}
.no_error{
    border: 1px solid #1D1A0F;
}

#buying_tittle{
    text-align: center;
    margin-top: 76px;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 45px;
}
    #buying_wrapper{
        width: 460px;
        height: 266px;
        background-color: #fff;
        border: 1px solid #1D1A0F;
        box-shadow: 0px 8px 10px -2px rgba(7, 62, 49, 0.25);
        margin: auto;
    }
    #buying_select{
        width: 404px;
        height: 31px;
        margin-top: 31px;
        margin-left: 26px;
        font-size: 12px;
        font-weight: 500;
        padding-left: 10px;
        margin-bottom: 22px;
        border: 1px solid #1D1A0F;
        background-color: #fff;
        cursor: pointer;
    }
    #buying_second_row, #buying_last_row{
        width: 414px;
        margin-left: 26px;
        height: 31px;
        margin-bottom: 22px;
        overflow: visible;
        position: relative;
    }
        #buying_second_row input, #buying_last_row input{
            height: 31px;
            font-size: 12px;
            font-weight: 500;
            padding-left: 10px;
        }
        #card, #name{
            width: 269px;
            margin-right: 21px;
        }
        #month, #year{
            width: 41px;
            padding-left: 7px;
        }
        #month{
            margin-right: 15px;
        }
        #buying_second_row span{
            margin-right: 10px;
        }
        #buying_last_row{
            display: flex;
        }
            #name{
                margin-right: 3px;
            }
            #text{
                font-size: 10px;
                width: 71px;
                font-weight: 500;
                text-align: right;
                padding-top: 5px;
            }
            #cvc{
                width: 49px;
                margin-left: 10px;
            }

            .buy_error, .buy_error_t{
                font-size: 10px;
                color: #E85F5F;
                line-height: 10px;
                height: 25px;
            }
            .buy_error{
                margin-top: -20px;
                margin-left: 27px;
            }
            .buy_error_t{
                margin-top: 5px;
                margin-left: 7px;
            }
            .b_err_right_t{
                margin-left: 240px;
            }
            .b_err_right{
                margin-left: 268px;
            }

            #buying_wrapper button{
                width: 169px;
                height: 31px;
                margin-left: 145px;
                margin-top: 10px;
                background-color: #1D1A0F;
                cursor: pointer;
                transition-duration: 0.5s;
                color: #fff;
                font-size: 18px;
                font-weight: 500;
            }
            #buying_wrapper button:hover{
                border: 1px solid #1D1A0F;
                background-color: #e8e8e8;
                color: #1D1A0F;
            }

            #buy_success{
                text-align: center;
                margin-top: 20px;
            }
        
</style>

<script>

    export default{
        name: 'Home',
        data(){
            return{
                size: '',
                card: '',
                month: '',
                year: '',
                name: '',
                cvc: '',
                errors: {
                    card: null,
                    month: null,
                    year: null,
                    name: null,
                    cvc: null,
                },
                success_msg: '',
                old_length: 0,
                prices: null
            }
        }, created(){
            // Проверка, если пользователь желает снять деньги
            // или купить пакеты 
            if(localStorage.getItem('pocket_price') != 'take'){
                this.size = localStorage.getItem('pocket_price')
            }
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                this.$axios.get('api/prices').then(response => {
                    this.prices = response.data;
                })
            });                 
        }, methods: {
            upper_case(){
                this.name = this.name.toUpperCase()
            },
            // Добовляет пробелы на поле с номером карты
            space(){
                let new_str = this.card.replaceAll(' ','').split('')

                for(let i = 0; i<new_str.length - 1; i++){
                    if((i+1) % 4 == 0){
                        new_str[i] += ' '
                    }
                }
                this.card = new_str.join('')

            },
            buy(){
                this.errors = {
                    card: null,
                    month: null,
                    year: null,
                    name: null,
                    cvc: null,
                }
                document.getElementById('card').classList.remove('error_input')
                document.getElementById('month').classList.remove('error_input')
                document.getElementById('year').classList.remove('error_input')
                document.getElementById('name').classList.remove('error_input')
                document.getElementById('cvc').classList.remove('error_input')

                let ammount = 0
                switch(localStorage.getItem('pocket_price')){
                    case(this.prices[2].price.toString()):
                        ammount = 10
                        break;
                    case(this.prices[1].price.toString()):
                        ammount = 40
                        break;
                    case(this.prices[0].price.toString()):
                        ammount = 120 
                        break;
                    case('take'):
                        ammount = 'take' 
                        break;
                }
                // Проверка на правильность введёного имени владельца
                if(!/\b[a-zA-Z]+\s[a-zA-Z]+\b/.test(this.name)){
                    this.errors.name = 'Некорректно заполнено поле';
                    document.getElementById('name').className = 'error_input';
                    return;
                }
                this.$axios.get("/sanctum/csrf-cookie").then((response) => {               
                    this.$axios.post('api/buypakege', {
                        card: this.card.replace(' ',''),
                        month: this.month,
                        year: this.year,
                        name: this.name,
                        cvc: this.cvc,
                        ammount: ammount
                    }).then(response => {
                        localStorage.removeItem('pocket_price')
                        this.success_msg = 'Опперация прошла успешно!'
                        setTimeout(() => window.location.href = "/user/" + window.Laravel.user.id, 2000);

                    }).catch((err) => {
                        if (err.response.data.errors.card) {
                            document.getElementById('card').className = 'error_input';
                        }
                        if (err.response.data.errors.month) {
                            document.getElementById('month').className = 'error_input';
                        }
                        if (err.response.data.errors.year) {
                            document.getElementById('year').className = 'error_input';
                        }
                        if (err.response.data.errors.name) {
                            document.getElementById('name').className = 'error_input';
                        }
                        if (err.response.data.errors.cvc) {
                            document.getElementById('cvc').className = 'error_input';
                        }

                        if (err.response.data.errors.card) {
                            this.errors.card = err.response.data.errors.card[0];
                        }
                        else if (err.response.data.errors.month) {
                            this.errors.month = err.response.data.errors.month[0];
                        }
                        else if (err.response.data.errors.year) {
                            this.errors.year = err.response.data.errors.year[0];
                        }
                        if (err.response.data.errors.name) {
                            this.errors.name = err.response.data.errors.name[0];
                        }
                        else if (err.response.data.errors.cvc) {
                            this.errors.cvc = err.response.data.errors.cvc[0];
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

            if(localStorage.getItem('pocket_price') == null || localStorage.getItem('pocket_price') == undefined){
                return next("/");
            }
            next();
        }
    }

</script>