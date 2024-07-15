<template>
    <div class="container">
        <div id="form_container_buying">
            <div class="payment small_p">
                <div class="packege">
                    <h1>Малый пакет ({{ prices[2].price }} Руб.)</h1>
                    <p>Вы приобретаете возможность скачать 10 работ</p>
                    <div class="cards">
                        <div class="center_card"></div>
                    </div>
                    <button @click="choose_pay('small')" class="small_p_b">Приобрести</button>
                </div>
            </div>
            <div class="payment mid_p">
                <div class="packege">
                    <h1>Средний пакет ({{ prices[1].price }} Руб.)</h1>
                    <p>Вы приобретаете возможность скачать 40 работ</p>
                    <div class="cards">
                        <div class="first_card"></div>
                        <div class="center_card"></div>
                        <div class="last_card"></div>
                    </div>
                    <button @click="choose_pay('mid')" class="mid_p_b">Приобрести</button>
                </div>
            </div>
            <div class="payment big_p">
                <div class="packege">
                    <h1>Большой пакет ({{ prices[0].price }} Руб.)</h1>
                    <p>Вы приобретаете возможность скачать 120 работ</p>
                    <div class="cards">
                        <div class="first_card"></div>
                        <div class="first_middle_card"></div>
                        <div class="center_card"></div>
                        <div class="second_middle_card"></div>
                        <div class="last_card"></div>
                    </div>
                    <button @click="choose_pay('big')" class="big_p_b">Приобрести</button>
                </div>     
            </div>
        </div>
        
    </div>
</template>

<style>

#form_container_buying{
    display: flex;
    justify-content: space-between;
    width: 1100px;
    padding-top: 50px;
    overflow: visible;
    margin: auto;
}

.payment{
    width: 297px;
    height: 401px;
    border: 1px solid #1D1A0F;
    box-shadow: 0px 10px 7px 3px rgba(30, 67, 56, 0.25);
    padding-top: 20px;
}
.packege h1{
    font-size: 24px;
    font-weight: 600;
    width: 185px;
    margin: auto;
    text-align: center;
}
.packege p{
    font-size: 14px;
    font-weight: 500;
    width: 208px;
    text-align: center;
    line-height: 20.72px;
    margin: auto;
    margin-top: 8px;
}
.cards{
    height: 144px;
    width: 208px;
    position: relative;
    margin: auto;
    margin-top: 14px;
}
    .cards div{
        position: absolute;
        height: 122px;
        width: 102px;
    }
    .center_card{
        left: 50%;
        transform: translateX(-50%);
        background-color: #B7B7B7;
        z-index: 10;
    }
    .first_card{
        bottom: 0;
        background-color: #D9D9D9;
    }
    .last_card{
        bottom: 0;
        right: 0;
        background-color: #8A8A8A;
    }
    .first_middle_card{
        top: 50%;
        transform: translateY(-50%);
        left: 20px;
        background-color: #CECDCD;
    }
    .second_middle_card{
        top: 50%;
        transform: translateY(-50%);
        right: 20px;
        background-color: #9D9D9D;
        z-index: 2;
    }
.packege button{
    margin-left: 45px;
    height: 31px;
    width: 208px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    margin-top: 22px;
    border: 0;
    background-color: #1D1A0F;
    cursor: pointer;
    transition-duration: 0.5s;
}
.packege button:hover{
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
                prices: null
            }
        }, created(){
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios.get('http://127.0.0.1:8000/api/prices').then(response => {
                this.prices = response.data;
                console.log(this.prices)
            })
            });                  
        }, methods: {
            choose_pay(size){
                switch(size){
                    case('small'):
                        localStorage.setItem('pocket_price', this.prices[2].price)
                        break;
                    case('mid'):
                        localStorage.setItem('pocket_price', this.prices[1].price)
                        break;
                    case('big'):
                        localStorage.setItem('pocket_price', this.prices[0].price) 
                        break;
                }

                window.location.href = "/bank"

            }
        },
        beforeRouteEnter(to, from, next) {
            if(!window.Laravel.user ){
                return next("/");
            }
            next();
        }
    }

</script>