<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Lesson</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Lesson</td>
              <td>Title</td>
              <td>Status</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item, index in lesson">
              <td>Lesson # {{index + 1}}</td>
              <td>{{item.title}}</td>
              <td>{{item.status}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="editLesson(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteLesson(item.id)"><i class="fa fa-trash"></i>Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>




    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalLesson !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Edit Lesson</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
            </span>
            <br v-if="errorMessage !== null">
            <br v-if="errorMessage !== null">
            <label>Title</label>
            <br> 
            <input type="text" name="title" class="form-control" v-bind:placeholder="modalLesson.title" v-model="modalLessonInput.title">
            <br>
            <label>Lesson Audio</label>
            <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupLessonAudioFile($event)" placeholder="Accent Text">
            <br>
            <br>
            <div>
              <h5>Accent Contents(Optional)</h5>
              <br>
              <label>Add Accent Text</label>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalLesson.accent_text" v-model="modalLessonInput.accentText">
              <br>
              <label>Add Accent Audio</label>
              <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupAccentAudioFile($event)" placeholder="Accent Text">
              <br>
              <label>Add Accent Video</label>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalLesson.accent_video" v-model="modalLessonInput.accentVideo">
            </div>
            <br>
            <div>
              <h5>Additional Information</h5>
              <br>
              <label>Grammar Tip</label>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalLesson.grammar_tip" v-model="modalLessonInput.grammarTip">
              <br>
              <label>Grammar Audio(Optional)</label>
              <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupGrammarAudioFile($event)" placeholder="Grammar Audio Optional">
              <br>
              <br>
              <label>Culture Tip</label>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalLesson.culture_tip" v-model="modalLessonInput.cultureTip">
              <br>
              <label>Culture Audio(Optional)</label>
              <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupCultureAudioFile($event)" placeholder="Culture Audio Optional">
              <br>
            </div>
            <div>
              <label>Lesson Status</label>
              <select v-model="modalLessonInput.status" class="form-control">
                <option class="form-control">PENDING</option>
                <option class="form-control">APPROVED</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateLesson()">Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
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
            <br v-if="errorMessage !== null">
            <label>Title</label>
            <br> 
            <input type="text" name="title" class="form-control" placeholder="Title" v-model="title">
            <br>
            <label>Lesson Audio</label>
            <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupLessonAudioFile($event)" placeholder="Accent Text">
            <br>
            <br>
            <div>
              <h5>Accent Contents(Optional)</h5>
              <br>
              <label>Add Accent Text</label>
              <input type="text" name="title" class="form-control" placeholder="Accent Text" v-model="accentText">
              <br>
              <label>Add Accent Audio</label>
              <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupAccentAudioFile($event)" placeholder="Accent Text">
              <br>
              <label>Add Accent Video</label>
              <input type="text" name="title" class="form-control" placeholder="Wistia URL" v-model="accentVideo">
            </div>
            <br>
            <div>
              <h5>Additional Information</h5>
              <br>
              <label>Grammar Tip(Optional)</label>
              <input type="text" name="title" class="form-control" placeholder="Grammar Tip" v-model="grammarTip">
              <label>Grammar Audio(Optional)</label>
              <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupGrammarAudioFile($event)" placeholder="Grammar Audio Optional">
              <br>
              <label>Culture Tip(Optional)</label>
              <input type="text" name="title" class="form-control" placeholder="Culture Tip" v-model="cultureTip">
              <br>
              <label>Culture Audio(Optional)</label>
              <input type="file" name="title" class="form-control" accept="audio/*"  @change="setupCultureAudioFile($event)" placeholder="Culture Audio Optional">
              <br>
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
    this.getLesson()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Lesson',
      title: null,
      fileCount: 0,
      accentText: null,
      accentVideo: null,
      lesson: [],
      cultureTip: null,
      grammarTip: null,
      errorMessage: null,
      closeFag: false,
      lessonFile: null,
      accentAudioFile: null,
      accentVideoFile: null,
      grammarAudioFile: null,
      cultureAudioFile: null,
      modalIndex: null,
      modalLessonInput: {
        id: null,
        title: null,
        status: null,
        accentText: null,
        accentVideo: null,
        cultureTip: null,
        grammarTip: null
      },
      modalLesson: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    setupLessonAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.lessonFile = files[0]
        this.createFile(files[0])
      }
    },
    setupAccentAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.accentAudioFile = files[0]
        this.createFile(files[0])
      }
    },
    setupGrammarAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.grammarAudioFile = files[0]
        this.createFile(files[0])
      }
    },
    setupCultureAudioFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.cultureAudioFile = files[0]
        this.createFile(files[0])
      }
    },
    setupAccentVideoFile(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.accentVideoFile = files[0]
        this.createFile(files[0])
      }
    },
    createFile(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
    },
    submit(){
      if(this.validation() === true){
        this.errorMessage = null
        this.sendRequest()
      }else{
        this.errorMessage = 'Please fillup the required information'
      }
    },
    sendRequest(){
      let formData = new FormData()
      formData.append('title', this.title)
      formData.append('lesson_file', this.lessonFile)
      formData.append('lesson_filename', this.lessonFile.name)
      if(this.grammarTip !== null){
        formData.append('grammar_tip', this.grammarTip)
      }
      if(this.cultureTip !== null){
        formData.append('culture_tip', this.cultureTip)
      }
      if(this.grammarAudioFile !== null){
        formData.append('grammar_file', this.grammarAudioFile)
        formData.append('grammar_filename', this.grammarAudioFile.name)
      }
      if(this.cultureAudioFile !== null){
        formData.append('culture_file', this.cultureAudioFile)
        formData.append('culture_filename', this.cultureAudioFile.name)
      }
      if(this.accentText !== null){
        formData.append('accent_text', this.accentText)
      }
      if(this.accentAudioFile !== null){
        formData.append('accent_audio_file', this.accentAudioFile)
        formData.append('audio_filename', this.accentAudioFile.name)
      }
      if(this.accentVideo !== null){
        formData.append('accent_video', this.accentVideo)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/lesson/create', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.result){
          this.closeFag = true
          ROUTER.go('/lesson_management')
        }else{
          this.closeFag = false
        }
      })
    },
    getLesson(){
      this.APIRequest('lesson/retrieve').then(response => {
        this.lesson = response.data
      })
    },
    editLesson(index){
      this.modalLesson = this.lesson[index]
      this.modalLessonInput.id = this.modalLesson.id
    },
    updateLesson(){
      let formData = new FormData()
      formData.append('id', this.modalLessonInput.id)
      console.log(this.modalLessonInput.status)
      if(this.modalLessonInput.status !== null){
        formData.append('status', this.modalLessonInput.status)
      }
      if(this.modalLessonInput.title !== null){
        formData.append('title', this.modalLessonInput.title)
      }
      if(this.lessonFile !== null){
        formData.append('lesson_file', this.lessonFile)
        formData.append('lesson_filename', this.lessonFile.name)
      }
      if(this.modalLessonInput.accentText !== null){
        formData.append('accent_text', this.modalLessonInput.accentText)
      }
      if(this.modalLessonInput.cultureTip !== null){
        formData.append('culture_tip', this.modalLessonInput.cultureTip)
      }
      if(this.cultureAudioFile !== null){
        formData.append('culture_file', this.cultureAudioFile)
        formData.append('culture_filename', this.cultureAudioFile.name)
      }
      if(this.modalLessonInput.grammarTip !== null){
        formData.append('grammar_tip', this.modalLessonInput.grammarTip)
      }
      if(this.grammarAudioFile !== null){
        formData.append('grammar_file', this.grammarAudioFile)
        formData.append('grammar_filename', this.grammarAudioFile.name)
      }
      if(this.accentAudioFile !== null){
        formData.append('accent_audio_file', this.accentAudioFile)
        formData.append('audio_filename', this.accentAudioFile.name)
      }
      if(this.modalLessonInput.accentVideo !== null){
        formData.append('accent_video', this.modalLessonInput.accentVideo)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/lesson/update', formData).then(response => {
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data === true){
          this.closeFag = true
          ROUTER.go('/lesson_management')
        }else{
          this.closeFag = false
        }
      })
    },
    deleteLesson(id){
      let parameter = {
        id: id
      }
      this.APIRequest('lesson/delete', parameter).then(response => {
        ROUTER.go('/lesson_management')
      })
    },
    validation(){
      if(this.title === null || this.grammarTip === null || this.cultureTip === null){
        return false
      }else{
        return true
      }
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
    background: #1caceb !important; 
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
