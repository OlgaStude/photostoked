<template>
    <div class="container">
      <div id="registration_form">
        <img :src="'/storage/imgs/image_3.png'" alt="">
        <form action="" enctype="multipart/form-data">
          <h1 id="form_title">Регистрация</h1>
          <input type="text" id="nikname" v-model="nikname" placeholder="Придумайте псевдоним" />
          <p class="error">{{ errors.nikname }}</p>
          <input type="email" id="email" v-model="email" placeholder="Укажите вашу электронную почту" />
          <p class="error">{{ errors.email }}</p>
          <input type="date" id="birthdate" v-model="birthdate" />
          <p class="error">{{ errors.birthdate }}</p>
          <input type="password" id="password" v-model="password" placeholder="Придумайте пароль" />
          <p class="error">{{ errors.password[0] }}</p>
          <input type="password" id="password_confirmation" v-model="password_confirmation" placeholder="Повторите пароль" />
          <p class="error">{{ errors.password[1] }}</p>
          <label id="img_label" class="custom-file-upload input-file">
            <input @change="show_name()" class="file_upload"  type="file" id="pfp" ref="pfp">
            <span id="file_selected">{{ file_name }}</span>
          </label>
          <p class="error">{{ errors.pfp }}</p>
          <input @click="check_perm = !check_perm" type="checkbox" id="permition" name="permition">
          <label id="permition_label" for="permition">Я даю согласие на обработку своих персональных данных</label>
          <button v-if="check_perm" id="register_btn" @click="register">Зарегистрироваться</button>
          <button v-else id="register_btn_blocked" @click="$event.preventDefault()">Зарегистрироваться</button>
          <p id="register_login">Уже с нами? <a href="/login">войдите</a> в свой аккаунт</p>
          
        </form>
      </div>
    </div>
  </template>
  

<style>



*{
  font-family: 'inter';
  box-sizing: border-box;

}

#permition_label{
  font-size: 12px;
  margin-left: 10px;
}


#registration_form{
  width: 725px;
  height: 522px;
  display: flex;
  margin: auto;
  box-shadow: 0px 8px 10px -2px rgba(33, 21, 16, 0.25);

}
  #registration_form form{
    border-width: 1px 1px 1px 0px;
    border-style: solid;
    border-color: #1D1A0F;
    width: 337px;
    padding-left: 19px;
  }
  #form_title{
    width: 100%;
    text-align: center;
    margin-top: 22px;
    margin-bottom: 24px;
    font-size: 24px;
    font-weight: 700;
    font-family: 'inter';
  }
  #registration_form input:not(#permition){
    width: 297px;
    height: 31px;
    font-size: 12px;
    
    margin-bottom: 19px;
    padding-left: 7px;
    border: 1px solid black;
  }
  #registration_form .error{
    font-size: 10px;
    color: #E85F5F;
    line-height: 10px;
    height: 10px;
    margin-top: -15px;
    margin-bottom: 5px;
    margin-left: 27px;
  }
  .file_upload{
    display: none;
  }
  #img_label{
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
      margin-top: 19px;
      margin-bottom: 20px;
      font-family: 'inter';
      box-sizing: border-box;
      overflow: hidden;
  }
      #img_label:hover{
          background-color: #e8e8e8;
      }
      #register_btn_blocked{
        width: 297px;
        height: 32px;
        color: white;
        font-size: 18px;
        font-weight: 600;
        border: 0;
        background-color: #454545;
        margin-top: 5px;

        cursor: not-allowed;
      }
      #register_btn{
        width: 297px;
        height: 32px;
        color: white;
        font-size: 18px;
        font-weight: 600;
        border: 0;
        background-color: #1D1A0F;
        margin-top: 5px;

        transition-duration: 0.5s;
        cursor: pointer;
      }
        #register_btn:hover{
            background-color: #e8e8e8;
            color: #1D1A0F;
            border: 1px solid #1D1A0F;
        }

        #register_login{
          font-size: 12px;
          width: 100%;
          text-align: center;
          margin-top: 10px;
        }
          #register_login a, #register_login a:visited{
              color: #1D1A0F;
          }
          #register_login a:hover{
              color: #E85F5F;
          }


</style>

  
  <script>
  export default {
    name: "Registration",
    data() {
      return {
        nikname: "",
        birthdate: "",
        email: "",
        password: "",
        password_confirmation: "",
        errors: {
          nikname: null,
          birthdate: null,
          email: null,
          password: [],
          password_confirmation: null,
          pfp: null,
        },
        file_name: 'Загрузите фото',
        check_perm: false
      };
    },
    created() {},
    methods: {
      // Показать имя загруженного файла
      show_name(){
        this.file_name = this.$refs.pfp.files[0].name
      },
      register(e) {
        e.preventDefault();
        this.errors = {
          nikname: null,
          birthdate: null,
          email: null,
          password: [],
          password_confirmation: null,
          pfp: null,
        };
        this.$axios.get("/sanctum/csrf-cookie").then((response) => {
          this.$axios.post("api/register",
            {
              nikname: this.nikname,
              birthdate: this.birthdate,
              email: this.email,
              password: this.password,
              password_confirmation: this.password_confirmation,
              pfp: this.$refs.pfp.files[0],
            },
            {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((response) => {
              if (response.data.status == 200) {
                window.location.href = "/user/" + response.data.user_id;
              } else {
                this.error = response.data.message;
              }
            })
            .catch((err) => {
              if (err.response.data.errors.email) {
                this.errors.email = err.response.data.errors.email[0];
              }
              if (err.response.data.errors.password) {
                this.errors.password = err.response.data.errors.password;
              }
              if (err.response.data.errors.nikname) {
                this.errors.nikname = err.response.data.errors.nikname[0];
              }
              if (err.response.data.errors.birthdate) {
                this.errors.birthdate = err.response.data.errors.birthdate[0];
              }
              if (err.response.data.errors.pfp) {
                this.errors.pfp = err.response.data.errors.pfp[0];
              }
            });
        });
      },
    },
    beforeRouteEnter(to, from, next) {
      // Если пользователь уже авторизован
      if (window.Laravel.isLogged) {
        return next("/user/" + window.Laravel.user.id);
      }
      next();
    },
  };
  </script>
