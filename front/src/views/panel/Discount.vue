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
                <Thead :titles="['غنوان','درصد نخفیف','کد','برای پلن','تاریخ پایان تخفیف','تاریخ ثبت','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="درصد نخفیف" class="rem--7">{{item.discount}} ٪</Td>
                        <Td title="کد" class="rem--7">{{item.code}}</Td>
                        <Td title="برای پلن" class="rem--7">{{item.plan}} </Td>
                        <Td title="تاریخ پایان تخفیف" class="rem--7">{{item.expire}}</Td>
                        <Td title="تاریخ ثبت" class="rem--7">{{item.created_at}}</Td>
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
        <Modal :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <Input v-model="title" id="title" title="عنوان"></Input>
            <div class="mb-8">
                <label for="plan_id" class="block mt-5 font-medium text-gray-300 rem--7">برای پلن <b class="text-red-600 rem--7">*</b> </label>
                <select id="plan_id" v-model="plan_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" selected disabled>--- انتخاب پلن ---</option>
                    <option v-for="plan in getAllData.plans" :key="plan.id" :value="plan.id">{{plan.title}}</option>
                </select>
            </div>
            <Input v-model="discount" id="discount" title="درصد تخفیف" @keydown="toEnglishDigits"></Input>
            <Input v-model="code" id="code" title="کد تخفیف" ></Input>
            <div>
                <label for="plan_id" class="block mt-5 font-medium text-gray-300 rem--7">تاریخ پایان <b class="text-red-600 rem--7">*</b> </label>
                <date-picker v-model="expire"></date-picker>
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
    import Paginate from '@/components/Paginate'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'Discount',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'discount',
                title:null,
                discount:null,
                plan_id:null,
                expire:null,
                code:null
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['plans','contentTitle']])
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
                    let expire = data.expire.split(' ')
                    this.id=data.id;
                    this.title=data.title;
                    this.discount=data.discount.toString();
                    this.plan_id=data.plan_id;
                    this.expire = expire[0];
                    this.code = data.code;
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
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['plans','contentTitle']]);
                this.loading = false;
            },
            setData(){
                return {
                    title:this.title,
                    discount:this.discount,
                    plan_id:this.plan_id,
                    code:this.code,
                    expire:this.expire
                }
            },
            clear(){
                this.title = null;
                this.discount = null;
                this.plan_id = null;
                this.expire = null;
                this.code = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>