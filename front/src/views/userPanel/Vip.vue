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
                <Thead :titles="['اشتراک','مبلغ','از کارت','تخفیف','روش پرداخت','کد پیگیری','تاریخ پایان','در تاریخ']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id"
                        :class="['border border-grey-500 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row ',item.isExpire ? 'bg-green-200' : 'bg-red-200']">
                        <Td title="اشتراک" class="rem--7">{{item.plan}} </Td>
                        <Td title="مبلغ" class="rem--7">{{item.price}} ریال</Td>
                        <Td title="از کارت" class="rem--7">{{item.cart}}</Td>
                        <Td title="تخفیف" class="rem--7">{{item.discount}} % </Td>
                        <Td title="روش پرداخت" class="rem--7">{{item.type}}</Td>
                        <Td title="کد پیگیری" class="rem--7">{{item.transaction_id}}</Td>
                        <Td title="تاریخ پایان" class="rem--7">{{item.expire}}</Td>
                        <Td title="در تاریخ" class="rem--7">{{item.created_at}} </Td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
        <Modal :title="isUpdate ? '' : `خرید ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'پرداخت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <div class="mb-8">
                <label for="plan_id" class="block mt-5 font-medium text-gray-300 rem--7">اشتراک <b class="text-red-600 rem--7">*</b> </label>
                <select id="plan_id" v-model="plan_id" @change="choosePlan($event)" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب اشتراک ---</option>
                    <option v-for="plan in getAllData.plans" :key="plan.id" :value="plan.id">{{ plan.title}}</option>
                </select>
            </div>
            <div class="mb-8">
                <label for="cart_id" class="block mt-5 font-medium text-gray-300 rem--7">کارت <b class="text-red-600 rem--7">*</b> </label>
                <select id="cart_id" v-model="cart_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب کارت ---</option>
                    <option v-for="cart in getAllData.carts" :key="cart.id" :value="cart.id">{{ cart.cart +' | '+ cart.bank_name}}</option>
                </select>
            </div>

            <div class="mb-8">
                <label for="type" class="block mt-5 font-medium text-gray-300 rem--7">روش پرداخت <b class="text-red-600 rem--7">*</b> </label>
                <select id="type" v-model="type" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب روش پرداخت ---</option>
                    <option value="wallet">کیف پول</option>
                    <option value="bank">اینترنتی</option>
                </select>
            </div>
            <div class="mb-8" v-show="type === 'bank'">
                <label for="bank_portal_id" class="block mt-5 font-medium text-gray-300 rem--7">درگاه <b class="text-red-600 rem--7">*</b> </label>
                <select id="bank_portal_id" v-model="bank_portal_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب درگاه ---</option>
                    <option v-for="portal in getAllData.bankPortals" :key="portal.id" :value="portal.id">{{ portal.bank_name}}</option>
                </select>
            </div>
            <Input v-model="discount" id="discount" title="کد تخفیف" :required="false"></Input>
        </Modal>

<!--        Get plan info -->
        <Modal :title="`اطلاعات  ${getAllData.contentTitle}`" save="" ref="planInfo" @callback="">
            <div class="mx-5">
                <ul class="">
                    <li class="mb-3"><span class="text-red-500">عنوان: </span> <b class="!font-bold text-blue-600">{{info.title}}</b></li>
                    <li class="mb-3"><span class="text-red-500">قیمت: </span> <b class="!font-bold text-blue-600">{{new Intl.NumberFormat().format(info.price)}} ریال</b></li>
                    <li class="mb-3"><span class="text-red-500">تعداد روز: </span> <b class="!font-bold text-blue-600">{{info.days}}</b></li>
                    <li class="mb-3"><span class="text-red-500">توضیحات</span>
                        <p class="!font-bold text-blue-600 break-all" v-text="info.description"></p>
                    </li>
                </ul>
            </div>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} ها`" description="" />
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
                model:'user-vip',
                plan_id:null,
                cart_id:null,
                bank_portal_id:null,
                discount:null,
                type:null,
                bankForm:null,
                info:[],
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['carts','bankPortals','message','plans','contentTitle']])
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
                await axios.post(this.api+this.model,this.setData()).then(resp=>{
                    if(this.type === 'wallet'){
                        let data = resp.data;
                        if(data.status === 'success'){
                            Alert.show('موفق','پرداخت با موفقیت انجام شد','success')
                        }
                    }else{
                        let data = resp.data.data;
                        let portal = resp.data.portal;
                        if(data.status === 'success'){
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
                    }
                    this.loading = false;
                    this.clear();
                    this.allData(false,this.current)
                }).catch(err=>{
                    this.$store.commit('catchError',err.response)
                    this.$store.commit('scrollModalTop')
                    this.loading = false;
                })

            },
            setData(){
                return {
                    plan_id:this.plan_id,
                    cart_id:this.cart_id,
                    bank_portal_id:this.bank_portal_id,
                    discount:this.discount,
                    type_pay:this.type,
                    'frontRoute':process.env.VUE_APP_URL+'/'+this.model
                }
            },
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['carts','bankPortals','message','plans','contentTitle']]);
                this.loading = false;
            },
            choosePlan(event){
                this.loading = true;
                let plan_id = event.target.value;
                axios.get(this.api+'choose-plan/'+plan_id).then(resp=>{
                   let data = resp.data;
                   if(data.status === 'success'){
                       this.$refs.planInfo.toggleModal();
                       this.info = data.data;
                   }
                    this.loading = false;
                }).catch(err=>{
                    console.log(err.response)
                    this.loading = false;
                });
            },
            clear(){
                this.plan_id=null;
                this.cart_id=null;
                this.bank_portal_id=null;
                this.discount=null;
                this.type=null;
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>