<template>
  <div>
    <div v-show="showModal"  id="modal" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center flex">
      <div :class="['relative my-6 mx-auto',width]">
        <!--content-->
        <div class="border-0 rounded-lg shadow-lg shadow-gray-600 relative flex flex-col w-full bg-[#1c1c22] outline-none focus:outline-none">
          <!--header-->
          <div class="flex items-start justify-between p-5 border-b border-solid border-gray-600 rounded-t">
            <h3 class="text-lg font-semibold rem--7">
              {{ title }}
            </h3>
          </div>
          <!--body-->
          <div class="relative p-6 flex-auto">
            <slot></slot>
          </div>
          <!--footer-->
          <div class="flex items-center justify-end p-6 border-t border-solid border-gray-600 rounded-b">
            <button @click="toggleModal()" :class="[
              `text-white
              hover:text-white border
              !border-orange-700 hover:!border-crimson-200
              hover:bg-crimson-100
              focus:outline-none focus:ring-0 font-medium ease-in duration-300 rounded-lg text-sm px-5 py-2.5 fm:py-1 fm:px-2 text-center mr-2 mb-2`]">
              بستن
            </button>
              <Button v-show="save !== ''" :text="save" @click="$emit('callback')" />
          </div>
        </div>
      </div>
    </div>
    <div v-show="showModal" class="opacity-25 fixed inset-0 z-40 bg-black"></div>
  </div>
</template>

<script>
import Button from '@/components/Button'
export default {
  name: "regular-modal",
  components:{Button},
  props:{
      title:String,
      save:String,
      width:{
          type:String,
          default:'w-[50%]'
      }
  },
  data() {
    return {
      showModal: false
    }
  },
  methods: {
    toggleModal(){
      this.showModal = !this.showModal;
    }
  }
}
</script>