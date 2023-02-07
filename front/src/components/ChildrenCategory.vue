<template>
    <ul class="tree" v-for="category in categories" :key="category.id">
        <li class="tree-item">
            <a  href="javascript:void(0)" :class="['trigger flex items-center gap-1']">
                <span class="mt-[.4rem] fm:mt-[.3rem]"><i class="fas fa-angle-right rem--7 text-blue-500"></i></span>
                <span :class="['rem--7 text-sm',category.status === 'yes' ? 'text-green-500' : 'text-red-500']">{{ category.title }}</span>
                <span @click="show(`${category .id}`)"><i class="fas fa-edit text-sm rem--7 text-black"></i></span>
                <span @click="destroy(`${category.id}`)"><i class="fas fa-trash text-sm rem--7 text-black"></i></span>
            </a>
            <ul class="tree-parent open" v-if="category.children.length >0" v-for="item in category.children" :key="item.id">
                <li class="tree-item view">
                    <a href="javascript:void(0)" :class="['flex items-center gap-1']">
                        <span class="mt-[.4rem] fm:mt-[.3rem]"><i class="fas rem--7 fa-angle-right text-blue-500"></i></span>
                        <span :class="['rem--7 text-sm',item.status === 'yes' ? 'text-green-500' : 'text-red-500']">{{ item.title}}</span>
                        <span @click="show(`${item.id}`)"><i class="fas rem--7 text-sm fa-edit text-black"></i></span>
                        <span @click="destroy(`${item.id}`)"><i class="fas rem--7 text-sm fa-trash text-black"></i></span>
                    </a>
                    <ChildrenCategory :categories="item.children"></ChildrenCategory>
                </li>
            </ul>
        </li>
    </ul>
</template>
<script>
    export default {
        name:"ChildrenCategory",
        props:["categories"],
        methods:{
            destroy(post_id){
                this.$parent.destroy(post_id)
            },
            show(post_id){
                this.$parent.show(post_id)
            }
        }
    }
</script>