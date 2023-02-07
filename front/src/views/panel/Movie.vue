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
            <div class="mb-3 mr-2 flex justify-between fm:flex-col items-center">
                <Button text="اضافه کردن" color="green" @click="create()"></Button>
                <div class="ml-2 fm:mt-3">
                    <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
                </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['عنوان','برای دسته','نوع','تصویر','زیرنویس پارسی','دوبله پارسی','وضعیت نمایش','وضعیت ارسال نظر','برای به زودی ها','تاریخ','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="برای دسته" class="rem--7">{{item.category}}</Td>
                        <Td title="نوع" class="rem--7">{{item.type}}</Td>
                        <Td title="تصویر" class="rem--7"><img :src="url+item.image" alt="" width="70" height="70"></Td>
                        <Td title="زیرنویس پارسی" class="rem--7">{{item.subtitle}}</Td>
                        <Td title="دوبله پارسی" class="rem--7">{{item.dubbed}}</Td>
                        <Td title="وضعیت نمایش" class="rem--7">{{item.status}}</Td>
                        <Td title="وضعیت ارسال نظر" class="rem--7">{{item.status_comment}}</Td>
                        <Td title="برای به زودی ها" class="rem--7">{{item.soon}}</Td>
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
        <Modal width="fm:w-[92%]" :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <Input v-debounce:2s="omdb" :debounce-events="['keydown']" id="omdb" :required="false" title="دریافت اطلاعات فیلم از omdb | مثال: legacies"></Input>

            <div class="grid grid-cols-2 gap-3 items-center">
                <Input v-model="title" id="title"  title="نام فیلم / سریال"></Input>
                <Input v-model="fa_title" id="fa_title"  title="نام پارسی فیلم / سریال"></Input>
            </div>
            <div>
                <div class="mb-7">
                    <label for="type" class="block mt-5 font-medium text-gray-300 rem--7">نوع <b class="text-red-600 rem--7">*</b> </label>
                    <select id="type" v-model="type" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                        <option value="null">--- انتخاب نوع ---</option>
                        <option value="serial">سریال</option>
                        <option value="movie">ویدیو</option>
                    </select>
                </div>
            </div>
            <div class="mb-7">
                <label for="category_id" class="block mt-5 font-medium text-gray-300 rem--7">برای دسته <b class="text-red-600 rem--7">*</b> </label>
                <select id="category_id" v-model="category_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null">--- انتخاب دسته ---</option>
                    <option v-for="category in getAllData.categories" :key="category.id" :value="category.id">{{category.title}}</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3 items-center">
                <Input v-model="quality" id="quality" title="کیفیت" :required="false"></Input>
                <Input v-model="genre" id="genre" title="ژانر" :required="false"></Input>
            </div>
            <div class="grid grid-cols-2 gap-3 items-center">
                <Input v-model="product" id="product" title="محصول" :required="false"></Input>
                <Input v-model="lang" id="lang" title="زبان" :required="false"></Input>
            </div>
             <div class="grid grid-cols-2 gap-3 items-center">
                <Input v-model="published_at" id="published_at" :title="type === 'serial' ? 'سال های پخش' : 'سال انتشار'" :required="false"></Input>
                <Input v-model="time" id="time" :title="type === 'serial' ? 'مدت زمان هر قسمت' : 'مدت زمان'" :required="false"></Input>
            </div>
            <div class="grid grid-cols-2 gap-3 items-center">
                <Input v-model="director" id="director" title="کارگردان" :required="false"></Input>
                <Input v-model="actor" id="actor" title="بازیگران" :required="false"></Input>
            </div>
            <div class="grid grid-cols-4 gap-3 items-center">
                <Input v-model="imdb" id="imdb" title="IMDB" :required="false"></Input>
                <Input v-model="critics" id="critics" title="نمره منتقدین" :required="false"></Input>
                <Input v-model="rank" id="rank" title="رتبه" :required="false"></Input>
                <Input v-model="pg" id="pg" title="رده سنی" :required="false"></Input>
            </div>
            <div class="grid grid-cols-2 gap-3 items-center">
                <Input v-model="imdbId" id="imdbId" title="ایدی IMDB"></Input>
                <Input v-model="awards" id="awards" title="جوایز" :required="false"></Input>
            </div>

            <div v-if="type === 'serial'">
                 <div class="grid grid-cols-3 gap-3 items-center">
                    <div>
                        <label for="play_status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت پخش </label>
                        <select id="play_status" v-model="play_status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                            <option value="yes">در حال پخش</option>
                            <option value="no">پایان یافته</option>
                        </select>
                    </div>
                    <Input v-model="broadcast_day" id="broadcast_day" title="روز پخش" :required="false"></Input>
                    <Input v-model="network" id="network" title="شبکه" :required="false"></Input>
                </div>
            </div>

            <div class="items-center mt-8 mb-6">
                <div class="relative z-0 group w-full">
                    <textarea v-model="description" id="description" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer">{{ description }}</textarea>
                    <label for="description" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        خلاصه داستان<b class="text-red-600 rem--7">*</b>
                    </label>
                </div>
            </div>
            <div class="items-center mt-8 mb-6">
              <div class="relative z-0 group w-full">
                <textarea v-model="moreDescription" id="moreDescription" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer">{{ moreDescription }}</textarea>
                <label for="moreDescription" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                  توضیحات بیشتر
                </label>
              </div>
            </div>
            <div class="items-center mt-8 mb-6">
              <div class="relative z-0 group w-full">
                <textarea v-model="keyword" id="keyword" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer">{{ keyword }}</textarea>
                <label for="keyword" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                  کلمات کلیدی
                </label>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3 items-center">
                <div>
                    <label for="subtitle" class="block mt-5 font-medium text-gray-300 rem--7">زیرنویس پارسی چسبیده <b class="text-red-600 rem--7">*</b> </label>
                    <select id="subtitle" v-model="subtitle" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                        <option value="yes">بله</option>
                        <option value="no">خیر</option>
                    </select>
                </div>
                <div>
                    <label for="dubbed" class="block mt-5 font-medium text-gray-300 rem--7">نسخه دوبله پارسی <b class="text-red-600 rem--7">*</b> </label>
                    <select id="dubbed" v-model="dubbed" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                        <option value="yes">بله</option>
                        <option value="no">خیر</option>
                    </select>
                </div>
            </div>
          <div class="grid grid-cols-1 gap-3 items-center">
            <div>
              <label for="soon" class="block mt-5 font-medium text-gray-300 rem--7">انتخاب برای به زودی<b class="text-red-600 rem--7">*</b> </label>
              <select id="soon" v-model="soon" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                <option value="null">--- انتخاب برای به زودی ---</option>
                <option value="yes">بله</option>
                <option value="no">خیر</option>
              </select>
            </div>
          </div>
            <div class="grid grid-cols-2 gap-3 items-center">
                <div>
                    <label for="status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت نمایش <b class="text-red-600 rem--7">*</b> </label>
                    <select id="status" v-model="status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                        <option value="yes">بله</option>
                        <option value="no">خیر</option>
                    </select>
                </div>
                <div>
                    <label for="status_comment" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت ارسال نظر <b class="text-red-600 rem--7">*</b> </label>
                    <select id="status_comment" v-model="status_comment" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                        <option value="yes">بله</option>
                        <option value="no">خیر</option>
                    </select>
                </div>
            </div>

            <div>
                <Button :inputBtn="true" :text="filePath.length <= 0 ? 'تصویر' : imageName()" color="blue" @click="showGallery()"><b class="text-red-500 rem--7">*</b></Button>
                <img class="mt-3" :src="checkImage()" alt="" width="120" height="120">
            </div>

        </Modal>
        <Loading :loading="loading"></Loading>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle}ها`" description="" />
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
    import Gallery from '@/components/Gallery'
    import Alert from '@/plugins/alert'
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'Movie',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading,Gallery},
        data(){
            return{
                url:process.env.VUE_APP_API,
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'movie',
                title:null,
                fa_title:null,
                type:null,
                quality:null,
                genre:null,
                product:null,
                lang:null,
                published_at:null,
                time:null,
                director:null,
                moreDescription:null,
                actor:null,
                imdb:null,
                critics:null,
                rank:null,
                pg:null,
                play_status:null,
                broadcast_day:null,
                network:null,
                keyword:null,
                subtitle:'yes',
                dubbed:'yes',
                category_id:null,
                status:'yes',
                status_comment:'yes',
                soon:'no',
                image:null,
                imdbId:null,
                awards:null,
                description:null,
                filePath:[]
            }
        },
        created() {
            this.allData()
        },
        computed:{
            ...mapGetters(["getAllData","getErrors"]),
            ...mapState(["current","next","previous","total"])
        },
        methods:{
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['categories','contentTitle']])
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
                    this.id=data.id;
                    this.category_id = data.category_id;
                    this.title = data.title;
                    this.fa_title = data.fa_title;
                    this.type = data.type;
                    this.quality = data.quality;
                    this.genre = data.genre;
                    this.product = data.product;
                    this.lang = data.lang;
                    this.published_at = data.published_at;
                    this.time = data.time;
                    this.director = data.director;
                    this.moreDescription = data.moreDescription;
                    this.actor = data.actor;
                    this.imdb = data.imdb;
                    this.critics = data.critics;
                    this.rank = data.rank;
                    this.pg = data.pg;
                    this.keyword = data.keyword;
                    this.play_status = data.play_status;
                    this.broadcast_day = data.broadcast_day;
                    this.network = data.network;
                    this.subtitle = data.subtitle;
                    this.dubbed = data.dubbed;
                    this.image = data.image;
                    this.status = data.status;
                    this.status_comment = data.status_comment;
                    this.soon = data.soon;
                    this.imdbId = data.imdbId;
                    this.awards = data.awards;
                    this.description = data.description;
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
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['categories','contentTitle']]);
                this.loading = false;
            },
            async omdb(text){
                this.loading = true;
                this.title = text;
                await this.$store.dispatch("show",[`get-info-movie?title=${text}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    this.fillData(data)
                }
                this.loading = false;
            },
            showGallery(){
                this.$refs.galleryMedia.toggleGalleryModal()
            },
            imageName(){
                if(this.image !== null){
                    let img = this.image != null ? this.image.split('/') : this.image;
                    return img[img.length - 1];
                }
            },
            fillData(data){
                this.fa_title = data.Title;
                this.type = data.Type === 'series' ? 'serial' : 'movie';
                this.genre = data.Genre;
                this.product = data.Country;
                this.lang = data.Language;
                this.published_at = this.type === 'serial' ? data.Year : data.Released;
                this.time = data.Runtime;
                this.director = data.Director;
                this.actor = data.Actors;
                this.imdb = `${data.imdbRating} از 10 میانگین رای ${data.imdbVotes} نفر`;
                this.critics = `${data.Metascore} از  100`;
                this.pg = data.Rated;
                this.image = data.Poster;
                this.description = data.Plot;
                this.awards = data.Awards;
                this.imdbId = data.imdbID

            },
            checkImage(){
              if(this.image !== null){
                  if(!this.image.includes('https://m.media-amazon.com/')){
                      return this.url+this.image;
                  }else{
                      return this.image;
                  }
              }
            },
            setData(){
                return {
                    category_id:this.category_id,
                    title:this.title,
                    fa_title:this.fa_title,
                    type:this.type,
                    quality:this.quality,
                    genre:this.genre,
                    product:this.product,
                    lang:this.lang,
                    published_at:this.published_at,
                    time:this.time,
                    director:this.director,
                    actor:this.actor,
                    imdb:this.imdb,
                    critics:this.critics,
                    moreDescription:this.moreDescription,
                    rank:this.rank,
                    pg:this.pg,
                    play_status:this.play_status,
                    broadcast_day:this.broadcast_day,
                    network:this.network,
                    subtitle:this.subtitle,
                    dubbed:this.dubbed,
                    image:this.image,
                    status:this.status,
                    status_comment:this.status_comment,
                    soon:this.soon,
                    imdbId:this.imdbId,
                    awards:this.awards,
                    keyword:this.keyword,
                    description:this.description,
                }
            },
            clear(){
                this.title = null;
                this.fa_title = null;
                this.category_id = null;
                this.type = null;
                this.quality = null;
                this.genre = null;
                this.product = null;
                this.lang = null;
                this.published_at = null;
                this.moreDescription = null;
                this.time = null;
                this.director = null;
                this.actor = null;
                this.imdb = null;
                this.critics = null;
                this.rank = null;
                this.pg = null;
                this.play_status = null;
                this.broadcast_day = null;
                this.network = null;
                this.keyword = null;
                // this.subtitle = null;
                // this.dubbed = null;
                // this.vip = null;
                // this.status = null;
                this.image = null;
                // this.status_comment = null;
                // this.soon = null;
                this.imdbId = null;
                this.awards = null;
                this.description = null;
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
                this.image = n;
            }
        }
    }
</script>