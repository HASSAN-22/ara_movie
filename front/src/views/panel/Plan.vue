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
                <Thead :titles="['عنوان','قیمت','تصویر','تعداد روز ها','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="قیمت" class="rem--7">{{item.price}} ریال</Td>
                        <Td title="تصویر" class="rem--7">
                            <img v-if="item.image !== null" :src="url+item.image" alt="" class="fm:w-16 fm:h-16 w-32 h-32">
                        </Td>
                        <Td title="تعداد روز ها" class="rem--7">{{item.days}}</Td>
                        <Td title="عملیات">
                          <div class="flex justify-center items-center">
                            <Button text="" color="blue" v-tooltip="'ویرایش'" @click="show(`${item.id}`)"><i class="fas rem--7 fa-edit text-blue-700"></i></Button>
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
            <Input v-model="title" id="title" title="عنوان"></Input>
            <Input v-model="price" id="price" title="قیمت (ریال)" @keydown="toEnglishDigits"></Input>
            <Input v-model="days" id="days" title="تعداد روز ها" @keydown="toEnglishDigits"></Input>
            <div class="items-center mt-8 mb-6">
                <div class="relative z-0 group w-full">
                    <textarea v-model="description" id="description" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer">{{ description }}</textarea>
                    <label for="description" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        توضیحات
                    </label>
                    <small class="text-red-500 rem--7">حداکثر تعداد کاراکتر ۷۰۰</small>

                </div>
            </div>
            <div>
                <Button :inputBtn="true" :text="filePath.length <= 0 ? 'تصویر' : imageName()" color="blue" @click="showGallery()"></Button>
                <img v-if="image !== null" class="mt-3" :src="checkImage()" alt="" width="120" height="120">
            </div>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} های VIP`" description="" />
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
    import Gallery from '@/components/Gallery'

    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'Plan',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading,Gallery},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                id:null,
                isUpdate:false,
                loading:false,
                model:'plan',
                title:null,
                price:null,
                days:null,
                description:null,
                image:null,
                filePath:[]
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle']])
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
            async show(post_id){
                this.emptyError();
                this.clear();
                this.loading = this.isUpdate = true;
                this.$refs.modal.toggleModal();
                await this.$store.dispatch("show",[`${this.model}/${post_id}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    this.id=data.id;
                    this.title = data.title;
                    this.price = data.price.toString();
                    this.days = data.days;
                    this.image = data.image;
                    this.description = data.description;
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
            showGallery(){
                this.$refs.galleryMedia.toggleGalleryModal()
            },
            imageName(){
                if(this.image !== null){
                    let img = this.image.split('/');
                    return img[img.length - 1];
                }else{
                    return 'تصویر'
                }
            },
            checkImage(){
                if(this.image !== null){
                    return this.url+this.image
                }
            },
            setData(){
                return {
                    title:this.title,
                    price:this.convertAmount(this.price),
                    days:this.days,
                    description:this.description,
                    image:this.image,
                }
            },
            clear(){
                this.title = null;
                this.price = null;
                this.days = null;
                this.description = null;
                this.image = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            },
            convertAmount(amount){
                if(amount !== null){
                    return parseInt(amount.replaceAll(',',''));
                }
            }
        },
        watch:{
            price(n,o){
                if(n !== null){
                    this.price = new Intl.NumberFormat().format(this.convertAmount(n))
                }
            },
            filePath(n,o){
                if(n !== null){
                  this.image = n;
                }
            }
        }
    }
</script>