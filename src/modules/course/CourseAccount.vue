<template>
  <div>
      <div class="module-header">
        <div class="title text-warning" style="width: 100%">
          <label v-on:click="redirect('/' + headerMethod + '/' + parameter)" class="text-underline"><b>{{headerTitle}}</b></label><label v-if="header !== null">/{{header.description}}</label>
        </div>
        <div class="items-display pull-right" style="width: 90%">
          <label>Show</label>
          <select v-model="selectedTotalItems" v-on:change="filter()">
            <option value="5">5</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="75">75</option>
            <option value="100">100</option>
          </select>
        </div>
  <!--       <div class="3">
          <input type="text" name="search" class="table-search">
        </div> -->
        <div class="add" style="width: 10%">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div>
      </div>
      <div class="table-result">
        <table class="table table-responsive table-bordered">
          <thead>
            <tr>
              <td>Account</td>
              <td>Account Informations</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody v-if="data.length > 0">
            <tr v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)">
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center empty-table" colspan="5" data-toggle="modal" data-target="#myModal" v-if="parameter !== 'default'">Click to Add Question Now!</td>     
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
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieveHeader()
    this.defaultParameter()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Question',
      parameter: this.$route.params.id,
      data: [],
      header: null,
      headerTitle: null,
      headerMethod: null,
      method: 'questions',
      methodId: 'quiz_id',
      errorMessage: null,
      closeFag: false,
      type: null,
      question: null,
      prevEditIndex: null,
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
      }
    }
  },
  components: {
    'multiple-choice': require('modules/question/MultipleChoice.vue'),
    'multiple-answers': require('modules/question/MultipleAnswers.vue'),
    'short-answer': require('modules/question/ShortAnswer.vue'),
    'long-answer': require('modules/question/LongAnswer.vue')
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveHeader(value){
      let method = window.location.href.split('/')[4]
      this.headerMethod = ''
      this.headerTitle = 'Enrolled Accounts'
      this.methodId = 'quiz_id'
      let parameter = {
        'condition': [{
          'value': this.parameter,
          'column': 'id',
          'clause': '='
        }]
      }
      this.APIRequest(this.headerMethod + '/retrieve', parameter).then(response => {
        this.header = response.data[0]
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
          this.data = []
        }else{
          this.data = response.data
        }
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
      if(this.$children.length > 0){
        this.$children[0].getAnswer()
        if(this.validation() === true){
          this.errorMessage = null
          this.createRequest()
        }else{
          if(this.$children[0].answer === null){
            this.errorMessage = this.$children[0].errorMessage
          }else if(this.$children[0].validation() === false){
            this.errorMessage = this.$children[0].errorMessage
          }else{
            this.errorMessage = 'Please FILLUP the required informations'
          }
        }
      }else{
        this.errorMessage = 'Please SELECT the type of the  question'
      }
    },
    createRequest(){
      let formData = new FormData()
      formData.append(this.methodId, this.parameter)
      formData.append('question', this.question)
      formData.append('type', this.type)
      formData.append('answer', this.$children[0].answer)
      let newId = null
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', formData).then(response => {
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          newId = response.data.data
          if(this.type === 'multiple_choice' || this.type === 'multiple_answers'){
            this.createRequestQuestionOptions(newId)
          }else{
            this.createParameter(this.parameter)
          }
        }else{
          this.errorMessage = response.error.message
        }
      })
    },
    createRequestQuestionOptions(newId){
      let parameter = {
        'question_id': newId,
        'options': this.$children[0].data
      }
      this.APIRequest('question_options/create', parameter).then(response => {
        if(response.data.data !== null){
          this.$children[0].message = 'Successfully Added!'
          this.$children[0].errorMessage = null
        }else{
          this.$children[0].message = null
          this.$children[0].errorMessage = response.data.message
        }
      }).done(() => {
        this.createParameter(this.parameter)
      })
    },
    deleteRequest(index){
      let parameter = {
        id: index,
        'value': this.parameter,
        'column': this.methodId
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
      if(this.question === null || this.type === null || this.$children[0].answer === null || this.$children[0].validation() === false){
        return false
      }else{
        return true
      }
    },
    edit(index){
      if(this.prevEditIndex === null){
        this.data[index].edit = true
        this.prevEditIndex = index
      }else{
        if(index === this.prevEditIndex){
          this.data[index].edit = false
          this.prevEditIndex = null
        }else{
          this.data[index].edit = true
          this.data[this.prevEditIndex].edit = false
          this.prevEditIndex = index
        }
      }
    },
    cancel(index){
      this.data[index].edit = false
      this.prevEditIndex = null
    },
    update(data, index){
      let parameter = {
        'data': data
      }
      this.APIRequest(this.method + '/update', parameter).then(response => {
        if(data.type === 'short_answer' || data.type === 'long_answer'){
          this.data[index].edit = false
          this.prevEditIndex = null
          this.createParameter(this.parameter)
        }
      }).done(() => {
        // Multiple Answers and Multiple Choice Template
        if(data.type === 'multiple_answers' || data.type === 'multiple_choice'){
          this.APIRequest('question_options/update', data).done(response => {
            if(response.data === true){
              this.data[index].edit = false
              this.prevEditIndex = null
              this.createParameter(this.parameter)
            }else{
              //
            }
          })
        }else{
          // other tempalte
        }
      })
    },
    toggle(index, order){
      if(this.data[index].type === 'multiple_answers'){
        if(this.data[index].answer.includes(',' + order + ',')){
          let text = ',' + order + ','
          let newAnswer = this.data[index].answer.replace(text, ',')
          this.data[index].answer = newAnswer
        }else{
          this.data[index].answer += order + ','
        }
      }else if(this.data[index].type === 'multiple_choice'){
        this.data[index].answer = order
      }else{
        //
      }
    },
    remove(index, indexOption){
      this.data[index].question_options.splice(indexOption, 1)
    },
    add(index){
      if(this.data[index].question_options.length > 0){
        let parameter = {
          'question_id': this.data[index].id,
          'description': null,
          'order': this.data[index].question_options.length + 1
        }
        this.data[index].question_options.push(parameter)
      }else{
        this.data[index].question_options = []
        let parameter = {
          'question_id': this.data[index].id,
          'description': null,
          'order': 1
        }
        this.data[index].question_options.push(parameter)
      }
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
td .question, td .answer, td .options, .options .option-item{
  float: left;
  width: 100%;
}
.editable-tr{
  cursor: pointer;
}
.question{
  font-size: 14px !important;
  margin-bottom: 10px;
}
.option-item{
  margin-bottom: 10px;
}
.option-item i{
  font-size: 24px !important;
}
.edit-option i{
  width: 5%;
  text-align: center;
  float: left;
  padding-top: 10px;
}
.edit-option input{
  width: 86%;
  margin: 0 2% 0 2%;
  float: left;
}

</style>
