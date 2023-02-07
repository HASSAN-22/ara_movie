<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">لیست {{getAllData.contentTitle}} های بانکی</li>
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
                <Thead :titles="['نام بانک','وضعیت','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="نام بانک" class="rem--7">{{item.bank_name}}</Td>
                        <Td title="وضعیت" class="rem--7">{{item.status}}</Td>
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
        <Modal :title="isUpdate ? `ویرایش ${getAllData.contentTitle} بانکی` : `ثبت ${getAllData.contentTitle} بانکی بانکی جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-8">
                <label for="bank_name" class="block mt-5 font-medium text-gray-300 rem--7">درگاه بانکی <b class="text-red-600 rem--7">*</b> </label>
                <select id="bank_name" v-model="bank_name" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب درگاه بانکی ---</option>
                    <option value="melat">ملت</option>
                    <option value="parsian">پارسیان</option>
                    <option value="zarinpal">زرین پال</option>
                </select>
            </div>
            <Input v-show="bank_name !== null" v-model="code_id" id="code_id" :title="codeId"></Input>
            <div v-if="bank_name === 'melat'">
                <Input v-model="username" id="username" title="نام کاربری"></Input>
                <Input v-model="password" id="password" title="کلمه عبور"></Input>
            </div>
            <div class="mb-8">
                <label for="status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت<b class="text-red-600 rem--7">*</b> </label>
                <select id="status" v-model="status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب وضعیت ---</option>
                    <option value="yes">فعال</option>
                    <option value="no">غیر فعال</option>
                </select>
            </div>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} های بانکی`" description="" />
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
    import Paginate from '@/components/Paginate'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'Plan',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'bankPortal',
                bank_name:null,
                code_id:null,
                username:null,
                password:null,
                status:null
            }
        },
        mounted() {
            this.allData()
        },
        computed:{
            codeId(){
                let title = '';
                switch (this.bank_name) {
                    case 'parsian': title = 'حساب کاربری';break;
                    case 'melat': title = 'ترمینال آیدی';break;
                    case 'zarinpal': title = 'مرچنت آیدی';break;
                }
                return title;
            },
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
                    this.bname(data.bank_name);
                    this.code_id = data.code_id;
                    this.username = data.username;
                    this.password = data.password;
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
            setData(){
                return {
                    bank_name : this.bank_name,
                    code_id : this.code_id,
                    username : this.username,
                    password : this.password,
                    status : this.status
                }
            },
            clear(){
                this.bank_name = null;
                this.code_id = null;
                this.username = null;
                this.password = null;
                this.status = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            },
            bname(name){
                switch (name) {
                    case 'ملت':this.bank_name = 'melat';break;
                    case 'پارسیان':this.bank_name = 'parsian';break;
                    case 'زرین پال':this.bank_name = 'zarinpal';break;
                }
            }
        }
    }
</script>