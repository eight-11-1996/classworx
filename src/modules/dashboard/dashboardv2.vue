<template>
  <div class="row">
      <span class="col-lg-10 mx-auto text-center welcome-title"><h3>WELCOME TO TALK FLUENT SPANISH</h3></span>
      <div class="col-lg-10 mx-auto row center-small">
        <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
          <span class="header-dashboard">Introduction: How to Study</span>
          <div class="wistia_embed wistia-holder"  v-bind:class="'wistia_async_u8p9wq6mq8'" ></div>
<!--           <span>
            <span class="header-dashboard">How to Study</span>
            <video controls>
              <source v-bind:src="guide" type="video/mp4">
            </video>
          </span> -->
        </span>
<!--         <div class="col-lg-7 5 col-md-6 col-sm-12">
          <hgroup class="speech-bubble">
            <h1>Hola</h1>
            <h2>Hello</h2>
          </hgroup>
        </div> -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto" v-if="dashboardLessons !== null">
          <span class="header-dashboard available-lesson">
            Available Lesson
          </span>
          <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
            <span class="top-lesson-holder" v-for="item, index in dashboardLessons">
              <span class="top-lesson-content"><h5>{{item.title}}</h5></span>
              <span class="category-lesson-holder" v-for="itemCat, indexCat in dashboardLessons[index].category" v-if="dashboardLessons[index].category !== null">
                <span class="category-lesson-content"><h6> &#9679; {{itemCat.title}}</h6></span>
                <span v-if="itemCat.lesson !== null">
                  <span class="lesson-holder" v-for="itemLesson, indexLesson in itemCat.lesson" v-if="itemLesson.status === 'APPROVED' || (itemLesson.status === 'PENDING' && account.account_type === 'ADMIN')" data-toggle="modal"data-target="#myModal" v-on:click="retrieveContent(itemLesson.id, itemLesson)" ><label class="lesson-holder-index">{{indexLesson + 1}}.</label> {{itemLesson.title}}</span>
                  <span v-else class="lesson-holder text-danger"><label class="lesson-holder-index text-danger">{{indexLesson + 1}}.</label> {{itemLesson.title}} - COMING SOON!</span>
                </span>
                <span v-else class="lesson-holder text-danger">No Lessons</span>
              </span>
            </span>
          </span>
        </div>
        <span v-else class="empty-lesson">No Lesssons Available</span>
      </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="selectedLesson !== null">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-volume-up header-fa-volume-up" onclick="playBasicAudio('lessonAudio')" v-bind:class="{'lesson-button-active': isLBActive === true}" id="lessonAudioPlayButton"></i> &nbsp; Lesson # {{selectedLesson.id}}. {{selectedLesson.title}}</h5>
            <audio id="lessonAudio" preload="auto">
                <source v-bind:src="config.BACKEND_URL + selectedLesson.lesson_audio" type="audio/ogg">
                <source v-bind:src="config.BACKEND_URL + selectedLesson.lesson_audio" type="audio/mpeg">
            </audio>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="pauseAllAudios()" onclick="pausePlaying()">
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
                  <span style="margin-top:40px;width:100%;float:left;"></span>
                  <span v-for="itemOuter, indexOuter in contentArray" class="content-items">
                    <span class="row">
                      <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 sentence-mobile">
                        <label class="number" v-on:click="retrieveSentence(indexOuter)" v-bind:class="{'number-clicked': content[indexOuter].saved === 1}" onclick="pausePlaying()">{{indexOuter + 1}}. </label>
                        <span v-for="item, index in contentArray[indexOuter]" class="tooltips" v-on:click="setWord(item.word, index, indexOuter)" v-bind:class="{'tooltips-clicked': item.click === 1}" onclick="pausePlaying()"><label v-html="item.word + item.char + '&nbsp;'"></label>
                          <span class="tooltipTitle">{{item.translation}}</span>
                        </span>
                      </span>
                    </span>
                    <span class="row">
                      <span  class="content-translation col-lg-12 col-md-12 col-sm-12 col-xs-12 sentence-mobile content-translation-mobile">{{indexOuter + 1}}. &nbsp; {{content[indexOuter].translation}}</span>
                    </span>
                  </span>
                </div>
                <div class="lesson-content-tip">
                  <span class="tip-content" v-if="selectedLesson !== null">
                    <b>Grammar Tip</b>
                    <br>
                    <i class="fa fa-volume-up" onclick="playBasicAudio('grammarAudio')" v-if="selectedLesson.grammar_audio !== null" v-bind:class="{'grammar-button-active': isGrammarActive === true}" id="grammarAudioPlayButton"></i>
                    <audio id="grammarAudio" preload="auto">
                        <source v-bind:src="config.BACKEND_URL + selectedLesson.grammar_audio" type="audio/ogg">
                        <source v-bind:src="config.BACKEND_URL + selectedLesson.grammar_audio" type="audio/mpeg">
                    </audio>
                    <br>
                    <label v-if="selectedLesson.grammar_tip !== null">{{selectedLesson.grammar_tip}}</label>
                    <br>
                    <b>Culture Tip</b>
                    <br>
                    <i class="fa fa-volume-up" onclick="playBasicAudio('cultureAudio')" v-if="selectedLesson.culture_audio !== null" v-bind:class="{'culture-button-active': isCultureActive === true}" id="cultureAudioPlayButton"></i>
                    <audio id="cultureAudio" preload="auto">
                        <source v-bind:src="config.BACKEND_URL + selectedLesson.culture_audio" type="audio/ogg">
                        <source v-bind:src="config.BACKEND_URL + selectedLesson.culture_audio" type="audio/mpeg">
                    </audio>
                    <br>
                    <label  v-if="selectedLesson.culture_tip !== null">{{selectedLesson.culture_tip}}</label>
                  </span>
                </div>
              </div>
            <div class="audio-manager" v-bind:class="{'is-active-am-word': responsiveView.audio, 'close-audio-manager':closeAudioManager, 'is-word-audio': isWordAudio}" v-if="audioSelected.word !== null">
              <!-- <span class="pull-right"><i class="fa fa-close audio-close" v-on:click="closeAudioManager()"></i></span> -->
              <div class="audio-holder">
                <span class="audio-content-holder">
                    <span class="audio-icons"><i class="fa fa-volume-up" v-on:click="play(), makeActiveButtons(4)"  v-bind:class="{'visualizer-button-active': isVisualizerActive === true}"></i></span>
                    <span id="audio-content">
                      <span id="word" v-html="audioSelected.word"></span>
                      <span id="translation" v-html="audioSelected.translation"></span>
                    </span>
                </span>

                <div class="progress" v-if="drawnFlag === false">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="audio-visualizer" id="mp3_player" v-bind:class="{'active-visualizer': drawnFlag === true}">
                </span>
                <span class="audio-content-holder">
                    <span class="audio-icons">
                      <i class="fa fa-volume-up" onclick="playBasicAudio('syllabusAudio')"  v-bind:class="{'syllabus-button-active': isSyllabusActive === true}" id="syllabusAudioPlayButton"></i>
                      <audio id="syllabusAudio" preload="auto">
                        <source v-bind:src="config.BACKEND_URL + audioSelected.syllabus_audio" type="audio/ogg">
                        <source v-bind:src="config.BACKEND_URL + audioSelected.syllabus_audio" type="audio/mpeg">
                      </audio>
                    </span>
                    <span id="audio-content">
                      <span id="word" v-html="audioSelected.syllabus"></span>
                    </span>
                </span>
              </div>
              <div class="accent-holder" v-if="accentSelected !== null">
