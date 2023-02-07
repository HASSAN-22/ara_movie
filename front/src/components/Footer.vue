<template>
    <footer class="box !mb-0 !pb-0">
        <div class="grid grid-cols-2 fm:grid-cols-1 items-center justify-items-end fm:justify-items-center gap-2">
            <div class="flex flex-col gap-2">
                <router-link :to="{name:'Index'}"> <img :src="url+config.logo" class="fm:h-[3rem] h-[3rem] w-[20%] fm:w-[50%]" alt=""></router-link>
                <p class="break-word text-sm rem--8">
                    {{config.description}}
                </p>
            </div>
            <div class="float-left fm:my-2 fm:!float-none">
                <ul class="flex gap-3 items-center">
                    <li><a target="_blank" :href="config.telegram" class="cursor-pointer"><i class="text-3xl ease-in duration-300 text-gray-300 hover:text-crimson-200 fab fa-telegram"></i></a></li>
                    <li><a target="_blank" :href="config.facebook" class="cursor-pointer"><i class="text-3xl ease-in duration-300 text-gray-300 hover:text-crimson-200 fab fa-facebook"></i></a></li>
                    <li><a target="_blank" :href="config.instagram" class="cursor-pointer"><i class="text-3xl ease-in duration-300 text-gray-300 hover:text-crimson-200 fab fa-instagram"></i></a></li>
                    <li><a target="_blank" :href="config.twitter" class="cursor-pointer"><i class="text-3xl ease-in duration-300 text-gray-300 hover:text-crimson-200 fab fa-twitter"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="mt-2 bg-gray-900 rounded w-full p-3">
            <p class="rem--5 text-[.7rem]">
                 {{config.copy_right}}
            </p>
        </div>
    </footer>
</template>
<script>
    export default{
        name:'Footer',
        props:['config'],
        data(){
            return{
                url:process.env.VUE_APP_API,
            }
        }
    }
</script>