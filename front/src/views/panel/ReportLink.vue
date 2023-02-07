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
<!--                <Button text="اضافه کردن" color="green" @click="create()"></Button>
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>-->
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['فیلم یا سریال','لینک','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="فیلم یا سریال" class="rem--7">
                            <Notify :notifications="getAllData.notifications" :id="item.id" :title="item.title"/>
                        </Td>
                        <Td title="لینک" class="rem--7">
                            <a class="rem--7 hover:text-crimson-100" :href="item.link" target="_blank">{{item.movieLinkTitle+' | '+item.linkTitle}}</a>
                        </Td>
                        <Td title="عملیات">
                            <div class="flex items-center justify-center">
                              <Button v-tooltip="'حذف'" @click="destroy(`${item.id}`)" text="" color="red"><i class="fas fa-trash text-red-700"></i></Button>
                            </div>
                        </Td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} ها`" description="" />
        <Loading :loading="loading" />
    </div>
</template>
<script>
    import Button from '@/components/Button'
    import Thead from '@/components/Thead'
    import Td from '@/components/Td'
    import Loading from '@/components/Loading'
    import Notify from '@/components/Notify'
    import Meta from '@/components/Meta'
    import {mapGetters, mapMutations, mapState} from "vuex";
    import Toast from "../../plugins/toast";
    export default {
        name:'ReportLink',
        components:{Button,Thead,Td,Meta,Loading,Notify},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                loading:false,
                model:'reportLink',
            }
        },
        mounted() {
            this.allData()
        },
        computed:{
            ...mapGetters(["getAllData"]),
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle','notifications']])
                this.loading = false
            },
            async destroy(post_id){
                await this.$store.dispatch('deleteRecord',[`${this.model}/${post_id}`,''])
                await this.allData(true,this.current)
                this.loading = false
            }
        }
    }
</script>