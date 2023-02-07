<template>
    <ul class="border-b border-b-red-300 pb-5 mb-5">
        <li class="red">{{ title }}</li>
        <li>
            <ul class="flex justify-around fm:flex-col fm:gap-2">

                <li class="flex items-center gap-2">
                    <span class="text-gray-400">لیست {{title}}</span>
                    <input type="checkbox" @change="updatePermission($event,`view_any_${table}`)" class="mr-0-5" v-if="perms.find(item=>item === `view_any_${table}`) !== undefined" checked>
                    <input type="checkbox" @change="updatePermission($event,`view_any_${table}`)" class="mr-0-5" v-else>
                </li>

                <li class="flex items-center gap-2">
                    <span class="text-gray-400">مشاهده {{title}}</span>
                    <input type="checkbox"  @change="updatePermission($event,`view_${table}`)" class="mr-0-5" v-if="perms.find(item=>item === `view_${table}`) !== undefined" checked>
                    <input type="checkbox" @change="updatePermission($event,`view_${table}`)" class="mr-0-5" v-else>
                </li>

                <li class="flex items-center gap-2">
                    <span class="text-gray-400">ثبت {{title}}</span>
                    <input type="checkbox"  @change="updatePermission($event,`create_${table}`)" class="mr-0-5" v-if="perms.find(item=>item === `create_${table}`) !== undefined" checked>
                    <input type="checkbox" @change="updatePermission($event,`create_${table}`)" class="mr-0-5" v-else>
                </li>

                <li class="flex items-center gap-2">
                    <span class="text-gray-400">ویرایش {{title}}</span>
                    <input type="checkbox"  @change="updatePermission($event,`update_${table}`)" class="mr-0-5" v-if="perms.find(item=>item === `update_${table}`) !== undefined" checked>
                    <input type="checkbox" @change="updatePermission($event,`update_${table}`)" class="mr-0-5" v-else>
                </li>

                <li class="flex items-center gap-2">
                    <span class="text-gray-400">حذف {{title}}</span>
                    <input type="checkbox"  @change="updatePermission($event,`delete_${table}`)" class="mr-0-5" v-if="perms.find(item=>item === `delete_${table}`) !== undefined" checked>
                    <input type="checkbox" @change="updatePermission($event,`delete_${table}`)" class="mr-0-5" v-else>
                </li>
            </ul>
        </li>
    </ul>
</template>
<script>
    export default {
        name:'Permission',
        props:["title","table","perms"],

        methods:{
            updatePermission(event,perm){
                if(event.target.checked){
                        this.$store.state.allPermissions.push(perm)
                }else{
                    this.$store.state.allPermissions = this.$store.state.allPermissions.filter((item)=>{return item !== perm})
                }
            }
        }
    }
</script>