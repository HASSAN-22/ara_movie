<template>
    <div>
        <div @click="watchList(movie.id)">
            <div v-if="movie.hasOwnProperty('watch_lists') && movie.watch_lists.length > 0"  :id="`mark-${movie.id}`">
                <span><i class="far fa-bookmark rem--8 text-3xl rem--1 hover:text-gray-300 text-crimson-200 cursor-pointer"></i></span>
            </div>
            <div v-else :id="`mark-${movie.id}`">
                <span><i class="far fa-bookmark rem--8 text-3xl rem--1 text-gray-300 hover:text-crimson-200 cursor-pointer"></i></span>
            </div>
        </div>
         <Loading :loading="loading"></Loading>
    </div>
</template>
<script>
import axios from '../plugins/axios';
import Alert from '../plugins/alert';
import Loading from '@/components/Loading'
export default {
    name:'WatchListCheck',
    props:["movie"],
    components:{Loading},
    data(){
        return {
            api:process.env.VUE_APP_API+'/api/',
            colorBookmark:'black',
            loading:false,
        }
    },
    methods:{
        async watchList(movie_id){
            const token = localStorage.getItem('token');
            if(token === '' || token === undefined || token === null){
            Alert.show('هشدار','برای ثبت در لیست تماشا باید وارد اکانت کاربری خود شوید','warning')
            }else{
            this.loading = true;
            let element = window.document.getElementById('mark-'+movie_id);
            await axios.post(this.api+'add-watch-list/'+movie_id).then(resp=>{
                if(resp.data.data === 'delete'){
                    element.innerHTML = `<span><i class="far fa-bookmark rem--8 text-3xl text-gray-300 hover:text-crimson-200 cursor-pointer"></i></span>`;
                    Alert.show('موفق','با موفقیت از لیست تماشا حذف شد','success');
                }else{
                    element.innerHTML = `<span ><i class="far fa-bookmark rem--8 text-3xl hover:text-gray-300 text-crimson-200 cursor-pointer"></i></span>`;
                    Alert.show('موفق','با موفقیت در لیست تماشا قرار گرفت','success');
                }
                
            }).catch(err=>{
                Alert.show('خطا','عملیت با خطا مواجه شد','error')
            })
            this.loading = false;
            }
              
        },
        watchListCheck(color){
            this.colorBookmark = color === 'red' ? 'hover:!text-crimson-200 !text-crimson-200 ' : 'hover:!text-gray-300 !text-gray-300';
            console.log(this.colorBookmark)
        },
    }
}
</script>