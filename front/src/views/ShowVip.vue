<template>
    <div class="box w-[70%] fm:w-full">
        <div class="box flex gap-3">
            <div class="max-w-sm bg-[#1c1c22]  rounded overflow-hidden shadow-lg" v-for="plan in plans" :key="plan.id">
                <div class="relative">
                <img v-if="plan.image !== null" class="w-[280px] h-[250px] rounded" :src="url+plan.image"  alt="Sunset in the mountains">
                <img v-else class="w-[280px] h-[250px]"  src="@/assets/img/vip.jpg" alt="Sunset in the mountains">
                <div v-if="plan.discounts.length > 0" class="absolute top-0 right-0 px-2 rounded bg-red-500 ">
                    <span class="text-white">{{ plan.discounts[0].discount }}٪ تخفیف {{ plan.discounts[0].title }}</span>
                </div>
                </div>
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2 text-gray-300">{{plan.title}}</div>
                    <p class="text-gray-300">
                        {{ plan.description }} ddddddddddddddddddddddd
                    </p>
                </div>
                <div class="px-6 pt-4 pb-2">
                <ul>
                    <li>
                    <span class="inline-block bg-gray-900 rounded px-3 py-1 rem--8 font-semibold text-crimson-200  mb-2">
                        قیمت: 
                        <b class="text-green-500">{{new Intl.NumberFormat().format(plan.price)}} ریال </b>
                    </span>
                    </li>
                    <li v-if="plan.discounts.length > 0">
                        <span class="inline-block bg-gray-900 rounded px-3 py-1 rem--8 font-semibold text-crimson-200 mb-2">
                        کد تخفیف: 
                        <b class="text-green-500">{{plan.discounts[0].code}}</b>
                    </span>
                    </li>
                    <li>
                    <span class="inline-block bg-gray-900 rounded px-3 py-1 rem--8 font-semibold text-crimson-200  mb-2">
                        تعداد روز: 
                        <b class="text-green-500">{{plan.days}}</b>
                    </span>
                    </li>
                </ul>
                </div>
                <div class="px-6 pt-4 pb-2 w-full mb-2">
                <router-link  :to="{name:'UserVip'}" class="text-white hover:text-white border !border-green-700 hover:!border-crimson-200 hover:bg-crimson-300 focus:outline-none focus:ring-0 font-medium ease-in duration-300 rounded-lg text-sm px-5 py-2.5 fm:py-1 fm:px-2 text-center mr-2 mb-2">خرید اشتراک</router-link>
                </div>
            </div>
        </div>

        <Meta :key="title" :title="siteName+'|'+title" description="" />
        <Loading :loading="loading" />
    </div>
</template>
<script>
    import Meta from "@/components/Meta";
    import Loading from '@/components/Loading'
    import axios from '../plugins/axios';
    export default{
        name :'ShowVip',
        components: {Meta,Loading},
        data() {
            return {
                api:process.env.VUE_APP_API+'/api/',
                url:process.env.VUE_APP_API,
                plans:null,
                title:null,
                siteName:null,
                loading:false,
            }
        },
        mounted() {
            this.$store.state.sidebar = true;
            this.$store.state.b_header = false;
            this.allData()
        },
        methods:{
            async allData(loading=true,page=1){
              this.loading = loading
              await axios.get(this.api+'get-plan-vip').then(resp=>{
                let data = resp.data.data;
                this.plans = data.plans;
                this.title = data.title;
                this.siteName = data.siteName;
              })
              this.loading = false
            },
        },
    }
</script>