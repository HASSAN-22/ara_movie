<template>
    <div id="snackbar"></div>
    
    <div class="container-fluid relative">
         <div :class="['absolute top-0 w-full h-full bg-black z-10000 ease-in duration-300', $store.state.showSearch ? 'right-0' : 'right-[-100%]']">
                <div class="text-left m-1">
                    <span class="ml-1 cursor-pointer" @click="toggleSearch()"><i class="fas text-2xl fa-times"></i></span>
                </div>
                <div class="mt-20 relative px-5">
                    <Input type="search" title="جستجو..." v-debounce:1s="search" :debounce-events="['keydown']" id="search"></Input>
                    <div class="absolute top-[.6rem] fm:top-0 fm:left-[13.5%] left-[3.5%]">
                        <span>
                            <i class="fas fa-search text-md"></i>
                        </span>
                    </div>
                </div>
                <div class="flex flex-col px-5">
                    <div class="bg-[#0a0a0a] hover:!bg-[#0e0e0e] flex fm:flex-col fm:items-center gap-5" v-for="movie in searchData" :key="movie.id">
                        <div class="w-[14%] fm:w-[35%]">
                            <router-link :to="{path:`/detail/${movie.slug}`}">
                                <img :src="url+movie.image" class="rounded-lg">
                            </router-link>
                        </div>
                        <div>
                            <ul class="flex flex-col gap-3" >
                                <li>
                                    <router-link :to="{path:`/detail/${movie.slug}`}" class="text-2xl rem--1 hover:text-crimson-100">
                                        {{movie.title}} | {{movie.fa_title}}
                                    </router-link>
                                </li>
                                <li>
                                    <div class="flex gap-2 items-start">
                                        <div class="flex gap-2 items-start">
                                            <div class="flex flex-row gap-2 items-center">
                                                <span class="fm:hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="44" height="18" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                                                </span>
                                                <span class="fd:hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="35" height="14" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                                                </span>
                                            </div>
                                            <span class="rem--8">امتیاز:</span>
                                        </div>
                                        <span class="rem--8">{{movie.imdb}}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex gap-2 items-start">
                                        <div class="flex gap-2 items-start">
                                            <span><i class="fas fa-theater-masks text-crimson-100 rem--8"></i></span>
                                            <span class="rem--8">ژانر:</span>
                                        </div>
                                        <span v-for="(genre,index) in splitData(movie.genre)" :key="index" class="rem--8">
                                            <router-link  :to="{path:'/'+genre}"  class="hover:text-crimson-200 text-md rem--7" >
                                            {{ genre }}<span v-if="(index+1) < splitData(movie.genre).length">، </span>
                                            </router-link>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="box !mt-0 flex gap-1">
                                        <span class="text-crimson-100 rem--8">داستان: </span>
                                        <p class="rem--8 break-word">
                                            {{movie.description}}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <header class="box !mt-0">
                <div>
                    <nav class="shadow-md rounded fd:p-2">
                        <div class="grid grid-cols-2 items-center">
                            <div class="flex flex-row justify-between items-center">
                                <div class="hidden fm:block">
                                    <span @click="toggleNavbar()"><i class="fas fa-bars text-2xl"></i></span>
                                </div>
                                <div class="fd:ml-8">
                                    <img class="fm:hidden" src="@/assets/img/lg-logo.png" />
                                    <img class="hidden fm:block" src="@/assets/img/sm-logo.png" />
                                </div>
                                <div :class="['fm:absolute fm:bg-black fm:opacity-90 fm:right-[-100%] z-50 top-0 h-full rounded w-[80%]  ease-in duration-300', showNavbar ? 'fm:!right-0' : 'fm:!right-[-100%]']">
                                    <div class="text-left fd:hidden flex justify-between items-center border-b mb-2 pb-2">
                                        <span class="mr-1"><img width="40" height="40" class="hidden fm:block" src="/img/sm-logo.png" /></span>
                                        <span class="ml-1" @click="toggleNavbar()"><i class="fas text-2xl fa-times"></i></span>
                                    </div>
                                    <ul class="flex items-center fm:items-start fm:mr-2 fm:flex-col gap-5 fm:!gap-2 z-40">
                                        <li class="hover:text-whtie"><router-link :to="{name:'Index'}" class="hover:text-crimson-100 text-gray-200 rem--8">خانه</router-link></li>
                                        <li class="hover:text-whtie group cursor-pointer" v-for="category in categories" :key="category.id" v-show="category.parent_id === 0">
                                            <span class="hover:text-crimson-100 text-gray-200 rem--8" v-if="category.parent_id === 0 ">{{category.title}}</span>
                                            <ul class="absolute w-[12rem] bg-black opacity-80  rounded z-50 group-hover:block hidden" v-if="category.hasOwnProperty('children') && category.children.length > 0">
                                                <li class="hover:bg-gray-900 p-2 m-2 break-all ease-in duration-300 font-bold mx-2 rounded" v-for="childe in category.children" :key="childe.id">
                                                    <router-link :to="{path:`/list/${childe.slug}`}" class="hover:!text-crimson-100 text-gray-200 rem--8">{{childe.title}}</router-link>
                                                </li>
                                            </ul>
                                        </li>
                                        <li v-show="config.page === 'yes'" class="hover:text-whtie group cursor-pointer">
                                            <a href="javascript:void(0)" v-if="sidebarText.page" class="hover:text-crimson-100 text-gray-200 rem--8">
                                                {{ sidebarText.page.title}}
                                            </a>
                                            <ul class="absolute w-[12rem] bg-black opacity-80  rounded z-50 group-hover:block hidden">
                                                <li v-for="page in pages" :key="page.id" class="hover:bg-gray-900 p-2 m-2 break-all ease-in duration-300 font-bold mx-2 rounded">
                                                    <router-link :to="{path:`/show-page/${page.title}`}" class="hover:!text-crimson-100 text-gray-200 rem--8">{{ page.title}}</router-link>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="hover:text-whtie" v-show="config.vip === 'yes'"><router-link v-if="sidebarText.vip" :to="{name:'ShowVip'}" class="hover:text-crimson-100 text-gray-200 rem--8 text-white">{{sidebarText.vip.title}}</router-link></li>
                                        <li class="hover:text-whtie" v-show="config.about_us === 'yes'"><router-link :to="{path:`/show-page/درباره ما`}" v-if="sidebarText.about" class="hover:text-crimson-100 text-gray-200 rem--8 text-white">{{sidebarText.about.title}}</router-link></li>
                                        <li class="hover:text-whtie" v-show="config.contact_us === 'yes'"><router-link :to="{path:`/show-page/تماس با ما`}" v-if="sidebarText.contactUs" class="hover:text-crimson-100 text-gray-200 rem--8 text-white">{{sidebarText.contactUs.title}}</router-link></li>

                                    </ul>
                                </div>
                                
                            </div>
                            <div class="flex flex-row items-center justify-end gap-2">
                                <button @click="toggleSearch()" class="bg-crimson-300 rounded-lg p-2 hover:bg-crimson-600 !font-bold flex gap-2 items-center">
                                    <span><i class="fas fa-search fm:text-md"></i></span>
                                    <span class="rem--8 fm:hidden">جستجو</span>
                                </button>
                                <div>
                                    <router-link v-if="userIsLogin" class="bg-crimson-300 rounded-lg p-2 hover:bg-crimson-600 !font-bold flex gap-2 items-center" :to="{name:'Dashboard'}">پنل کاربری</router-link>
                                    <router-link v-else :to="{name:'Login'}" class="bg-crimson-300 rounded-lg p-2 hover:bg-crimson-600  hover:text-gray-300 !font-bold flex gap-2 items-center">
                                        <span><i class="far fa-user fm:text-md"></i></span>
                                        <span class="rem--8 fm:hidden">ورود</span>
                                    </router-link>
                                </div>
                            </div>
                        </div>

                    </nav>
                </div>
            </header>
            <Bheader v-show="$store.state.b_header" :sliders="sliders" :genres="genres"></Bheader>

            <main class="flex fm:flex-col gap-3">
                <router-view/>
                <Sidebar v-show="$store.state.sidebar" :sidebarText="sidebarText" :lastSerial="lastSerial" :genres="genres" :comingSoon="comingSoon" :config="config"></Sidebar>
            </main>

            <Footer :config="config"></Footer>
            <Loading :loading="loading"></Loading>
        </div>