<!--                 <span class="accent-header">Stress & Accent</span>
                <span class="accent-item accent-text" v-if="accentSelected.accent_text !== null" v-html="accentSelected.accent_text">
                </span>
                <span class="accent-margin"></span>
                <span class="accent-item accent-audio" v-if="accentAudioList !== null" v-for="accentItem, accentIndex in accentAudioList">
                    <i class="fa fa-volume-up small-volume-icons" v-on:click="player(accentItem.url)"></i>
                    <label v-html="accentItem.title" class="accent-audio-text"></label>
                </span>
                <span v-for="accentVideoItem, accentVideoIndex in accentVideoList" class="accent-video-holder"> 
                  <div class="wistia_embed popover=true accent-item accent-video"  v-bind:class="'wistia_async_' +  accentVideoItem.url"></div>
                  <span class="accent-video-text">{{accentVideoItem.title}}</span>
                </span>
                -->
                <!--
                  TEMPLATE


                  <span class="accent-header">Hello &nbsp;&nbsp; /He'lou/</span>
                 <span class="accent-header" style="color: #cccc;margin-top:-10px;">Verb</span>
                 <span class="accent-item">
                    <span class="accent-content">
                     <span class="accent-audio">
                       <i class="fa fa-volume-up small-volume-icons" onclick="playBasicAudio('basicAudio1')" id="basicAudio1PlayButton"></i>
                       <audio id="basicAudio1" preload="auto">
                        <source src="http://api.talkfluentspanish.com/api/public/storage/audio_files/S01.mp3" type="audio/ogg">
                        <source src="http://api.talkfluentspanish.com/api/public/storage/audio_files/S01.mp3" type="audio/mpeg">
                      Your browser does not support the audio element.
                      </audio>
                     </span>
                     <span class="accent-letter">
                       <label>H</label>
                     </span>
                     <span class="accent-letter-defi">
                        <p>Lorem ipsum dolor sit amet, tempor cursus integer  anim magna, Lorem ipsum dolor sit amet, tempor cursus integer  anim magna,  copy</p>
                     </span>
                   </span>
                   <span class="accent-media-holder">
                     <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
                     <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
                     <img src="https://api.zenithal.co/public/images/mirror/2_0_Capture.PNG" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_0_Capture.PNG')">
                     <div class="wistia_embed popover=true accent-media-item wistia_async_u8p9wq6mq8"></div>
                   </span>
                 </span>

