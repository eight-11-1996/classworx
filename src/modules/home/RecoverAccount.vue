<template>
  <div class="row">
    <div class="col-lg-4 col-md-6 mx-auto custom-holder">
      <span class="site-title text-center text-primary"><h1 class="text-center">Class<b>Worx</b></h1></span>
      <span class="text-center login-spacer"><h6 class="text-center">Recover Account</h6></span>
      <div class="input-holder">
        <div class="login-message-holder login-spacer" v-if="errorMessage != ''">
            <span class="text-danger text-center" v-if="successMessage === null && errorMessage !== null"><b>Oops!</b> {{errorMessage}}</span>
            <span class="text-primary text-center" v-else>{{successMessage}}</span>
        </div>
        <input type="text" name="username" placeholder="Username or Email Address" class="form-control" v-model="username">

        <br>
        <input type="password" name="username" placeholder="New Password" class="form-control" v-model="password">
        <br>
        <input type="password" name="username" placeholder="Confirm New Password" class="form-control" v-model="confirmPassword">
        <br>
        <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="update()">Update</button><button class="btn btn-danger btn-block btn-login login-spacer" v-on:click="redirect('/')">Back to Login</button>
        <br>
        <br>
     </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
export default {
  name: '',
  components: {
    'input-group': require('components/input_field/InputGroup.vue')
  },
  mounted(){
  },
  data(){
    return{
      username: '',
      password: '',
      confirmPassword: '',
      flag: false,
      errorMessage: null,
      successMessage: null
    }
  },
  methods: {
    update(){
      this.validate()
      let parameter = {
        username: this.username,
        password: this.password
      }
      if(this.flag === true){
        this.APIRequest('accounts/update', parameter).then(response => {
          if(response.data === true){
            this.successMessage = 'Password was successfully changed! Click back to login button.'
            console.log(response.data)
            console.log(this.successMessage)
          }if(response.message.error.status === 100){
            this.errorMessage = 'Username or Email Address not found!'
          }else if(response.message.error.status !== 'undefined'){
            this.errorMessage = 'Username or Email Address not found!'
          }
        })
      }
    },
    validate(){
      if(this.username.length >= 6 && this.password !== null && this.password.localeCompare(this.confirmPassword) === 0){
        this.flag = true
      }else if(this.username.length < 6){
        this.errorMessage = 'Username or email not found.'
      }else if(this.password.length < 6){
        this.errorMessage = 'Password must be atleast 6 characters'
      }else if(this.password.localeCompare(this.confirmPassword) !== 0){
        this.errorMessage = 'Password didnot matched'
      }else{
        this.flag = false
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style>
.custom-holder{
  margin-top: 100px;
}

.header-title{
  width: 90%;
  margin:  25px 5% 0 5%;
  font-size: 24px;
  font-weight: 700px;
}
.input-holder{
  width: 90%;
  margin:  0 5% 0 5%;
}

.btn, .form-control{
  height: 45px !important;
}
.site-title{
  margin-top: 25px;
  width: 100%;
  float: left;
}
/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .custom-holder{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 50px !important;
  }
}
</style>
