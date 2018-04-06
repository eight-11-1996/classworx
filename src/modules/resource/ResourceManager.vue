<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Resources</b></label>
        </div>
        <div class="items-display">
          <label v-if="semesters.length > 0">Semesters</label>
          <select v-if="semesters.length > 0" v-on:change="filterSemester()" v-model="semesterId">
            <option v-for="item, index in semesters"  v-bind:value="item.id">{{item.description}}</option>
          </select>
          <label v-if="courses.length > 0">Courses</label>
          <select v-if="courses.length > 0" v-on:change="filterCourses()" v-model="parameter">
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
        <div class="add">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div>
      </div>
      <div class="table-result row">
        <div v-for="filename, index in filenames" class="files-card">
          <div class="card-container">
            <div class="center-area">
              <i class="fa fa-file-word-o" v-if="index%3 === 0"></i>
              <i class="fa fa-file-powerpoint-o" v-if="index%3=== 1"></i>
              <i class="fa fa-file-excel-o" v-if="index%3 === 2"></i>
            </div>
          </div>
          <div class="card-footer" v-on:mouseover="setActiveIndex(index)">
            <span v-show="filename.edit == false"><i class="fa fa-eye" data-toggle="modal" data-target="#viewerModal" data-hover="tooltip" data-placement="top" title="Viewers"></i></span>
            <span v-show = "filename.edit == false">
              <label @dblclick = "filename.edit = true" class="file-name"> &nbsp;{{filename.title}} </label>
            </span>
            <span>
              <input class="card-form" v-show = "filename.edit == true" v-model = "filename.title"
                v-on:blur= "filename.edit=false; $emit('update')"
                @keyup.enter = "filename.edit=false; $emit('update')">
            </span>
          </div>
        </div>
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
    <div class="modal fade" id="viewerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Viewed by:</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="table-result">
              <ul v-for="item, index in viewers">
                {{item}}
              </ul>
            </table>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="Submit()" v-if="closeFag == false">update</button>
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="fileCount = 0">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="upload-body">
             <!--  <form>
                 <div class="upload-form">
                    <input type="file" multiple class="btn" @change="fileCount = $event.target.files.length, add($event.target.files)" >
                    <div v-if="fileCount === 0" class="center-area"><i class="fa fa-upload"></i> Drag and Drop Files</div>
                    <div v-else class="center-area">{{fileCount}}</div>
                  </div>
               </form> -->
                <div>
                  <span>Resource Type</span>
                  <input type="text" name="type" class="form-control" placeholder="Type"></br>
                  <span>Resource Title</span>
                  <input type="text" name="type" class="form-control" placeholder="Title">
                </div>
            </div>
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
      modalTitle: 'Add Resource',
      parameter: this.$route.params.courseId,
      data: [],
      semesters: [],
      semesterId: null,
      courses: [],
      courseId: this.$route.params.courseId,
      method: 'resources',
      methodId: 'course_id',
      quizzes: [],
      errorMessage: null,
      closeFag: false,
      description: null,
      type: null,
      start: null,
      end: null,
      timer: null,
      modalView: null,
      modalInput: {
        id: null,
        description: null,
        type: null,
        start: null,
        end: null,
        timer: null
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
      filenames: [{'title': 'one value', 'edit': false},
            {'title': 'one value', 'edit': false},
            {'title': 'one value', 'edit': false},
            {'title': 'one value', 'edit': false},
            {'title': 'one value', 'edit': false},
            {'title': 'one value', 'edit': false},
            {'title': 'one value', 'edit': false},
            {'title': 'otro titulo', 'edit': false}],
      editedTodo: null,
      activeIndex: null,
      fileCount: 0,
      viewers: ['Kennette Canales', 'June Ray Mag-usara', 'Fretzel Sanchez']
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
      this.createParameter(this.parameter)
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
        this.retrieveRequest(true, param)
      }else if(parseInt(this.parameter) > 0){
        alert('Incorrect Parameter Supplied')
      }
    },
    retrieveRequest(flag, parameter){
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        if(response.data === null){
          this.quizzes = []
        }else{
          this.quizzes = response.data
        }
        this.data = this.quizzes
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
      formData.append(this.methodId, this.parameter)
      formData.append('description', this.description)
      formData.append('type', this.type)
      formData.append('start', this.start)
      formData.append('end', this.end)
      formData.append('timer', this.timer)
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', formData).then(response => {
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          this.createParameter(this.parameter)
        }else{
          this.errorMessage = response.error.message
        }
      })
    },
    deleteRequest(index){
      let parameter = {
        id: index
      }
      this.APIRequest(this.method + '/delete', parameter).then(response => {
        if(response.data === null){
          // Error Message
        }else{
          this.createParameter(this.parameter)
        }
      })
    },
    validation(){
      if(this.description === null || this.type === null || this.start === null || this.end === null || this.timer === null){
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
      if(this.modalInput.description !== null){
        formData.append('description', this.modalInput.description)
      }
      if(this.modalInput.type !== null){
        formData.append('type', this.modalInput.type)
      }
      if(this.modalInput.start !== null){
        formData.append('start', this.modalInput.start)
      }
      if(this.modalInput.end !== null){
        formData.append('end', this.modalInput.end)
      }
      if(this.modalInput.timer !== null){
        formData.append('timer', this.modalInput.timer)
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
    editName(name){
      this.editLoop = name
    },
    setActiveIndex(index){
      this.activeIndex = index
    },
    log(){
      console.log(this.activeIndex)
    },
    editTodo(todo){
      this.editedTodo = todo
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
.file-name{
  cursor: text;
}
.card-form{
  margin-top: 0 !important;
  height: 25px !important;
  width: 100%;
  padding-left: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.fa-file-excel-o{
  color: #028170;
}

.fa-file-powerpoint-o{
  color: #dc4c4c;
}

.fa-file-word-o{
  color: #4c4cff;
}

.center-area{
  text-align: center;
  padding-top: 10%;
}
.card-container{
  height: 70%;
  text-align: center;
  font-size: 100px;
}
.files-card{
  width: 15em;
  box-shadow: 0 4px 8px 0 #3f0040;
  height: 18em;
  margin: 10px;
  border-radius: 5px;
}

.files-card:hover{
  box-shadow: 2px 4px 8px 2px #3f0050;
  font-weight: bold;
  cursor: pointer;
}

.card-footer{
  text-align: center;
  margin-top: 20px;
}
.upload-body{
  margin-top: 5em;
}

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
}

.fa-eye:hover{
  font-weight: bold;
  color: #028170;
}
.fa-eye{
  color: #3f0050;
}
.fa-upload{
  font-size: 50px;
  color: #3f0050;
}

td button i{
  padding-right: 10px;
}
thead{
  font-weight: 700;
}

</style>
