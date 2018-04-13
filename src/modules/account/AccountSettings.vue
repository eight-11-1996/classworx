<template>
  <div class="account-settings-holder" v-if="data !== null">
    <div class="account-header text-center">
      <span class="profile-image-settings" v-if="data.account_profile !== null">
        <img v-bind:src="config.BACKEND_URL + data.account_profile.profile_url" width="100%" height="100%" class="profile-image-content">
        <div class="middle" data-toggle="modal" data-target="#uploadModal">
          <i class="fa fa-plus"></i>
        </div>
      </span>
      <span class="profile-image-settings" v-else>
        <i class="fa fa-user-circle-o profile-image-content"></i>
        <div class="middle" data-toggle="modal" data-target="#uploadModal">
          <i class="fa fa-plus"></i>
        </div>
      </span>
      <span class="account-name">
        {{data.account_information.first_name + ' ' + data.account_information.last_name}}
      </span>
    </div>
    <div class="information-holder" style="margin-right: 1%;">
      <span class="header">
        <i class="fa fa-user"></i>Personal Information <i class="fa fa-pencil pull-right action-link" v-on:click="editPersonalInformation()"></i>
      </span>
      <span v-if="personalInfoFlag === false">
        <span class="item">
          <span class="content">
            <label>
              <i class="fa fa-user"></i>
              {{data.account_information.first_name + ' ' + data.account_information.middle_name + ' ' + data.account_information.last_name}}
            </label>
          </span>
        </span>
        <span class="item">
          <span class="content">
            <label>
              <i class="fa fa-calendar"></i>
              {{data.account_information.birth_date}}
            </label>
          </span>
        </span>
        <span class="item">
          <span class="content">
            <label>
              <i class="fas fa-transgender"></i>
              {{data.account_information.sex}}
            </label>
          </span>
        </span>
        <span class="item">
          <span class="content">
            <label>
              <i class="fa fa-phone"></i>
              {{data.account_information.cellular_number}}
            </label>
          </span>
        </span>
        <span class="item">
          <span class="content">
            <label>
              <i class="fas fa-map-marker-alt"></i>
              {{data.account_information.address}}
            </label>
          </span>
        </span>
      </span>
      <span v-else>
        <div class="input-group">
          <span class="input-group-addon">First Name</span>
          <input type="text" v-model="data.account_information.first_name" class="form-control">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Middle Name</span>
          <input type="text" v-model="data.account_information.middle_name" class="form-control">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Last Name</span>
          <input type="text" v-model="data.account_information.last_name" class="form-control">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Birthdate</span>
          <input type="date" v-model="data.account_information.birth_date" class="form-control">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Sex</span>
          <select class="form-control" v-model="data.account_information.sex">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="input-group">
          <span class="input-group-addon">Cellular Number</span>
          <input type="text" v-model="data.account_information.cellular_number" class="form-control">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Address</span>
          <input type="text" v-model="data.account_information.address" class="form-control">
        </div>
        <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="updatePersonalInformation()"><i class="fa fa-sync"></i> Update</button>
          <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="editPersonalInformation()"><i class="fa fa-ban"></i> Cancel</button>
      </span>
    </div>
    <div class="information-holder"  style="margin-left: 1%;">
      <span class="header">
        <i class="fa fa-university"></i>Educational Background 
        <i class="fa fa-plus pull-right action-link" v-on:click="addBackgroundFlag()"></i>
      </span>
      <span class="degree-holder" v-if="data.account_degree !== null && data.account_degree.length > 0" v-for="item, index in data.account_degree">
        <span v-if="item.edit_flag === false">
          <span class="item-half course">
            <label>{{item.course}}</label>
            <i class="fa fa-pencil text-primary pull-right action-link" v-on:click="edit(index)"></i>
          </span>
          <span class="item-half details">
            <label><i class="fa fa-university"></i>{{item.school}}</label>
          </span>
          <span class="item-half details">
            <label><i class="fas fa-map-marker-alt"></i>{{item.address}}</label>
          </span>
          <span class="item-half details">
            <label><i class="fa fa-calendar"></i>{{item.month_started + ' ' + item.year_started + ' - ' + item.month_end + ' ' + item.year_end}}</label>
          </span>
        </span>
        <span v-else>
          <div class="input-group">
            <span class="input-group-addon">Course</span>
            <input type="text" v-model="data.account_degree[index].course" class="form-control">
          </div>
          <div class="input-group">
            <span class="input-group-addon">School</span>
            <input type="text" v-model="data.account_degree[index].school" class="form-control">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Address</span>
            <input type="text" v-model="data.account_degree[index].address" class="form-control">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Started</span>
            <select class="form-control" v-model="data.account_degree[index].year_started">
              <option v-for="i in 50" v-bind:value="2010 - i">{{2010 - i}}</option>
            </select>
            <select class="form-control" v-model="data.account_degree[index].month_started">
              <option v-for="i in months" v-bind:value="i">{{i}}</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Ended</span>
            <select class="form-control" v-model="data.account_degree[index].year_end">
              <option v-for="i in 50" v-bind:value="2010 - i">{{2010 - i}}</option>
            </select>
            <select class="form-control" v-model="data.account_degree[index].month_end">
              <option v-for="i in months" v-bind:value="i">{{i}}</option>
            </select>
          </div>
          <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="updateDegree(index)"><i class="fa fa-sync"></i> Update</button>
          <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="edit(index)"><i class="fa fa-ban"></i> Cancel</button>
        </span>
      </span>
      <span v-if="addFlag === true">
          <label><b>Add New</b></label>
          <div class="input-group">
            <span class="input-group-addon">Course</span>
            <input type="text" v-model="newBackground.course" class="form-control">
          </div>
          <div class="input-group">
            <span class="input-group-addon">School</span>
            <input type="text" v-model="newBackground.school" class="form-control">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Address</span>
            <input type="text" v-model="newBackground.address" class="form-control">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Started</span>
            <select class="form-control" v-model="newBackground.year_started">
              <option v-for="i in 50" v-bind:value="2010 - i">{{2010 - i}}</option>
            </select>
            <select class="form-control" v-model="newBackground.month_started">
              <option v-for="i in months" v-bind:value="i">{{i}}</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Ended</span>
            <select class="form-control" v-model="newBackground.year_end">
              <option v-for="i in 50" v-bind:value="2010 - i">{{2010 - i}}</option>
            </select>
            <select class="form-control" v-model="newBackground.month_end">
              <option v-for="i in months" v-bind:value="i">{{i}}</option>
            </select>
          </div>
          <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="addDegree()"><i class="fa fa-plus"></i> Add</button>
          <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="addBackgroundFlag()"><i class="fa fa-ban"></i> Cancel</button>
        </span>
    </div>
    <div class="information-holder information-holder-whole">
      <span class="header">
        <i class="fa fa-user"></i>Account Information
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-user"></i>
            @{{data.username}}
          </label>
        </span>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-envelope"></i>
            {{data.email}}
          </label>
        </span>
      </span>
      <span class="item">
        <span class="content">
          <label>
            <i class="fa fa-calendar"></i>
            {{data.created_at}}
          </label>
        </span>
      </span>
    </div>
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Viewed by:</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="table-result">
            <form>
             <div class="">
                <input type="file" name="audio" accept="image" @change="fileCount = $event.target.files.length, readURL(this)" class="btn">
                <img id="profile_picture" src="#" alt="your image" />
              </div>
            </form>
          </div>
          <div class="modal-footer">
              <!-- <button type="button" class="btn btn-primary" @click="Submit()" v-if="closeFag == false">update</button> -->
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
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
      data: null,
      prevDegreeEditIndex: null,
      personalInfoFlag: false,
      addFlag: false,
      newBackground: {
        account_id: null,
        course: null,
        school: null,
        address: null,
        year_started: null,
        month_started: null,
        year_end: null,
        month_end: null
      },
      months: [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
      ],
      profilePic: null
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
    },
    edit(index){
      this.addFlag = false
      if(this.prevDegreeEditIndex === null){
        this.data.account_degree[index].edit_flag = true
        this.prevDegreeEditIndex = index
      }else{
        if(this.prevDegreeEditIndex === index){
          this.data.account_degree[index].edit_flag = false
          this.prevDegreeEditIndex = null
        }else{
          this.data.account_degree[this.prevDegreeEditIndex].edit_flag = false
          this.data.account_degree[index].edit_flag = true
          this.prevDegreeEditIndex = index
        }
      }
    },
    updateDegree(index){
      this.APIRequest('account_degrees/update', this.data.account_degree[index]).then(response => {
        if(response.data === true){
          this.data.account_degree[index].edit_flag = false
          this.prevDegreeEditIndex = null
          this.retrieveRequest()
        }
      })
    },
    addBackgroundFlag(){
      this.addFlag = !this.addFlag
    },
    addDegree(){
      this.newBackground.account_id = this.user.userID
      this.APIRequest('account_degrees/create', this.newBackground).then(response => {
        this.addBackgroundFlag()
        this.retrieveRequest()
      })
    },
    editPersonalInformation(){
      this.personalInfoFlag = !this.personalInfoFlag
    },
    updatePersonalInformation(){
      this.APIRequest('account_informations/update', this.data.account_information).then(response => {
        if(response.data === true){
          this.editPersonalInformation()
        }else{
          //
        }
      })
    },
    readURL(input){
      if (input.files && input.files[0]){
        var reader = new FileReader()
        reader.onload = function (e) {
          $('#profile_picture')
          .attr('src', e.target.result)
          .width(150)
          .height(200)
        }
        reader.readAsDataURL(input.files[0])
      }
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
  .profile-image-settings{
    position: relative;
  }
  .profile-image-settings i{
    font-size: 150px;
    color: #3f0050;
    padding-top: 50px;
  }
  .profile-image-settings img{
    height: 150px;
    width: 150px;
    border-radius: 25px;
  }
  .profile-image-content {
    opacity: 1;
    display: block;
    width: 100%;
    height: auto;
    transition: .5s ease;
    backface-visibility: hidden;
  }

  .middle {
    transition: .5s ease;
    opacity: 0;
    position: absolute;
    right: -40%;
    top: -10%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    text-align: center;
  }

  .profile-image-settings i:hover, .profile-image-settings img{
    cursor: pointer;
  }
  .profile-image-settings:hover .image {
    opacity: 0.3;
    cursor: pointer;
  }

  .profile-image-settings:hover .middle {
    opacity: 1;
    cursor: pointer;
  }

  .middle i{
    font-size: 16px !important;
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
    /*FCCD04*/
    height: 50px;
    width: 100%;
    float: left;
    background: #eee;
    color: #333;
    font-size: 18px;
    font-weight: 500;
    text-transform: uppercase;
  }
  .header i{
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
  .content label i, .item-half label i{
    padding-right: 10px;
    padding-left: 10px;
    color: #FCCD04;
  }
  .degree-holder{
    width: 100%;
    float: left;
    min-height: 100px;
    overflow-y: hidden;
    margin-top: 5px;
  }
  .degree-holder:hover{
    cursor: pointer;
    border-bottom: solid 1px #028170;
  }
  .degree-icon{
    width: 30%;
    float: left;
    min-height: 100px;
    text-align: center;
    overflow-y: hidden;
  }
  .degree-icon i{
    font-size: 16px;
  }
  .item-half{
    width: 100%;
    float: left;
    min-height: 20px;
    overflow-y: hidden;
  }
  .course{
    font-size: 14px;
    font-weight: 600;
  }
  .details{
    width: 98%;
    margin-left: 2%;
  }
  .date{
    width: 20%;
    margin-right: 1%;
    float: left;
  }
  .input-group{
    margin-top: 5px;
    font-size: 13px !important;
  }
  .input-group-addon{
    width: 125px;
    font-size: 13px !important;
    background: #3f0050;
    color: #fff;
  }
  .input-group-addon2{
    width: 150px;
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
