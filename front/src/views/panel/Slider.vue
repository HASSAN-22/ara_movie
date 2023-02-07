<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">لیست {{getAllData.contentTitle}} ها</li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3">
            <div class="mb-3 mr-2 flex fm:flex-col justify-between items-center">
                <Button text="اضافه کردن" color="green" @click="create()"></Button>
<!--              <div class="ml-2 fm:mt-3">-->
<!--                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />-->
<!--              </div>-->
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['عنوان','تصویر','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="تصویر" class="rem--7">
                            <img :src="url+item.image" alt="" class="fm:w-16 fm:h-16 w-32 h-32">
                        </Td>
                        <Td title="عملیات">
                          <div class="flex justify-center items-center">
                            <Button v-tooltip="'حذف'" @click="destroy(`${item.id}`)" text="" color="red"><i class="fas rem--7 fa-trash text-red-700"></i></Button>
                          </div>
                        </Td>
                    </tr>
                </tbody>
            </table>
            <div v-if="next !== null || previous !== null">
              <Paginate :current="current" :next="next" :previous="previous" :total="total"></Paginate>
            </div>
          </div>
        </div>
        <Modal :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-7">
                <label for="movie_id" class="block mt-5 font-medium text-gray-300 rem--7">فیلم / سریال  <b class="text-red-600 rem--7">*</b> </label>
                <select id="movie_id" v-model="movie_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null">--- انتخاب فیلم / سریال ---</option>
                    <option v-for="movie in getAllData.movies" :key="movie.id" :value="movie.id">{{movie.title}}</option>
                </select>
            </div>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} ها`" description="" />
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
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'

    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'Plan',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                id:null,
                isUpdate:false,
                loading:false,
                model:'slider',
                movie_id:null,
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle','movies']])
                this.loading = false
            },
            create(){
                this.emptyError();
                this.clear();
                this.isUpdate = false;
                this.$refs.modal.toggleModal();
            },
            async insert(){
                this.loading = true;
                let data=[
                    this.model,
                    this.setData()
                ]
                await this.$store.dispatch("insert",data)
                await this.allData(false,this.current)
                this.loading = false;
                if(this.$store.state.clear){
                    this.clear();
                }
            },
           
            async destroy(post_id){
                await this.$store.dispatch('deleteRecord',[`${this.model}/${post_id}`,''])
                await this.allData(true,this.current)
                this.loading = false
            },
            // async search(text){
            //     this.loading = true;
            //     await this.$store.dispatch('search',[`${this.model}-search`,{search:text}]);
            //     this.loading = false;
            // },
            
            setData(){
                return {
                    movie_id:this.movie_id
                }
            },
            clear(){
                this.movie_id = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>