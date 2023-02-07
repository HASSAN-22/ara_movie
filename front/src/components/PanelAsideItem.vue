  <template>
    <li class="relative">
        <a href="javascript:void(0)" @click="isAsideClick = !isAsideClick" :class="['flex pr-5 py-3 justify-between items-center']">
            <span class="rem--7 text-gray-300">
                <i :class="[icon, 'text-crimson-100']"></i>
                &nbsp {{ title }}
            </span>
            <span class="ml-3 rem--7 flex gap-2 items-center">
                <span v-show="notify" :class="['bg-red-500  text-white text-sm font-medium mr-2 px-1.5 rem--7 rounded-full dark:bg-pink-200 dark:text-pink-900',colorNotify()]">{{notifyCount}}</span>
                <span><i class="text-crimson-100 fa fa-chevron-down"></i></span>
            </span>
        </a>
        <ul :class="['relative p-3 mr-5 w-100 bg-[#18181d] border-r border-r-green-600', isAsideClick ? 'block' : 'hidden']">
            <li class="hover:bg-[#212129] p-2 cursor-pointer rounded" v-for="(subItem,i) in subItems" :key="i">
                <router-link class="rem--7 text-gray-300 hover:!text-crimson-100" :to="subItem.link">{{ subItem.title }}
                    <span v-show="subItem.hasOwnProperty('notify') && subItem.notify" :class="['bg-red-500 rem--7 text-white text-sm font-medium mr-2 px-1.5  rounded-full dark:bg-pink-200 dark:text-pink-900',colorNotify()]">
                        {{subItem.count}}
                    </span>
                </router-link>
            </li>
        </ul>
    </li>
</template>
<script>
export default {
    name:'PanelAsideItem',
    props:{
        title:String,
        icon:String,
        notify:{
            type:Boolean,
            default:false
        },
        notifyCount:{
            type:Number,
            default:0
        },
        subItems:{
            type:Array
        }
    },
    data(){
        return{
            isAsideClick:false,
            colors:['bg-red-500', 'bg-blue-500', 'bg-yellow-500', 'bg-slate-500', 'bg-gray-500', 'bg-zinc-500', 'bg-neutral-500', 'bg-stone-500', 'bg-orange-500', 'bg-amber-500', 'bg-lime-500', 'bg-emerald-500', 'bg-teal-500', 'bg-cyan-500', 'bg-sky-500', 'bg-indigo-500', 'bg-violet-500', 'bg-purple-500'],
        }
    },
    methods:{
        colorNotify(){
            return this.colors[Math.floor(Math.random() * this.colors.length)];
        },
    }
}
</script>