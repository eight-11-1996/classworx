 <template>
  <div>
    <div class="system-header">
      <a class="navbar-brand" v-on:click="redirect('dashboard')">
        <label class="navbar-brand"><b>Class</b>Worx</label>
      </a>
    </div>
    <nav class="header-navbar">
    <span class="navbar-menu-toggler-md" v-bind:class="{'active-menu': menuFlag === true}" data-toggle="collapse" data-target="#ClassWorx" aria-controls="ClassWorx" aria-expanded="false" aria-label="Toggle navigation" v-on:click="makeActive('menu')">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </span>
    <span class="header-margin"></span><!--       
    <div class="dropdown"> 
        <div class="header-navbar-nav" id="account-holder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <span>
              <i class="fa fa-user-circle" aria-hidden="true"></i>
              <label>Hi {{user.username}}!</label>
           </span> 
           <span class="dropdown-menu" aria-labelledby="account-holder" id="account">
            <span class="dropdown-item-profile">
              <span class="account-picture text-center">
                <img src="../../assets/img/sample.jpg" class="rounded-circle" height="125" width="125">
              </span>
              <span class="account-info text-center">{{user.username}}</span>
            </span>
            <span class="dropdown-item-button text-center"><button class="btn btn-danger-hallow" v-on:click="logOut()">Logout</button></span>
          </span> 
        </div>
    </div>     -->
    <div class="dropdown"> 
        <span class="nav-item" v-bind:class="{'active-menu': settingFlag === true}" data-toggle="dropdown" id="settings" aria-haspopup="true" aria-expanded="false" v-on:click="makeActive('dropdown')" v-bind:onkeypress="makeActive('')">
          <span>
            <i class="fa fa-cog"></i>
          </span>
          <span class="dropdown-menu dropdown-menu-right" aria-labelledby="settings">
            <span class="dropdown-item-profile">
              <span class="account-picture text-center">
                <span v-if="account !== null" class="profile-photo">
                  <span class="profile-image-holder"  v-if="account.account_profile !== null">
                    <img v-bind:src="config.BACKEND_URL + account.account_profile.profile_url">
                  </span>
                  <i class="fa fa-user-circle-o" v-else></i>
                </span>
              </span>
              <span class="account-info text-center">{{user.username}}</span>
            </span>
            <span class="dropdown-item" v-on:click="redirect('/account_settings')">
              <i class="fa fa-cog"></i>
              <label>Account Settings</label>
            </span>
            <span class="dropdown-item" v-on:click="redirect('/help')">
              <i class="far fa-question-circle"></i>
              <label>Help</label>
            </span>
            <span class="dropdown-item" v-on:click="logOut()">
              <i class="fas fa-sign-out-alt"></i>
                <label>Logout</label>
              </span>
          </span>
        </span>
    </div>
    </nav>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.getAccontInfo()
  },
  created(){
    this.display()
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      account: null,
      settingFlag: false,
      menuFlag: false
    }
  },
  methods: {
    makeActive(icon){
      if(icon === 'dropdown'){
        this.settingFlag = true
        this.menuFlag = false
      }else if(icon === 'sidebar'){
        this.settingFlag = false
        this.menuFlag = true
      }else{
        this.settingFlag = false
        this.menuFlag = false
      }
    },
    logOut(){
      AUTH.deaunthenticate()
      ROUTER.go('/login')
      ROUTER.push('/login')
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    display(){
    },
    getAccontInfo(){
      let parameter = {
        'condition': [{
          'column': 'id',
          'clause': '=',
          'value': this.user.userID
        }]
      }
      this.APIRequest('accounts/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.account = response.data[0]
        }else{
          this.account = null
        }
      })
    }
  }
}
</script>
<style type="text/css">

/*
        BODY
*/
body{
  font-size: 13px;
  font-weight: 400;
}

.btn{
  font-size: 12px;
}
.btn:hover{
  cursor: pointer;
}


.account-picture i{
  font-size: 100px !important;
}


/*------------------------------------

          TABLES

--------------------------------------*/

.table{
  font-size: 12px;
}

/*----------------------------------------

            Forms

------------------------------------------*/
.form-control{
  height: 35px;
  font-size: 12px;
}

  .system-header{
    float: left;
    height: 50px;
    font-size: 24px;
    width: 18%;
    background: #3f0050;
    text-align: center;
    position: fixed;
    z-index: 2;
  }
  .header-navbar{
    background: #6a0090;
    height: 50px;
    float: left;
    width: 82%;
    position: fixed;
    margin-left: 18%;
    z-index: 2;
  }/*-- navbar --*/
  .system-header .navbar-brand{
    color: #fff;
  }
  
/*---------------------------------------------
 

        HEADER ACCOUNT PROFILE


-----------------------------------------------*/
  .header-navbar-nav{
      height: 50px;
      float: left;
      color: #fff;
      width: 15%;
  }
  .header-navbar-nav label{
    font-size: 13px;
    font-weight: 500;
    vertical-align: middle;
    padding-left: 10px;
  }
  .header-navbar-nav i{
    font-size: 24px;
     padding: 10px 0 0 5%;
  }

  .header-navbar-nav:hover{
    cursor: pointer;
    background: #3f0050 ;
  }


