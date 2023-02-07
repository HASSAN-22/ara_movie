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
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['کاربر','موبایل','اشتراک','مبلغ','از کارت','تخفیف','روش پرداخت','کد پیگیری','تاریخ پایان','در تاریخ']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id"
                        :class="['border border-grey-500 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row ',item.isExpire ? 'bg-green-200' : 'bg-red-200']">
                        <Td title="کاربر" class="rem--7">{{item.user}} </Td>
                        <Td title="موبایل" class="rem--7">{{item.mobile}} </Td>
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
              <div class="mt-5">
                  <span class="text-blue-500">جمع کل با احتساب تخفیفات: </span><b class="!font-bold text-blue-600">{{getAllData.amount}} ریال </b>
              </div>
          </div>
        </div>

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
                model:'vip',
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
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['amount','contentTitle']])
                this.loading = false
            },

            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['amount','contentTitle']]);
                this.loading = false;
            }
        }
    }
</script>