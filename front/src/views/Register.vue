<template>
<div class="flex w-full justify-around" >
    <div class="flex items-center w-[30%] fm:w-full justify-center box">
        <div class="px-8 py-6 mt-4 w-full text-left box shadow-lg">
            
            <div class="flex justify-center" v-if="config !== null">
                <img :src="url+config.logo_mobile" />
            </div>
            <h3 class="text-2xl font-bold text-center rem--8">ثبت نام</h3>
            <h6 class="mt-2 text-center"><routerLink :to="{name:'Login'}" class="font-bold rem--6 text-crimson-200 hover:text-crimson-400">اگر قبلا ثبت نام کرده اید وارد شوید</routerLink></h6>
            <div class="mt-4">
                <div><Error :errors="getErrors"></Error></div>
                <div>
                    <Input v-model="name" title="نام و نام خانوادگی" id="name"></Input>
                </div>
                <div>
                    <Input v-model="mobile" @keydown="toEnglishDigits" title="موبایل" id="mobile"></Input>
                </div>
                <div>
                    <Input v-model="email" title="ایمیل" id="email"></Input>
                </div>
                <div class="mt-4">
                    <Input v-model="password" type="password" title="رمز عبور" id="password"></Input>
                </div>
                <div class="mt-4">
                    <Input v-model="password_confirmation" type="password" title="تکرار رمز عبور" id="password_confirmation"></Input>
                </div>
                <div class="flex items-baseline justify-between">
                    <Button text="ثبت نام" @click="register()"></Button>
                </div>
            </div>
        </div>
        <Meta title="ثبت نام در سایت" description="" />
    </div>
</div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Error from '@/components/Error'
    import Meta from "@/components/Meta";
    import {mapGetters,mapMutations} from "vuex";
    import axios from '../plugins/axios';
    export default{
        name:'Register',
        components:{Button,Input,Meta,Error},
        data(){
            return{
                name:null,
                mobile:null,
                email:null,
                password:null,
                password_confirmation:null,
                config:null,
            }
        },
        mounted() {
            this.$store.state.sidebar = false;
            this.$store.state.b_header = false;
            this.getConfig()
        },
        computed:{
            ...mapGetters(["getErrors"]),
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            register(){
                let data=[
                    {'mobile':this.mobile,'email':this.email,'name':this.name,'password':this.password,'password_confirmation':this.password_confirmation},
                    'register'
                ]
                this.$store.dispatch('registerAndLogin',data);
                
            },
            getConfig(){
                axios.get(this.api+'get-config').then(resp=>{
                    this.config = resp.data.data;
                })
            },
        }
    }
</script>