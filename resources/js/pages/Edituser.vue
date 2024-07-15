<template>
    <div class="container">
        <div id="back_to_user">
            <a :href="$router.resolve({name: 'Userpage', params: { id: user.id }}).href"><img :src="'/storage/imgs/arrow_back.png'" alt=""><p id="go_back_text">Назад на страницу</p></a>
        </div>
        <form id="user_edit_form">
            <div id="ue_form_left">
                <input type="text" name="nikname" placeholder="Изменить ваше прозвище" v-model="nikname">
                <p class="ue_error">{{ errors.nikname }}</p>
                <input type="text" name="email" placeholder="Изменить почту" v-model="email">
                <p class="ue_error">{{ errors.email }}</p>
                <input type="date" name="birthdate" v-model="birthdate">
                <p class="ue_error">{{ errors.birthdate }}</p>
                <textarea name="add_info" id="" cols="30" placeholder="Расскажите немного о себе" rows="10"  v-model="add_info"></textarea>
                <p class="ue_error">{{ errors.add_info }}</p>
                <button @click="edit">Сохранить изменения</button>
                <p id="ue_success_msg">{{ success }}</p>
            </div>
            <div id="form_right">
                <label @change="show_name()" id="label_form_ue" class="custom-file-upload input-file">
                    <input class="file_upload" type="file" ref="pfp" name="pfp">
                    <span id="file_selected">{{ file_name }}</span>
                </label>
                <p class="ue_error">{{ errors.pfp }}</p>
            </div>            
            
        </form>
    </div>
</template>


<style>

  #back_to_user a{
    display: flex;
    align-items: center;
    column-gap: 10px;
  }
  #back_to_user a, #back_to_user a:visited{
    color: #1D1A0F;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
  }
  #back_to_user img{
    width: 22px;
  }

  #user_edit_form{
    display: flex;
    margin: 50px auto;
    width: 758px;
    column-gap: 163px;
  }

  #ue_form_left input{
    width: 297px;
    height: 31px;
    border: 1px solid #1D1A0F;
    padding-left: 7px;
    margin-bottom: 30px;
  }
  #ue_form_left textarea{
    width: 337px;
    height: 143px;
    padding: 14px;
    font-size: 12px;
    resize: none;
    border: 1px solid #1D1A0F;
    margin-bottom: 45px;
  }
  .ue_error{
    font-size: 10px;
    color: #E85F5F;
    line-height: 0px;
    height: 20px;
    margin-top: -15px;
  }
  #ue_form_left button{
    width: 193px;
    height: 31px;
    border: 1px solid black;
    cursor: pointer;
    transition-duration: 0.5s;
    color: #fff;
    background-color: #1D1A0F;
    margin-bottom: 12px;
  }
    #ue_form_left button:hover{
      color: #1D1A0F;
      background-color: #e8e8e8;
    }
  #ue_success_msg{
    font-size: 14px;
    line-height: 0px;
    height: 20px;
    margin-top: 5px;
    font-weight: 500;
  }

  #label_form_ue{
    width: 228px;
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
    #label_form_ue:hover{
        background-color: #e8e8e8;
    }

</style>


<script>

    export default{
        name: 'Home',
        data(){
            return{
                user: window.Laravel.user,
                nikname: window.Laravel.user.nikname,
                email: window.Laravel.user.email,
                birthdate: window.Laravel.user.birthdate,
                add_info: window.Laravel.user.add_info,
                errors: {
                    nikname: "",
                    email: "",
                    birthdate: "",
                    add_info: "",
                    pfp: "",
                },
                success: '',
                file_name: 'Изменить фото профиля'
            }
        }, created(){

        }, methods: {
          show_name(){
                this.file_name = this.$refs.pfp.files[0].name
            },
            edit(e){
                e.preventDefault();
                
            this.errors = {
                nikname: "",
                email: "",
                birthdate: "",
                add_info: "",
                pfp: "",
            }
            this.success = ''
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
            this.$axios
            .post("api/edit", {
                nikname: this.nikname,
                email: this.email,
                birthdate: this.birthdate,
                add_info: this.add_info,
                pfp: this.$refs.pfp.files[0],
            },
              {
                headers: {
                  "Content-Type": "multipart/form-data",
                },
              })
            .then((response) => {
              console.log(response.data);
              this.success = 'Данные обновлены!'
            })
            .catch((err) => {
              console.log(err.response.data);
              if (err.response.data.errors.nikname) {
                this.errors.nikname = err.response.data.errors.nikname[0];
              }
              if (err.response.data.errors.email) {
                this.errors.email = err.response.data.errors.email[0];
              }
              if (err.response.data.errors.birthdate) {
                this.errors.birthdate = err.response.data.errors.birthdate[0];
              }
              if (err.response.data.errors.add_info) {
                this.errors.add_info = err.response.data.errors.add_info[0];
              }
              if (err.response.data.errors.pfp) {
                this.errors.pfp = err.response.data.errors.pfp[0];
              }
              
            });
            });      
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