</template>
<script>
import Input from '@/components/Input';
import Sidebar from '@/components/Sidebar'
import Bheader from '@/components/Bheader'
import {mapGetters, mapState} from "vuex";
import Loading from '@/components/Loading'
import Footer from '@/components/Footer'
import axios from "../plugins/axios";
export default {
    name:'FrontLayout',
    components:{Input,Sidebar,Bheader,Loading,Footer},
    data(){
        return{
            api:process.env.VUE_APP_API+'/api/',
            url:process.env.VUE_APP_API,
            showNavbar:false,
            searchData:[],
            allData:[],
            config:[],
            pages:[],
            categories:[],
            sidebarText:[],
            lastSerial:[],
            comingSoon:[],
            sliders:[],
            genres:[],
            loading:false,
        }
    },
    mounted() {
        this.getData();
    },
    computed:{
          ...mapGetters(["getAllData","getErrors","getGenres"]),
          userIsLogin(){
              const token = localStorage.getItem('token') ;
              return (token !== undefined && token !== '' && token !== null) ? true : false;
          },
          username(){
              return localStorage.getItem('name');
          }
    },
    methods:{
        getData(){
              this.loading = true;
              axios.get(this.api+'index').then(resp=>{
                let data = resp.data.data;
                this.categories = data.categories;
                this.config = data.config;
                this.pages = data.pages;
                this.sidebarText = data.sidebarText;
                this.lastSerial = data.lastSerial;
                this.comingSoon = data.comingSoon;
                this.sliders = data.sliders;
                this.genres = data.genres;
                this.loading =false;
              }).catch(err=>{
                this.loading = false;
              })
            },
        toggleNavbar(){
            return this.showNavbar = !this.showNavbar;
        },
        toggleSearch(){
            return this.$store.state.showSearch = !this.$store.state.showSearch;
        },
        async search(text){
            this.loading = true;
            await axios.post(`${this.api}search`,{text:text}).then(resp=>{
                this.searchData = resp.data.data;
            })
            this.loading = false;
        },
        splitData(data){
            if(data !== null){
                return data.split('،')
            }
            return data;
        },
    }
}
</script>
