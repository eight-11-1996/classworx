<template>
  <div class="account-settings-holder" v-if="data !== null">
    <div class="account-header text-center">
      <span class="profile-image" v-if="data.account_profile !== null">
        <img v-bind:src="config.BACKEND_URL + data.account_profile.profile_url" width="100%" height="100%">
      </span>
      <span class="profile-image" v-else>
        <i class="fa fa-user-circle-o"></i>
      </span>
      <span class="account-name">
        {{data.account_information.first_name + ' ' + data.account_information.last_name}}
      </span>
    </div>
    <div class="information-holder" style="margin-right: 1%;">
      <span class="header">
        <i class="fa fa-user"></i>Personal Information <i class="fa fa-pencil pull-right action-link"></i>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-calendar"></i>
            Birthdate: {{data.account_information.birthdate}}
          </label>
        </span>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fas fa-transgender"></i>
            Sex: {{data.account_information.sex}}
          </label>
        </span>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-phone"></i>
            Cellular Number: {{data.account_information.cellular_number}}
          </label>
        </span>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fas fa-map-marker-alt"></i>
            Address: {{data.account_information.address}}
          </label>
        </span>
      </span>
    </div>
    <div class="information-holder"  style="margin-left: 1%;">
      <span class="header">
        <i class="fa fa-user"></i>Educational Background <i class="fa fa-pencil pull-right action-link"></i>
      </span>
      <span class="item"></span>
    </div>
    <div class="information-holder information-holder-whole">
      <span class="header">
        <i class="fa fa-user"></i>Account<i class="fa fa-pencil pull-right action-link"></i>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-user"></i>
            Username: {{data.username}}
          </label>
        </span>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-envelope"></i>
            Email: {{data.email}}
          </label>
        </span>
      </span>
    </div>
  </div>
</template><script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieveRequest()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      data: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequest(){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'id',
          'clause': '='
        }]
      }
      this.APIRequest('accounts/retrieve', parameter).then(response => {
        if(response.data !== null || response.data.length > 0){
          this.data = response.data[0]
        }else{
          this.data = null
        }
      })
    }
  }
}
</script>
<style>
  .acccount-settings-holder{
    width: 100%;
    min-height: 500px;
    overflow-y: hidden;
  }
  .account-header{
    width: 100%;
    height: 250px;
    background: #eee;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }
  .profile-image i{
    font-size: 150px;
    color: #3f0050;
    padding-top: 50px;
  }
  .profile-image img{
    height: 150px;
    width: 150px;
    border-radius: 25px;
  }
  .account-name{
    height: 35px;
    width: 100%;
    float: left;
    font-weight: 600;
    padding-top: 10px;
    font-size: 18px;
    color: #028170;
  }
  .information-holder{
    width: 49%;
    float: left;
    min-height: 100px;
    margin-top: 5px;
    overflow-y: hidden;
  }
  .information-holder-whole{
    width: 100%;
  }

  .information-holder .header{
    height: 50px;
    width: 100%;
    float: left;
    background: #FCCD04;
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .header i{
    color: #fff !important;
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 10px;
  }
  .information-holder .item{
    width: 100%;
    float: left;
  }
  .item .content{
    height: 50px;
    float: left;
    width: 100%;
  }
  .content label{
    padding-top: 15px;
  }
  .content label i{
    padding-right: 10px;
    padding-left: 10px;
  }
  @media screen (min-width: 200px), screen and (max-width: 991px){
    .information-holder{
      width: 100%;
    }
    .information-holder .header{
      font-size: 15px;
    }
   }

</style>