<span class="accent-item">
                    <span class="accent-content">
                     <span class="accent-audio">
                       <i class="fa fa-volume-up small-volume-icons" onclick="playBasicAudio('basicAudio2')" id="basicAudio2PlayButton"></i>
                       <audio id="basicAudio2" preload="auto">
                        <source src="http://api.talkfluentspanish.com/api/public/storage/audio_files/S01.mp3" type="audio/ogg">
                        <source src="http://api.talkfluentspanish.com/api/public/storage/audio_files/S01.mp3" type="audio/mpeg">
                      Your browser does not support the audio element.
                      </audio>
                     </span>
                     <span class="accent-letter">
                       <label>H</label>
                     </span>
                     <span class="accent-letter-defi">
                        <p>Lorem ipsum dolor sit amet, tempor cursus integer  anim magna, Lorem ipsum dolor sit amet, tempor cursus integer  anim magna,  copy</p>
                     </span>
                   </span>
                   <span class="accent-media-holder">
                     <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
                     <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
                     <img src="https://api.zenithal.co/public/images/mirror/2_0_Capture.PNG" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_0_Capture.PNG')">
                     <div class="wistia_embed popover=true accent-media-item wistia_async_u8p9wq6mq8"></div>
                   </span>
                 </span>
                -->
                <span class="accent-item" v-html="accentSelected.accent_text"></span>
              </div>
              <div class="audio-list-holder" v-if="listFlag === true">
                <span class="audio-list-item" v-for="item, index in wordPopup" v-on:click="changeSelected(item)" v-if="wordPopup.length !== 0">
                <span class="audio-list-button">
                  <i class="fa fa-volume-up small-volume-icons" v-on:click="makePopupButtonActive(1, index)" v-bind:class="'words-popup-active-' + index" v-bind:onclick="parameterStart + 'wordPopupAudio' + index + parameterEnd" v-bind:id="'wordPopupAudio' + index + 'PlayButton'">
                  </i>
                  <audio v-bind:id="'wordPopupAudio' + index" preload="auto">
                    <source v-bind:src="config.BACKEND_URL + item.audio" type="audio/ogg">
                    <source v-bind:src="config.BACKEND_URL + item.audio" type="audio/mpeg">
                  </audio>
                </span>
                <span class="audio-list-content">{{index + 1}}.&nbsp;
                  <span class="tooltipsAudio" v-for="innerItem, innerIndex in contentArray2DAudio[index]">
                    <label  v-html="innerItem.word + innerItem.char + '&nbsp;'"></label>
                    <span class="tooltipTitleAudio">{{innerItem.translation}}</span>
                  </span>
                </span>
                <span  class="audio-list-translation">{{index + 1}}. &nbsp;{{item.translation}}</span>
                </span>
              </div>
              <div class="audio-list-holder" v-else>
                <span class="audio-list-item" v-for="item, index in sentenceSelectedListSuggestion" v-on:click="changeSelected(item)">
                  <span class="audio-list-button">
                    <i class="fa fa-volume-up small-volume-icons" v-bind:class="'sentence-popup-active-' + index" v-on:click="makePopupButtonActive(2, index)" v-bind:onclick="parameterStart + 'sentencePopupAudio' + index + parameterEnd" v-bind:id="'sentencePopupAudio' + index + 'PlayButton'">
                    </i>
                    <audio v-bind:id="'sentencePopupAudio' + index" preload="auto">
                      <source v-bind:src="config.BACKEND_URL + item.audio" type="audio/ogg">
                      <source v-bind:src="config.BACKEND_URL + item.audio" type="audio/mpeg">
                    </audio>
                  </span>
                  <span class="audio-list-content">{{index + 1}}.&nbsp;
                    <span class="tooltipsAudio" v-for="innerItem, innerIndex in contentArray2D[index]">
                      <label v-html="innerItem.word + innerItem.char + '&nbsp;'"></label>
                      <span class="tooltipTitleAudio">{{innerItem.translation}}</span>
                    </span>
                  </span>
                  <span  class="audio-list-translation">{{index + 1}}. &nbsp;{{item.translation}}</span>
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
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" v-on:click="pauseAllAudios()" onclick="pausePlaying()">Close</button>
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
    // this.initBasicAudio()
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
      dashboardLessons: [],
      lesson: [],
      selectedLesson: null,
      content: [],
      selectedContent: null,
      selectedContentIndex: null,
      active: 0,
      clicked: 0,
      acc: null,
      audio: null,
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
      contentArray2D: [],
      contentArray2DAudio: [],
      wordPopup: null,
      wistia: {
        url: 'http://fast.wistia.com/assets/external/E-v1.js',
        charset: 'ISO-8859-1'
      },
      accentAudioList: null,
      accentVideoList: null,
      activeButton: false,
      isLBActive: false,
      isCultureActive: false,
      isGrammarActive: false,
      isVisualizerActive: false,
      isSyllabusActive: false,
      lessonAudio: null,
      syllabusAudio: null,
      listAudios: null,
      listSentenceAudios: null,
      accentAudio: null,
      drawnFlag: false,
      onReady: false,
      parameterStart: 'playBasicAudio(\'',
      parameterEnd: '\')',
      mobileFlag: false
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
      this.APIRequest('lesson/dashboard', {}).then(response => {
        this.dashboardLessons = response.data
        // for (let i = 0; i <= this.lesson.length - 1; i++) {
        //   if(this.lesson[i].status === 'APPROVED' || (this.lesson[i].status === 'PENDING' && this.account.account_type === 'ADMIN')){
        //     this.lessonFlag = true
        //     break
        //   }
        // }
      })
    },
    retrieveContent(id, lesson){
      this.setModalStatic()
      let parameter = {
        'column': 'lesson_id',
        'clause': '=',
        'value': id,
        'account_id': this.user.userID
      }
      this.contentArray = []
      this.isWordAudio = true
      this.isSentenceAudio = false
      this.selectedLesson = lesson
      let lessonAudiosURL = []
      lessonAudiosURL[0] = CONFIG.BACKEND_URL + lesson.lesson_audio
      lessonAudiosURL[1] = CONFIG.BACKEND_URL + lesson.grammar_audio
      lessonAudiosURL[2] = CONFIG.BACKEND_URL + lesson.culture_audio
      this.loadLessonAudios(lessonAudiosURL)
      this.APIRequest('content/dashboard', parameter).then(response => {
        if(response.message === null){
          this.content = response.content
          this.contentArray = response.data
          this.audioSelectedListSuggestion = response.content
          this.listFlag = true
          this.selectedContentIndex = 0
          this.selectedContent = this.audioSelectedListSuggestion[0]
          this.accentAudioList = response.audio
          this.accentVideoList = response.video
        }else{
          this.content = null
        }
      })
      if(lesson.accent_text !== null || lesson.accent_video !== null || lesson.accent_audio !== null){
        this.accentSelected = lesson
        this.accentSelected.new_path = CONFIG.BACKEND_URL + this.accentSelected.video_path + this.accentSelected.accent_video
      }
    },
    loadLessonAudios(url){
      let i = 0
      if(url.length >= 0){
        this.lessonAudio = []
        for (i = 0; i < url.length; i++) {
          this.lessonAudio[i] = new Howl({
            src: url[i]
          })
        }
      }
    },
    loadSyllabusAudios(url){
      this.syllabusAudio = new Howl({
        src: url
      })
    },
    loadListAudios(){
      this.listAudios = []
      for (let i = 0; i < this.wordPopup.length; i++) {
        this.listAudios[i] = new Howl({
          src: CONFIG.BACKEND_URL + this.wordPopup[i].audio
        })
      }
    },
    loadSentenceAudios(){
      this.listSentenceAudios = []
      for (let i = 0; i < this.sentenceSelectedListSuggestion.length; i++) {
        this.listSentenceAudios[i] = new Howl({
          src: CONFIG.BACKEND_URL + this.sentenceSelectedListSuggestion[i].audio
        })
      }
    },
    loadAccentAudio(url){
      console.log(url)
      this.accentAudio = new Howl({
        src: CONFIG.BACKEND_URL + url
      })
      this.pauseAllAudios()
      this.accentAudio.seek(0)
      this.accentAudio.play()
    },
    pauseAllAudios(){
      let i = 0
      if(this.lessonAudio !== null){
        for (i = 0; i < this.lessonAudio.length; i++) {
          this.lessonAudio[i].pause()
        }
      }
      if(this.listAudios !== null){
        for (i = 0; i < this.listAudios.length; i++) {
          this.listAudios[i].pause()
        }
      }
      if(this.listSentenceAudios !== null){
        for (i = 0; i < this.listSentenceAudios.length; i++) {
          this.listSentenceAudios[i].pause()
        }
      }
      if(this.syllabusAudio !== null){
        this.syllabusAudio.pause()
      }
      if(this.wavesurfer !== null){
        this.wavesurfer.pause()
      }
      if(this.accentAudio !== null){
        this.accentAudio.pause()
      }
    },
    playLessonAudios(index){
      this.makeActiveButtons(index + 1)
      this.pauseAllAudios()
      if(this.lessonAudio !== null){
        this.lessonAudio[index].seek(0)
        this.lessonAudio[index].play()
      }
    },
    playSyllabusAudios(){
      this.makeActiveButtons(5)
      this.pauseAllAudios()
      if(this.syllabusAudio !== null){
        this.syllabusAudio.seek(0)
        this.syllabusAudio.play()
      }
    },
    playListAudios(index){
      this.makeActiveButtons(5)
      this.pauseAllAudios()
      if(this.listAudios !== null){
        this.listAudios[index].seek(0)
        this.listAudios[index].play()
      }
    },
    playListSentenceAudios(index){
      this.pauseAllAudios()
      if(this.listSentenceAudios !== null){
        this.listSentenceAudios[index].seek(0)
        this.listSentenceAudios[index].play()
      }
    },
    initVisualizer(){
      let parameter = {
        container: '#mp3_player',
        waveColor: '#1caceb',
        progressColor: '#1caceb',
        hideScrollbar: true,
        height: 128
        // backend: 'MediaElement'
      }
      let flag = false
      if(this.wavesurfer === null){
        this.wavesurfer = WaveSurfer.create(parameter)
      }else{
        this.wavesurfer.empty()
      }
      this.drawnFlag = false
      this.wavesurfer.load(CONFIG.BACKEND_URL + this.audioSelected.audio)
      // this.pauseAllAudios()
      $('.progress-bar').css({'width': '0%'})
      this.wavesurfer.on('loading', (response) => {
        this.drawnFlag = false
        $('.progress-bar').css({'width': response + '%'})
      })
      this.wavesurfer.on('ready', () => {
        $('.progress-bar').css({'width': 100 + '%'})
        setTimeout(() => {
          this.drawnFlag = true
          this.wavesurfer.play()
        }, 500)
        flag = true
      })
      if(this.drawnFlag === true && this.wavesurfer.isPlaying() === false){
        // this.wavesurfer.play()
      }
      if (/Mobi/.test(navigator.userAgent) && this.mobileFlag === false) {
        this.mobileFlag = true
        this.wavesurfer.load(CONFIG.BACKEND_URL + this.audioSelected.audio)
        // this.pauseAllAudios()
        $('.progress-bar').css({'width': '0%'})
        this.wavesurfer.on('loading', (response) => {
          this.drawnFlag = false
          $('.progress-bar').css({'width': response + '%'})
        })
        this.wavesurfer.on('ready', () => {
          $('.progress-bar').css({'width': 100 + '%'})
          setTimeout(() => {
            this.drawnFlag = true
            this.wavesurfer.play()
          }, 500)
          flag = true
        })
      }
    },
    play(){
      // this.pauseAllAudios()
      if(this.wavesurfer !== null){
        this.wavesurfer.play(0)
      }
    },
    makeActiveButtons(buttonNumber){
      this.clearAllActive()
      if(buttonNumber === 1){
        this.isLBActive = true
      }
      if(buttonNumber === 2){
        this.isGrammarActive = true
      }
      if(buttonNumber === 3){
        this.isCultureActive = true
      }
      if(buttonNumber === 4){
        this.isVisualizerActive = true
      }
      if(buttonNumber === 5){
        this.isSyllabusActive = true
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
    setWord(word, index, indexOuter){
      let parameter = {
        'column': 'word',
        'clause': '=',
        'value': word,
        'account_id': this.user.userID
      }
      // this.pauseAllAudios()
      this.contentArray2DAudio = []
      this.APIRequest('word_audio/dashboard', parameter).then(response => {
        if(response.data !== null){
          this.audioSelected = response.data[0]
          this.contentArray2DAudio = response.content
          this.wordPopup = response.word
          this.clearAllActive()
          this.responsiveView.content = false
          this.responsiveView.audio = true
          this.pageActiveContent = false
          this.pageActiveAudio = true
          this.listFlag = true
          this.isWordAudio = true
          this.contentArray[indexOuter][index].click = 1
          // this.loadSyllabusAudios(CONFIG.BACKEND_URL + this.audioSelected.syllabus_audio)
          // this.loadListAudios()
          this.saveWord(this.audioSelected.id)
        }else{
          this.wavesurfer = null
          this.contentArray2DAudio = []
          this.audioSelected.word = null
          this.responsiveView.content = true
          this.responsiveView.audio = false
          this.pageActiveContent = true
          this.pageActiveAudio = false
        }
      }).done(() => {
        this.initVisualizer()
      })
    },
    loadVisualizer(path){
      this.wavesurfer.load(CONFIG.BACKEND_URL + path)
    },
    closeAudioManager(){
      this.audioManagerClose = false
    },
    changeSelected(item){
    },
    clearAllActive(){
      this.isLBActive = false
      this.isGrammarActive = false
      this.isCultureActive = false
      this.isVisualizerActive = false
      this.isSyllabusActive = false
      let i = 0
      if(this.wordPopup !== null){
        for (i = 0; i < this.wordPopup.length; i++) {
          $('.words-popup-active-' + i).css({'background': '#eee', 'color': '#1caceb'})
        }
      }
      if(this.sentenceSelectedListSuggestion !== null){
        for (i = 0; i < this.sentenceSelectedListSuggestion.length; i++) {
          $('.sentence-popup-active-' + i).css({'background': '#eee', 'color': '#1caceb'})
        }
      }
    },
    makePopupButtonActive(section, index){
      this.clearAllActive()
      if(section === 1){
        $('.words-popup-active-' + index).css({'background': '#1caceb', 'color': '#fff'})
      }else{
        $('.sentence-popup-active-' + index).css({'background': '#1caceb', 'color': '#fff'})
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
      this.selectedContent.saved = 1
      this.content[index].saved = 1
      // this.pauseAllAudios()
      let parameter = {
        'column': 'content_id',
        'clause': '=',
        'value': this.audioSelected.id,
        'account_id': this.user.userID,
        'save_content': sentence.saved,
        'content_id': this.audioSelected.id
      }
      this.APIRequest('sentence_popup/dashboard', parameter).then(response => {
        if(response.data !== null){
          this.sentenceSelectedListSuggestion = response.data
          this.listFlag = false
          this.pageActiveContent = false
          this.pageActiveAudio = true
          this.responsiveView.content = false
          this.responsiveView.audio = true
          this.contentArray2D = response.content_array
          this.clearAllActive()
          // this.loadSentenceAudios()
        }else{
          this.sentenceSelectedListSuggestion = null
          this.wordPopup = null
          this.listFlag = true
          this.contentArray2D = null
          this.pageActiveContent = true
          this.pageActiveAudio = false
          this.responsiveView.content = true
          this.responsiveView.audio = false
        }
      }).done(() => {
        this.initVisualizer()
      })
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
    background: #ccc;
    float: left;
    text-align: center;
    font-weight: 500;
    font-size:20px;
    height: 50px;
    padding-top: 10px;
    float: left;
  }
  .available-lesson{
    text-align: left;
    padding-left: 20px;
    margin-top: 10px;
  }
  .wistia-holder{
    float: left; 
    height: 450px;
    width: 100%;
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

.top-lesson-holder{
  width: 100%;
  min-height: 40px;
  float: left;
  overflow: hidden;
}
.top-lesson-content{
  width: 100%;
  float: left;
  height: 100%;
  margin-bottom: 10px;
}
.category-lesson-holder{
  width: 90%;
  min-height: 40px;
  float: left;
  margin-left: 10%;
  overflow: hidden;
  margin-bottom: 10px;
}
.category-lesson-content{
  width: 100%;
  float: left;
  height: 100%;
}
.lesson-holder{
  width: 90%;
  margin-left: 10%;
  float: left;
  padding-top: 15px;
  padding-bottom: 10px;
  font-size: 16px;
  border-bottom: 1px solid #ccc;
}

.lesson-holder-index{
  padding-left: 10px;
  padding-right: 10px;
}
.lesson-holder:hover{
  background: #1caceb;
  cursor: pointer;
  color: white;
}

.modal-body{
  min-height: 500px !important;
  overflow: hidden;
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

.content-items{
  float: left;
  width: 100%;
}

/*
      
    GRAMMAR AND CULTURE TIP

*/
.lesson-content-tip{
  width: 100%;
  min-height: 200px;
  float: left;
  font-size: 16px !important;
  overflow: hidden;
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
  padding-left: 10px;
}

.small-volume-icons{
  color: #1caceb;
  font-size: 18px;
  background: white;
  border: solid 2px #1caceb;
  border-radius: 50%;
  width: 40px;
  height:40px;
  line-height: 38px;
  padding-left: 1px;
}
.fa-volume-up:hover{
  cursor: pointer;
  background: #1caceb;
  border: solid 2px #fff;
  color: #fff;
}
.lesson-button-active{
  background: #fff;
  border: solid 2px #1caceb;
  color: #1caceb;
}
.audio-button-active, .grammar-button-active, .culture-button-active, .visualizer-button-active, .syllabus-button-active{
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
  height: 350px;
  overflow-y: scroll;
  margin: 0 2% 0 2%;
  background: #fff;
}
.accent-header{
  width: 98%;
  float: left;
  height: 30px;
  font-weight: 600;
  margin: 0 1% 0 1%;
  z-index: 10000;
}
.accent-item{
  height: auto;
  width: 96%;
  margin: 10px 2% 10px 2%;
  float: left;
}
.accent-content{
  width: 100%;
  float: left;
  height: auto;
}
.accent-audio{
  width: 12%;
  float: left;
}
.accent-letter{
  width: 15%;
  float: left;
  margin-top: -15px;
}
.accent-letter label{
  font-size: 50px;
}
.accent-letter-defi{
  width: 73%;
  float: left;
  text-align: justify;
}
.accent-media-holder{
  width: 73%;
  margin-left: 27%;
  float: left;
}
.accent-media-item{
  float: left;
  height: 45px;
  width: 24%;
  margin-left: 1%;
}
.accent-media-item:hover{
  cursor: pointer;
  border: 1px solid #ccc;
}


.audio-list-holder{
  height: auto;
  width: 96%;
  margin: 50px 2% 0 2%;
  float: left;

  font-size: 16px !important;
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
  visibility: hidden;
}
.audio-visualizer > canvas{ 
  width:90%;
  height:70px;
  margin: 15px 5% 15px 5%;
  float:left;
}

.progress{
  width: 100%;
  height: 20px;
  float: left;
}
.active-visualizer{
  visibility: visible;
}

.hide-progress-bar{
  display: none;
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
  color:#000;
  margin-bottom: 10px;
  margin-right: 5px;
  font-size: 16px;
}
.number{
  color: gray;
  font-size: 20px;
  margin-top: 10px !important;
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


.audio-list-button{
  width: 15%;
  float: left;
}
.audio-list-content{
  width: 85%;
  float: left;
  padding-top: 8px;
}
.audio-list-translation{
  width: 85%;
  margin-left: 15%;
  float: left;
  margin-top: -15px;
  padding-bottom: 5px;
  color: #aaa;
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
    .wistia-holder{
      height: 300px;
    }
    .header-dashboard{
      font-size: 16px;
    }


.top-lesson-holder{
  text-align: left;
}
.top-lesson-content{
  text-align: left;
}
.category-lesson-holder{
  text-align: left;
}
.category-lesson-content{
  text-align: left;
}
.lesson-holder{
  padding-top: 15px;
  padding-bottom: 10px;
  font-size: 14px;
}

.lesson-holder-index{
  padding-left: 5px;
  padding-right: 5px;
}
.audio-list-button{
  width: 20%;
}
.audio-list-content{
  width: 80%;
}
.audio-list-translation{
  width: 80%;
  margin-left: 20%;
}
.accent-audio{
  width: 100%;
  float: left;
}
.accent-letter{
  width: 20%;
  float: left;
  margin-top: -15px;
}
.accent-letter label{
  font-size: 50px;
}
.accent-letter-defi{
  width: 80%;
  float: left;
  text-align: justify;
}
.accent-media-holder{
  width: 80%;
  margin-left: 20%;
}
.accent-media-item{
  float: left;
  height: 45px;
  width: 48%;
  margin-left: 2%;
  margin-top: 5px;
}

}
</style>
