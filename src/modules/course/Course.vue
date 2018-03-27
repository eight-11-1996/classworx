<template>
  <div class="row">
      <div class="col-lg-3 mx-auto">
        <label>Show</label> &nbsp;
        <select class="table-selector table-selector-large-text" @change="retrieveRequestCourse()" v-model="semesterId">
          <option v-bind:value="item.id" v-for="item, index in semesters" >{{item.description}}</option>
        </select>
      </div>
      <div class="col-lg-4">
        <h3>My <b>Courses</b></h3>
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
              <td>Code</td>
              <td>Description</td>
              <td>Units</td>
              <td>Schedule</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody v-if="courses.length !== 0">
            <tr v-for="item, index in courses">
              <td>{{item.code}}</td>
              <td>{{item.description}}</td>
              <td>{{item.units}}</td>
              <td>{{item.time_start + ' - ' + item.time_end + ' ' + item.days}}</td>
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
            <br>
            <label>Code</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modal.code" v-model="modalInput.code">
            <br>
            <label>Description</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modal.description" v-model="modalInput.description">
            <br>
            <label>Units</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modal.units" v-model="modalInput.units">
            <br>
            <label>Time Start</label>
            <br>
            <input type="time" class="form-control" v-bind:placeholder="modal.time_start" v-model="modalInput.timeStart">
            <br>
            <label>Time End</label>
            <br>
            <input type="time" class="form-control" v-bind:placeholder="modal.time_end" v-model="modalInput.timeEnd">
            <br>
            <label>Recurring Days</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modal.days" v-model="modalInput.days">
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
            <label>Code</label>
            <br>
            <input type="text" class="form-control" placeholder="Course Code" v-model="code">
            <br>
            <label>Description</label>
            <br>
            <input type="text" class="form-control" placeholder="Course Description" v-model="description">
            <br>
            <label>Units</label>
            <br>
            <input type="text" class="form-control" placeholder="Course Units" v-model="units">
            <br>
            <label>Time Start</label>
            <br>
            <input type="time" class="form-control" v-model="timeStart">
            <br>
            <label>Time End</label>
            <br>
            <input type="time" class="form-control" v-model="timeEnd">
            <br>
            <label>Recurring Days</label>
            <br>
            <input type="text" class="form-control" placeholder="MTWTHFSSAT" v-model="days">
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
      modalTitle: 'Add Course',
      semesters: [],
      semesterId: null,
      courses: [],
      errorMessage: null,
      closeFag: false,
      code: null,
      description: null,
      units: null,
      timeStart: null,
      timeEnd: null,
      days: null,
      modalView: null,
      modalInput: {
        id: null,
        code: null,
        description: null,
        units: null,
        timeStart: null,
        timeEnd: null,
        days: null
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
    retrieveRequestCourse(){
      let parameter = {
        'condition': [{
          'value': this.semesterId,
          'column': 'semester_id',
          'clause': '='
        }]
      }
      this.APIRequest('courses/retrieve', parameter).then(response => {
        this.courses = response.data
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
      formData.append('semester_id', this.semesterId)
      formData.append('code', this.code)
      formData.append('description', this.description)
      formData.append('units', this.units)
      formData.append('time_start', this.timeStart)
      formData.append('time_end', this.timeEnd)
      formData.append('days', this.days)
      axios.post(CONFIG.BACKEND_URL + '/courses/create', formData).then(response => {
        if(response.data !== null){
          ROUTER.push('/courses')
        }else{
          this.errorMessage = response.error.message
        }
      })
    },
    deleteRequest(index){
      let parameter = {
        id: index
      }
      this.APIRequest('courses/delete', parameter).then(response => {
        ROUTER.go('/courses')
      })
    },
    validation(){
      if(this.code === null || this.description === null || this.units === null || this.timeStart === null || this.timeEnd === null || this.days === null){
        return false
      }else{
        return true
      }
    },
    editModalView(index){
      this.modalView = this.courses[index]
      this.modalInput.id = this.modalView.id
    },
    updateRequest(){
      let formData = new FormData()
      if(this.modalInput.code !== null){
        formData.append('code', this.modalInput.code)
      }
      if(this.modalInput.description !== null){
        formData.append('description', this.modalInput.description)
      }
      if(this.modalInput.units !== null){
        formData.append('units', this.modalInput.units)
      }
      if(this.modalInput.timeStart !== null){
        formData.append('time_start', this.modalInput.timeStart)
      }
      if(this.modalInput.timeEnd !== null){
        formData.append('time_end', this.modalInput.timeEnd)
      }
      if(this.modalInput.days !== null){
        formData.append('days', this.modalInput.days)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/courses/update', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.closeFag = true
          ROUTER.go('/courses')
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
