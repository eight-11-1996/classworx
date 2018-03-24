<template>
  <div class="row">
      <span class="col-lg-10 mx-auto text-center welcome-title"><h3>WELCOME TO TALK FLUENT SPANISH</h3></span>
      <div class="col-lg-10 mx-auto row center-small">
        <span class="col-lg-5 col-md-6 col-sm-12">
          <span>
            <span class="header-dashboard">Introduction</span>
            <video controls>
              <source v-bind:src="introduction" type="video/mp4">
            </video>
          </span>
          <span>
            <span class="header-dashboard">How to Study</span>
            <video controls>
              <source v-bind:src="guide" type="video/mp4">
            </video>
          </span>
        </span>
        <div class="col-lg-7 5 col-md-6 col-sm-12">
          <hgroup class="speech-bubble">
            <h1>Hola</h1>
            <h2>Hello</h2>
          </hgroup>
        </div>
        <table class="table" v-if="lesson.length > 0 && lessonFlag === true">
          <thead>
            <tr>
              <h4>Available Lessons</h4>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item, index in lesson" class="hover-on checked" data-toggle="modal"data-target="#myModal" v-on:click="retrieveContent(item.id, index)" v-if="item.status === 'APPROVED' || (item.status === 'PENDING' && account.account_type === 'ADMIN')">
              <td class="td-font text-left checked" v-bind:class="{'checked': item.clicked === 1}"><i class="fa fa-check to-right checked"></i><span class="to-right">Lesson {{item.id}}. {{item.title}}</span></td>
            </tr>
          </tbody>
        </table>
        <span v-else class="empty-lesson">No Lesssons Available</span>
      </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="selectedLesson !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-volume-up header-fa-volume-up" v-on:click="playLesson(selectedLesson.lesson_audio_path + selectedLesson.lesson_audio)"></i> &nbsp; Lesson # {{selectedLesson.id}}. {{selectedLesson.title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="pause()">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="content !== null && selectedContent !== null">
              <div class="mobile-viewer">
                <span class="mobile-viewer-button1" v-on:click="setActive('content')" v-bind:class="{'page-active-content': pageActiveContent}"><h5>Content</h5></span>
                <span class="mobile-viewer-button2" v-on:click="setActive('audio')" v-bind:class="{'page-active-audio': pageActiveAudio}"><h5>Audio</h5></span>
              </div>
              <div class="content-manager" v-bind:class="{'is-active-cm': responsiveView.content}">
                <div class="lesson-content-holder">
                  <span class="col-lg-6 col-md-6 col-sm-12 col-xs-12 sentence-mobile">
                    <label class="number" v-on:click="retrieveSentence(selectedContentIndex)" v-bind:class="{'number-clicked': selectedContent.saved === 1}">{{selectedContentIndex + 1}}.</label>
                    <span v-for="item, index in contentArray" class="tooltips" v-on:click="setWord(item.word, index)" v-bind:class="{'tooltips-clicked': item.click === 1}"><label v-html="item.word + item.char + '&nbsp;'"></label>
                      <span class="tooltipTitle">{{item.translation}}</span>
                    </span>
                  </span>
                  <span  class="content-translation col-lg-6 col-md-6 col-sm-12 col-xs-12 sentence-mobile content-translation-mobile">{{selectedContentIndex + 1}}. &nbsp; {{selectedContent.translation}}</span>
                </div>
                <div class="lesson-content-tip">
                  <span class="tip-content" v-if="selectedLesson !== null">
                    <b>Grammar Tip</b>
                    <br>
                    <i class="fa fa-volume-up small-volume-icons" v-on:click="playGrammarAudio(selectedLesson.grammar_audio_path + selectedLesson.grammar_audio, 5)" v-if="selectedLesson.grammar_audio !== null"></i>
                    <br>
                    <label v-if="selectedLesson.grammar_tip !== null">{{selectedLesson.grammar_tip}}</label>
                    <br>
                    <b>Culture Tip</b>
                    <br>
                    <i class="fa fa-volume-up small-volume-icons" v-on:click="playCultureAudio(selectedLesson.culture_audio_path + selectedLesson.culture_audio, 6)" v-if="selectedLesson.culture_audio !== null"></i>
                    <br>
                    <label  v-if="selectedLesson.culture_tip !== null">{{selectedLesson.culture_tip}}</label>
                  </span>
                </div>
              </div>
            <div class="audio-manager" v-bind:class="{'is-active-am-word': responsiveView.audio, 'close-audio-manager':closeAudioManager, 'is-word-audio': isWordAudio}" v-if="audioSelected.word !== null">
              <!-- <span class="pull-right"><i class="fa fa-close audio-close" v-on:click="closeAudioManager()"></i></span> -->
              <div class="audio-holder">
                <span class="audio-content-holder">
                    <span class="audio-icons"><i class="fa fa-volume-up" v-on:click="play()"></i></span>
                    <span id="audio-content">
                      <span id="word" v-html="audioSelected.word"></span>
                      <span id="translation" v-html="audioSelected.translation"></span>
                    </span>
                </span>
                <span class="audio-visualizer" id="mp3_player">
                </span>
                <span class="audio-content-holder">
                    <span class="audio-icons"><i class="fa fa-volume-up" v-on:click="playSyllabus(audioSelected.syllabus_path + audioSelected.syllabus_audio, 0)"></i></span>
                    <span id="audio-content">
                      <span id="word" v-html="audioSelected.syllabus"></span>
                    </span>
                </span>
              </div>
              <div class="accent-holder" v-if="accentSelected !== null">
                <span class="accent-header">Stress & Accent</span>
                <span class="accent-item accent-text" v-if="accentSelected.accent_text !== null" v-html="accentSelected.accent_text">
                </span>
                <span class="accent-item accent-audio" v-if="accentSelected.accent_audio !== null">
                    <i class="fa fa-volume-up small-volume-icons" v-on:click="playAccent(accentSelected.audio_path + accentSelected.accent_audio)"></i>
                </span>
                <div class="wistia_embed popover=true accent-item accent-video"  v-bind:class="'wistia_async_' +  accentSelected.accent_video" v-if="accentSelected.accent_video !== null"></div>
              </div>
              <div class="audio-list-holder" v-if="listFlag === true">
                <span class="audio-list-item" v-for="item, index in audioSelectedListSuggestion" v-on:click="changeSelected(item)">
                  <span class="col-lg-1 col-md-1 col-sm-1 col-xs-6">
                    <i class="fa fa-volume-up small-volume-icons" v-on:click="playSentenceAudio(item.path +  item.audio, index)"></i>
                  </span>
                  <span class="col-lg-6 col-md-6 col-sm-8 col-xs-12">{{index + 1}}.&nbsp;
                    <span class="tooltipsAudio" v-for="innerItem, innerIndex in contentArray2DAudio[index]">
                      <b><label  v-html="innerItem.word + innerItem.char + '&nbsp;'"></label></b>
                      <span class="tooltipTitleAudio">{{innerItem.translation}}</span>
                    </span>
                  </span>
                  <span  class="col-lg-4 col-md-5 col-sm-12  col-xs-12 text-center">{{index + 1}}. &nbsp;{{item.translation}}</span>
                </span>
              </div>
              <div class="audio-list-holder" v-else>
                <span class="audio-list-item" v-for="item, index in sentenceSelectedListSuggestion" v-on:click="changeSelected(item)">
                  <span class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <i class="fa fa-volume-up small-volume-icons" v-on:click="playSentenceAudio(item.path + item.audio, index)"></i>
                  </span>
                  <span class="col-lg-5 col-md-5 col-sm-10 col-xs-10">{{index + 1}}.&nbsp;
                    <span class="tooltipsAudio" v-for="innerItem, innerIndex in contentArray2D[index]">
                      <b><label v-html="innerItem.word + innerItem.char + '&nbsp;'"></label></b>
                      <span class="tooltipTitleAudio">{{innerItem.translation}}</span>
                    </span>
                  </span>
                  <span  class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-center">{{index + 1}}. &nbsp;{{item.translation}}</span>
                </span>
              </div>
            </div>
          </div>
          <div v-else class="text-center">
              <h5 class="text-danger">No content added to this lesson.</h5>
              <span>Please report to support@talkfluent.com</span>
          </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" v-on:click="pause()">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import WaveSurfer from 'wavesurfer.js'
