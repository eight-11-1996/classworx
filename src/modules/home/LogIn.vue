<template>
  <div class="container-fluid custom-container" v-if="!tokenData.verifyingToken && !tokenData.token">
    <div class="row">
      <div class="col-sm-12 col-md-8 hide-this">
        <div class="container-fluid banner">
          <!-- <img src="../../assets/img/Top.png"  height="100%" width="100%"> -->
        </div>
      </div>
      <div class="col-sm-12 col-md-4">
        <div class="login-wrapper">
          <div class="login-header" style="margin-top: 50px;text-align:center;">
            <h1 class="navbar-brand text-center text-primary" style="font-size:32px;">
              Class<b>Worx</b>
            </h1>
          </div>
          <span style="width:100%;float:left;text-align:center;margin-top:25px;font-size:20px;margin-bottom:20px;">Login</span>
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
            <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="logIn()">Login</button>
            <!-- <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input">
                Keep me logged in
              </label>
            </div> -->
            <button class="btn btn-login-warning btn-block btn-login login-spacer" v-on:click="redirect('forget_password')">Forget your Password?</button>
            <br>
            <div class="container-fluid separator">
                or
            </div>
            <br>
            <button class="btn btn-blue btn-block btn-login login-spacer" v-on:click="redirect('signup')">Create Account Now!</button>
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
      password: '',
      errorMessage: '',
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      branchesEmployees: [],
      branches: []
    }
  },
  methods: {
    logIn(){
      AUTH.authenticate(this.username, this.password, (response) => {
        ROUTER.go('/dashboard')
      }, (response, status) => {
        this.errorMessage = (status === 401) ? 'Your Username and password did not matched.' : 'Cannot log in? Contact us through email: support@classworx.ph'
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

.app-img{
  float: left;
  width: 30%;
  text-align: right;
}
.app-title{
  float: left;
  width: 70%;
  text-align: left;
}
.app-img img{
  height: 50px;
  width: 50px;
}

.app-title label{
  vertical-align: middle;
  font-size: 30px;
  left: 0;
}
.login-header{
  height: 40px;
  color: #006600;
  width: 100%;
  float: left;
}/*-- login-header --*/

.login-message-holder{
  min-height: 30px;
  font-size: 12px;
  float: left;
  overflow: hidden;
}

.login-spacer{
  margin-bottom: 10px;
}/*-- login-spacer --*/

.forgot-password a{
  color: #006600 !important;
}
.forgot-password a:hover{
  cursor: pointer !important;
  text-decoration: underline !important;
  color: #009900 !important;
}

.input-group-addon{
  width: 50px;
}
/*----------------------------------------

            Forms

------------------------------------------*/
.form-control-login{
  height: 45px !important;
}


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

.btn-login-primary{
  background: #6a0090;
  color: #fff;
  height: 45px !important;
}
.btn-login-primary:hover{
  border: solid 1px #3f0050;
}

.btn-login-warning{
  color: #fff;
  background: #FCCD04;
  height: 45px !important;
}
.btn-login-warning:hover{
  color: #fff;
  border: solid 1px #bb9800;
}
.btn-blue{
  background: #028170;
  color: #fff;
  height: 45px !important;
}
.btn-blue:hover{
  border: solid 1px #026759;
}
/*---------------------------------------------------------

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/

/*-------------- Large Screens for Desktop --------------*/
@media (min-width: 1200px){
  .login-wrapper{
    width: 80%;
    margin: 0 5% 0 15%;
  }
}


/*-------------- Medium Screen for Tablets  --------------*/
@media screen (min-width: 992px), screen and (max-width: 1199px){
  .login-wrapper{
    width: 80%;
    margin: 0 5% 0 15%;
  }
}

/*-------------- Small Screen for Mobile Phones  --------------*/
@media screen (min-width: 800px), screen and (max-width: 991px){
  .login-wrapper{
    width: 98%;
    margin: 0 2% 0 0%;
  }
}

@media screen (min-width: 501px), screen and (max-width: 799px){
  .login-wrapper{
    width: 70%;
    margin: 0 15% 0 15%;
  }
}
/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 500px){
  .hide-this{
    display: none;
  }
  .login-wrapper{
    width: 90%;
    margin: 0 5% 0 5%;
  }
}
</style>
