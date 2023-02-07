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
            <div class="mb-3 mr-2 flex fm:flex-col justify-end items-center">
<!--                <Button text="اضافه کردن" color="green" @click="create()"></Button>-->
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['کاربر','موبایل','بانک صادر کننده','شماره کارت','شماره شبا','وضعیت']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="کاربر" class="rem--7">
                            <Notify :notifications="getAllData.notifications" :id="item.id" :title="item.user"/>
                        </Td>
                        <Td title="موبایل" class="rem--7">{{item.mobile}}</Td>
                        <Td title="بانک صادر کننده" class="rem--7">{{item.bank}}</Td>
                        <Td title="شماره کارت" class="rem--7">{{item.cart}}</Td>
                        <Td title="شماره شبا" class="rem--7">IR{{item.shaba}} </Td>
                        <Td title="وضعیت" class="rem--7">
                            <span :class="[item.color]">{{item.status}}</span>&nbsp;
                            <span class="cursor-pointer" @click="show(item.id)"><i class="fas fa-edit text-indigo-600"></i></span>
                        </Td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
        <Modal :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-8">
                <label for="status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت <b class="text-red-600 rem--7">*</b> </label>
                <select id="status" v-model="status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب وضعیت ---</option>
                    <option value="yes">تایید</option>
                    <option value="no">رد</option>
                    <option value="pending">در انتظار بررسی</option>
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
    import Loading from '@/components/Loading'
    import Notify from '@/components/Notify'
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    import Toast from "../../plugins/toast";
    export default {
        name:'Cart',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Loading,Notify},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'cart',
                status:null,
            }
        },
        mounted() {
            this.allData()
        },
        computed:{
            ...mapGetters(["getAllData","getErrors"]),
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle','notifications']])
                this.loading = false
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
                    this.status = data.status;
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
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['contentTitle','notifications']]);
                this.loading = false;
            },
            setData(){
                return {
                    status:this.status,
                }
            },
            clear(){
                this.status = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            },
        }
    }
</script>