<template>
    <div :class="['relative z-0 mb-6 group',width]">
        <input
        :type="type"
        :v-mode="modelValue"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :id="id"
        class="block py-2.5 fm:py-1.5 px-0 w-full text-md rem--7
        text-gray-300 bg-transparent border-b-2
        border-green-600 appearance-none
         focus:outline-none focus:ring-0
        focus:border-green-600 peer"
        :placeholder="placeholder"/>

        <label :for="id" class="absolute text-md rem--7 right-0
        text-gray-300
        duration-300 transform
        -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:right-0
        peer-focus:text-white
        peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
        peer-focus:scale-75 peer-focus:-translate-y-6">{{ title }} <b v-show="required"
        class="text-crimson-100 rem--7">*</b></label>
    </div>
</template>
<script>
export default {
    name:'Input',
    props:{
        modelValue:String|Number,
        width:{
            type:String,
            default:'w-full'
        },
        type:{
            type:String,
            default:'text'
        },
        placeholder:{
            type:String,
            default:' '
        },
        title:String,
        id:String,
        required:{
            type:Boolean,
            default:true
        }
    }
}
</script>
