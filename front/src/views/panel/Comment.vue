<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-300">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">لیست {{getAllData.contentTitle}}</li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3">
            <div class="mb-3 mr-2 flex fm:flex-col justify-end items-center">
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <div v-for="comment in getAllData" :key="comment.id" class="mb-5">
                <div class="shadow-md p-3 rounded border-b border-red-400 bg-[#2b2b34]">
                    <div class="border-b border-green-400 mb-3 pb-3">
                        <div class="flex justify-between fm:flex-col items-center fm:items-start mb-3">
                            <div class="flex justify-between  gap-4 fm:gap-2 fm:flex-col items-center fm:items-start">
                                <span>
                                    <Notify :notifications="getAllData.commentNotify" :id="comment.id" title=""/>
                                </span>
                                <span class="rem--7"><i class="fas fa-user ml-2 rem--7"></i>{{ comment.name }}</span>
                                <span class="rem--7"><i class="fas fa-envelope ml-2 rem--7"></i>{{comment.email}}</span>
                                <span class="rem--7"><i class="fab fa-accusoft ml-2 rem--7"></i>{{comment.status}}</span>
                                <span>
                                   
                                </span>
                            </div>
                            <span class="rem--7"><i class="fas fa-clock ml-2 rem--7"></i>{{comment.ago}}</span>
                        </div>
                        <div>
                            <p class="break-all rem--7">{{comment.comment}}</p>
                        </div>
                        <div class="flex gap-4 mt-4">
                            <span class="cursor-pointer text-green-500 rem--7" @click="show(comment.id,'comment')">ویرایش وضعیت</span>
                            <span class="cursor-pointer text-blue-500 rem--7" @click="create(comment.id)">پاسخ</span>
                            <span class="cursor-pointer text-red-500 rem--7" @click="destroy(comment.id,'comment')">حذف</span>
                        </div>
                    </div>
                    <div v-if="comment.hasOwnProperty('commentAnswers') && comment.commentAnswers.length > 0">
                        <div v-for="answer in comment.commentAnswers" :key="answer.id" class="shadow bg-[#17171c] rounded p-3 my-3 mr-2">
                            <div class="flex justify-between fm:flex-col items-center fm:items-start mb-3">
                                <div class="flex justify-between  gap-4 fm:gap-2 fm:flex-col items-center fm:items-start">
                                    <span>
                                        <Notify :notifications="getAllData.answerNotify" :id="answer.id" title=""/>
                                    </span>
                                    <span class="rem--7"><i class="fas fa-user ml-2 rem--7"></i>{{ answer.name }}</span>
                                    <span class="rem--7"><i class="fas fa-envelope ml-2 rem--7"></i>{{answer.email}}</span>
                                    <span class="rem--7"><i class="fab fa-accusoft ml-2 rem--7"></i>{{answer.status}}</span>
                                </div>
                                <span class="rem--7"><i class="fas fa-clock ml-2 rem--7"></i>{{answer.ago}}</span>
                            </div>
                            <div>
                                <p class="break-all rem--7">{{answer.answer}}</p>
                            </div>
                            <div class="flex gap-4 mt-4">
                                <span class="cursor-pointer text-green-500 rem--7" @click="show(answer.id,'answer')">ویرایش وضعیت</span>
                                <span class="cursor-pointer text-blue-500 rem--7" @click="create(answer.comment_id,answer.id)">پاسخ</span>
                                <span class="cursor-pointer text-red-500 rem--7" @click="destroy(answer.id,'answer')">حذف</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="next !== null || previous !== null">
              <Paginate :current="current" :next="next" :previous="previous" :total="total"></Paginate>
            </div>
          </div>
        </div>
        <Modal title="ویرایش " save="ثبت تغییرات" ref="modal" @callback="update()">
            <div><Error :errors="getErrors"></Error></div>
            <div>
                <label for="status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت <b class="text-red-600 rem--7">*</b> </label>
                <select id="status" v-model="status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="yes">تایید</option>
                    <option value="no">رد</option>
                </select>
            </div>
        </Modal>
        <Modal title="پاسخ" save="ثبت" ref="comment" @callback="insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="items-center mt-8 mb-6">
                <div class="relative z-0 group w-full">
                    <textarea v-model="comment" id="comment" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer"></textarea>
                    <label for="comment" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        پاسخ<b class="text-red-600 rem--7">*</b>
                    </label>
                </div>
            </div>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle}`" description="" />
        <Loading :loading="loading" />
    </div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Thead from '@/components/Thead'
    import Td from '@/components/Td'
    import Modal from '@/components/Modal'
    import Error from '@/components/Error'
    import Paginate from '@/components/Paginate'
    import Notify from '@/components/Notify'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'Comment',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading,Notify},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'comment',
                status:null,
                comment:null,
                answer_id:null,
                action:null,
            }
        },
        mounted() {
            this.allData()
        },
        computed:{
            ...mapGetters(["getAllData","getErrors"]),
            ...mapState(["current","next","previous","total"])
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle','answerNotify','commentNotify']])
                this.loading = false
            },
            create(id,answer_id=0){
                this.emptyError();
                this.comment = null;
                this.answer_id = answer_id;
                this.id = id;
                this.isUpdate = false;
                this.$refs.comment.toggleModal();
            },
            async insert(){
                this.loading = true;
                let data=[
                    this.model,
                    {comment:this.comment,comment_id:this.id,answer_id:this.answer_id}
                ]
                await this.$store.dispatch("insert",data)
                await this.allData(false,this.current)
                this.loading = false;
                if(this.$store.state.clear){
                    this.$refs.comment.toggleModal();
                }
            },
            async show(post_id,action){
                this.emptyError();
                this.clear();
                this.loading = this.isUpdate = true;
                this.$refs.modal.toggleModal();
                await this.$store.dispatch("show",[`${this.model}-show/${post_id}/${action}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    this.id=data.id;
                    this.status = data.status;
                    this.action = data.action;
                    this.loading = false;
                }else{
                    this.$refs.modal.toggleModal();
                    this.loading = this.$store.state.isLoading;
                }
            },
            async update(){
                this.loading = true;
                let data = [
                    `${this.model}/${this.id}`,
                    this.setData()
                ]
                await this.$store.dispatch('update',data)
                await this.allData(false,this.current)
                this.loading = false
            },
            async destroy(post_id,action){
                await this.$store.dispatch('deleteRecord',[`${this.model}/${post_id}?action=${action}`,'دقت نمایدد چنانچه نظر دارای پاسخ باشد به همراه پاسخ ها حذف میشود'])
                await this.allData(true,this.current)
                this.loading = false
            },
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['contentTitle','answerNotify','commentNotify']]);
                this.loading = false;
            },
            setData(){
                return {
                    status:this.status,
                    action:this.action,
                }
            },
            clear(){
                this.status = null;
                this.action = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>