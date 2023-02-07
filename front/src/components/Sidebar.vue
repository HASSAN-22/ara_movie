<template>
<div class="box w-[30%] fm:w-full">
    <div class="box">
        <div class="flex fm:flex-row fm:flex-col gap-3">
            <a :href="config.instagram" class="flex justify-between gap-2 items-center p-2 rounded-xl hover:text-gray-300 instagram">
                <span class="text-lg text-white rem--9">ما را در اینستاگرام دنبال کنید</span>
                <span><i class="text-4xl fab fa-instagram"></i></span>
            </a>
            <a :href="config.telegram" class="flex justify-between gap-2 items-center p-2 rounded-xl hover:text-gray-300 telegram">
                <span class="text-lg rem--9 text-white">ما را در تلگرام دنبال کنید</span>
                <span><i class="text-4xl fab fa-telegram"></i></span>
            </a>
        </div>
    </div>
    <div class="box">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 justify-between" v-if="sidebarText.hasOwnProperty('lastSerial')">
                <span><i :class="['text-crimson-200 text-2xl rem--8',sidebarText.lastSerial.icon]"></i></span>
                <span class="rem--8 text-lg">{{sidebarText.lastSerial.title}}</span>
            </div>
            <router-link :to="{path:`/list/update-serise`}" class="text-sm rem--6 hover:text-crimson-100">مشاهده همه</router-link>
        </div>
        <div class="mt-3 relative group" v-for="movie in lastSerial" :key="movie.id">
            <router-link :to="{path:`/detail/${movie.slug}`}" class="">
                <div class="flex gap-3">
                  <img :src="url+movie.image" class="h-auto w-[33%] rounded-lg">
                  <div class="flex flex-col gap-5">
                    <span class="text-gray-300 text-xs rem--8">{{movie.title}}</span>
                    <span class="text-crimson-100 !font-bold text-xs rem--8">{{movie.latest_movie_link.caption_movie}}</span>
                    <div class="flex items-center gap-2">
                      <span class="rem--8 text-gray-300 text-xs !font-extrabold">{{movie.imdb_number}}/10</span>
                      <span class="fm:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="44" height="18" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                      </span>
                        <span class="fd:hidden">
                          <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="35" height="14" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                      </span>
                    </div>
                  </div>
                </div>
            </router-link>
        </div>
    </div>

    <div class="box">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 justify-between" v-if="sidebarText.hasOwnProperty('genre')">
                <span><i :class="['text-crimson-200 text-2xl rem--8',sidebarText.genre.icon]"></i></span>
                <span class="rem--8 text-lg">{{sidebarText.genre.title}}</span>
            </div>
            <div class="flex items-center">
                <button @click="switchGenre('movie')" :class="['bg-gray-900 rem--8 rounded-r-full p-2 gap-2', $store.state.genreSwitch === 'movie' ? '!bg-crimson-200' : '']">فیلم</button>
                <button @click="switchGenre('serial')" :class="['bg-gray-900 rem--8 rounded-l-full p-2 gap-2', $store.state.genreSwitch === 'serial' ? '!bg-crimson-200' : '']">سریال</button>
            </div>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2" >
            <router-link v-for="(key,genre) in genres" :key="key" :to="{path:`/${genre}`}" class="mb-1 flex items-center justify-around rounded-lg ease-in duration-300 bg-gray-900 py-2 hover:bg-crimson-200 hover:text-gray-100">
                <span class="rem--8">{{genre}}</span>
                <span class="rem--8">{{key}}</span>
            </router-link>
        </div>
    </div>

    <div class="box">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 justify-between" v-if="sidebarText.hasOwnProperty('soon')">
                <span><i :class="['text-crimson-200 text-2xl rem--8',sidebarText.soon.icon]"></i></span>
                <span class="rem--8 text-lg">{{sidebarText.soon.title}}</span>
            </div>
            <router-link :to="{path:`/list/coming-soon`}" class="text-sm rem--6 hover:text-crimson-100">مشاهده همه</router-link>
        </div>
        <div class="mt-3 relative" v-for="movie in comingSoon" :key="movie.id">
            <router-link :to="{path:`/detail/${movie.slug}`}" class="">
                <div class="flex gap-3">
                  <img :src="url+movie.image" class="h-auto w-[33%] rounded-lg">
                  <div class="flex flex-col gap-5">
                    <span class="text-gray-300 text-xs rem--8">{{movie.title}}</span>
                    <span class="rem--8 text-xs px-2 text-crimson-100 !font-bold">{{diffDays(movie.published_at)}} روز مانده</span>
                  </div>
                </div>
            </router-link>
        </div>
    </div>
</div>
</template>
<script>
    export default{
        name:'Sidebar',
        props:["lastSerial","genres","comingSoon","config","sidebarText"],
        data(){
            return{
                url:process.env.VUE_APP_API,
                genreSwitch:'movie'
            }
        },
        methods:{
            switchGenre(type){
                this.$store.state.genreSwitch = type;
                this.$store.state.genreisSwitched = true;
            },
            diffDays(date){
                let dateHash = {
                    'ژانویه' : 'Jan',
                    'فوریه': 'Feb',
                    'مارس': 'Mar',
                    'آوریل': 'Apr',
                    'مه': 'May',
                    'ژوئن': 'Jun',
                    'ژوئیه': 'Jul',
                    'اوت': 'Aug',
                    'سپتامبر': 'Sep',
                    'اکتبر': 'Oct',
                    'نوامبر': 'Nov',
                    'دسامبر': 'Dec'
                };
                let arrDate = date.split(' ');
                date = `${arrDate[0]}-${dateHash[arrDate[1]]}-${arrDate[2]}`
                const date1 = new Date();
                const date2 = new Date(Date.parse(date));
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                return diffDays; 
            }
        }

    }
</script>