<template>
    <div class="text-center m-5 flex items-center justify-center">
        <span class="text-lg rem--7 cursor-pointer flex fm:w-8 fm:h-8 w-12 h-12 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-emerald-500 bg-black text-emerald-500" v-if="previous !== null" @click="getPage(current -1)" title="صفحه قبلی"><i class="fa fa-chevron-right"></i></span>
        <span v-else class="text-lg rem--7 flex fm:w-8 fm:h-8 w-12 h-12 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-yellow-400 text-yellow-400" title="صفحه قبلی"><i class="fa fa-chevron-right"></i></span>
        <span class="text-lg rem--7 flex fm:w-8 fm:h-8 w-12 h-12 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-crimson-200 bg-crimson-200 text-white">{{ current }}</span>
        <span class="text-lg rem--7 cursor-pointer flex fm:w-8 fm:h-8 w-12 h-12 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-emerald-500 bg-black text-emerald-500" v-if="next !== null" @click="getPage(current+1)" title="صفحه بعدی"><i class="fa fa-chevron-left"></i></span>
        <span v-else class="text-lg rem--7 flex fm:w-8 fm:h-8 w-12 h-12 mx-1 p-0 rounded-full items-center justify-center leading-tight relative border border-solid border-yellow-400  text-yellow-400" title="صفحه بعدی"><i class="fa fa-chevron-left"></i></span>
    </div>
</template>
<script>
    export default {
        name:'Paginate',
        props:["current","next","previous","total"],
        methods:{
            getPage(page){
                this.$parent.allData(true,page);
            }
        }
    }
</script>
