<template>
  <div>
    <div>
      <ul class="pagination pagination-lg">
        <li class="page-item page-link" v-bind:class="{'word-active':isActive.word === true}" v-on:click="view('word')">Words</li>
        <li class="page-item page-link page-link2" v-bind:class="{'sentence-active':isActive.sentence === true}" v-on:click="view('sentence')">Sentence</li>
         <li class="page-item disabled page-item-block">
        </li>
      </ul>
    </div>
    <div class="word-result" v-bind:class="{'word-result-active': isActive.word === true}">
      <table class="table table-hover borderless"  v-if="word.length >= 1">
        <thead>
          <tr class="thead">
            <td>WORD</td>
            <td>TRANSLATION</td>
            <td>SYLLABLE</td>
          </tr>
        </thead>
        <tbody class="table-hover">
          <tr v-for="item, index in word">
            <td>
              <i class="fa fa-volume-up header-fa-volume-up" v-on:click="play(item.audio)"></i>
              <label v-html="item.word"></label>
            </td>
            <td>
              <label v-html="item.translation"></label>
            </td>
            <td>
              <i class="fa fa-volume-up header-fa-volume-up" v-on:click="play(item.syllabus_audio)"></i>
              <label v-html="item.syllabus"></label>
            </td>
          </tr>
        </tbody>
      </table>
      <span v-else class="error-container text-danger">No Result Found</span>
    </div>
    <div class="sentence-result" v-bind:class="{'sentence-result-active': isActive.sentence === true}">
      <table class="table table-hover borderless" v-if="sentence.length >= 1">
        <thead>
          <tr class="thead">
            <td>SENTENCE</td>
            <td>TRANSLATION</td>
            <td>SYLLABLE</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item, index in sentence" >
            <td>
              <i class="fa fa-volume-up header-fa-volume-up" v-on:click="play(item.audio)"></i>
              <label v-html="item.original"></label>
            </td>
            <td><label v-html="item.translation"></label></td>
            <td>
              <i class="fa fa-volume-up header-fa-volume-up" v-on:click="play(item.syllabus_audio)"></i>
              <label v-html="item.syllabus"></label>
            </td>
          </tr>
        </tbody>
      </table>
      <span v-else class="error-container text-danger">No Result Found</span>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import {Howl, Howler} from 'howler'
export default {
  mounted(){
    this.view('word')
  },
  created(){
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      audio: null,
      word: [],
      sentence: [],
      isActive: {
        word: true,
        sentence: false
      },
      wordAudio: null
    }
  },
  methods: {
    view(method){
      if(this.audio !== null){
        this.audio.pause()
      }
      if(method === 'word'){
        this.isActive.word = true
        this.isActive.sentence = false
      }else{
        this.isActive.word = false
        this.isActive.sentence = true
      }
      if(this.isActive.word === true){
        this.retrieveWord()
      }else{
        this.retrieveSentece()
      }
    },
    play(url){
      if(this.audio === null){
        this.audio = new Howl({
          src: CONFIG.BACKEND_URL + url
        })
        this.audio.play()
      }else{
        this.audio.unload()
        this.audio = new Howl({
          src: CONFIG.BACKEND_URL + url
        })
        this.audio.play()
      }
    },
    retrieveWord(){
      let parameter = {
        'account_id': this.user.userID
      }
      this.APIRequest('save/retrieve_history', parameter).then(response => {
        this.word = response.data
      })
    },
    retrieveSentece(){
      let parameter = {
        'account_id': this.user.userID
      }
      this.APIRequest('save_content/retrieve_history', parameter).then(response => {
        this.sentence = response.data
      })
    }
  }
}
</script>
<style>
  .word-active, .sentence-active, .page-link:hover{
    background: #1caceb !important;
    color: white !important;
  }

  .pagination{
    border:none;
    background: #eee;
    border-bottom: 2px solid #ccc !important;
  }
  .page-link:hover{
    cursor: pointer;
  }
  .page-item{
    width: 15% !important;
    text-align: center !important;
  }
  .page-item-block{
    width: 55% !important;
    float: left;
  }
  .page-item, .disabled, .page-link{
    border:none !important;
    background: #eee;
  }



  /*
      
        AUDIO
    
  */
  .fa-volume-up{
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
    background: #1caceb;
    color: white;
    cursor: pointer;
  }

  /*

      Word Result
  */
  .word-result, .sentence-result{
    display: none;
  }
  .word-result-active, .sentence-result-active{
    display: block;
  }
  .error-container{
    width: 100% !important;
    float: left;
    text-align: center;
  }
  .table-hover:hover{
    cursor: pointer !important;
  }

  thead{
    border-bottom: 2px solid #ccc !important;
  }
  table.borderless td,table.borderless th{
     border: none !important;
  }

  .thead{ 
    font-weight: 600 !important;
    font-size: 16px;
  }
  @media (min-width: 200px) and (max-width: 991px) {
    .page-item{
      width: 30% !important;
      text-align: center !important;
    }
    .page-link2{
      width: 40% !important;
      text-align: center !important;
    }
    .page-item-block{
      width: 0% !important;
      float: left;
    }
  }
</style>
