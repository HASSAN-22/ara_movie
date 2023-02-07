<template>
    <div class="box w-[70%] fm:w-full" v-if="page !== null">
        
        <div class="box !bg-[#1c1c22]">
            <div class="bg-[#23232b] p-2 text-center w-full rounded flex justify-between items-center">
                <h1 class="text-white">{{page.title}}</h1>
                <span class="text-white">{{new Date(page.created_at).toLocaleDateString('fa-IR')}} <i class="fa fa-calendar text-white"></i></span>
            </div>
            <div class="mt-5 flex gap-3 fm:flex-col">
                <div class="w-[18%] fm:!w-[45%]">
                    <p class="break-all" v-html="page.description"></p>
                </div>
            </div>
        </div>
        <Meta :key="siteName" :title="siteName+'|'+page.title" description="" />
        <Loading :loading="loading" />
    </div>
</template>
<script>
    import Meta from "@/components/Meta";
    import Loading from '@/components/Loading'
    import axios from '../plugins/axios';
    export default{
        name :'Page',
        components: {Meta,Loading},
        data() {
            return {
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                page:null,
                siteName:null,
                title:null,
                loading:false,
                advertising:null
            }
        },

        mounted() {
            this.$store.state.sidebar = true;
            this.$store.state.b_header = false;
            this.title = this.$route.params.title;
        },

        methods:{
          async allData(){
            this.loading = true
            await axios.get(this.api+'show-page/'+this.title).then(resp=>{
              let data = resp.data.data;
              this.page = data.page;
              this.siteName = data.siteName;
              this.advertising = data.advertising;
            })
            this.loading = false
          },
        },
        updated() {
            this.title = this.$route.params.title;
        },
        watch: {
          title(n,o) {
            this.title = n;
            this.allData();
          }
        }
    }
</script>