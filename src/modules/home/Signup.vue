<template>
  <div class="row">
    <div class="col-md-6 col-lg-4 mx-auto signup-container">
      <div class="login-wrapper">
        <span class="site-title"><h5 class="text-center">Class Worx</h5></span>
        <br>
        <span class="text-center login-spacer"><h6 class="text-center">Signup</h6></span>
        <div class="signup-holder">
          <div class="login-message-holder login-spacer text-center" v-if="errorMessage != ''">
            <span class="text-danger text-center"><b>Oops!</b> {{errorMessage}}</span>
          </div>
          <br>
          <br>  
          <div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-1"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control form-control-login" placeholder="Username" aria-describedby="addon-1" v-model="username">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-1"><i class="fa fa-envelope"></i></span>
              <input type="text" class="form-control form-control-login" placeholder="Email" aria-describedby="addon-1" v-model="email">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-2"><i class="fa fa-key"></i></span>
              <input type="password" class="form-control form-control-login" placeholder="Password" aria-describedby="addon-2" v-model="password">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-2"><i class="fa fa-key"></i></span>
              <input type="password" class="form-control form-control-login" placeholder="Confirm Password" aria-describedby="addon-2" v-model="cpassword">
            </div>
<!--              <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input">
                I agree the terms and conditions
              </label>
            </div> -->
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="signUp()">Signup</button>
            <button class="btn btn-danger btn-block btn-login login-spacer" v-on:click="redirect('/')">Back to Login</button>  
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
export default {
  mounted(){
  },
  data(){
    return {
      username: '',
      email: '',
      password: '',
      cpassword: '',
      errorMessage: '',
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      flag: false
    }
  },
  methods: {
    signUp(){
      this.validate()
      let parameter = {
        username: this.username,
        email: this.email,
        password: this.password
      }
      if(this.flag === true){
        this.APIRequest('accounts/create', parameter).then(response => {
          if(response.error.status === 100){
            this.errorMessage = ('email' in response.error.message && !('name' in response.error.message)) ? 'Email Address is already taken.' : 'Please fill up the required informations.'
          }else if(response.error.status !== 'undefined'){
            this.login()
          }
        })
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    validate(){
      if(this.username.length >= 6 && this.email !== null && this.password !== null && this.password.localeCompare(this.cpassword) === 0){
        this.flag = true
      }else if(this.username.length < 6){
        this.errorMessage = 'Username must be atleast 6 characters.'
      }else if(this.password.length < 6){
        this.errorMessage = 'Password must be atleast 6 characters'
      }else if(this.password.localeCompare(this.cpassword) !== 0){
        this.errorMessage = 'Password didnot matched'
      }else{
        this.flag = false
      }
    },
    login(){
      AUTH.authenticate(this.username, this.password, (response) => {
        ROUTER.push('dashboard')
      }, (response, status) => {
        this.errorMessage = (status === 401) ? 'Your Username and password didnot matched.' : 'Cannot log in? Contact us through email: support@talkfluent.com'
      })
    }
  }
}
</script>
<style>
.signup-container{
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
