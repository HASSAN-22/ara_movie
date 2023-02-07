<template>
    <div v-if="show" class="mb-5 box text-center">
        <div class="flex items-center justify-center h-screen bg-gradient-to-r from-gray-800 to-gray-900">
            <div class="px-40 py-20 bg-[#1c1c22] rounded-md shadow-xl">
                <div class="flex flex-col items-center">
                    <h1 class="font-bold text-blue-600 text-9xl">404</h1>

                    <h6 class="mb-2 text-2xl font-bold text-center text-gray-300 md:text-3xl">
                    <span class="text-crimson-100">اووپس!</span> صفحه پیدا نشد
                    </h6>

                    <p class="mb-8 text-center text-gray-300 md:text-lg">
                    صفحه مورد نظر وجود ندارد.
                    </p>

                    <router-link
                    to="/"
                    class="text-white hover:text-white border !border-green-700 hover:!border-crimson-200 hover:bg-crimson-300 focus:outline-none focus:ring-0 font-medium ease-in duration-300 rounded-lg text-sm px-5 py-2.5 fm:py-1 fm:px-2 text-center mr-2 mb-2"
                    >بازگشت به صفحه اصلی</router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default{
    name:'NotFound',
    data(){
        return{
            show:false
        }
    },
    mounted(){
        this.timeOut();
    },
    methods:{
        timeOut(){
            setTimeout(()=>{
                this.show = true;
            },700);
        }
    }
}
</script>