<template>
    <div class="box w-[70%] fm:w-full" v-if="getAllData.length > 0">
        <MovieDetail :movies="getAllData"></MovieDetail>
        <div v-if="next !== null || previous !== null" class="box">
            <Paginate :current="current" :next="next" :previous="previous" :total="total"></Paginate>
        </div>
        <Meta type="name" title="description" :description="$store.state.metaDescription" />
        <Meta type="property" title="og:locale" description="fa_IR" />
        <Meta type="property" title="og:type" description="website" />
        <Meta :key="getAllData" type="property" title="og:title" :description="getAllData.siteName +'-مرجع دانلود فیلم و سریال'" />
        <Meta type="property" title="og:description" :description="$store.state.metaDescription" />
        <Meta type="property" title="og:url" :description="appUrl" />
        <Meta type="name" title="twitter:card" description="summary_large_image" />
        <Meta :key="getAllData" type="property" title="og:site_name" :description="getAllData.siteName" />
        <Meta :key="getAllData" :title="`صفحه اصلی | ${getAllData.siteName}`" description="مرجع دانلود فیلم و سریال" />
        <Loading :loading="loading"></Loading>
    </div>
</template>
<script>
    import Paginate from '@/components/Paginate'
    import Loading from '@/components/Loading'
    import MovieDetail from '@/components/MovieDetail'
    import Meta from "@/components/Meta";
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default{
        name :'Index',
        components:{Paginate,Loading,MovieDetail,Meta},
        data(){
            return{
                url:process.env.VUE_APP_API,
                appUrl:process.env.VUE_APP_URL,
                value:null,
                type:null,
                loading:false,
            }
        },
    
        mounted() {
            this.$store.state.sidebar = true;
            this.$store.state.b_header = true;
            this.value = this.$route.params.value;
        },
        computed:{
            ...mapGetters(["getAllData","getErrors","getGenres","getPg","getImdb"]),
            ...mapState(["current","next","previous","total"]),
        },
        methods:{
            async allData(loading=true,page=1){
              this.loading = loading
              let v = (this.value !== null && this.value !== undefined) ? '&v='+this.value : '';
              let type = this.$store.state.genreisSwitched ? this.$store.state.genreSwitch : '';
              await this.$store.dispatch('allData',[`get-movies?page=${page}${v}&type=${type}&t=${localStorage.getItem('token')}`,true,['countries','siteName']])
              this.loading = false
            }

        },
        updated() {
          this.value =  this.$route.params.value;
        },
        watch: {
          value(n,o) {
            this.value = n;
            this.allData()
          }
        }
    }
</script>