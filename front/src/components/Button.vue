<template>
    <div v-if="inputBtn" >
        <button :type="type" :class="[my_class,
            `text-white
            border-0
            border-b
            w-full
            !border-green-700
            focus:!outline-none focus:ring-0 font-medium text-sm px-5 py-2.5 fm:py-1 fm:px-2 text-center mr-2 mb-2`]">
          <span class="rem--8 break-all">{{ text }}</span>
          <slot></slot>
        </button>

    </div>
    <div v-else>
      <button :type="type" :class="[my_class,
        `text-white
        hover:text-white 
        border
        border-green-700 
        hover:!border-crimson-200
        hover:bg-crimson-300
        focus:outline-none focus:ring-0 font-medium ease-in duration-300 rounded-lg text-sm px-5 py-2.5 fm:py-1 fm:px-2 text-center mr-2 mb-2`]">
        <span class="rem--8">{{ text }}</span>
        <slot></slot>
      </button>
    </div>
</template>
<script>
export default {
    name:'Button',
    props:{
        text:String,
        my_class:String,
        inputBtn:{
          type:Boolean,
          default:false
        },
        type:{
            type:String,
            default:'button'
        }
    }
}
</script>
