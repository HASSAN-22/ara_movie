<template>
    <div class="box w-[70%] fm:w-full">
        <MovieDetail :key="getAllData" :movies="getAllData"></MovieDetail>
        <div v-if="next !== null || previous !== null" class="box">
            <Paginate :current="current" :next="next" :previous="previous" :total="total"></Paginate>
        </div>
        <Meta type="name" title="description" :description="$store.state.metaDescription" />
        <Meta type="property" title="og:locale" description="fa_IR" />
        <Meta type="property" title="og:type" description="article" />
        <Meta :key="getAllData" type="property" title="og:title" :description="getAllData.siteName +'-مرجع دانلود فیلم و سریال'" />
        <Meta type="property" title="og:description" :description="$store.state.metaDescription" />
        <Meta type="property" title="og:url" :description="appUrl" />
        <Meta type="name" title="twitter:card" description="summary_large_image" />
        <Meta :key="getAllData" type="property" title="og:site_name" :description="getAllData.siteName" />
        <Meta :key="getAllData" :title="`لیست فیلم و سریال | ${getAllData.siteName}`" description="مرجع دانلود فیلم و سریال" />
        <Loading :loading="loading"></Loading>
    </div>
</template>
<script>
    import Paginate from '@/components/Paginate'
    import Loading from '@/components/Loading'
    import Meta from "@/components/Meta";
    import {mapGetters, mapMutations, mapState} from "vuex";
    import MovieDetail from '@/components/MovieDetail'
    export default{
        name:'MoviesList',
        components:{Paginate,Loading,MovieDetail,Meta},
        data(){
            return{
                url:process.env.VUE_APP_API,
                appUrl:process.env.VUE_APP_URL,
                slug:null,
                loading:false,
            }
        },
        mounted() {
            this.$store.state.sidebar = true;
            this.$store.state.b_header = false;
            this.slug = this.$route.params.slug;
        },
        computed:{
            ...mapGetters(["getAllData","getErrors","getGenres","getPg","getImdb"]),
            ...mapState(["current","next","previous","total"]),
        },
        methods:{
            async allData(loading=true,page=1){
              this.loading = loading
              let slug = (this.slug !== null && this.slug !== undefined) ? '&slug='+this.slug : '';
              await this.$store.dispatch('allData',[`list-movies?page=${page}${slug}&t=${localStorage.getItem('token')}`,true,['countries','siteName']])
              this.loading = false
            }
        },
        updated() {
          this.slug =  this.$route.params.slug;
        },
        watch: {
          slug(n,o) {
            this.slug = n;
            this.allData()
          }
        }



    }
</script>