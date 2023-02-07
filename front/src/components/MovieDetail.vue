<template>
    <div class="box" v-if="movies.length <= 0">
        <NotFound></NotFound>
    </div>
    <div v-else class="box !bg-[#1c1c22]" v-for="movie in movies" :key="movie.id">
        <div class=" flex fm:items-center gap-3 fm:flex-col">
            <div class="w-[18%] fm:!w-[45%]">
                <div>
                    <router-link :to="{path:`/detail/${movie.slug}`}">
                        <img :src="url+movie.image" class="h-[19rem] fm:!h-[12rem] rounded-lg">
                    </router-link>
                </div>
                <div v-if="movie.subtitle === 'yes'" class="flex items-center justify-between mt-2 border !border-green-700 p-2 rounded-lg">
                    <span class="rem--8">زیرنویس پارسی</span>
                    <span>
                        <i class="fas fa-check text-crimson-100 rem--8"></i>
                    </span>
                </div>
                <div v-if="movie.dubbed === 'yes'" class="flex items-center justify-between mt-2 border !border-green-700 p-2 rounded-lg">
                    <span class="rem--8">دوبله پارسی</span>
                    <span>
                        <i class="fas fa-check text-crimson-100 rem--8"></i>
                    </span>
                </div>
            </div>
            <div class="w-[82%]">
                <div class="flex items-center justify-between">
                    <routerLink :to="{path:`/detail/${movie.slug}`}" class="text-xl rem--1 !font-bold">{{movie.fa_title}} | {{movie.title}} </routerLink>
                    <WatchListCheck :movie="movie"></WatchListCheck>
                </div>
                <div v-if="movie.type === 'serial' && movie.latest_movie_link !== null" class="mt-3 w-full box">
                    <span class="rem--8">{{movie.latest_movie_link.caption_movie}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
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

                <div class="mt-3 flex gap-2 items-start">
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
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-calendar text-crimson-100 rem--8"></i></span>
                        <span class="rem--8">سال تولید:</span>
                    </div>
                    <span class="rem--8">{{splitData(movie.published_at,'–').length > 0 ? movie.published_at.replace('–','') : movie.published_at}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-users text-crimson-100 rem--8"></i></span>
                        <span class="rem--8">بازیگران:</span>
                    </div>
                    <span v-for="(actor,index) in splitData(movie.actor)" :key="index" class="rem--8">
                        <router-link class="hover:text-crimson-200 text-md rem--7"  :to="{path:'/'+actor}">
                        {{ actor }}<span v-if="(index+1) < splitData(movie.actor).length">، </span>
                        </router-link>
                    </span>
                </div>
                <div class="mt-3 box flex gap-1">
                    <span class="text-crimson-100 rem--8">داستان: </span>
                    <p class="rem--8 break-word">
                        {{movie.description}}
                    </p>
                </div>
            </div>
        </div>
        <div class="float-left fm:float-none fm:mt-3">
            <router-link :to="{path:`/detail/${movie.slug}`}" class="flex items-center ease-in duration-300 fm:justify-center gap-1 group text-white hover:text-white border !border-green-700 hover:!border-crimson-200 hover:bg-crimson-300 focus:outline-none focus:ring-0 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                <span class="rem--8">ادامه و دانلود</span>
                <span><i class="fas fa-arrow-to-bottom ease-in duration-300 text-crimson-100 group-hover:!text-gray-200 rem--8 text-2xl"></i></span>
            </router-link>
        </div>
    </div>
</template>

<script>
import WatchListCheck from '@/components/WatchListCheck';
import NotFound from '@/components/NotFound';
export default {
    name:'MovieDetail',
    components:{WatchListCheck,NotFound},
    props:['movies'],
    data(){
        return {
            url:process.env.VUE_APP_API,
            
        }
    },
    methods:{
        splitData(data){
            if(data !== null){
                return data.split('،')
            }
            return data;
        },
    }
}
</script>