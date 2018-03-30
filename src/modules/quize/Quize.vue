<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label>My <b>Quizes</b></label>
        </div>
        <div class="items-display pull-right">
          <label v-if="semesters.length > 0">Semesters</label>
          <select v-if="semesters.length > 0" v-on:change="filterSemester()" v-model="semesterId">
            <option v-for="item, index in semesters"  v-bind:value="item.id">{{item.description}}</option>
          </select>
          <label v-if="courses.length > 0">Courses</label>
          <select v-if="courses.length > 0" v-on:change="filterCourses()" v-model="courseId">
            <option v-for="item, index in courses"  v-bind:value="item.id">{{item.description}}</option>
          </select>
          <label>Show</label>
          <select v-model="selectedTotalItems" v-on:change="filter()">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
          </select>
        </div>
  <!--       <div class="3">
          <input type="text" name="search" class="table-search">
        </div> -->
        <div class="add">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div>
      </div>
      <div class="table-result">
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
          <tbody v-if="data.length > 0">
            <tr v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)">
              <td>{{item.code}}</td>
              <td>{{item.description}}</td>
              <td>{{item.units}}</td>
              <td>{{item.time_start + '-' + item.time_end + ' (' + item.days + ')'}}</td>
              <td>
                <i class="fa fa-clipboard text-primary" v-on:click="redirect()"></i>
                <i class="fa fa-pencil text-warning" v-on:click="editModalView(index)" data-toggle="modal" data-target="#editModal">
                </i>
                <i class="fa fa-trash text-danger" v-on:click="deleteRequest(item.id)"></i>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center empty-table" colspan="5" data-toggle="modal" data-target="#myModal" >Click to Add Quizes Now!</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="table-footer">
        <div class="items-total pull-left">
          <label>Showing <b>{{display.current}}</b> out of <b>{{display.total}}</b> entries</label>
        </div>
        <div class="items-pager">
          <ul class="pagination pull-right">
            <li class="page-item" v-bind:class="{'disabled': display.prevFlag === true}" v-on:click="pager(1, null)"><span class="page-link">Previous</span></li>
            <li class="page-item" v-for="i, index in display.pager" v-on:click="pager(2, index)"><span class="page-link" v-bind:class="'pager-active-' + index">{{index + 1}}</span></li>
            <li class="page-item" v-bind:class="{'disabled': display.nextFlag === true}" v-on:click="pager(3, null)"><span class="page-link">Next</span></li>
          </ul>
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
            <label>Course Code</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modalView.code" v-model="modalInput.code">
            <br>
            <label>Course Description</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modalView.description" v-model="modalInput.description">
            <br>
            <label>Course Units</label>
            <br>
            <input type="text" class="form-control" v-bind:placeholder="modalView.units" v-model="modalInput.units">
            <br>
            <label>Time Start</label>
            <br>
            <input type="date" class="form-control" v-bind:placeholder="modalView.time_start" v-model="modalInput.timeStart">
            <br>
            <label>End Date</label>
            <br>
            <input type="date" class="form-control" v-bind:placeholder="modalView.time_end" v-model="modalInput.timeEnd">
            <br>
            <label>Days</label>
            <br>
            <input type="date" class="form-control" v-bind:placeholder="modalView.days" v-model="modalInput.days">
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
            <label>Course Code</label>
            <br>
            <input type="text" class="form-control" placeholder="Couse Code" v-model="code">
            <br>
            <label>Couse Description</label>
            <br>
            <input type="text" class="form-control" placeholder="Course Description" v-model="description">
            <br>
            <label>Couse Units</label>
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
            <label>Days</label>
            <br>
            <input type="text" class="form-control" v-model="days">
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
    this.retrieveRequestSemester()
    this.defaultParameter()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Course',
      parameter: this.$route.params.semesterId,
      data: [],
      semesters: [],
      semesterId: this.$route.params.semesterId,
      courses: [],
      courseId: this.$route.params.courseId,
      quizes: [],
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
      },
      selectedTotalItems: null,
      totalDisplay: 5,
      currentTotalIndex: 0,
      displayIndexAdder: 0,
      display: {
        current: 1,
        total: null,
        pager: null,
        prevFlag: true,
        nextFlag: true,
        currentPager: 1,
        pagerActive: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequestSemester(){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        }]
      }
      this.APIRequest('semesters/retrieve', parameter).then(response => {
        this.semesters = response.data
      })
    },
    filterSemester(){
      this.retrieveCourse(this.semesterId)
    },
    retrieveCourse(value){
      let parameter = {
        'condition': [{
          'value': value,
          'column': 'semester_id',
          'clause': '='
        }]
      }
      this.APIRequest('courses/retrieve', parameter).then(response => {
        this.courses = response.data
      })
    },
    filterCourses(){
      this.createParameter(this.courseId)
    },
    createParameter(value){
      let parameter = {
        'condition': [{
          'value': value,
          'column': 'course_id',
          'clause': '='
        }]
      }
      this.retrieveRequest(true, parameter)
    },
    defaultParameter(){
      let param = null
      if(this.parameter !== 'default'){
        param = {
          'condition': [{
            'value': this.parameter,
            'clause': '=',
            'column': 'course_id'
          }]
        }
        this.retrieveRequest(true, param)
      }else if(parseInt(this.parameter) > 0){
        alert('Incorrect Parameter Supplied')
      }
    },
    retrieveRequest(flag, parameter){
      this.APIRequest('quizes/retrieve', parameter).then(response => {
        this.quizes = response.data
        this.data = this.quizes
      }).done(() => {
        if(flag === true){
          this.initDisplayer()
          setTimeout(() => {
            this.makeActive(0)
          }, 100)
        }
      })
    },
    initDisplayer(){
      this.display.total = this.data.length
      let pagerSize = 0
      this.display.currentPager = 0
      this.displayIndexAdder = 0
      if(this.display.total > this.totalDisplay){
        pagerSize = ((this.display.total % this.totalDisplay) !== 0) ? (parseInt(this.display.total / this.totalDisplay) + 1) : parseInt(this.display.total / this.totalDisplay)
        this.display.current = this.totalDisplay
        if(pagerSize > 1){
          this.display.nextFlag = false
        }else{
          this.display.nextFlag = true
        }
      }else{
        pagerSize = 1
        this.display.currentPager = 1
        this.display.current = this.display.total
        this.display.nextFlag = true
      }
      this.currentTotalIndex = 0
      this.display.pager = new Array(pagerSize)
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
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          this.createParameter(this.semesterId)
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
        if(response.data === null){
          // Error Message
        }else{
          this.createParameter(this.semesterId)
        }
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
      this.modalView = this.data[index]
      this.modalInput.id = this.modalView.id
    },
    updateRequest(){
      let formData = new FormData()
      formData.append('id', this.modalInput.id)
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
        if(response.data.data === true){
          $('#editModal').modal('hide')
          this.createParameter(this.semesterId)
        }
      })
    },
    filter(){
      this.currentTotalIndex = 0
      this.displayIndexAdder = 0
      this.data = this.data
      this.totalDisplay = this.selectedTotalItems
      this.display.pagerActive = null
      this.display.currentPager = 0
      if(this.display.total < this.selectedTotalItems){
        this.display.current = this.display.total
        this.display.pager = new Array(1)
        this.display.nextFlag = true
        this.display.prevFlag = true
      }else{
        this.display.current = this.selectedTotalItems
        this.display.nextFlag = false
        let pagerSize = 0
        pagerSize = ((this.display.total % this.selectedTotalItems) !== 0) ? (parseInt(this.display.total / this.selectedTotalItems) + 1) : parseInt(this.display.total / this.selectedTotalItems)
        this.display.current = this.selectedTotalItems
        if(pagerSize > 1){
          this.display.nextFlag = false
        }else{
          this.display.nextFlag = true
          this.display.prevFlag = true
        }
        this.display.pager = new Array(pagerSize)
      }
      this.makeActive(0)
    },
    pager(section, index){
      if(section === 1 && this.display.prevFlag === false){
        this.display.currentPager -= 1
        this.makeActive(this.display.currentPager)
      }else if(section === 3 && this.display.nextFlag === false){
        this.display.currentPager += 1
        this.makeActive(this.display.currentPager)
      }else if(section === 2){
        this.display.currentPager = index
        this.makeActive(index)
      }else{
        //
      }
      this.displayIndexAdder = this.display.currentPager
      if((this.display.pager.length - 1) === this.displayIndexAdder){
        this.display.current = this.display.total
      }else if(this.display.pager.length > this.displayIndexAdder && this.displayIndexAdder > 0){
        this.display.current = (this.totalDisplay * (this.displayIndexAdder + 1))
      }else{
        this.display.current = this.totalDisplay
      }
      this.setPrevFlag()
      this.setNextFlag()
    },
    setPrevFlag(){
      if(this.display.currentPager !== 0){
        this.display.prevFlag = false
      }else{
        this.display.prevFlag = true
      }
    },
    setNextFlag(){
      if(this.display.pager.length > (this.display.currentPager + 1)){
        this.display.nextFlag = false
      }else{
        this.display.nextFlag = true
      }
    },
    makeActive(index){
      $('.pager-active-' + index).css({'background': '#009900', 'color': 'white', 'border': 'solid 1px #009900'})
      if(this.display.pagerActive !== index && this.display.pagerActive !== null){
        $('.pager-active-' + this.display.pagerActive).css({'background': 'inherit', 'color': '#009900', 'border': 'solid 1px #ddd'})
        this.display.pagerActive = index
      }else if(this.display.pagerActive === null){
        this.display.pagerActive = index
      }
    }
  }
}
</script>
<style>

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
