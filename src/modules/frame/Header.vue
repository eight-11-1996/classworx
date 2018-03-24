 <template>
  <div>
    <div class="system-header">
      <a class="navbar-brand" v-on:click="redirect('dashboard')">
        <label class="navbar-brand"><b>Talk</b>Fluent</label>
      </a>
    </div>
    <nav class="header-navbar">
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
        <span class="nav-item" data-toggle="dropdown" id="settings" aria-haspopup="true" aria-expanded="false">
          <span>
            <i class="fa fa-cog"></i>
          </span>
          <span class="dropdown-menu dropdown-menu-right" aria-labelledby="settings">
            <span class="dropdown-item-profile">
              <span class="account-picture text-center">
                <i class="fa fa-user-circle-o"></i>
              </span>
              <span class="account-info text-center">{{user.username}}</span>
            </span>
            <span class="dropdown-item" v-on:click="redirect('category_lesson')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Category Lesson</span>
            <span class="dropdown-item" v-on:click="redirect('subcategory_lesson')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Sub Category Lesson</span>
            <span class="dropdown-item" v-on:click="redirect('lesson_management')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Lesson Management</span>
            <span class="dropdown-item" v-on:click="redirect('audio_files')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Audio File Management</span>
            <!-- <span class="dropdown-item" v-on:click="redirect('accent_audio')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Accent Audio Management</span>
            <span class="dropdown-item" v-on:click="redirect('accent_video')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Accent Video Management</span> -->
            <span class="dropdown-item" v-on:click="redirect('content_management')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Content Management</span>
            <span class="dropdown-item" v-on:click="redirect('word_popup')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Word Popup</span>
            <span class="dropdown-item" v-on:click="redirect('sentence_popup')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Sentence Popup</span>
            <span class="dropdown-item" v-on:click="redirect('word_audio')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Word Management</span>
            <span class="dropdown-item" v-on:click="redirect('history')" v-if="user.type === 'ADMIN' || account.account_type === 'ADMIN'">Already Known</span>
            <span class="dropdown-item" v-on:click="redirect('dashboard')">Dashboard</span>
            <span class="dropdown-item" v-on:click="redirect('help')">Help</span>
            <span class="dropdown-item" v-on:click="logOut()">Logout</span>
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
      account: []
    }
  },
  methods: {
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
        this.account = response.data[0]
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


/*
        BUTTONS
*/
.btn-primary{
  background: #009900;
  border-color: #009900;
}

.btn-primary:hover{
  background: #009900;
  border-color: #009900;
}

.btn-danger{
  background: #ff0000;
}

.btn-danger:hover{
  background: #ff0000;
  border-color: #ff0000;
}
.btn-facebook{
  background: #124abf;
  color: #fff;
}

.btn-facebook:hover{
  background: #3b5998;
}

.btn-gmail{
  background: #c70000;
  color: #fff;
}
.btn-gmail:hover{
  background: #c71610;
}
/*
      HALLOW

*/

.btn-primary-hallow{
  border-color: #009900;
  color: #009900;
  background: #fff;
}
.btn-primary-hallow:hover{
  color: #009900;
  border-color: #009900;
}
.btn-danger-hallow{
  border-color: #aa0000;
  background: #fff;
  color: #aa0000;
}
.btn-danger-hallow:hover{
  color: #ff0000;
  border-color: #ff0000;
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
    background: #009900;
    text-align: center;
  }
  .header-navbar{
    background: #009900;
    height: 50px;
    float: left;
    width: 82%;
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
    background: #1c73eb;
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
  background: #1c73eb;
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
  color: #888;
  font-weight: 550;
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
  background: #1c73eb;
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
     width: 0% !important;
    }
    .system-header{
      width: 40%;
    }
    .header-navbar{
      width: 60%;
    }
   .navbar-menu-toggler-md{
      width: 15%
    }
    .header-navbar-nav{
      text-align: center;
      width: 50%;
    }
    .nav-item{
      width: 40%;
    }
  }
</style>
