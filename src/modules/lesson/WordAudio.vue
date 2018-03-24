<template>
  <div class="row">
      <div class="col-lg-10 mx-auto">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Audio</button>
      </div>
      <div class="col-lg-10 mx-auto history">
        <table class="table table-responsive">
          <thead>
            <tr>
              <td>Word</td>
              <td>Translation</td>
              <td>Syllabus</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody v-if="audioList !== null">
            <tr v-for="item, index in audioList">
              <td>
                <button class="btn btn-primary" v-on:click="play(item.audio)">Play</button>
              </td>
              <td>{{item.word}}</td>              
              <td>{{item.translation}}</td>  
              <td>
                <button class="btn btn-primary" v-on:click="play(item.syllabus_audio)">Play</button>
              </td>     
              <td>{{item.syllabus}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="editModal(index)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i>Edit</button>
                <button class="btn btn-danger" v-on:click="deleteWord(item.id)"><i class="fa fa-trash"></i>Delete</button>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center" colspan="3">Empty</td>
            </tr>
          </tbody>
        </table>
      </div>


          <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalAudio !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Word Translation</h5>
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
              <label>Spanish Word</label>
              <br>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalAudio.word" v-model="modalAudioInput.word">
              <br>
              <label>Translation</label>
              <br>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalAudio.translation" v-model="modalAudioInput.translation">
              <br>
              <label>Syllabus</label>
              <br>
              <input type="text" name="title" class="form-control" v-bind:placeholder="modalAudio.syllabus" v-model="modalAudioInput.syllabus">
              <br>
              <label>Syllabus Audio</label>
              <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileSyllabusUpload($event)">
              <br>
              <label>Word Audio</label>
              <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileUpload($event)">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateWord()">Update</button>
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
              <label>Spanish Word</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Spanish Word" v-model="word">
              <br>
              <label>Translation</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Translation" v-model="translation">
              <br>
              <label>Syllabus</label>
              <br>
              <input type="text" name="title" class="form-control" placeholder="Syllabus" v-model="syllabus">
              <br>
              <label>Syllabus Audio</label>
              <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileSyllabusUpload($event)">
              <br>
              <label>Word Audio</label>
              <input type="file" class="form-control" name="file" accept="audio/*" @change="setUpFileUpload($event)">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Submit</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
            </div>
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
      modalTitle: 'Add Word Audio',
      audioList: [],
      audioFile: '',
      syllabusFile: null,
      translation: null,
      syllabus: null,
      attachment: [],
      word: null,
      closeFag: false,
      audioPlay: null,
      errorMessage: null,
      file: null,
      modalAudio: null,
      modalAudioInput: {
        id: null,
        word: null,
        translation: null,
        syllabus: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    setUpFileUpload(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.file = files[0]
        this.createAudio(files[0])
      }
    },
    setUpFileSyllabusUpload(e){
      let files = e.target.files || e.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.syllabusFile = files[0]
        this.createAudio(files[0])
      }
    },
    createAudio(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
      fileReader.addEventListener('load', (e) => {
        this.audioFile = e.target.result
      })
    },
    submit(){
      const formData = new FormData()
      if(this.validate() === true){
        formData.append('file', this.file)
        formData.append('audio', this.file.name)
        formData.append('word', this.word)
        formData.append('translation', this.translation)
        formData.append('syllabus', this.syllabus)
        formData.append('syllabus_file', this.syllabusFile)
        formData.append('syllabus_filename', this.syllabusFile.name)
        axios.post(CONFIG.BACKEND_URL + '/word_audio/create', formData).then(response => {
          console.log(response.data)
          if(response.data.error.status === 100){
            let error = response.data.error.message
            if(error.word !== 'undefined'){
              this.errorMessage = error.word[0]
            }else{
              this.errorMessage = JSON.stringify(error)
            }
          }else if(response.data.result){
            this.closeFag = true
            ROUTER.go('/word_audio')
          }else{
            this.closeFag = false
          }
        })
      }else{
        this.errorMessage = 'Please fill up the required information.'
      }
    },
    getLesson(){
      this.APIRequest('word_audio/retrieve').then(response => {
        this.audioList = response.data
      })
    },
    deleteWord(index){
      let parameter = {
        id: index
      }
      this.APIRequest('word_audio/delete', parameter).then(response => {
        ROUTER.go('/word_audio')
      })
    },
    play(path){
      this.audio = new Audio(CONFIG.BACKEND_URL + path)
      this.audio.play()
    },
    pause(path){
      this.audio.pause()
    },
    validate(){
      if(this.word === null || this.translation === null){
        return false
      }else{
        return true
      }
    },
    editModal(index){
      this.modalAudio = this.audioList[index]
      this.modalAudioInput.id = this.modalAudio.id
    },
    updateWord(){
      const formData = new FormData()
      formData.append('id', this.modalAudioInput.id)
      if(this.file !== null){
        formData.append('file', this.file)
        formData.append('audio', this.file.name)
      }
      if(this.syllabusFile !== null){
        formData.append('syllabus_file', this.syllabusFile)
        formData.append('syllabus_filename', this.syllabusFile.name)
      }
      if(this.modalAudioInput.word !== null){
        formData.append('word', this.modalAudioInput.word)
      }
      if(this.modalAudioInput.syllabusFile !== null){
        formData.append('syllabus', this.modalAudioInput.syllabus)
      }
      if(this.modalAudioInput.translation !== null){
        formData.append('translation', this.modalAudioInput.translation)
      }
      if(this.modalAudioInput.translation !== null){
        formData.append('translation', this.modalAudioInput.translation)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/word_audio/update', formData).then(response => {
        console.log(response.data)
        if(response.data.error.status === 100){
          let error = response.data.error.message
          if(error.word !== 'undefined'){
            this.errorMessage = error.word[0]
          }else{
            this.errorMessage = JSON.stringify(error)
          }
        }else if(response.data.data){
          this.closeFag = true
          ROUTER.go('/word_audio')
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
