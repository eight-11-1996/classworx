<template>
  <div class="row">
    <h1 class="col-lg-10 mx-auto history">Welcome To Talk Fluent Spanish<hr></h1>
      <div class="col-lg-10 mx-auto row center-small">
        <div class="col-lg-4 col-sm-12 col-md-4">
          <div class="col-12">
            <h6>Intoduction</h6>
            <iframe width="300" height="200" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
            <h6>How to Study</h6>
            <iframe width="300" height="200" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
          </div>
        </div>
        <div class="col-sm-12 col-md-8 col-lg-8">
          <hgroup class="speech-bubble">
            <h1>Hola</h1>
            <h2>Hello</h2>
          </hgroup>
        </div>
        <table class="table">
          <thead>
            <tr>
              <h1>Lessons</h1>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item, index in lesson" class="hover-on checked" data-toggle="modal"data-target="#myModal" v-on:click="retrieveContent(item.id), lessonNo = item.id, lessonTitle = item.title, ctip = item.culture_tip, gtip = item.grammar_tip, acc = item.accent_text">
              <td class="td-font checked" v-bind:class="{'checked': item.clicked === 1}"><i class="fa fa-check to-right checked"></i><span class="to-right">Lesson {{index + 1}}. {{item.title}}</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>{{modalTitle}} # {{lessonNo}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal(), wavesurfer.pause()">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <modal-tutorial :lessonN="lessonNo" :les="sentence" :pop="pop" :title="lessonTitle" :less="content" :acc="acc" :ctip="ctip" :gtip="gtip" :clicked="clicked" :act="active"></modal-tutorial>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import WaveSurfer from 'C:/xampp/htdocs/talkfluent/node_modules/wavesurfer.js/dist/wavesurfer.js'
import Word from 'modules/translation/word.vue'
export default {
  mounted(){
    this.getLesson()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      ctip: null,
      gtip: null,
      pop: false,
      lessonTitle: null,
      modalTitle: 'Lesson',
      fileCounter: 0,
      stringAppender: null,
      title: null,
      original: null,
      tranSentence: null,
      audio: [],
      fileCount: 0,
      accentText: null,
      lesson: [],
      lessonNo: null,
      objSent: [{}],
      id: 0,
      selected: null,
      sentence: [],
      content: [],
      active: 0,
      clicked: 0,
      acc: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    submit(id){
      this.done.push()
    },
    incrementWord(){
      this.fileCounter++
    },
    decrementWord(){
      this.fileCounter--
    },
    getLesson(){
      this.APIRequest('lesson/retrieve', {}).then(response => {
        this.lesson = response.data
      })
    },
    retrieveContent(id){
      let parameter = {
        'condition': [{
          'column': 'lesson_id',
          'clause': '=',
          'value': id
        }]
      }
      this.APIRequest('contents/retrieve', parameter).then(response => {
        this.content = response.data
        this.splitSentence(this.content[0].original)
      })
    },
    deleteLesson(index){
      let parameter = {
        id: index
      }
      this.APIRequest('lesson/delete', parameter).then(response => {
        console.log(response)
      })
    },
    splitSentence(spltStr){
      var text = spltStr.split(' ')
      var len = text.length
      for (var i = 0; i < len; i = i + 1) {
        this.sentence.push({
          word: text[i],
          click: 0
        })
      }
    },
    closeModal(){
      while(this.sentence.length !== 0){
        this.sentence.pop()
      }
      this.selected = null
    }
  },
  components: {
    'modal-tutorial': Word
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

  td button i{
    padding-right: 10px;
  }
  thead{
    font-weight: 700;
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
.to-right{
  margin-left: 10px;
}

@media (max-width: 575px) {  
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
}

/*// Small devices (landscape phones, 576px and up)
@media (min-width: 576px) and (max-width: 767px) { ... }

// Medium devices (tablets, 768px and up)
@media (min-width: 768px) and (max-width: 991px) { ... }

// Large devices (desktops, 992px and up)
@media (min-width: 992px) and (max-width: 1199px) { ... }

// Extra large devices (large desktops, 1200px and up)
@media (min-width: 1200px) { ... }*/
</style>
