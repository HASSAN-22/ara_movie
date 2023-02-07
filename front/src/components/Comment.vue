<template>
    <div>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span><i class="text-2xl rem--8 text-crimson-100 fas fa-comment"></i></span>
            <span class="rem--8">{{ commentCount }} دیدگاه</span>
        </div>
        <div>
            <Button @click="openModal('comment')" text="ثبت دیدگاه" />
        </div>
      </div>

      <div class="mt-8 p-3 bg-[#1c1c22] rounded grid grdi-row-2 gap-3" v-for="comment in allComments" :key="comment.id">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-between gap-1">
                <span><i class="text-lg rem--7 text-crimson-100 fas fa-user"></i></span>
                <span class="rem--7 font-bold">{{comment.name}}</span>
                <span class="text-sm rem--6">{{new Date(comment.created_at).toLocaleDateString('fa-IR')}}</span>
            </div>
            <div>
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center justify-center gap-2 cursor-pointer" @click="openModal('answer',comment.id)">
                      <span><i class="fas fa-reply text-sm fm:!text-sm text-blue-500"></i></span>
                    </div>
                    <div class="flex items-center justify-center gap-2 cursor-pointer" @click="like(comment.id,'like')">
                        <span><i class="fas fa-thumbs-up text-sm text-green-500"></i></span>
                        <span class="rem--7 text-sm">{{countLikeDislike(comment,'like')}}</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 cursor-pointer" @click="like(comment.id,'dislike')">
                        <span><i class="fas fa-thumbs-down text-sm text-red-500"></i></span>
                        <span class="rem--7 text-sm">{{countLikeDislike(comment,'dislike')}}</span>
                    </div>
                    
                </div>
            </div>
        </div>
        <div>
            <div class="mb-3">
                <p class="rem--7" v-html="comment.comment"></p>
            </div>
            <!-- Answer -->
              <div class="mt-2 mr-3 p-3 bg-[#2b2b34] rounded grid grdi-row-2 gap-3" v-for="answer in comment.comment_answers" :key="answer.id">
                  <div class="flex items-center justify-between">
                      <div class="flex items-center justify-between gap-2">
                          <span><i class="text-lg rem--7 text-crimson-100 fas fa-user"></i></span>
                          <span class="rem--7 font-bold">{{answer.name}}</span>
                          <span class="text-sm rem--6">{{new Date(answer.created_at).toLocaleDateString('fa-IR')}}</span>
                      </div>
                      <div>
                          <div class="flex items-center justify-between gap-3">
                              <div class="flex items-center justify-center gap-2 cursor-pointer" @click="openModal('answer',answer.comment_id,answer.id)">
                                  <span><i class="fas fa-reply text-sm fm:!text-sm text-blue-500"></i></span>
                              </div>
                              <div class="flex items-center justify-center gap-2 cursor-pointer" @click="like(answer.id,'like','answer')">
                                  <span><i class="fas fa-thumbs-up text-sm fm:!text-sm text-green-500"  ></i></span>
                                  <span class="rem--7 text-sm">{{countLikeDislike(answer,'like')}}</span>
                              </div>
                              <div class="flex items-center justify-center gap-2 cursor-pointer" @click="like(answer.id,'dislike','answer')">
                                  <span><i class="fas fa-thumbs-down text-sm fm:!text-sm text-red-500"></i></span>
                                  <span class="rem--7 text-sm">{{countLikeDislike(answer,'dislike')}}</span>
                              </div>
                              
                          </div>
                      </div>
                  </div>
                  <div>
                      <p class="rem--7" v-html="answer.answer"></p>
                  </div>
              </div>
            <!-- End Answer -->
        </div>
    </div>
    <Modal title="ثبت دیدگاه" save="ثبت" ref="modal" @callback="sendComment()">
      <div><Error :errors="getErrors"></Error></div>
      <div class="items-center mt-8 mb-6">
          <div class="relative z-0 group w-full">
              <div class="relative z-0 group w-full">
                  <textarea v-model="comment" id="comment" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer"></textarea>
                  <label for="comment" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                      توضیحات
                  </label>
                  <small class="text-crimson-100 rem--7">حداکثر تعداد کاراکتر ۱۰۰۰</small>
              </div>
          </div>
          <div class="flex items-center gap-3 justify-evenly mt-5">
              <Input v-model="name" title="نام شما" id="name"></Input>
              <Input v-model="email" title="ایمیل" id="email"></Input>
          </div>
      </div>
    </Modal>
    <Loading :loading="loading" />
    </div>
</template>

<script>
import Loading from '@/components/Loading'
import Button from '@/components/Button'
import Input from '@/components/Input'
import Editor from "@/components/Editor"
import Error from "@/components/Error"
import Modal from '@/components/Modal'
import {mapGetters} from "vuex";
import axios from "../plugins/axios";
import Alert from "../plugins/alert";
export default {
    name:'Comment',
    props:['comments','movieId'],
    components:{Loading,Button,Input,Editor,Error,Modal},
    data(){
      return{
        api:process.env.VUE_APP_API+'/api/',
        url:process.env.VUE_APP_API,
        comment:null,
        allComments:this.comments,
        name:null,
        email:null,
        comment_id:null,
        answer_id:null,
        type:null,
        loading:false,
      }
    },
    computed:{
      ...mapGetters(["getErrors"]),
      commentCount(){
        return this.allComments.length > 0 ? parseInt(this.allComments.length)+parseInt(this.allComments.map(item=>item.comment_answers.length)) : 0;
      }
    },
    methods:{
      async like(id,type,action='comment'){
        this.loading = true;
        await axios.post(this.api+'comment-like-dislike',{id:id,action:action,type:type}).then(resp=>{
          this.allComments = resp.data.data
          this.loading = false;
        }).catch(err=>{
          this.loading = false;
        })
      },
      async sendComment(){
        this.loading = true;
        await axios.post(this.api+'comment-send/'+this.movieId,{
          comment:this.comment,
          name:this.name,
          email:this.email,
          type:this.type,
          answer_id:this.answer_id,
          comment_id:this.comment_id
          }).then(resp=>{
          let data = resp.data
          if(data.status === 'success'){
            this.allComments = data.data
            this.emptyError();
            Alert.show('موفق','با موفقیت ثبت شد بعد از تایید نمایش داده خواهد شد','success');
          }
        }).catch(err=>{
          if(err.response.status === 422){
            this.$store.commit('catchError',err.response)
          }else{
            Alert.show('خطا','ثبت نظر با خطا مواجه شد','error')
          }
        })
        this.loading =false;
      },
      countLikeDislike(comment,type){
        let likes = comment.likes
        if(likes !== undefined){
          return likes.filter(item=>item.type == type).length;
        }else{
          return 0;
        }
      },
      openModal(type,comment_id=null,answer_id=0){
        this.comment_id = comment_id;
        this.type = type;
        this.answer_id = answer_id;
        this.emptyError();
        this.clear();
        this.$refs.modal.toggleModal();
      },
      clear(){
        this.name = null;
        this.email = null;
        this.comment = null;
      },
      emptyError(){
          this.$store.state.errors = [];
      },
    }
}
</script>