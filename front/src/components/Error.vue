<template>
    <div v-if="errors.length > 0" class="bg-[#ffbcc8] mb-3 w-full text-center rounded p-3">
        <ul class="flex flex-col gap-3">
            <li class="text-crimson-100" v-for="(error,i) in errors" :key="i">{{ error[0] }}</li>
        </ul>
    </div>
</template>
<script>
export default {
    name:'Error',
    props:{errors:Array}
}
</script>
