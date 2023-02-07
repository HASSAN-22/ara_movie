<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7"> {{contentTitle}} سایت</li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3">
            <div><Error :errors="getErrors"></Error></div>
            <div>
              <div class="grid grid-cols-2 gap-5">
                <Input v-model="bc_link" id="bc_link" title="لینک سایت (بک اند)"></Input>
                <Input v-model="front_link" id="front_link" title="لینک سایت (فرانت)"></Input>
              </div>
              <div class="grid grid-cols-2 gap-5">
                <Input v-model="site_name" id="site_name" title="نام سایت"></Input>
                <Input v-model="email" type="email" id="email" title="ایمیل" :required="false"></Input>
              </div>
              <div class="grid grid-cols-2 gap-5">
                <Input v-model="mobile" id="mobile" @keydown="toEnglishDigits" title="موبایل" :required="false"></Input>
                <Input v-model="phone" id="phone" @keydown="toEnglishDigits" title="تلفن" :required="false"></Input>
              </div>
              <div class="grid grid-cols-2 gap-5">
                <Input v-model="telegram" id="telegram" title="تلگرام" :required="false"></Input>
                <Input v-model="instagram" id="instagram" title="اینستاگرام" :required="false"></Input>
              </div>
              <div class="grid grid-cols-2 gap-5">
                <Input v-model="twitter" id="twitter" title="تویتر" :required="false"></Input>
                <Input v-model="facebook" id="facebook" title="فیسبوک" :required="false"></Input>
              </div>