import {Howl, Howler} from 'howler'
export default {
  mounted(){
    this.getLesson()
    this.getAccuontInfo()
  },
  created(){
    this.initWistia()
  },
  data(){
    return {
      user: AUTH.user,
      account: [],
      tokenData: AUTH.tokenData,
      lessonFlag: false,
      config: CONFIG,
      introduction: CONFIG.BACKEND_URL + '',
      guide: CONFIG.BACKEND_URL + '',
      lesson: [],
      selectedLesson: null,
      content: [],
      selectedContent: null,
      selectedContentIndex: null,
      active: 0,
      clicked: 0,
      acc: null,
      contentArray: [],
      wordAudio: null,
      pageActiveAudio: false,
      pageActiveContent: true,
      responsiveView: {
        content: true,
        audio: false
      },
      audioSelected: {
        id: null,
        word: null,
        translation: null,
        path: null,
        audio: null
      },
      accentSelected: null,
      accentVideoPath: null,
      audioSelectedListSuggestion: [],
      audioManagerClose: true,
      audioContainer: null,
      accentAudio: null,
      isWordAudio: false,
      isSentenceAudio: false,
      sentenceResponseView: {
        audio: false
      },
      sentenceSelectedListSuggestion: [],
      listFlag: true,
      isClickedNumber: false,
      wavesurfer: null,
      sentenceAudio: null,
      sentenceAudioIndex: null,
      sentenceAudioPath: null,
      audioFlag: {
        sentence: false,
        accent: false,
        syllabus: false,
        lesson: false,
        grammar: false,
        cuture: false,
        visualizer: false
      },
      contentArray2D: [],
      contentArray2DAudio: [],
      syllabusAudio: null,
      lessonAudio: null,
      grammarAudio: null,
      cultureAudio: null,
      wistia: {
        url: 'http://fast.wistia.com/assets/external/E-v1.js',
        charset: 'ISO-8859-1'
      }
    }
  },
  methods: {
    initWistia(){
      let script = document.createElement('script')
      script.type = 'text/javascript'
      script.src = this.wistia.url
      script.charset = this.wistia.charset
      document.body.appendChild(script)
    },
    setModalStatic(){
      $('#myModal').modal({
        backdrop: 'static',
        keyboard: true
      })
    },
    getAccuontInfo(){
      let parameter = {
        'condition': [{
          'column': 'id',
          'clause': '=',
          'value': this.user.userID
        }]
      }
      this.APIRequest('accounts/retrieve', parameter).then(response => {
        this.account = response.data[0]
      })
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    getLesson(){
      this.APIRequest('lesson/retrieve', {}).then(response => {
        this.lesson = response.data
        for (let i = 0; i <= this.lesson.length - 1; i++) {
          if(this.lesson[i].status === 'APPROVED' || (this.lesson[i].status === 'PENDING' && this.account.account_type === 'ADMIN')){
            this.lessonFlag = true
            break
          }
        }
      })
    },
    retrieveContent(id, index){
      this.setModalStatic()
      let parameter = {
        'column': 'lesson_id',
        'clause': '=',
        'value': id,
        'account_id': this.user.userID
      }
      this.contentArray = []
      this.contentArray2DAudio = []
      this.isWordAudio = true
      this.isSentenceAudio = false
      this.selectedLesson = this.lesson[index]
      this.APIRequest('contents/dashboard', parameter).then(response => {
        if(response.message === null){
          this.content = response.content
          this.contentArray = response.data
          this.contentArray2DAudio = response.content_array
          this.audioSelectedListSuggestion = response.content
          this.listFlag = true
          this.selectedContentIndex = 0
          this.selectedContent = this.audioSelectedListSuggestion[0]
        }else{
          this.content = null
        }
      })
      if(this.lesson[index].accent_text !== null || this.lesson[index].accent_video !== null || this.lesson[index].accent_audio !== null){
        this.accentSelected = this.lesson[index]
        this.accentSelected.new_path = CONFIG.BACKEND_URL + this.accentSelected.video_path + this.accentSelected.accent_video
      }
    },
    deleteLesson(index){
      let parameter = {
        id: index
      }
      this.APIRequest('lesson/delete', parameter).then(response => {
        ROUTER.push('/dashboard')
      })
    },
    saveWord(wordId){
      let parameter = {
        'account_id': this.user.userID,
        'word_audio_id': wordId
      }
      this.APIRequest('save/create', parameter).then(response => {
      })
    },
    wordRetrieve(word, classParameter){
      let parameter = {
        'condition': [{
          'column': 'word',
          'clause': '=',
          'value': word
        }]
      }
      this.wordAudio = ''
      this.APIRequest('word_audio/retrieve', parameter).then(response => {
        if(response.data.length !== 0){
          this.wordAudio = response.data[0].translation
        }else{
          this.wordAudio = 'Not Found'
        }
      })
    },
    setActive(view){
      if(view === 'content'){
        this.responsiveView.content = true
        this.responsiveView.audio = false
        this.pageActiveContent = true
        this.pageActiveAudio = false
        this.sentenceResponseView.audio = false
      }else if(view === 'sentence'){
        this.sentenceResponseView.audio = true
        this.responsiveView.content = false
        this.responsiveView.audio = false
      }else{
        this.responsiveView.content = false
        this.responsiveView.audio = true
        this.pageActiveContent = false
        this.pageActiveAudio = true
        this.sentenceResponseView.audio = false
      }
    },
    setWord(word, index){
      let parameter = {
        'condition': [{
          'column': 'word',
          'clause': '=',
          'value': word
        }]
      }
      this.APIRequest('word_audio/retrieve', parameter).then(response => {
        if(response.data.length !== 0){
          this.audioSelected = response.data[0]
          this.responsiveView.content = false
          this.responsiveView.audio = true
          this.pageActiveContent = false
          this.pageActiveAudio = true
          this.audioContainer = null
          this.listFlag = true
          this.contentArray[index].click = 1
          this.isWordAudio = true
          this.pause()
          this.saveWord(this.audioSelected.id)
          if(this.wavesurfer === null){
            setTimeout(() => this.initVisualizer(), 500)
          }else{
            this.empty()
          }
          setTimeout(() => {
            this.loadVisualizer(this.audioSelected.path + this.audioSelected.audio)
          }, 500)
        }else{
          this.audioSelected = null
          this.wavesurfer = null
        }
      })
    },
    initVisualizer(){
      this.wavesurfer = WaveSurfer.create({
        container: '#mp3_player',
        waveColor: '#1caceb',
        progressColor: '#1caceb',
        hideScrollbar: true,
        height: 128
      })
    },
    loadVisualizer(path){
      this.wavesurfer.load(CONFIG.BACKEND_URL + path)
    },
    play(){
      this.playPauseAllAudio(2, null, null)
      if(this.wavesurfer !== null){
        if(this.audioFlag.visualizer === false){
          this.wavesurfer.play()
          this.audioFlag.visualizer = true
        }else{
          this.wavesurfer.pause()
          this.audioFlag.visualizer = false
        }
      }
    },
    pause(){
      if(this.wavesurfer !== null){
        this.wavesurfer.pause()
        this.audioFlag.visualizer = false
      }
      if(this.accentAudio !== null){
        this.audioFlag.accent = false
        this.accentAudio.pause()
      }
      if(this.sentenceAudio !== null){
        this.audioFlag.sentence = false
        this.sentenceAudio.pause()
      }
      if(this.audioContainer !== null){
        this.audioContainer.pause()
      }
      if(this.syllabusAudio !== null){
        this.audioFlag.syllabus = false
        this.syllabusAudio.pause()
      }
      if(this.lessonAudio !== null){
        this.audioFlag.lesson = false
        this.lessonAudio.pause()
      }
      if(this.grammarAudio !== null){
        this.audioFlag.grammar = false
        this.grammarAudio.pause()
      }
      if(this.cultureAudio !== null){
        this.audioFlag.culture = false
        this.cultureAudio.pause()
      }
    },
    empty(){
      this.wavesurfer.empty()
    },
    closeAudioManager(){
      this.audioManagerClose = false
    },
    changeSelected(item){
    },
    playAccent(path){
      this.playPauseAllAudio(1, path, null)
      if(this.accentAudio !== null){
        if(this.audioFlag.accent === false){
          this.accentAudio.play()
          this.audioFlag.accent = true
        }else{
          this.accentAudio.pause()
          this.audioFlag.accent = false
        }
      }
    },
    playLesson(path){
      this.playPauseAllAudio(4, path, null)
      if(this.lessonAudio !== null){
        if(this.audioFlag.lesson === false){
          this.lessonAudio.play()
          this.audioFlag.lesson = true
        }else{
          this.lessonAudio.pause()
          this.audioFlag.lesson = false
        }
      }
    },
    playSyllabus(path, index){
      this.playPauseAllAudio(0, path, index)
      if(this.syllabusAudio !== null){
        if(this.audioFlag.syllabus === false){
          this.syllabusAudio.play()
          this.audioFlag.syllabus = true
        }else{
          this.syllabusAudio.pause()
          this.audioFlag.syllabus = false
        }
      }
    },
    playGrammarAudio(path, index){
      this.playPauseAllAudio(5, path, null)
      if(this.grammarAudio !== null){
        if(this.audioFlag.grammar === false){
          this.grammarAudio.play()
          this.audioFlag.grammar = true
        }else{
          this.grammarAudio.pause()
          this.audioFlag.grammar = false
        }
      }
    },
    playCultureAudio(path, index){
      this.playPauseAllAudio(6, path, null)
      if(this.cultureAudio !== null){
        if(this.audioFlag.culture === false){
          this.cultureAudio.play()
          this.audioFlag.culture = true
        }else{
          this.cultureAudio.pause()
          this.audioFlag.culture = false
        }
      }
    },
    playSentenceAudio(path, index){
      this.playPauseAllAudio(3, path, index)
      if(this.sentenceAudio !== null){
        if(this.audioFlag.sentence === false){
          this.sentenceAudio.play()
          this.audioFlag.sentence = true
        }else{
          this.sentenceAudio.pause()
          this.audioFlag.sentence = false
        }
      }
    },
    playPauseAllAudio(p, path, index){
      this.pause()
      switch(p){
        case 0:
          if(this.syllabusAudio === null){
            this.syllabusAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.audioFlag.syllabus = false
          }else{
            this.audioFlag.syllabus = false
          }
          break
        case 1:
          if(this.accentAudio === null){
            this.accentAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.audioFlag.accent = false
          }else{
            this.audioFlag.accent = false
          }
          break
        case 2:
          if(this.wavesurfer !== null){
            this.audioFlag.visualizer = false
          }
          break
        case 3:
          if(this.sentenceAudio === null){
            this.sentenceAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.sentenceAudioPath = path
            this.audioFlag.sentence = false
          }else if(this.sentenceAudio !== null && path !== this.sentenceAudioPath){
            this.sentenceAudio.pause()
            this.sentenceAudioPath = path
            this.sentenceAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.audioFlag.sentence = false
          }else{
            this.audioFlag.sentence = false
          }
          break
        case 4:
          if(this.lessonAudio === null){
            this.lessonAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.audioFlag.lesson = false
          }else{
            this.audioFlag.lesson = false
          }
          break
        case 5:
          if(this.grammarAudio === null){
            this.grammarAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.audioFlag.grammar = false
          }else{
            this.audioFlag.grammar = false
          }
          break
        case 6:
          if(this.cultureAudio === null){
            this.cultureAudio = new Howl({
              src: CONFIG.BACKEND_URL + path
            })
            this.audioFlag.culture = false
          }else{
            this.audioFlag.culture = false
          }
          break
      }
    },
    retrieveSentence(index){
      let sentence = this.content[index]
      this.audioSelected.word = sentence.original
      this.audioSelected.translation = sentence.translation
      this.audioSelected.path = sentence.path
      this.audioSelected.audio = sentence.audio
      this.audioSelected.id = sentence.id
      this.audioSelected.syllabus = sentence.syllabus
      this.audioSelected.syllabus_path = sentence.syllabus_path
      this.audioSelected.syllabus_audio = sentence.syllabus_audio
      this.pageActiveContent = false
      this.pageActiveAudio = true
      this.responsiveView.content = false
      this.responsiveView.audio = true
      this.selectedContent.saved = 1
      this.pause()
      let parameter = {
        'column': 'content_id',
        'clause': '=',
        'value': this.audioSelected.id,
        'account_id': this.user.userID,
        'save_content': sentence.saved,
        'content_id': this.audioSelected.id
      }
      this.APIRequest('answers/dashboard', parameter).then(response => {
        this.sentenceSelectedListSuggestion = response.data
        this.listFlag = false
        this.contentArray2D = response.content_array
      })
      if(this.wavesurfer === null){
        setTimeout(() => this.initVisualizer(), 500)
      }else{
        this.empty()
      }
      setTimeout(() => this.loadVisualizer(this.audioSelected.path + this.audioSelected.audio), 500)
    }
  }
}
</script>
<style>
  .td-font{
    font-size: 20px;
  }
  .checked i{
    color: #1caceb;
  }
  .checked td{
    color: black;
  }
  .hover-on{
    color: #a1a1a1;
  }
  .hover-on:hover{
    background-color: #efefef;
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
  .modal{
    width: 100%;
    position: fixed;
  }
  .dash-header{
    text-align: center;
  }

  .fa-check{
    padding-right: 10px;
  }
  .empty-lesson{
    height: 50px;
    width: 100%;
    font-size: 24px;
    color: #ff0000;
  }

  td button i{
    padding-right: 10px;
  }
  thead{
    font-weight: 700;
  }

  .audio_container{
    visibility: hidden;
  }

  .welcome-title{
    margin: 20px 0 20px 0;
  }
  .header-dashboard{
    width: 100%;
    height: 40px;
    background: #ccc;
    float: left;
    text-align: center;
    font-weight: 500;
    font-size:20px;
  }
  .speech-bubble {
    position: relative;
    background: #1caceb;
    border-radius: 4em;
    text-align: center;
    height: 450px;
    padding-top: 10%;
  }
.speech-bubble:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 0;
  border: 20px solid transparent;
  border-top-color: #1caceb;
  border-bottom: 0;
  border-left: 0;
  margin-left: -10px;
  margin-bottom: -20px;
}
.speech-bubble h1{
  color: white;
  font-size: 150px;
}
.speech-bubble h2{
  color: gray;
  font-size: 75px;
}

.table{
  margin-top: 50px;
}
/*

  CONTENT

*/
.mobile-viewer{
  display: none;
}
.mobile-viewer-button1, .mobile-viewer-button2{
  width: 48%;
  float: left;
  text-align: center;
  border: 1px solid #ccc;
  margin-bottom: 20px;
}
.mobile-viewer-button1{
  margin-left: 2%;
}

.mobile-viewer-button1 h5, .mobile-viewer-button2 h5{
  padding-top: 10px;
  padding-bottom: 10px;
}

.mobile-viewer-button1:hover, .mobile-viewer-button2:hover{
  cursor: pointer;
  background: #1caceb;
  color: #fff;
  border: 1px solid #1caceb;
}
.content-manager{
  float: left;
  width: 55%;
  display: block;
}

.lesson-content-holder{
  width: 96%;
  min-height: 200px;
  overflow: hidden;
  margin: 0 2% 0 2%;
  float: left;
  }

.content-translation{
  font-size: 20px;
}
.content-translation{
  color: #ccc;
}

/*
      
    GRAMMAR AND CULTURE TIP

*/
.lesson-content-tip{
  width: 100%;
  height: 200px;
  float: left;
}
.tip-header{
  width: 96%;
  float: left;
  margin: 0 2% 0 2%;
}
.fa-play-circle{
  color: #1caceb;
  padding-right: 10px;
  font-size: 50px;
}
.tip-header .icon{
  width: 8%;
  float: left;
}
.tip-header .text{
  width: 92%;
  float: left;
  padding-top: 10px;
}
.tip-content{
  width: 96%;
  float: left;
  margin: 0 2% 0 2%;
}




/*
    
  AUDIO MANAGER

*/

.audio-manager{
  float: left;
  width: 45%;
  display: none;
  background: #eee;
  min-height: 450px;
  overflow: hidden;
}
.close-audio-manager{
}
.is-word-audio, .is-sentence-audio{
  display: block;
}

.audio-holder{
  width: 96%;
  float: left;
  margin: 20px 2% 0 2%;
}
.audio-icons{
  float: left;
  height: 50px;
  width: 20%;
}
#audio-content{
  height: 50%;
  width: 80%;
  float: left;
}
#audio-content #word, #audio-content #translation{
  height: 25px;
  width: 100%;
  float: left;
}
#audio-content #word{
  font-size: 20px;
  font-weight: 400;
} 
#audio-content #word b, #audio-content #translation b{
  font-weight: 600;
}
#audio-content #translation{
  font-weight: 400;
  font-size: 18px;
  color: #555;
}
.small-audio-icons{
  height: 40px;
}
.small-word{
  height: 20px;
  float: left;
  font-weight: 600;
}
.small-audio:hover{
  text-decoration: underline;
  cursor: pointer;
}
.small-translation{
  height: 20px;
  width: 85%;
  float: left;
  margin-left: 5%;
  color: #555;
}
.fa-volume-up{
  color: #1caceb;
  font-size: 25px;
  background: white;
  border: solid 2px #1caceb;
  border-radius: 50%;
  width: 45px;
  height: 45px;
  line-height: 45px;
  text-align: center;
}
.header-fa-volume-up{
  color: #1caceb;
  font-size: 24px;
  background: white;
  border: solid 2px #1caceb;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  line-height: 50px;
  padding-left: 5px;
  text-align: center;
}

