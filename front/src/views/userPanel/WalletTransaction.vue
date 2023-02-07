<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">{{getAllData.contentTitle}}</li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3">
              <div v-if="getAllData.message !== null" class="mb-5 text-center bg-blue-200 p-3 rounded">
                  <span class="rem--7 text-blue-500">{{ getAllData.message }}</span>
              </div>
            <div class="mb-3 mr-2 flex fm:flex-col justify-between items-center">
                <Button text="اضافه کردن" color="green" @click="create()"></Button>
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['مبلغ','از کارت','کد پیگیری','در تاریخ']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="مبلغ" class="rem--7">{{item.amount}} ریال</Td>
                        <Td title="از کارت" class="rem--7">{{item.cart}}</Td>
                        <Td title="کد پیگیری" class="rem--7">{{item.transaction_id}}</Td>
                        <Td title="در تاریخ" class="rem--7">{{item.created_at}} </Td>
                    </tr>
                </tbody>
            </table>
              <div class="m-3">
                  <ul>
                      <li class="mb-2">
                          <span class="text-blue-500">جمع کل: </span><b class="!font-bold text-blue-600">{{ getAllData.amount }} ریال </b>
                      </li>
                      <li>
                          <span class="text-green-500">باقی مانده: </span><b class="!font-bold text-green-600">{{getAllData.wallet}} ریال</b>
                      </li>
                  </ul>
              </div>
          </div>
        </div>
        <Modal :title="isUpdate ? '' : `شارژ ${getAllData.contentTitle}`" :save="isUpdate ? 'ثبت تغییرات' : 'پرداخت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-8">
                <label for="cart_id" class="block mt-5 font-medium text-gray-300 rem--7">کارت <b class="text-red-600 rem--7">*</b> </label>
                <select id="cart_id" v-model="cart_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب کارت ---</option>
                    <option v-for="cart in getAllData.carts" :key="cart.id" :value="cart.id">{{ cart.cart +' | '+ cart.bank_name}}</option>
                </select>
            </div>
            <div class="mb-8">
                <label for="bank_portal_id" class="block mt-5 font-medium text-gray-300 rem--7">درگاه <b class="text-red-600 rem--7">*</b> </label>
                <select id="bank_portal_id" v-model="bank_portal_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب درگاه ---</option>
                    <option v-for="portal in getAllData.bankPortals" :key="portal.id" :value="portal.id">{{ portal.bank_name}}</option>
                </select>
            </div>
            <Input v-model="amount" id="amount" title="مبلغ ( ریال )" @keydown="toEnglishDigits"></Input>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle}`" description="" />
        <Loading :loading="loading" />
        <div v-html="bankForm"></div>
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
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    import axios from '@/plugins/axios'
    import Alert from "../../plugins/alert";
    export default {
        name:'WalletTransaction',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'user-wallet-transaction',
                cart_id:null,
                bank_portal_id:null,
                amount:null,
                bankForm:null,
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['carts','bankPortals','message','wallet','amount','contentTitle']])
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
                axios.post(this.api+this.model,this.setData()).then(resp=>{
                    let data = resp.data.data;
                    let portal = resp.data.portal;
                    if(data.status === 'success'){
                        this.clear();
                        if(portal === 'Zarinpal'){
                            window.location.href = data.data;
                        }else if(portal === 'Melat'){
                            this.bankForm = data.data;
                            setTimeout(()=>{
                                let form = window.document.getElementsByClassName('myform');
                                form[0].submit();
                            },700)
                        }

                    }else{
                        Alert.show('خطا',data.data,'error')
                    }
                    this.loading = false;
                }).catch(err=>{
                    this.$store.commit('catchError',err.response)
                    this.$store.commit('scrollModalTop')
                    this.loading = false;
                })

            },
            setData(){
                return {
                    bank_portal_id:this.bank_portal_id,
                    cart_id:this.cart_id,
                    amount:this.convertAmount(this.amount),
                    'frontRoute':process.env.VUE_APP_URL+'/'+this.model
                }
            },
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['carts','bankPortals','message','wallet','amount','contentTitle']]);
                this.loading = false;
            },
            clear(){
                this.bank_portal_id = null;
                this.cart_id = null;
                this.amount = null;
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
            amount(n,o){
                if(n !== null){
                    this.amount = new Intl.NumberFormat().format(this.convertAmount(n))
                }
            }
        }
    }
</script>