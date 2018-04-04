<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Courses</b></label>
        </div>
        <div class="items-display pull-right">
          <label v-if="semesters.length > 0">Semesters</label>
          <select v-if="semesters.length > 0" v-on:change="filterSemester()" v-model="filterIndex">
            <option v-for="item, index in semesters"  v-bind:value="index">{{item.description}}</option>
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
              <td>Code / Units</td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">Description</td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">Grade Settings</td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">Schedule</td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">Actions</td>
            </tr>
          </thead>
          <tbody v-if="data.length > 0">
            <tr v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)">
              <td v-if="gradeSetting === 0 || gradeSetting === 1">{{item.code + '- (' + item.units + ')'}}</td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">{{item.description}}</td>
              <td v-if="gradeSetting === 0">
                <label>All Courses have the same Grade Settings</label>
                <i class="fa fa-eye pull-right action-link text-primary" data-hover="tooltip" data-placement="top" title="Edit Grade Settings" v-on:click="redirect('/semesters')"></i>
              </td>
              <td v-if="gradeSetting === 1">
                <i class="fa fa-cog pull-right action-link text-primary" data-hover="tooltip" data-placement="top" title="Edit Grade Settings" v-on:click="editGradeSettings(index)"></i>
                <label v-if="item.grade_setting_flag === false"><b>General Settings (100%)</b>
                  <br>
                  <label> 
                    Attendance: {{item.grade_settings_content[0].attendance_rate + '%'}}  
                    Exams: {{item.grade_settings_content[0].exams_rate + '%'}}  
                    Quizzes: {{item.grade_settings_content[0].quizzes_rate + '%'}} 
                    Projects: {{item.grade_settings_content[0].projects_rate + '%'}}
                  </label>
                  <br>
                    <label><b>Passing Percentage Settings</b></label>
                  <br>
                  <label>
                   Exams: {{item.grade_settings_content[0].passing_percentage_quizzes + '%'}}  
                   Quizzes: {{item.grade_settings_content[0].passing_percentage_exams + '%'}}
                  </label>
                </label>
                <span v-if="item.grade_setting_flag === true">
                    <br>
                    <label><b>General Settings(Total of 100%)</b></label>
                    <br>
                    <label class="text-danger" v-if="item.error_message !== null"><b>Opps!</b> {{item.error_message}}</label>
                    <div class="input-group">
                      <span class="input-group-addon">Attendance</span>
                      <input type="text" class="form-control" v-model="item.grade_settings_content[0].attendance_rate">
                    </div>
                    <div class="input-group">
                      <span class="input-group-addon">Exams</span>
                      <input type="text" class="form-control" v-model="item.grade_settings_content[0].exams_rate">
                    </div>
                    <div class="input-group">
                      <span class="input-group-addon">Quizzes</span>
                      <input type="text" class="form-control" v-model="item.grade_settings_content[0].quizzes_rate">
                    </div>
                    <div class="input-group">
                      <span class="input-group-addon">Projects</span>
                      <input type="text" class="form-control" v-model="item.grade_settings_content[0].projects_rate">
                    </div>
                    <br>
                    <label><b>Passing Percentage Settings</b></label>
                    <div class="input-group">
                      <span class="input-group-addon input-group-addon2">Exams Passing Rate</span>
                      <input type="text" class="form-control" v-model="item.grade_settings_content[0].passing_percentage_exams">
                    </div>
                    <div class="input-group">
                      <span class="input-group-addon input-group-addon2">Quizzes Passing Rate</span>
                      <input type="text" class="form-control" v-model="item.grade_settings_content[0].passing_percentage_quizzes">
                    </div>
                    <button class="btn btn-primary pull-right" style="margin-top:5px;" v-on:click="save(index)"><i class="fa fa-sync"></i>Save</button>
                </span>
              </td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">{{item.time_start + '-' + item.time_end + ' (' + item.days + ')'}}</td>
              <td v-if="gradeSetting === 0 || gradeSetting === 1">
                <i class="fas fa-file-alt text-primary action-link" v-on:click="redirect('/exams/' + item.id)" data-hover="tooltip" data-placement="top" title="View Exams"></i>
                <i class="fa fa-file-text-o text-primary action-link" v-on:click="redirect('/quizzes/' + item.id)" data-hover="tooltip" data-placement="top" title="View Quizzes"></i>
                <i class="fa fa-pencil text-warning action-link" v-on:click="editModalView(index)" data-toggle="modal" data-target="#editModal" data-hover="tooltip" data-placement="top" title="Edit Course">
                </i>
                <i class="fa fa-trash text-danger action-link" v-on:click="deleteRequest(item.id)" data-hover="tooltip" data-placement="top" title="Delete Course"></i>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center empty-table" colspan="5" data-toggle="modal" data-target="#myModal" v-if="parameter !== 'default'">Click to Add Course Now!</td>     
              <td class="text-danger text-center" colspan="5" v-else>Empty! Please Select the options above.</td>
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
      parameter: this.$route.params.id,
      filterIndex: null,
      data: [],
      gradeSetting: 0,
      semesters: [],
      semesterId: this.$route.params.id,
      courses: [],
      method: 'courses',
      methodId: 'semester_id',
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
        current: 0,
        total: 0,
        pager: null,
        prevFlag: true,
        nextFlag: true,
        currentPager: 1,
        pagerActive: null
      },
      prevGradeSettingIndex: null
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
    retrieveRequestSemesterByParameter(id){
      let parameter = {
        'condition': [{
          'value': id,
          'column': 'id',
          'clause': '='
        }]
      }
      this.APIRequest('semesters/retrieve', parameter).then(response => {
        if(response.data !== null || response.data.length > 0){
          this.gradeSetting = response.data[0].grade_setting
        }
      })
    },
    filterSemester(){
      this.gradeSetting = parseInt(this.semesters[this.filterIndex].grade_setting)
      this.createParameter(this.semesters[this.filterIndex].id)
      this.parameter = this.semesters[this.filterIndex].id
    },
    createParameter(value){
      let parameter = {
        'condition': [{
          'value': value,
          'column': this.methodId,
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
            'column': this.methodId
          }]
        }
        this.retrieveRequestSemesterByParameter(this.parameter)
        this.retrieveRequest(true, param)
      }else if(parseInt(this.parameter) > 0){
        alert('Incorrect Parameter Supplied')
      }
    },
    retrieveRequest(flag, parameter){
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        if(response.data === null){
          this.courses = []
        }else{
          this.courses = response.data
        }
        this.data = this.courses
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
      formData.append(this.methodId, this.semesterId)
      formData.append('code', this.code)
      formData.append('description', this.description)
      formData.append('units', this.units)
      formData.append('time_start', this.timeStart)
      formData.append('time_end', this.timeEnd)
      formData.append('days', this.days)
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', formData).then(response => {
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
      this.APIRequest(this.metod + '/delete', parameter).then(response => {
        if(response.data === null){
          // Error Message
        }else{
          this.createParameter(this.parameter)
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
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/update', formData).then(response => {
        if(response.data.data === true){
          $('#editModal').modal('hide')
          this.createParameter(this.parameter)
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
      $('.pager-active-' + index).css({'background': '#3f0050', 'color': 'white', 'border': 'solid 1px #3f0050'})
      if(this.display.pagerActive !== index && this.display.pagerActive !== null){
        $('.pager-active-' + this.display.pagerActive).css({'background': 'inherit', 'color': '#3f0050', 'border': 'solid 1px #ddd'})
        this.display.pagerActive = index
      }else if(this.display.pagerActive === null){
        this.display.pagerActive = index
      }
    },
    editGradeSettings(index){
      if(this.prevGradeSettingIndex === null){
        this.data[index].grade_setting_flag = true
        this.prevGradeSettingIndex = index
      }else{
        if(this.prevGradeSettingIndex === index){
          this.data[index].grade_setting_flag = false
          this.prevGradeSettingIndex = null
        }else{
          this.data[index].grade_setting_flag = true
          this.data[this.prevGradeSettingIndex].grade_setting_flag = false
          this.prevGradeSettingIndex = index
        }
      }
    },
    save(index){
      if(this.gradeValidation(index) === true){
        this.data[index].error_message = null
        let parameter = {
          'data': this.data[index].grade_settings_content[0],
          'course_id': this.data[index].id
        }
        this.APIRequest('grade_settings/update', parameter).then(response => {
          if(response.data === true || response.data !== null){
            this.createParameter(this.parameter)
          }else{
            //
          }
        })
      }else{
        this.data[index].error_message = 'General Settings must be equal to 100'
      }
    },
    gradeValidation(index){
      let quizzesRate = parseInt(this.data[index].grade_settings_content[0].quizzes_rate)
      let attendanceRate = parseInt(this.data[index].grade_settings_content[0].attendance_rate)
      let examsRate = parseInt(this.data[index].grade_settings_content[0].exams_rate)
      let projectsRate = parseInt(this.data[index].grade_settings_content[0].projects_rate)
      let totalGrade = quizzesRate + attendanceRate + projectsRate + examsRate
      if(totalGrade === 100){
        return true
      }else{
        return false
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


</style>