.small-volume-icons{
  color: #1caceb;
  font-size: 10px;
  background: white;
  border: solid 2px #1caceb;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  line-height: 17px;
  text-align: center;
}
.fa-volume-up:hover{
  cursor: pointer;
  background: #1caceb;
  border: solid 2px #fff;
  color: #fff;
}
.audio-close{
  padding: 10px;
  color: #fff;
}

.audio-close:hover{
  cursor: pointer;
}

.audio-content-holder{
  height: 60px;
  width: 100%;
  float: left;
}

.accent-holder{
  width: 96%;
  float: left;
  height: 175px;
  overflow-y: scroll;
  margin: 0 2% 0 2%;
  background: #fff;
}
.accent-header{
  width: 98%;
  float: left;
  height: 30px;
  font-weight: 600;
  position: fixed;
  margin: 0 1% 0 1%;
  z-index: 10000;
}
.accent-item{
  height: auto;
  width: 90%;
  margin: 0 5% 0 5%;
  float: left;
}
.accent-text, .accent-audio{
  margin-top: 25px;
}
.accent-audio{
  margin-bottom: 25px;
}
.accent-video{
  width:90%;
  height:auto;
  margin-left:5%;
  margin-bottom:25px;
}

.accent-text{
  text-align: justify;
}
.accent-video{
  height: 200px;
}
.audio-list-holder{
  height: auto;
  width: 96%;
  margin: 50px 2% 0 2%;
  float: left;
}
.audio-list-item{
  height: auto;
  width: 100%;
  float: left; 
}

