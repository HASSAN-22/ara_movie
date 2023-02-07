<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7"> {{getAllData.contentTitle}} </li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3">
            <div class="mb-3 mr-2 flex fm:flex-col justify-end items-center">
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['عنوان','IMDB','تصویر','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="عنوان" class="rem--7">{{item.title}}</Td>
                        <Td title="IMDB" class="rem--7">{{item.imdb}}</Td>
                        <Td title="تصویر" class="rem--7">
                            <img :src="url+item.image" width="70" height="70" alt="">
                        </Td>
                        <Td title="عملیات" class="">
                            <Button v-tooltip="'حذف'" @click="destroy(`${item.id}`)" text="" color="red"><i class="fas fa-trash text-red-700"></i></Button>
                        </Td>
                    </tr>
                </tbody>
            </table>
            <div v-if="next !== null || previous !== null">
              <Paginate :current="current" :next="next" :previous="previous" :total="total"></Paginate>
            </div>
          </div>
        </div>
        <Meta :key="getAllData" :title="` ${getAllData.contentTitle} `" description="" />
        <Loading :loading="loading" />
    </div>
</template>
<script>Input
    import Thead from '@/components/Thead'
    import Input from '@/components/Input'
    import Td from '@/components/Td'
    import Paginate from '@/components/Paginate'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'

    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'WatchList',
        components:{Thead,Td,Meta,Loading,Paginate,Input},
        data(){
            return{
                url:process.env.VUE_APP_API,
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'user-watch-list',
                title:null,
                status:null,
                description:null
            }
        },
        mounted() {
            this.allData()
        },
        computed:{
            ...mapGetters(["getAllData"]),
            ...mapState(["current","next","previous","total"])
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle']])
                this.loading = false
            },
            async destroy(post_id){
                await this.$store.dispatch('deleteRecord',[`${this.model}/${post_id}`,''])
                await this.allData(true,this.current)
                this.loading = false
            },
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['contentTitle']]);
                this.loading = false;
            }
        }
    }
</script>