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
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['فیلم یا سریال','عنوان','لینک','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="فیلم یا سریال" class="rem--7">{{item.movie}}</Td>
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="لینک" class="rem--7">{{item.link}}</Td>
                        <Td title="عملیات">
                          <div class="flex items-center justify-center">
                            <Button text="" color="blue" v-tooltip="'ویرایش'" @click="show(`${item.id}`)"><i class="fas fa-edit text-blue-700"></i></Button>
                            <Button v-tooltip="'حذف'" @click="destroy(`${item.id}`)" text="" color="red"><i class="fas fa-trash text-red-700"></i></Button>
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
        <Modal width="w-[75%] fm:w-[85%]" :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-8">
                <label for="movie_id" class="block mt-5 font-medium text-gray-300 rem--7">برای فیلم / سریال <b class="text-red-600 rem--7">*</b> </label>
                <select id="movie_id" v-model="movie_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب فیلم / سریال ---</option>
                    <option v-for="movie in getAllData.movies" :key="movie.id" :value="movie.id">{{movie.title}}</option>
                </select>
            </div>

            <div>
                <Button color="green" :text="`اضافه کردن ${getAllData.contentTitle} `" @click="addTag()"></Button>
                <div class="grid grid-cols-2 gap-3 relative mt-8" v-for="count in tagCount" :key="count-1">
                    <Input v-model="titles[count-1]" id="titles" title="عنوان"></Input>
                    <Input v-model="links[count-1]" id="links" title="لینک"></Input>
                    <div class="absolute left-0 top-0">
                        <span class="cursor-pointer" @click="removeTag(count-1)"><i class="fa fa-trash text-red-500 rem--7"></i></span>
                    </div>
                </div>
            </div>

        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} ها`" description="" />
        <Loading :loading="loading" />
        <Gallery ref="galleryMedia"></Gallery>
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
    import Alert from '@/plugins/alert'
    import Gallery from '@/components/Gallery'
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'MovieTag',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading,Gallery},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'movieTag',
                movie_id:null,
                titles:[],
                links:[],
                tagCount:0,
                filePath:[],
                currentCount:0
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
            addTag(){
              this.tagCount ++;
                this.titles.push(null);
                this.links.push(null);
            },
            removeTag(count){
                this.tagCount --;
                this.tags = this.tags.filter((value,key)=>{return key !== count})
                this.links = this.links.filter((value,key)=>{return key !== count})
            },
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['movies','contentTitle']])
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
                this.emptyError();
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
            async show(post_id){
                this.emptyError();
                this.clear();
                this.loading = this.isUpdate = true;
                this.$refs.modal.toggleModal();
                await this.$store.dispatch("show",[`${this.model}/${post_id}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    let tags = data[0].movie_tags;
                    for (let i =0; i<tags.length;i++){
                        this.titles.push(tags[i].title)
                        this.links.push(tags[i].link)
                    }
                    this.tagCount = tags.length;
                    this.id=data[1];
                    this.movie_id = data[0].id;
                    this.loading = false;
                }else{
                    this.$refs.modal.toggleModal();
                    this.loading = this.$store.state.isLoading;
                }
            },
            async update(){
                this.loading = true;
                this.emptyError();
                let data = [
                    `${this.model}/${this.id}`,
                    this.setData()
                ]
                await this.$store.dispatch('update',data)
                await this.allData(false,this.current)
                this.loading = false
            },
            async destroy(post_id){
                await this.$store.dispatch('deleteRecord',[`${this.model}/${post_id}`,''])
                await this.allData(true,this.current)
                this.loading = false
            },
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['movies','contentTitle']]);
                this.loading = false;
            },
            setData(){
                if(this.titles.includes(null) || this.links.includes(null)){
                    Alert.show('هشدار','یک یا چند تا از موارد عنوان یا لینک  خالی است','warning',8000)
                }else{
                    return {
                        movie_id:this.movie_id,
                        titles:this.titles,
                        links:this.links,
                    }
                }

            },
            clear(){
                this.movie_id = null;
                this.titles = [];
                this.links = [];
                this.tagCount = 0;
            },
            showAlert(){
                Alert.show('هشدار','برای ویرایش سطوح دسترسی لازم از کاربر مدیر سایت شود','warning',6000)
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>