.audio-list-item .original{
  padding-left: 10px;
}

.audio-list-item .translation{
  padding-left: 20px;
}


/*

  AUDIO VISUALIZER
    
*/

.audio-visualizer{
  max-height: 64px;
  width: 100%;
  float: left;
  overflow: hidden;
  margin-bottom: 20px;
  margin-top: 20px;
}
.audio-visualizer > canvas{ 
  width:90%;
  height:70px;
  margin: 15px 5% 15px 5%;
  float:left;
}


/* Tooltip container */
.tooltips {
  position: relative;
  display: inline-block;
  color: gray;
  font-size: 20px;
}
.tooltipsAudio{
  position: relative;
  display: inline-block;
  color: gray;
  font-size: 12px;
  font-weight: 450;
  color:#000;
  margin-bottom: 10px;
  margin-right: 5px;
}
.number{
  color: gray;
  font-size: 20px;
  margin-top: 50px !important;
}

.number:hover{
  cursor: pointer;
  text-decoration: underline;
  color: #1caceb;
}
.number-clicked{
  color: black;
}

.tooltips-new-line {
    position: relative;
    color: gray;
}
.tooltips-clicked{
    color: #000 !important;
}

/* 
    Tooltip text 

*/


.tooltips .tooltipTitle,  .tooltips-new-line .tooltipTitle{
    visibility: hidden;
    width: 120px;
    background-color: #e1e1e1;
    color: black;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 1;
    white-space: pre-line;
    font-size: 20px;
}

