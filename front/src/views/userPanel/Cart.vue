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
<!--              <div class="ml-2 fm:mt-3">-->
<!--                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />-->
<!--              </div>-->
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['بانک صادر کننده','شماره کارت','شماره شبا','وضعیت']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="بانک صادر کننده" class="rem--7">{{item.bank}}</Td>
                        <Td title="شماره کارت" class="rem--7">{{item.cart}}</Td>
                        <Td title="شماره شبا" class="rem--7">IR{{item.shaba}} </Td>
                        <Td title="وضعیت" class="rem--7">{{item.status}}</Td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
        <Modal :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <Input v-model="cart" id="cart" title="شماره کارت" @keydown="toEnglishDigits"></Input>
            <Input v-model="shaba" id="shaba" title="شماره شبا بدونه IR" @keydown="toEnglishDigits"></Input>
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
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    import Toast from "../../plugins/toast";
    export default {
        name:'Cart',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Loading},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'user-cart',
                cart:null,
                shaba:null,
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
                if(this.checkCart()){
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
                }
            },

            setData(){
                return {
                    cart:this.cart,
                    shaba:this.shaba,
                }
            },
            clear(){
                this.cart = null;
                this.shaba = null;
            },
            emptyError(){
                this.$store.state.errors = [];
            },
            isNumeric(value){
                return /^-?\d+$/.test(value)
            },
            checkCart(){
                let cart = this.cart;
                if(this.cart === null){
                    Toast.show('هشدار','شماره کارت خالی میباشد','warning')
                }else{
                    if(this.isNumeric(cart)){
                        let numberCart = cart.split('');
                        numberCart.pop();
                        let sum = 0;
                        numberCart.forEach(number=>{
                            let result = number % 2 === 0 ? number * 2 : number * 1;
                            sum += result > 9 ? result - 9 : result
                        })
                        if(sum%10 === 0){
                            return this.checkShaba();

                        }else{
                            Toast.show('خطا','شماره کارت وارد شده معتبر نمیباشد','error');
                            return false;
                        }
                    }else{
                        Toast.show('هشدار','شماره کارت وارد شده باید عدد باشد','warning')
                        return false;
                    }
                }
            },
            checkShaba(){
                if(this.shaba === null){
                    Toast.show('هشدار','شماره شبا خالی میباشد','warning')
                }else{
                    let numberShaba = this.shaba.split('');
                    let towNumber = numberShaba.filter((item,key)=>key < 2)
                    towNumber = towNumber.join('');
                    let otherNumber = numberShaba.filter((item,key)=>key > 2)
                    otherNumber = otherNumber.join('')
                    let result = parseInt(parseInt(otherNumber+'1827'+towNumber) / 97);
                    if(result === 1){
                        return true;

                    }else{
                        Toast.show('خطا','شماره شبا وارد شده معتبر نمیباشد','error');
                        return false;
                    }
                }

            }
        }
    }
</script>