<template>
    <div class="flex gap-1">
        <span class="rem--7">{{title}}</span>
        <span  v-show="checkNotify()" class="rounded-lg px-2 rem--7 text-white bg-red-500">جدید</span>
    </div>
</template>
<script>
export default {
    name:'Notify',
    props:['notifications','id','title'],
    methods:{
        checkNotify(){
            let foundId = false;
            if(this.notifications !== undefined){
                for(let i=0; i<this.notifications.length; i++){
                let notify = this.notifications[i];
                let id = Object.values(notify.data)[0]['id'];
                if(id === this.id){
                    foundId =  true;
                }
            }
            }
            return foundId;
        },
    }
}
</script>