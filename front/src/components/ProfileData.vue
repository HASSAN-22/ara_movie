<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

            <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
                <ul class="inline-flex ">
                  <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
                    <li class="mr-2 text-gray-500 rem--7">پروفایل </li>
                </ul>
            </div>

            <div class="mt-10 px-3 mb-3">
                <div><Error :errors="getErrors"></Error></div>
                <div class="mt-8 mb-8 bg-yellow-400 text-red-500 !font-bold p-3 rm-7 w-full">
                    کاربر گرامی یک کد تایید به ایمیل شما ارسال شده است برای ویرایش پروفایل کد ارسال شده را در قسمت ( کد تایید ) وارد نمایید
                </div>
                <Input v-model="name" id="name" title="نام و نام خانوادگی"></Input>
                <Input v-model="email" id="email" type="email" title="ایمیل"></Input>
                <Input v-model="mobile" id="mobile" title="موبایل" @keydown="toEnglishDigits"></Input>
                <Input v-model="p_password" type="password" id="p_password" title="رمز عبور قبلی"></Input>
                <Input v-model="password" type="password" id="password" title="رمز عبور جدید"></Input>
                <Input v-model="confirm_code" id="confirm_code" title="کد تایید"></Input>
                <div>
                    <Button class="mt-5" color="blue" text="ثبت تغییرات" @click="update()"></Button>
                </div>
            </div>

        </div>
        <Meta title="ویرایش پروفایل" description="" />
        <Loading :loading="loading" :text="text" />
    </div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Error from '@/components/Error'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations} from "vuex";
    export default {
        name:'ProfileData',
        props:['model'],
        components:{Input,Button,Meta,Error,Loading},
        data(){
            return{
                url:process.env.VUE_APP_API,
                id:null,
                isUpdate:false,
                loading:false,
                name:null,
                email:null,
                mobile:null,
                password:null,
                p_password:null,
                confirm_code:null,
                text:'',
            }
        },
        mounted() {
            this.show(localStorage.getItem('id'));
        },
        computed:{
            ...mapGetters(["getErrors"]),
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async show(post_id){
                this.text = 'در حال ارسال ایمیل'
                this.clear();
                this.emptyError();
                this.loading = this.isUpdate = true;
                await this.$store.dispatch("show",[`${this.model}/${post_id}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    this.id=data.id;
                    this.name = data.name;
                    this.email = data.email;
                    this.mobile = data.mobile;
                    this.password = null;
                    this.p_password = null;
                    this.loading = false;
                }else{
                    this.loading = this.$store.state.isLoading;
                }
                this.text = '';
            },
            async update(){
                this.loading = true;
                this.emptyError();
                let data = [
                    `${this.model}/${this.id}`,
                    this.setData()
                ]
                await this.$store.dispatch('updateImage',data)
                await this.$store.dispatch('checkToken')
                this.loading = false
                this.show(this.id);
            },
            setData() {
              return {
                id: this.id,
                name: this.name,
                email: this.email,
                mobile: this.mobile,
                password: this.password,
                p_password: this.p_password,
                confirm_code: this.confirm_code
              }
            },
            clear(){
                this.name = null;
                this.email = null;
                this.mobile = null;
                this.password = null;
                this.p_password = null;
                this.confirm_code = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>