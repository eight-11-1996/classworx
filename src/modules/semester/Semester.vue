<template>
  <div class="row">
      <div class="col-lg-3 mx-auto">
        <label>Show</label>
        <select class="table-selector">
          <option value="5">5</option>
          <option value="5">10</option>
          <option value="5">15</option>
          <option value="5">20</option>
          <option value="5">25</option>
        </select>
        <label>Entries</label>
      </div>
      <div class="col-lg-4">
        <h3>My <b>Semesters</b></h3>
      </div>
<!--       <div class="3">
        <input type="text" name="search" class="table-search">
      </div> -->
      <div class="col-lg-3 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
      </div>
      <div class="col-lg-11 mx-auto history">
        <table class="table table-responsive table-bordered">
          <thead>
            <tr>
              <td>Description</td>
              <td>Grades Setting</td>
              <td>Start Date</td>
              <td>End Date</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody v-if="semesters.length !== 0">
            <tr v-for="item, index in semesters">
              <td>{{item.description}}</td>
              <td>All Courses / Per Courses</td>
              <td>{{item.start_date}}</td>
              <td>{{item.end_date}}</td>
              <td>
                <i class="fa fa-pencil text-warning" v-on:click="editModalView(index)" data-toggle="modal" data-target="#editModal">
                </i>
                <i class="fa fa-trash text-danger" v-on:click="deleteRequest(item.id)"></i>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center" colspan="5">Click Add Semester Now!</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-lg-11 mx-auto">
        <div class="row"> 
          <div class="pull-left col-lg-4">
            <label>Showing <b></b> out of <b></b> entries</label>
          </div>
          <div class="col-lg-4">
          </div>
          <div class="pull-right col-lg-3">
            <ul class="pagination pull-right">
              <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">5</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
         </div>
        </div>
      </div>
     

    <!-- Modal 

      EDIT
    -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalView !== null">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Semester</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
            </span>
            <br v-if="errorMessage !== null">
            <label>Description</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modal.description" v-model="modalInput.description">
            <br>
            <label>Start Date</label>
            <br>
            <input type="date" class="form-control" v-bind:placeholder="modal.start_date" v-model="modalInput.startDate">
            <br>
            <label>End Date</label>
            <br>
            <input type="date" class="form-control" v-bind:placeholder="modal.end_date" v-model="modalInput.endDate">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateRequest()" v-if="closeFag == false">update</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>



    <!-- Modal 

      ADD
    -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>{{modalTitle}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
            </span>
            <br v-if="errorMessage !== null">
            <br>
            <label>Description</label>
            <br>
            <input type="text" class="form-control" placeholder="Description of the Semester" v-model="description">
            <br>
            <label>Start Date</label>
            <br>
            <input type="date" class="form-control" v-model="startDate">
            <br>
            <label>End Date</label>
            <br>
            <input type="date" class="form-control" v-model="endDate">
            <br>
            <label>Grades Settings</label>
            <br>
            <select class="form-control">
              <option value="1">The same grades settings of all courses</option>
              <option value="2">Grade setting is different per courses</option>
            </select>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Submit</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
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
      modalTitle: 'Add Semester',
      semesters: [],
      errorMessage: null,
      closeFag: false,
      description: null,
      startDate: null,
      endDate: null,
      accent: [],
      modalView: null,
      modalInput: {
        id: null,
        description: null,
        startDate: null,
        endDate: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequest(){
      let parameter = {
      }
      this.APIRequest('semesters/retrieve', parameter).then(response => {
        this.semesters = response.data
      })
    },
    submit(){
      if(this.validation() === true){
        this.errorMessage = null
        this.createRequest()
      }else{
        this.errorMessage = 'Please fillup the required information'
      }
    },
    createRequest(){
      let formData = new FormData()
      formData.append('account_id', this.user.userID)
      formData.append('description', this.description)
      formData.append('start_date', this.startDate)
      formData.append('end_date', this.endDate)
      axios.post(CONFIG.BACKEND_URL + '/semesters/create', formData).then(response => {
        if(response.data !== null){
          ROUTER.push('/semesters')
        }else{
          this.errorMessage = response.error.message
        }
      })
    },
    deleteRequest(index){
      let parameter = {
        id: index
      }
      this.APIRequest('semesters/delete', parameter).then(response => {
        ROUTER.go('/semesters')
      })
    },
    validation(){
      if(this.description === null || this.startDate === null || this.endDate === null){
        return false
      }else{
        return true
      }
    },
    editModalView(index){
      this.modalView = this.semesters[index]
      this.modalInput.id = this.modalView.id
    },
    updateRequest(){
      let formData = new FormData()
      if(this.modalInput.description !== null){
        formData.append('description', this.modalInput.description)
      }
      if(this.modalInput.startDate !== null){
        formData.append('start_date', this.modalInput.startDate)
      }
      if(this.modalInput.description !== null){
        formData.append('end_date', this.modalInput.endDate)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/semesters/update', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.closeFag = true
          ROUTER.go('/semesters')
        }else{
          this.closeFag = false
        }
      })
    }
  }
}
</script>
<style>
.result-holder{
  color: #000;
  margin-bottom: 5%;
  height: 150px;
}
mark{
  background: none;
}
form{
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -100px;
  margin-left: -250px;
  width: 500px;
  height: 120px;
  border: 4px dashed;
  border-radius: 3px;
}
form p{
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 120px;
}
form input{
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
}
  .history{
    margin-top: 25px;
  }

  .bg-primary{
    background: #009900 !important; 
  }

  .modal-title i{
    padding-right: 10px;
  }

  .form-control{
    height: 45px !important;
  }

  td button i{
    padding-right: 10px;
  }
  thead{
    font-weight: 700;
  }
</style>
