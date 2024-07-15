<template>
    <div class="container">
      <div id="login_div">
        <img :src="'/storage/imgs/image_4.png'" alt="">
        <form action="" enctype="multipart/form-data">
          <h1 id="login_h1">Вход</h1>
          <input type="email" id="email" v-model="email" placeholder="Почта" />
          <p class="error">{{ errors.email }}</p>
          <input type="password" id="password" v-model="password" placeholder="Пароль" />
          <p class="error">{{ errors.password }}</p>
          <p class="error">{{ errors.unmatched }}</p>
          <button type="submit" @click="login">Войти в аккаунт</button>
          <p id="login_login">Ещё не с нами? Вы можете <a href="/registration">Зарегистрироваться</a></p>
        </form>
      </div>
      
    </div>
  </template>

  <style>

  #login_div{
    width: 735px;
    height: 365px;
    box-shadow: 0px 8px 10px -2px rgba(33, 21, 16, 0.25);
    display: flex;
    margin: auto;
    background-color: #fff;
  }
    #login_div form{
      border-width: 1px 1px 1px 0px;
      border-style: solid;
      border-color: #1D1A0F;
      width: 345px;
    }
    #login_h1{
      width: 100%;
      text-align: center;
      margin-top: 39px;
      margin-bottom: 38px;
      font-size: 24px;
      font-weight: 700;
    }
    #login_div input{
      width: 297px;
      height: 31px;
      padding: 8px;
      font-size: 12px;
      margin-left: 22px;
      margin-bottom: 38px;
    }
    #login_div .error{
      font-size: 10px;
      color: #E85F5F;
      line-height: 10px;
      height: 25px;
      margin-top: -35px;
      margin-left: 27px;
    }
    #login_div button{
      width: 297px;
      height: 32px;
      color: white;
      font-size: 18px;
      font-weight: 600;
      border: 0;
      background-color: #1D1A0F;
      margin-left: 19px;
      margin-bottom: 21px;
      margin-top: 30px;
      transition-duration: 0.5s;
      cursor: pointer;
      font-family: 'inter';
    }
      #login_div button:hover{
        background-color: #e8e8e8;
        color: #1D1A0F;
        border: 1px solid #1D1A0F;
      }
      #login_login{
        font-size: 12px;
        width: 100%;
        text-align: center;
      }
        #login_login a, #login_login a:visited{
          color: #1D1A0F;
        }
        #login_login a:hover{
          color: #E85F5F;
        }

  </style>
  
  
  <script>
  export default {
    name: "Home",
    data() {
      return {
        email: "",
        password: "",
        errors: {
          email: null,
          password: null,
          unmatched: null,
        },
      };
    },
    created() {},
    methods: {
      login(e) {
        e.preventDefault();
        this.errors = {
          email: null,
          password: null,
          unmatched: null,
        };
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
          this.$axios
            .post("api/login", {
              email: this.email,
              password: this.password,
            })
            .then((response) => {
              console.log(response.data);
              if (response.data.status == 422) {
                this.errors.password = response.data.message;
              } else if (response.data.status == 200) {
                if(response.data.is_admin == 1){
                  window.location.href = "/admin"
                }else{
                  window.location.href = "/user/" + response.data.user_id;
                }
              } else {
                this.error = response.data.message;
              }
            })
            .catch((err) => {
              console.log(err.response.data);
              if (err.response.data.errors.email) {
                this.errors.email = err.response.data.errors.email[0];
              }
              if (err.response.data.errors.password) {
                this.errors.password = err.response.data.errors.password[0];
              }
            });
        });
      },
    },
    beforeRouteEnter(to, from, next) {
      if (window.Laravel.isLogged) {
        return next("/user/" + window.Laravel.user.id);
      }
      next();
    },
  };
  </script>