<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">لیست درخواست های {{getAllData.contentTitle}}</li>
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
                <Thead :titles="['ارسال به کارت','مبلغ','وضعیت','تاریخ درخواست','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="ارسال به کارت" class="rem--7">{{item.cart}}</Td>
                        <Td title="مبلغ" class="rem--7">{{item.amount}} ریال</Td>
                        <Td title="وضعیت" class="rem--7">{{item.status_fa}}</Td>
                        <Td title="تاریخ درخواست" class="rem--7">{{item.created_at}}</Td>
                        <Td title="عملیات">
                          <div class="flex items-center justify-center">
                            <Button v-if="item.status === 'pending'" text="" color="blue" v-tooltip="'لغو'" @click="update(`${item.id}`)">لغو</Button>
                            <span v-else>---</span>
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
        <Modal :title="isUpdate ? '' : `ثبت درخواست های ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-8">
                <label for="cart_id" class="block mt-5 font-medium text-gray-300 rem--7">کارت <b class="text-red-600 rem--7">*</b> </label>
                <select id="cart_id" v-model="cart_id" class="bg-white border-b border-b-blue-500 text-gray-900bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب کارت ---</option>
                    <option v-for="cart in getAllData.carts" :key="cart.id" :value="cart.id">{{ cart.cart +' | '+ cart.bank_name}}</option>
                </select>
            </div>
            <Input v-model="amount" id="amount" title="مبلغ برداشت (ریال)" @keydown="toEnglishDigits"></Input>
        </Modal>
        <Meta :key="getAllData" :title="`لیست درخواست های ${getAllData.contentTitle}`" description="" />
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
        name:'Checkout',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'user-checkout',
                cart_id:null,
                amount:null,
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['carts','contentTitle']])
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

            async update($id){
                this.loading = true;
                let data = [
                    `${this.model}/${$id}`,
                    {}
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
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['carts','contentTitle']]);
                this.loading = false;
            },
            setData(){
                return {
                    cart_id:this.cart_id,
                    amount:this.convertAmount(this.amount)
                }
            },
            clear(){
                this.cart_id = null;
                this.amount = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            },
            convertAmount(amount){
                return parseInt(amount.replaceAll(',',''));
            }
        },
        watch:{
            amount(n,o){
                if(n !== null){
                    this.amount = new Intl.NumberFormat().format(this.convertAmount(n))
                }
            }
        }
    }
</script>