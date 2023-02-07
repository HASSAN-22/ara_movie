<template>
    <div class="box w-full fm:w-full" v-if="movie !== null">
        <div class="flex items-center fm:gap-3 justify-between">
            <div>
                <h2 class="text-lg fm:!text-sm">دانلود فیلم {{movie.title}} | {{movie.fa_title}}</h2>
            </div>
            <div class="flex items-center gap-3 mx-3">
                <div>
                    <WatchListCheck :movie="movie"></WatchListCheck>
                </div>
            </div>
        </div>
        <div class="box !bg-[#1c1c22] flex fm:flex-col gap-3">
            <div class="flex fm:justify-around fd:w-[15%]">
                <div class="fm:w-[70%] fm:order-1">
                    <img :src="url+movie.image" class="h-[19rem] rounded" />
                </div>
            </div>
            <div class="flex flex-wrap flex-col w-[32%] fm:w-full fm:order-3">
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-theater-masks text-sm text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">ژانر:</span>
                    </div>
                    <span class="rem--8 text-sm" v-for="(genre,index) in splitData(movie.genre)" :key="index">
                        <router-link  :to="{path:'/'+genre}"  class="hover:text-crimson-200 text-md rem--7" >
                            {{ genre }}<span v-if="(index+1) < splitData(movie.genre).length">، </span>
                        </router-link>
                    </span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-calendar text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">سال تولید:</span>
                    </div>
                    <span class="rem--8 text-sm">{{splitData(movie.published_at,'–').length > 0 ? movie.published_at.replace('–','') : movie.published_at}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-user-plus text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">رده سنی:</span>
                    </div>
                    <span class="rem--8 text-sm">{{movie.pg}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-globe text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">زبان:</span>
                    </div>
                    <span class="rem--8 text-sm">{{movie.lang}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-clock text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">زمان:</span>
                    </div>
                    <span class="rem--8 text-sm">{{movie.time}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-user text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">کارگردان:</span>
                    </div>
                    <span class="rem--8 text-sm">{{ movie.director !== null ? movie.director : 'N/A'}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-users text-sm text-crimson-100 rem--8"></i></span>
                        <span class="rem--8 text-sm">ستارگان:</span>
                    </div>
                    <span class="rem--8 text-sm" v-for="(actor,index) in splitData(movie.actor)" :key="index">
                        <router-link class="hover:text-crimson-200 text-md rem--7"  :to="{path:'/'+actor}">
                            {{ actor }}<span v-if="(index+1) < splitData(movie.actor).length">، </span>
                        </router-link>
                    </span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-microphone text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">دوبله:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.dubbed === 'yes' ? 'دارد' : 'ندارد'}}</span>
                </div>

            </div>
            <div class="w-[20%] fm:w-full fm:order-4">
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-star text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">امتیاز منتقدین:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.critics}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-star text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">رتبه:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.rank === null ? '---' : movie.rank}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-tv-retro text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">شبکه:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.network}}</span>
                </div>
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-globe-americas text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">محصول:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.product}}</span>
                </div>

                <div class="mt-3" v-if="movie.type==='serial'">
                    <div class="mt-3 flex gap-2 items-start" v-if="movie.type==='serial'">
                        <div class="flex gap-2 items-start">
                            <span><i class="fas fa-tv-retro text-sm text-crimson-100 rem--8"></i></span>
                            <span class="text-sm rem--8">روز پخش:</span>
                        </div>
                        <span class="text-sm rem--8">{{movie.broadcast_day}}</span>
                    </div>
                    <div class="mt-3 flex gap-2 items-start" v-if="movie.type==='serial'">
                        <div class="flex gap-2 items-start">
                            <span><i class="fas fa-tv-retro text-sm text-crimson-100 rem--8"></i></span>
                            <span class="text-sm rem--8">وضعیت پخش:</span>
                        </div>
                        <span class="text-sm rem--8">{{movie.play_status === 'yes' ? 'در حال پخش' : 'پایان یافته'}}</span>
                    </div>
                </div>
                
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-tv-retro text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">کیفیت:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.quality}}</span>
                </div>
                
                <div class="mt-3 flex gap-2 items-start">
                    <div class="flex gap-2 items-start">
                        <span><i class="fas fa-closed-captioning text-sm text-crimson-100 rem--8"></i></span>
                        <span class="text-sm rem--8">زیرنویس:</span>
                    </div>
                    <span class="text-sm rem--8">{{movie.subtitle === 'yes' ? 'دارد' : 'ندارد'}}</span>
                </div>
                
            </div>
            <div class="w-[30%] fm:w-full fm:order-2">
                <div class="flex items-center fm:flex-col fd:gap-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center gap-2 cursor-pointer" @click="like(movie.id,'like')">
                            <span><i class="fas fa-thumbs-up text-lg rem--1 text-green-500"></i></span>
                            <span class="rem--8 text-sm">{{likeCount}}</span>
                        </div>
                        <div class="flex items-center justify-center gap-2 cursor-pointer" @click="like(movie.id,'dislike')">
                            <span><i class="fas fa-thumbs-down text-lg rem--1 text-red-500"></i></span>
                            <span class="rem--8 text-sm">{{dislikeCount}}</span>
                        </div>
                    </div>
                    <div>
                        <span class="rem--8 text-sm">{{(movie.imdb.split(' '))[5]}} رای</span>
                    </div>
                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-md rem--9">{{movie.imdb_number}}/10</span>
                            <div class="flex flex-row gap-2 items-center">
                                <span class="fm:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="60" height="28" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                                </span>
                                <span class="fd:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="35" height="18" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 box w-[25rem] flex justify-center gap-2 fm:hidden">
                    <span class="text-crimson-100 rem--8 text-sm">داستان: </span>
                    <p class="rem--8 text-sm">
                        {{movie.description}}
                    </p>
                </div>
            </div>

            <div class="mt-3 box flex justify-center gap-2 fm:order-5 fd:hidden">
                <span class="text-crimson-100 rem--8">داستان: </span>
                <p class="rem--8">
                    هفت سال پس از آن که زمین تبدیل به یک کره ی یخ زده و بایر شده است، بازماندگان انسان ها در یک قطار ساکن شده اند که هرگز متوقف نشده و کره ی زمین را دور می زند.
                </p>
            </div>
        </div>

        <div class="box mt-2">
            <div class="grid grid-cols-4 bg-[#1c1c22] ">
                <div @click="detailTab = 'links'" :class="['cursor-pointer text-center p-2 rounded-r-lg', detailTab == 'links' ? 'bg-crimson-200' : '']">
                    <span class="rem--8 !font-bold text-sm">لینک ها</span>
                </div>
                <div @click="detailTab = 'trailer'" :class="['cursor-pointer text-center p-2', detailTab == 'trailer' ? 'bg-crimson-200' : '']"><span class="rem--8 text-sm !font-bold">تریلر</span></div>
                <div @click="detailTab = 'screenshot'" :class="['cursor-pointer text-center p-2', detailTab == 'screenshot' ? 'bg-crimson-200' : '']"><span class="rem--8 text-sm !font-bold">اسکرین شات</span></div>
                <div @click="detailTab = 'description'" :class="['cursor-pointer text-center p-2 rounded-l-lg', detailTab == 'description' ? 'bg-crimson-200' : '']"><span class="rem--8 text-sm !font-bold">توضیحات بیشتر</span></div>
            </div>
            <div v-show="detailTab === 'links'" class="bg-[#1c1c22] p-1">
                <div v-for="movieLink in movie.movie_links" :key="movieLink.id">
                    <div @click="linksToggle(movieLink.id)" class="box !rounded-b-none !bg-[#17171c] !p-4 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span class="rem--9 !font-bold text-sm">{{movieLink.title}}</span>
                            <span><i class="rem--9 text-sm fas fa-angle-down !font-bold"></i></span>
                        </div>
                    </div>
                    <div v-if="showLink === movieLink.id" v-for="(link,index) in movieLink.links" :key="index" :class="['p-3 bg-black ease-in duration-300', index === (movieLink.links.length-1) ? 'rounded-b-lg' : '']" >
                        <div :class="['flex items-center fm:flex-col justify-between', index-1 < (movieLink.links.length-1) ? 'border-b border-b-gray-800 pb-3' : '']">
                            <div class="flex items-center gap-3 fm:flex-col fm:order-2 fm:mt-2" v-if="movieLink.vip === 'no' || movie.vip || movie.isAdmin">
                                <a :href="link.link" target="_blank" class="flex justify-center gap-2 bg-[#23232b] px-4 py-2 !rounded-full cursor-pointer">
                                    <span><i class="fas fa-arrow-to-bottom rem--1 text-xl text-crimson-100"></i></span>
                                    <span class="rem--8 text-sm ">دانلود {{ link.title }} </span>
                                </a>
                                <div class="flex gap-3 items-center">
                                    <a v-if="link.subtitle !== null" :href="url+link.subtitle" target="_blank"  class="cursor-pointer text-sm " data-title="زیرنویس"><i class="fas fa-closed-captioning rem--8 text-lg text-crimson-100"></i></a>
                                    <span class="cursor-pointer text-sm " data-title="گزارش خرابی لینک" @click="openModalReport(movieLink.id,link.id)"><i class="fas fa-reply rem--8 text-lg text-crimson-100"></i></span>
                                </div>
                            </div>
                            <div v-else class="flex items-center gap-3 fm:flex-col fm:order-2 fm:mt-2">
                                <Button text='نیازمند اشتراک'></Button>
                            </div>
                            <div class="flex flex-col gap-2 items-center fm:order-1">
                                <div><span class="text-md fm:!text-sm text-green-500">کیفیت: {{movie.quality}}</span></div>
                                <div><span class="fm:text-xs text-md">{{link.caption}}</span></div>
                            </div>
                        </div>
                        <div v-if="(movieLink.vip === 'no' || movie.vip || movie.isAdmin) && index === (movieLink.links.length-1) && movie.type == 'serial'">
                            <div class="text-center mt-3">
                                <Button text="مشاهده تمامی لینک های فصل" @click="linkList =! linkList"></Button>
                            </div>
                            <div v-show="linkList" class="mt-2 ltr flex flex-col gap-1 p-2 border border-gray-800 rounded">
                                <span class="" v-for="link in movieLink.links" :key="link.id" @click="copy(link.link)">{{link.link}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="detailTab === 'trailer'"  class="bg-[#1c1c22] p-1">
                <div class="box !bg-[#1c1c22]">
                    <div class="flex flex-wrap items-center justify-evenly">
                        <div v-for="movieLink in movie.movie_links" :key="movieLink.id" class="mt-3">
                             <div v-for="trailer in movieLink.trailers" :key="trailer.id" class="mt-3">
                                <video width="320" height="240" controls>
                                    <source :src="trailer.trailer" :type="trailerType(trailer.trailer)">
                                </video>
                                <div class="bg-black text-crimson-100 text-center p-2"><span class="rem--8 font-bold text-sm">{{trailer.caption}}</span></div>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="detailTab === 'screenshot'"  class="bg-[#1c1c22] p-1">
                <div class="box !bg-[#1c1c22]">
                    <div class="flex flex-wrap items-center justify-evenly">
                        <div v-for="movieLink in movie.movie_links" :key="movieLink.id">
                            <a v-if="movieLink.screen_shot !== null" class="mt-3" :href="url+movieLink.screen_shot" target="_blank">
                                <img :src="url+movieLink.screen_shot.screen_shot" />
                                <div class="bg-black text-crimson-100 text-center p-2"><span class="rem--8 font-bold text-sm">{{movieLink.title}}</span></div>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div v-show="detailTab === 'description'"  class="bg-[#1c1c22] p-1">
                <div class="box !bg-[#1c1c22]">
                    <p class="rem--8 break-word text-sm">
                        {{movie.moreDescription}}
                    </p>
                </div>
            </div>
        </div>

        <div class="box mt-2">
            <div class="flex items-center gap-3 mb-2">
                <span><i class="rem--1 text-2xl text-crimson-100 fab fa-artstation"></i></span>
                <span class="text-lg rem--1">مطالب مرتبط</span>
            </div>
            <Owl :item="[3,4,6]" id="related-movie">
                <div class="item relative group" v-for="movie in movie.relatedMovies" :key="movie.id">
                    <routerLink :to="{path:`/detail/${movie.slug}`}">
                        <img class="h-[19rem] fm:h-[11rem] rounded-lg" :src="url+movie.image" />
                        <figure class="absolute z-50 bottom-0 right-0 w-full cursor-pointer h-0 group group-hover:h-full ease-in duration-300 rounded-lg bg-black opacity-70">
                            <svg class="play-b"></svg>
                        </figure>
                        <div class="absolute bottom-0 w-full z-50">
                            <div class="flex flex-row w-full gap-2 p-2 items-center  bg-black opacity-60">
                                <span class="fm:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="44" height="18" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                                </span>
                                <span class="fd:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="35" height="14" viewBox="0 0 64 32"><g fill="#e80056"><rect x="0" y="0" width="100%" height="100%" rx="4"></rect></g><g transform="translate(8.000000, 7.000000)" fill="#000000" fill-rule="nonzero"><polygon points="0 18 5 18 5 0 0 0"></polygon><path d="M15.6725178,0 L14.5534833,8.40846934 L13.8582008,3.83502426 C13.65661,2.37009263 13.4632474,1.09175121 13.278113,0 L7,0 L7,18 L11.2416347,18 L11.2580911,6.11380679 L13.0436094,18 L16.0633571,18 L17.7583653,5.8517865 L17.7707076,18 L22,18 L22,0 L15.6725178,0 Z"></path><path d="M24,18 L24,0 L31.8045586,0 C33.5693522,0 35,1.41994415 35,3.17660424 L35,14.8233958 C35,16.5777858 33.5716617,18 31.8045586,18 L24,18 Z M29.8322479,3.2395236 C29.6339219,3.13233348 29.2545158,3.08072342 28.7026524,3.08072342 L28.7026524,14.8914865 C29.4312846,14.8914865 29.8796736,14.7604764 30.0478195,14.4865461 C30.2159654,14.2165858 30.3021941,13.486105 30.3021941,12.2871637 L30.3021941,5.3078959 C30.3021941,4.49404499 30.272014,3.97397442 30.2159654,3.74371416 C30.1599168,3.5134539 30.0348852,3.34671372 29.8322479,3.2395236 Z"></path><path d="M44.4299079,4.50685823 L44.749518,4.50685823 C46.5447098,4.50685823 48,5.91267586 48,7.64486762 L48,14.8619906 C48,16.5950653 46.5451816,18 44.749518,18 L44.4299079,18 C43.3314617,18 42.3602746,17.4736618 41.7718697,16.6682739 L41.4838962,17.7687785 L37,17.7687785 L37,0 L41.7843263,0 L41.7843263,5.78053556 C42.4024982,5.01015739 43.3551514,4.50685823 44.4299079,4.50685823 Z M43.4055679,13.2842155 L43.4055679,9.01907814 C43.4055679,8.31433946 43.3603268,7.85185468 43.2660746,7.63896485 C43.1718224,7.42607505 42.7955881,7.2893916 42.5316822,7.2893916 C42.267776,7.2893916 41.8607934,7.40047379 41.7816216,7.58767002 L41.7816216,9.01907814 L41.7816216,13.4207851 L41.7816216,14.8074788 C41.8721037,15.0130276 42.2602358,15.1274059 42.5316822,15.1274059 C42.8031285,15.1274059 43.1982131,15.0166981 43.281155,14.8074788 C43.3640968,14.5982595 43.4055679,14.0880581 43.4055679,13.2842155 Z"></path></g></svg>
                                </span>
                                <span class="rem--7 text-gray-200 !font-extrabold">{{movie.imdb_number}}/10</span>
                            </div>
                            <div class="bg-black opacity-60 w-full text-center">
                                <span class="rem--7 text-gray-200 !font-extrabold">{{movie.title}}</span>
                            </div>
                        </div>
                    </routerLink>
                </div>
            </Owl>
        </div>

        <div class="box" v-if="movie.status_comment == 'yes'">
            <Comment :comments="movie.comments" :movieId="movie.id"></Comment>
        </div>
        <Modal title="گزارش خرابی لینک" save="ثبت" ref="reportModal" @callback="report(movie.id)">
            <div><Error :errors="getErrors"></Error></div>
            <div class="items-center mt-8 mb-6">
                <div class="relative z-0 group w-full">
                    <div class="relative z-0 group w-full">
                        <textarea v-model="description" id="description" cols="7" placeholder=" " rows="3" class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7 text-gray-200 bg-transparent border-0 border-b-2 border-green-700 appearance-none focus:outline-none focus:ring-0 focus:border-green-700 peer"></textarea>
                        <label for="description" class="absolute text-md rem--7 text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0 peer-focus:text-gray-300 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            توضیح در مورد مشکل
                        </label>
                        <small class="text-crimson-100 rem--7">حداکثر تعداد کاراکتر ۱۰۰۰</small>
                    </div>
                </div>
            </div>
        </Modal>
        <Meta type="name" title="description" :description="movie.keyword !== null ? movie.keyword : $store.state.metaDescription+','+movie.title" />
        <Meta type="property" title="og:locale" description="fa_IR" />
        <Meta type="property" title="og:type" description="article" />
        <Meta :key="movie" type="property" title="og:title" :description="movie.siteName +'-مرجع دانلود فیلم و سریال'" />
        <Meta type="property" title="og:description" :description="movie.keyword !== null ? movie.keyword : $store.state.metaDescription+','+movie.title" />
        <Meta type="property" title="og:url" :description="appUrl" />
        <Meta type="property" title="article:published_time" :description="movie.created_at" />
        <Meta type="property" title="article:modified_time" :description="movie.updated_at" />
        <Meta type="property" title="og:image" :description="url+movie.image" />
        <Meta type="property" title="og:image:width" description="400" />
        <Meta type="property" title="og:image:height" description="592" />
        <Meta type="property" title="og:image:type" :description="imageType(movie.image)" />
        <Meta type="name" title="twitter:card" description="summary_large_image" />
        <Meta :key="movie" type="property" title="og:site_name" :description="movie.siteName" />
        <Meta :key="movie" :title="movie.siteName + '|'+movie.title" :description="movie.title" />
        <Loading :loading="loading"></Loading>
    </div>
</template>
<script>
    import Owl from '@/components/Owl'
    import Button from '@/components/Button'
    import Error from '@/components/Error'
    import axios from "../plugins/axios";
    import {mapGetters} from "vuex";
    import Loading from '@/components/Loading'
    import Input from '@/components/Input'
    import Alert from '../plugins/alert';
    import Modal from '@/components/Modal'
    import Meta from "@/components/Meta"
    import Comment from '@/components/Comment'
    import WatchListCheck from '@/components/WatchListCheck';
    export default{
        name:'Detail',
        components:{Owl,Button,Loading,Error,Modal,Input,Comment,WatchListCheck,Meta},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                appUrl:process.env.VUE_APP_URL,
                detailTab:'links',
                showLink:null,
                slug:null,
                movie:null,
                loading:false,
                dislikeCount:0,
                likeCount:0,
                answerId:null,
                description:null,
                movieLinkId:null,
                inkId:null,
                linkList:false,

            }
        },
        mounted() {
            this.$store.state.sidebar = false;
            this.$store.state.b_header = false;
            this.slug = this.$route.params.slug;
        },
        computed:{
            ...mapGetters(["getErrors"]),
        },
        methods:{
            async getMovie(){
              this.loading = true;
              await axios.get(this.api+'movie-detail/'+this.slug).then(resp=>{
                  this.movie = resp.data.data
                  this.dislikeCount = this.movie.dislike;
                  this.likeCount = this.movie.like;
                  this.loading = false;
                  
              })
              this.loading = false;
            },
            linksToggle(number){
                this.showLink =  this.showLink !== number ? number : null;
            },
            splitData(data,point='،'){
              if(data !== null){
                return data.split(point)
              }
              return data;
            },
            trailerType(data){
              let t= data.split('.')
              return 'video/'+t[t.length-1]
            },

            async report(id){
              this.$store.state.errors = [];
              this.loading = true;
              await axios.post(this.api+'report-link',{movie_id:id,movie_link_id:this.movieLinkId,link_id:this.linkId,description:this.description}).then(resp=>{
                Alert.show();
                this.description = null;
                this.$refs.reportModal.toggleModal();
              }).catch(err=>{
                this.$store.commit('catchError',err.response)
              })
              this.loading = false;
            },

            openModalReport(movieLinkId, linkId){
              this.description = null;
              this.movieLinkId = movieLinkId;
              this.linkId = linkId;
              this.$refs.reportModal.toggleModal();
            },

            async like(id,type){
              this.loading = true;
              await axios.post(this.api+'movie-like-dislike/'+id,{type:type}).then(resp=>{
                this.dislikeCount = resp.data.data.dislike;
                this.likeCount = resp.data.data.like;
                this.loading = false;
              }).catch(err=>{
                this.loading = false;
              })
            },
            imageType(image){
              if(image !== '' || image !== null || image !== undefined){
                let img = image.split('.');
                return img[img.length-1];
              }
              return 'jpg';
            }
        },
        updated() {
            this.slug = this.$route.params.slug;
        },
        watch: {
          slug(n,o) {
            this.$store.state.showSearch = false;
            this.slug = n;
            this.getMovie();
          }
        }

    }
</script>
