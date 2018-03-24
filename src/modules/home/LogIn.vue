<template>
  <div class="container-fluid custom-container" v-if="!tokenData.verifyingToken && !tokenData.token">
    <div class="row">
      <div class="col-md-6 col-lg-4 mx-auto login-container">
        <div class="login-wrapper">
          <span class="login-company-name">TALK TO FLUENT SPANISH</span>
          <br />
          <span class="text-primary login-spacer login-trial"><label v-on:click="redirect('signup')">Start your 15 Day Free Trial</label></span>
          <br />
          <br />
          <div class="login-input-holder">
          <span class="text-center login-spacer"><h6 class="text-center">Login</h6></span>
          <div class="login-message-holder login-spacer" v-if="errorMessage != ''">
            <span class="text-danger"><b>Oops!</b> {{errorMessage}}</span>
          </div>
          <div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-1"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control form-control-login" placeholder="Username or Email" aria-describedby="addon-1" v-model="username">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-2"><i class="fa fa-key"></i></span>
              <input type="password" class="form-control form-control-login" placeholder="********" aria-describedby="addon-2" v-model="password">
            </div>
            <div class="form-check">
<!--               <label class="form-check-label">
                <input type="checkbox" class="form-check-input">
                Remember Me
              </label> -->
              <label class="text-primary text-center" v-on:click="redirect('recover_account')">
                Forgot Password?
              </label>
            </div>
            <button class="btn btn-primary btn-block btn-login login-spacer" v-on:click="logIn()">Login</button>
            <br />
            <br />
            <br />
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
  },
  data(){
    return {
      username: '',
      password: '',
      errorMessage: '',
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG
    }
  },
  methods: {
    logIn(){
      AUTH.authenticate(this.username, this.password, (response) => {
        ROUTER.push('dashboard')
      }, (response, status) => {
        this.errorMessage = (status === 401) ? 'Your Username and password didnot matched.' : 'Cannot log in? Contact us through email: support@talkfluent.com'
      })
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    request(parameter){
      this.APIRequest(parameter, {}).then(response => {
      })
    }
  }
}
</script>
<style>
/*
  Designs Rules
  1. Top to Bottom
  2. Left to Right
  3. Common
  4. Screen Changes
*/
.app-logo{
  width: 80%;
  margin: 0 10% 20px 10%;
}

.login-header{
  height: 40px;
  color: #1caceb;
  width: 100%;
  float: left;
}/*-- login-header --*/

.login-container{
  margin-top: 150px;
  box-shadow: 2px 2px 2px 2px #ccc;
}

.login-company-name{
  font-weight: 700;
  font-size: 18px;
  width: 100%;
}
.login-message-holder{
  min-height: 30px;
  font-size: 12px;
  float: left;
  overflow: hidden;
}

.login-trial label{
  text-align: right;
  width: 100%;
  float: left;
}

.login-wrapper{
    width: 96%;
    margin: 25px 2% 0 2%;
}

.login-input-holder{
  width: 70%;
  margin: 0 15% 0 15%;
}
.login-spacer{
  margin-bottom: 10px;
}/*-- login-spacer --*/

.forgot-password a{
  color: #1caceb !important;
}
.forgot-password a:hover{
  cursor: pointer !important;
  text-decoration: underline !important;
  color: #1caceb !important;
}


.form-control-login{
  height: 45px;
}



/*----------------------------------------

            Buttons

------------------------------------------*/
.btn-login{
  height: 45px;
}/*-- form-control --*/


/*    Line with text on top  */
.separator>*{
  display: inline-block;
  vertical-align: middle;
}
.separator {
    text-align: center;
    border: 0;
    white-space: nowrap;
    display: block;
    overflow: hidden;
    padding: 0;
    margin: 0;
}
.separator:before, .separator:after {
    content: "";
    height: 1px;
    width: 50%;
    background-color: #ccc;
    margin: 0 5px 0 5px;
    display: inline-block;
    vertical-align: middle;
}
.separator:before {
    margin-left: -100%;
}
.separator:after {
    margin-right: -100%;
}

/*      Colors           */
.primary-color{
  color: #1caceb;
}
/*---------------------------------------------------------

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/

/*-------------- Large Screens for Desktop --------------*/
@media (min-width: 1200px){

}


/*-------------- Medium Screen for Tablets  --------------*/
@media screen (min-width: 992px), screen and (max-width: 1199px){
}

/*-------------- Small Screen for Mobile Phones  --------------*/
@media screen (min-width: 768px), screen and (max-width: 991px){
 
}

/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .hide-this{
    display: none;
  }
  .login-container{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 75px !important;
  }
  .login-company-name, .login-trial label, .form-check{
    text-align: center !important; 
    width: 100% !important;
  }
  .login-trial label{
    margin-top: 20px;
    margin-bottom: 20px;
  }
  .login-input-holder{
    width: 100% !important;
    margin-left: 0 !important;
  }
}
</style>