.tooltipsAudio .tooltipTitleAudio{
    visibility: hidden;
    width: 80px;
    background-color: #e1e1e1;
    color: black;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -40px;
    opacity: 0;
    transition: opacity 1;
    white-space: pre-line;
    font-size: 12px;
}

/* Tooltip arrow */
.tooltips .tooltipTitle::after, .tooltips-new-line .tooltipTitle::after{
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #e1e1e1 transparent transparent transparent;
}

.tooltipsAudio .tooltipTitleAudio::after{
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #e1e1e1 transparent transparent transparent;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltips:hover .tooltipTitle, .tooltips-new-line:hover .tooltipTitle, .tooltipsAudio:hover .tooltipTitleAudio {
    visibility: visible;
    opacity: 1;
}


@media (min-width: 200px) and (max-width: 991px) {
  .mobile-viewer{
    display: block;
  }
  .content-manager, .audio-manager{
    width: 100%;
  }
  .content-manager, .audio-manager{
    display: none;
  }
  
  .is-active-cm, .is-active-am-sentence, .is-active-am-word{
    display: block;
  }

  .page-active-content, .page-active-audio{
    background: #1caceb;
    color: #fff;
    border: 1px solid #1caceb;
  }
  .td-font{
    font-size: 15px;
  }
   .speech-bubble {
    height: 300px;
   }
   .history{
    text-align: center;
   }
   .center-small{
    text-align: center;
   }
   .speech-bubble h1{
      color: white;
      font-size: 100px;
    }
    .speech-bubble h2{
      color: gray;
      font-size: 75px;
    }
    .sentence-mobile{
      width: 100%;
      float: left;
    }
}
</style>