/*---------------------------------------------
       


                  ICONS


-----------------------------------------------*/

#messagesHeader{
  margin-left: 70%;
}
.nav-item{
  width: 5%;
  height: 50px;
  text-align: center;
  float: right;
  color: #fff;
  display: inline;
}
.header-margin{
  height: 50px;
  float: left;
  width: 80%;
}

.nav-item span i{
  padding: 12px 0 15px 0;
  font-size: 24px;
}

.nav-item .label{
  z-index: 1000;
  background: #ff0000;
  padding: 5px;
  margin: -10px 0 0 -10px;
  border-radius: 2px;
  border-color: solid 1px #ff0000;
}

.nav-item:hover{
  background: #3f0050 ;
  cursor: pointer;
}

.dropdown-menu{
  width: 250px;
  min-height: 250px;
  border-radius: 0px !important;
}
.dropdown-item{
  width: 100%;
  height: 40px;
  float: left;
  background: #fff;
}
.dropdown-item:hover{
  background: #ddd !important;
}

.dropdown-item i{
  font-size: 14px !important;
  padding-right: 10px !important;
  color: #FCCD04 !important;
}
.dropdown-item label{
  font-size: 14px !important;
}
.dropdown-item label:hover, .dropdown-item i:hover{
  cursor: pointer;
}
.dropdown-header{
  padding: 5px 0 10px 0;
  width: 100%;
  text-align: center;
  border-bottom: solid 1px #ccc;
}
.dropdown-item-profile{
  height: 200px;
  width: 100%;
  float: left;
  top: 0;
}

#account{
  background: #fff;
}
.account-picture{
  height: 150px;
  width: 100%;
  float: left;
}
.account-info{
  height: 50px;
  width: 100%;
  float: left;
  font-weight: 550;
  color: #3f0050;
}
.dropdown-item-button{
  height: 50px;
  width: 100%;
  float: left;
  background: #fff;
  border-top: solid 1px #ddd;
}
.dropdown-item-button button{
  height: 40px;
  border-radius: 0;
  width: 100px;
  margin-top: 5px;  
}
.dropdown-item-button button:hover{
  background: #ff0000;
  color: #fff;
}

/*---------------------------------------------
 
 
        HEADER TOGGLER MENU


-----------------------------------------------*/
.navbar-menu-toggler-md{
  height: 50px;
  float: left;
  text-align: center;
  font-size: 16px;
  color: #fff;
  padding: 10px 0 15px 0;
  display: none;
}
.navbar-menu-toggler-md:hover{
  cursor: pointer;
  background: #3f0050;
}

.active-menu{
  background: #3f0050;
}


/*--------------------------------------

          PROFILE PICTURE
  
---------------------------------------*/


.profile-photo{
  float: left;
  width: 100%;
  height: 100px;
  color: #3f0050;
}
.profile-image-holder{
  width: 100%;
  float: left;
  height: 80px;
  margin-bottom: 10px;
  text-align: center;
}
.profile-image-holder img{
  width: 80px;
  height: 80px;
  border-radius: 20px;
}

.profile-photo i{
  float: left;
  font-size: 80px;
  width: 100%;
  height: 80px;
  margin-bottom: 10px;
}

/*---------------------------------------------------------          

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/
/*-------------- Medium and Large Screens for Tablets and Desktop --------------*/

 @media screen (min-width: 1200px){
    .system-header{
      width: 23%;
    }
    .header-navbar{
      width: 77%;
      margin-left: 23%;
    }
    .header-navbar-nav{
      width: 15% !important;
    }
    .navbar-menu-toggler-md{
      display: none;
    }
    .header-margin{
      width: 80% !important;
    }
    .nav-item{
      width: 5% !important;
    }
  }

 @media screen (min-width: 992px), screen and (max-width: 1199px){
    .system-header{
      width: 23%;
    }
    .header-navbar{
      width: 77%;
      margin-left: 23%;
    }
    .header-navbar-nav{
      width: 30%;
    }
    .navbar-menu-toggler-md{
      display: none;
    }
    .header-margin{
      width: 60%;
    }
    .nav-item{
      width: 10%;
    }
  }

@media screen (min-width: 768px), screen and (max-width: 991px){
   .system-header{
      width: 30%;
    }
    .header-navbar{
      width: 70%;
      margin-left: 30%;
    }
   .header-navbar-nav{
      width: 30%;
    }
    .navbar-menu-toggler-md{
      width: 10%;
      text-align: center;
      display: block;
    }
    .header-margin{
      width: 60%;
    }
    .nav-item{
      width: 10%;
    }
 }

/*-------------- Small Screen for Mobile Phones --------------*/
 @media (max-width: 767px){
    .header-margin{
     width: 10% !important;
    }
    .system-header{
      width: 40%;
    }
    .header-navbar{
      width: 60%;
      margin-left: 40%;
    }
   .navbar-menu-toggler-md{
      width: 20%
    }
    .header-navbar-nav{
      text-align: center;
      width: 50%;
    }
    .nav-item{
      width: 20%;
    }
  }
</style>
