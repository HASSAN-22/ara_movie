<template>
    <div class="flex w-full justify-around">
        <div class="flex items-center w-[30%] fm:w-full justify-center box">
            <div class="px-8 py-6 mt-4 w-full text-left box shadow-lg">
                <div class="flex justify-center" v-if="config !== null">
                    <img :src="url+config.logo_mobile" />
                </div>
                <h3 class="text-2xl font-bold text-center rem--8">بازیابی رمز عبور</h3>
                <div class="mt-4" v-if="tokenIsValid">
                    <div><Error :errors="getErrors"></Error></div>
                    <div class="mt-4">
                        <Input v-model="password" type="password" title="رمز عبور" id="password"></Input>
                    </div>
                     <div class="mt-4">
                        <Input type="password" v-model="password_confirmation" title="تکرار رمز عبور" id="password_confirmation"/>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <Button text="تغییر رمز عبور" @click="resetPassword()"></Button>
                    </div>
                </div>
                <div v-else class="text-center mt-4">
                    <span class="text-2xl !font-bold text-center rem--1">لینک بازیابی منقضی شده است لطفا دوباره درخواست را ارسال نمایید</span>
                </div>
            </div>
            <Meta title="بازیابی رمز عبور" description="" />
            <Loading :loading="loading"></Loading>
        </div>
    </div>

</template>
<script>
    import Input from "@/components/Input"
    import Button from "@/components/Button"
    import Error from '@/components/Error';
    import Meta from "@/components/Meta";
    import Modal from '@/components/Modal'
    import Loading from '@/components/Loading'
    import Alert from '@/plugins/alert'
    import {mapGetters} from "vuex";
    import axios from '../plugins/axios';
    export default {
        name:'ForgetPassword',
        components:{Input,Button,Error,Meta,Modal,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                email:null,
                password:null,
                config:null,
                token:null,
                password_confirmation:null,
                tokenIsValid:false,
                loading:false,
            }
        },
        mounted() {
            this.$store.state.sidebar = false;
            this.$store.state.b_header = false;
            this.getConfig();
            this.token = this.$route.params.token;
            this.email = this.$route.params.email;
            this.getToken();
        },
        computed:{
            ...mapGetters(["getErrors"]),
            
        },
        methods:{
            async resetPassword(){
                this.loading = true;
                let data=[
                    'reset-password',
                    {'email':this.email,'token':this.token,'password':this.password,'password_confirmation':this.password_confirmation},
                ]
                await this.$store.dispatch('insert',data);
                this.loading = false;
                if(this.$store.state.clear){
                    window.location.href=process.env.VUE_APP_URL;
                }
            },
            getToken(){
                this.loading = true;
                axios.post(this.api+'get-reset-password-token',{'token':this.token,'email':this.email}).then(resp=>{
                    this.tokenIsValid = true;
                }).catch(err=>{
                    this.tokenIsValid = false;
                })
                this.loading = false;
            },
            getConfig(){
                axios.get(this.api+'get-config').then(resp=>{
                    this.config = resp.data.data;
                })
            }
        }
    }
</script>