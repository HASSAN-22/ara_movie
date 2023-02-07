<template>
    <div class="flex w-full justify-around">
        <div class="flex items-center w-[30%] fm:w-full justify-center box">
            <div class="px-8 py-6 mt-4 w-full text-left box shadow-lg">
                <div class="flex justify-center" v-if="config !== null">
                    <img :src="url+config.logo_mobile" />
                </div>
                <h3 class="text-2xl font-bold text-center rem--8">ورود به اکانت</h3>
                <h6 class="mt-2 text-center"><routerLink :to="{name:'Register'}" class="font-bold rem--6 text-crimson-200 hover:text-crimson-400">اگر ثبت نام نکرده اید ثبت نام کنید</routerLink></h6>
                <div class="mt-4">
                    <div>
                        <Input v-model="mobile" @keydown="toEnglishDigits" title="موبایل" id="mobile"></Input>
                    </div>
                    <div class="mt-4">
                        <Input v-model="password" type="password" title="رمز عبور" id="password"></Input>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <Button text="ورود" @click="login()"></Button>
                        <a href="#" class="text-sm text-crimson-100 rem--6 hover:text-crimson-300 hover:underline" @click="openModal()">آیا رمز خود را فراموش کرده اید؟</a>
                    </div>
                </div>
            </div>
            <Modal title="بازیابی رمز عبور" save="دریافت لینک بازیابی" class="z-10000" @callback="forgetPassword()" ref="ForgetPassword">
                <div><Error :errors="getErrors"></Error></div>
                <Input v-model="email" title="ایمیل" id="email"/>
            </Modal>
            <Meta title="ورود به سایت" description="" />
            <Loading :loading="loading"></Loading>
        </div>
    </div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Error from '@/components/Error'
    import Meta from "@/components/Meta";
    import Loading from '@/components/Loading';
    import Modal from '@/components/Modal'
    import {mapGetters, mapMutations} from "vuex";
    import axios from '../plugins/axios';
    export default{
        name:'Login',
        components:{Button,Input,Error,Meta,Modal,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                mobile:null,
                password:null,
                email:null,
                config:null,
                loading:false,
            }
        },
        mounted() {
            this.$store.state.sidebar = false;
            this.$store.state.b_header = false;
            this.getConfig();
        },
        computed:{
            ...mapGetters(["getErrors"]),
            
        },
        methods:{
             ...mapMutations(["toEnglishDigits"]),
             login(){
                let data=[
                    {'mobile':this.mobile,'password':this.password},
                    'login',
                ]
                this.$store.dispatch('registerAndLogin',data);
            },
            async getConfig(){
                await axios.get(this.api+'get-config').then(resp=>{
                    this.config = resp.data.data;
                })
            },
            async forgetPassword(){
                this.loading = true;
                this.$store.state.errors = [];
                let data=[
                    'forget-password',
                    {'email':this.email},
                    
                ]
                await this.$store.dispatch('insert',data);
                this.loading = false;
                if(this.$store.state.clear){
                    this.$refs.ForgetPassword.toggleModal()
                }
            },
            openModal(){
                this.$refs.ForgetPassword.toggleModal();
            }
        }
    }
</script>