<!--              <div class="grid grid-cols-2 gap-5">-->
<!--                <Input v-model="captcha_key" id="captcha_key" title="کلید کپچا"></Input>-->
<!--                <div>-->
<!--                  <label for="captcha" class="block font-medium text-gray-300 rem&#45;&#45;7">کپچا <b class="text-red-600 rem&#45;&#45;7">*</b> </label>-->
<!--                  <select id="captcha" v-model="captcha" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem&#45;&#45;7 focus:outline-none block w-full">-->
<!--                    <option value="yes">فعال</option>-->
<!--                    <option value="no">غیر فعال</option>-->
<!--                  </select>-->
<!--                </div>-->
<!--              </div>-->
              <div class="gap-5 grid grid-cols-1 fm:grid-cols-1 mb-8">
                <div class="relative z-0 group w-full">
                  <textarea v-model="description" id="description" cols="7" placeholder=" " rows="3" class="block pt-2 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer">{{ description }}</textarea>
                  <label for="description" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    توضیحات
                  </label>
                  <small class="rem--7">حداکثر تعداد کاراکتر ۷۰۰</small>
                </div>
              </div>
              <div>
                <Input v-model="omdb_api" id="omdb_api" title="OMDB Api" :required="false"></Input>
              </div>
              <div class="grid grid-cols-2 fm:grid-cols-1 gap-5">
                <Input v-model="min_amount" id="min_amount" @keydown="toEnglishDigits" title="حداقل مبلغ درخواست و برداشت (ریال)"></Input>
                <Input v-model="max_amount" id="max_amount" @keydown="toEnglishDigits" title="حداکثر مبلغ درخواست و برداشت (ریال)"></Input>
              </div>
              <div class="gap-5 grid grid-cols-2 fm:grid-cols-1 mb-8">
                 <div class="relative z-0 group w-full">
                      <textarea v-model="alert" id="alert" cols="7" placeholder=" " rows="3" class="block pt-2 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer">{{ alert }}</textarea>
                      <label for="alert" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                          اعلان بالای صفحه
                      </label>
                      <small class="rem--7">حداکثر تعداد کاراکتر ۷۰۰</small>
                  </div>
                <Input v-model="alert_link" id="alert_link" title="لینک اعلان" :required="false"></Input>
              </div>
              <div class="grid grid-cols-4 gap-5 fm:grid-cols-1" v-if="front !== null">
                <div>
                  <label for="about_us" class="block font-medium text-gray-300 rem--7">نمایش {{ front.about.title }} <b class="text-red-600 rem--7">*</b> </label>
                  <select id="about_us" v-model="about_us" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="yes">فعال</option>
                    <option value="no">غیر فعال</option>
                  </select>
                </div>
                <div>
                  <label for="contact_us" class="block font-medium text-gray-300 rem--7">نمایش {{ front.contactUs.title }} <b class="text-red-600 rem--7">*</b> </label>
                  <select id="contact_us" v-model="contact_us" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="yes">فعال</option>
                    <option value="no">غیر فعال</option>
                  </select>
                </div>
                <div>
                  <label for="page" class="block font-medium text-gray-300 rem--7">وضعیت {{ front.page.title }} <b class="text-red-600 rem--7">*</b> </label>
                  <select id="page" v-model="page" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="yes">فعال</option>
                    <option value="no">غیر فعال</option>
                  </select>
                </div>
                <div>
                  <label for="vip" class="block font-medium text-gray-300 rem--7">وضعیت {{ front.vip.title }} <b class="text-red-600 rem--7">*</b> </label>
                  <select id="vip" v-model="vip" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="yes">فعال</option>
                    <option value="no">غیر فعال</option>
                  </select>
                </div>
              </div>
              <div class="fm:mt-5">
                <Input v-model="copy_right" id="copy_right" title="کپی رایت"></Input>
              </div>
              <div class="grid grid-cols-2 gap-5">
                <div>
                  <Button :inputBtn="true" :text="logo === null ? 'لوگو' : imageName('logo')" color="blue" @click="showGallery('logo')"><b class="text-red-600 rem--7">*</b> </Button>
                  <img v-if="logo !== null" class="mt-3" :src="checkImage('logo')" alt="" width="120" height="120">
                </div>
                <div>
                  <Button :inputBtn="true" :text="logo_mobile === null ? 'لوگو نسخه موبایل' : imageName('logo_mobile')" color="blue" @click="showGallery('logo_mobile')"><b class="text-red-600 rem--7">*</b> </Button>
                  <img v-if="logo_mobile !== null" class="mt-3" :src="checkImage('logo_mobile')" alt="" width="120" height="120">
                </div>
              </div>
              <div class="my-8">
                <Button color="blue" text="ثبت تغییرات" @click="update()"></Button>
              </div>
            </div>
          </div>
        </div>
        <Meta :key="contentTitle" :title="` ${contentTitle} سایت`" description="" />
        <Loading :loading="loading" />
        <Gallery ref="galleryLogo"></Gallery>
        <Gallery ref="galleryLogoMobile"></Gallery>
    </div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Error from '@/components/Error'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'
    import Gallery from '@/components/Gallery'
    import axios from "../../plugins/axios";
    import {mapGetters, mapMutations, mapState} from "vuex";
    import Alert from "../../plugins/alert";
    export default {
        name:'Plan',
        components:{Input,Button,Meta,Error,Loading,Gallery},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                id:null,
                isUpdate:false,
                loading:false,
                model:'configSite',
                logo_mobile:null,
                logo:null,
                site_name:null,
                email:null,
                mobile:null,
                phone:null,
                copy_right:null,
                telegram:null,
                twitter:null,
                facebook:null,
                instagram:null,
                omdb_api:null,
                captcha_key:null,
                min_amount:null,
                max_amount:null,
                captcha:null,
                about_us:null,
                contact_us:null,
                front_link:null,
                bc_link:null,
                page:null,
                vip:null,
                alert:null,
                alert_link:null,
                description:null,
                contentTitle:null,
                front:null,
                showMobileLogo:false,
                showLogo:false,
                filePath:[]
            }
        },
        mounted() {
            this.show(1)
        },
        computed:{
            ...mapGetters(["getErrors"]),
            ...mapState(["current","next","previous","total"])
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async show(post_id){
                  this.emptyError();
                  this.clear();
                  this.loading = this.isUpdate = true;
                  await this.$store.dispatch("show",[`${this.model}/${post_id}`])
                  let data = this.$store.getters.getSingleData;
                  if(data[0] !== null){
                    this.contentTitle = data[1];
                    this.front = data[2];
                    data = data[0];
                    this.id=data.id;
                    this.logo_mobile = data.logo_mobile;
                    this.logo = data.logo;
                    this.site_name = data.site_name;
                    this.email = data.email;
                    this.mobile = data.mobile;
                    this.description = data.description;
                    this.phone = data.phone;
                    this.captcha = data.captcha;
                    this.copy_right = data.copy_right;
                    this.telegram = data.telegram;
                    this.twitter = data.twitter;
                    this.facebook = data.facebook;
                    this.instagram = data.instagram;
                    this.omdb_api = data.omdb_api;
                    this.captcha_key = data.captcha_key;
                    this.min_amount = data.min_amount;
                    this.max_amount = data.max_amount;
                    this.about_us = data.about_us;
                    this.contact_us = data.contact_us;
                    this.page = data.page;
                    this.vip = data.vip;
                    this.alert = data.alert;
                    this.alert_link = data.alert_link;
                    this.front_link = data.front_link;
                    this.bc_link = data.bc_link;
                    this.loading = false;
                  }else{
                    this.loading = this.$store.state.isLoading;
                  }
            },
            async update(){
                this.loading = true;
                await axios.patch(this.api+'configSite/'+this.id,this.setData()).then(resp=>{
                  Alert.show('موفق','با موفقیت .یرایش شد')
                  this.showTimeOut()
                }).catch(err=>{
                  console.log(err.code);
                  if(err.response !== undefined && err.code !== 'ERR_NETWORK'){
                    this.$store.commit('catchError',err.response);
                    this.loading = false;
                  }else{
                    Alert.show('موفق','با موفقیت .یرایش شد')
                    this.showTimeOut()
                  }
                })
            },
            showTimeOut(){
              setTimeout(()=>{
                this.show(this.id)
              },200)
            },
            showGallery(action){
                if(action === 'logo'){
                  this.showLogo = true;
                  this.showMobileLogo = false;
                  this.$refs.galleryLogo.toggleGalleryModal()
                }else{
                  this.showLogo = false;
                  this.showMobileLogo = true;
                  this.$refs.galleryLogoMobile.toggleGalleryModal()
                }
            },
            imageName(action){
                if(action === 'logo'){
                  if(this.logo !== null){
                    let img = this.logo.split('/');
                    return img[img.length - 1];
                  }
                }else{
                  if(this.logo_mobile !== null){
                    let img = this.logo_mobile.split('/');
                    return img[img.length - 1];
                  }
                }

            },
            checkImage(action){
                if(action === 'logo'){
                  if(this.logo !== null){
                    return this.url+this.logo
                  }
                }else{
                  if(this.logo_mobile !== null){
                    return this.url+this.logo_mobile
                  }
                }
            },
            setData(){
                return {
                  logo_mobile:this.logo_mobile,
                  logo:this.logo,
                  site_name:this.site_name,
                  email:this.email,
                  mobile:this.mobile,
                  phone:this.phone,
                  copy_right:this.copy_right,
                  telegram:this.telegram,
                  twitter:this.twitter,
                  facebook:this.facebook,
                  instagram:this.instagram,
                  omdb_api:this.omdb_api,
                  captcha_key:this.captcha_key,
                  min_amount:this.convertAmount(this.min_amount),
                  max_amount:this.convertAmount(this.max_amount),
                  about_us:this.about_us,
                  contact_us:this.contact_us,
                  page:this.page,
                  vip:this.vip,
                  alert:this.alert,
                  alert_link:this.alert_link,
                  captcha:this.captcha,
                  front_link:this.front_link,
                  bc_link:this.bc_link,
                  description:this.description
                }
            },
            clear(){
                this.logo_mobile = null;
                this.logo = null;
                this.site_name = null;
                this.email = null;
                this.mobile = null;
                this.phone = null;
                this.copy_right = null;
                this.telegram = null;
                this.twitter = null;
                this.facebook = null;
                this.instagram = null;
                this.omdb_api = null;
                this.captcha_key = null;
                this.min_amount = null;
                this.max_amount = null;
                this.about_us = null;
                this.contact_us = null;
                this.vip = null;
                this.page = null;
                this.alert = null;
                this.alert_link = null;
                this.captcha = null;
                this.front_link = null;
                this.bc_link = null;
                this.description = null;
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
            min_amount(n,o){
                if(n !== null){
                    this.min_amount = new Intl.NumberFormat().format(this.convertAmount(n))
                }
            },
           max_amount(n,o){
                if(n !== null){
                    this.max_amount = new Intl.NumberFormat().format(this.convertAmount(n))
                }
            },
            filePath(n,o){
                if(n !== null){
                  if(this.showMobileLogo){
                    this.logo_mobile = n;
                  }else if(this.showLogo){
                    this.logo = n;
                  }
                }
            }
        }
    }
</script>