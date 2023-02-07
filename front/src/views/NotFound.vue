<template>
    <div class="box w-[70%] fm:w-full">
        <notfound></notfound>
        <Meta title="خطای 404" description="" />
    </div>
</template>
<script>
     import Meta from "@/components/Meta";
     import notfound from "@/components/NotFound";
    export default{
        name :'NotFound',
        components:{Meta,notfound},
         mounted() {
            this.$store.state.sidebar = true;
            this.$store.state.b_header = false;
        },
    }
</script>