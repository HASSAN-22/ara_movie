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
                <Thead :titles="['فیلم یا سریال','عنوان','دارای اشتراک','تاریخ','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="فیلم یا سریال" class="rem--7">{{item.movie}}</Td>
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="دارای اشتراک" class="rem--7">{{item.vip}}</Td>
                        <Td title="تاریخ" class="rem--7">{{item.created_at}}</Td>
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
            <div class="mb-8 grid grid-cols-3 fm:grid-cols-1 gap-3">
              <div class="">
                <label for="movie_id" class="block font-medium text-gray-300 dark:text-gray-400 rem--7">برای فیلم / سریال <b class="text-red-600 rem--7">*</b> </label>
                <select id="movie_id" v-model="movie_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                  <option value="null" disabled selected>--- انتخاب فیلم / سریال ---</option>
                  <option v-for="movie in getAllData.movies" :key="movie.id" :value="movie.id">{{movie.title}}</option>
                </select>
              </div>
              <div class="mt-4">
                <Input v-model="caption_movie" id="caption_movie" title="کپشن" :required="false" placeholder="کپشن (برای سریال) مثال: فصل ۱ قسمت ۵ اضافه شد"></Input>
              </div>
                <div>
                    <label for="vip" class="block font-medium text-gray-300 rem--7">دارای اشتراک <b class="text-red-600 rem--7">*</b> </label>
                    <select id="vip" v-model="vip" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                        <option value="yes">بله</option>
                        <option value="no">خیر</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <Input v-model="title" id="title" title="عنوان مثال: فصل اول يا نسخه دوبله"></Input>
                <Button :inputBtn="true" :text="screenShot !==null ? imageName(screenShot) : 'اسکرین شات'" color="blue" @click="showGallery('screenShot')"></Button>

            </div>
            <div>
                <Button color="green" text="اضافه کردن تریلر" @click="addTrailer()"></Button>
                <div class="grid grid-cols-2 gap-3 relative" v-for="count in trailerCount" :key="count-1">
                    <Button :inputBtn="true" :text="(trailers.length > 0 && trailers[count - 1] !== null) ? imageName(trailers[count-1]) : 'تریلر'" color="blue" @click="showGallery('trailer',count-1)"><b class="text-red-500 rem--7">*</b></Button>
                    <Input v-model="trailerCaptions[count-1]" id="trailerCaption" title="کپشن"></Input>
                    <div class="absolute left-0 top-0">
                        <span class="cursor-pointer" @click="removeTrailer(count-1)"><i class="fa fa-trash text-red-500 rem--7"></i></span>
                    </div>
                </div>
            </div>
            <div class="mt-12">
                <Button color="blue" text="اضافه کردن لینک" @click="addLink()"></Button>
                <div class="grid grid-cols-4 fm:grid-cols-2 gap-3 mt-4 relative" v-for="count in linkCount" :key="count-1">
                    <Input v-model="titles[count - 1]" id="titles" title="عنوان مثال: قسمت اول | کیفیت ۷۲۰"></Input>
                    <Input v-model="links[count - 1]" id="links" title="لینک"></Input>
                    <Input v-model="captions[count - 1]" id="captions" title="کپشن" :required="false"></Input>
                    <Button :inputBtn="true" :text="(subtitles.length > 0 && subtitles[count - 1] !== null) ? imageName(subtitles[count-1]) : 'زیرنویس'" color="blue" @click="showGallery('subtitle',count-1)"></Button>
                    <div class="absolute left-0 top-0">
                        <span class="cursor-pointer" @click="removeLink(count-1)"><i class="fa fa-trash text-red-500 rem--7"></i></span>
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
        name:'MovieLink',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading,Gallery},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'movieLink',
                movie_id:null,
                caption_movie:null,
                title:null,
                vip:null,
                screenShot:null,
                trailers:[],
                trailerCaptions:[],
                titles:[],
                links:[],
                captions:[],
                subtitles:[],
                linkCount:0,
                trailerCount:0,
                isScreenShot:false,
                isTrailer:false,
                isSubtitle:false,
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
            addTrailer(){
              this.trailerCount ++;
                this.trailers.push(null);
                this.trailerCaptions.push(null);
            },
            addLink(){
                this.linkCount ++;
                this.titles.push(null);
                this.links.push(null);
                this.captions.push(null);
                this.subtitles.push(null);
            },
            removeTrailer(count){
                this.trailerCount --;
                this.trailers = this.trailers.filter((value,key)=>{return key !== count})
                this.trailerCaptions = this.trailerCaptions.filter((value,key)=>{return key !== count})
            },
            removeLink(count){
                this.linkCount --;
                this.titles = this.titles.filter((value,key)=>{return key !== count})
                this.links = this.links.filter((value,key)=>{return key !== count})
                this.captions = this.captions.filter((value,key)=>{return key !== count})
                this.subtitles = this.subtitles.filter((value,key)=>{return key !== count})
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
                    let links = data.links;
                    let trailers = data.trailers;
                    for (let i =0; i<trailers.length;i++){
                        this.trailers.push(trailers[i].trailer)
                        this.trailerCaptions.push(trailers[i].caption)
                    }

                    for (let i =0; i<links.length;i++){
                        this.titles.push(links[i].title)
                        this.links.push(links[i].link)
                        this.captions.push(links[i].caption)
                        this.subtitles.push(links[i].subtitle)
                    }
                    this.linkCount = links.length;
                    this.trailerCount = trailers.length;
                    this.id=data.id;
                    this.movie_id = data.movie_id;
                    this.title = data.title;
                    this.vip = data.vip;
                    this.caption_movie = data.caption_movie;
                    this.screenShot = data.screen_shot !== null ? data.screen_shot.screen_shot : null;
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
            showGallery(action,count = 0){
                this.filePath = [];
                this.$refs.galleryMedia.toggleGalleryModal()
                this.currentCount = count;
                switch (action) {
                    case 'screenShot':
                        this.isScreenShot = true;
                        this.isTrailer = false;
                        this.isSubtitle = false;
                    break;
                    case 'trailer':
                        this.isTrailer = true;
                        this.isScreenShot = false
                        this.isSubtitle = false
                    break;
                    case 'subtitle':
                        this.isTrailer = false;
                        this.isScreenShot = false
                        this.isSubtitle = true
                    break;
                }
            },
            imageName(image){
                if(image !== null && image !== undefined && typeof(image) !== 'object'){
                    let img = image.split('/');
                    return img[img.length - 1];
                }
            },
            setData(){
                if(this.titles.includes(null) || this.links.includes(null)){
                    Alert.show('هشدار','در لینک ها یک یا چند تا از موارد عنوان یا لینک  خالی است','warning',8000)
                }else{
                    return {
                        movie_id:this.movie_id,
                        title:this.title,
                        caption_movie:this.caption_movie,
                        screenShot:this.screenShot,
                        trailers:this.trailers,
                        trailerCaptions:this.trailerCaptions,
                        titles:this.titles,
                        links:this.links,
                        vip:this.vip,
                        captions:this.captions,
                        subtitles:this.subtitles,
                    }
                }

            },
            clear(){
                this.movie_id = null;
                this.title = null;
                this.screenShot = null;
                this.caption_movie = null;
                this.vip = null;
                this.trailers = [];
                this.trailerCaptions = [];
                this.titles = [];
                this.links = [];
                this.captions = [];
                this.subtitles = [];
                this.linkCount = 0;
                this.trailerCount = 0;
            },
            showAlert(){
                Alert.show('هشدار','برای ویرایش سطوح دسترسی لازم از کاربر مدیر سایت شود','warning',6000)
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        },
        watch:{
            filePath(n,o){
                if(n !== null && typeof(n) !== 'object'){
                    if(this.isScreenShot){
                        this.screenShot = n;
                    }else if(this.isTrailer){
                        this.trailers[this.currentCount] = n;
                    }else if(this.isSubtitle){
                        this.subtitles[this.currentCount] = n;
                    }
                }
            }
        }
    }
</script>