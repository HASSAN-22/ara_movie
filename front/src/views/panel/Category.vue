<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">
          <div class="mt-4 self-right text-xl sm:text-sm text-gray-200">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">لیست {{ getAllData.contentTitle}} ها</li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3 w-full">
              <Error :errors="getErrors" />
            <div class="flex justify-between fm:flex-col mr-10">
                <div>
                    <Input v-model="title" id="title" title="عنوان دسته"  />
                    <div>
                        <label for="category_id" class="block mt-5 font-medium text-gray-300 rem--7">دسته <b class="text-red-600 rem--7">*</b> </label>
                        <select id="category_id" v-model="category_id" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                            <option value="null" disabled selected>--- انتخاب دسته ---</option>
                            <option value="0">سردسته</option>
                            <option v-for="category in getAllData" :key="category.id" :value="category.id">{{ category.title}}</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت <b class="text-red-600 rem--7">*</b> </label>
                        <select id="status" v-model="status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                            <option value="null" disabled selected>--- انتخاب وضعیت ---</option>
                            <option value="yes">فعال</option>
                            <option value="no">غیر فعال</option>
                        </select>
                    </div>
                    <Button text="ثبت" class="mt-5 w-full" color="green" @click="insert()" />
                </div>
                <div class="fm:mt-12">
                    <ul class="tree" v-for="item in getAllData" :key="item.id">
                        <li class="tree-item " v-if="item.parent_id === 0">
                            <a  href="javascript:void(0)" :class="['trigger flex items-center gap-1']">
                                <span class="mt-[.4rem] fm:mt-[.2rem]"><i class="fas fa-folder rem--7 text-yellow-500"></i></span>
                                <span :class="[item.status,'rem--7 text-sm']">{{ item.title}}</span>
                                <span @click="show(`${item.id}`)"><i class="fas rem--7 text-sm fa-edit text-blue-400"></i></span>
                                <span @click="destroy(`${item.id}`)"><i class="fas rem--7 text-sm fa-trash text-black"></i></span>
                            </a>
                            <ChildrenCategory :categories="item.children"></ChildrenCategory>
                        </li>
                    </ul>
                </div>
            </div>
          </div>
        </div>
        <Modal :title="'ویرایش '+getAllData.contentTitle" save="ثبت تغییرات" ref="modal" @callback="isUpdate ? update() : insert()">
            <Input v-model="e_title" id="e_title" title="عنوان دسته"  />
            <div>
                <label for="e_category_id" class="block mt-5 font-medium text-white rem--7">دسته <b class="text-red-600 rem--7">*</b> </label>
                <select id="e_category_id" v-model="e_category_id" class="bg-[#1c1c22] border-b border-b-green-500 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب دسته ---</option>
                    <option value="0">سردسته</option>
                    <option v-for="category in getAllData" :key="category.id" :value="category.id">{{ category.title}}</option>
                </select>
            </div>
            <div>
                <label for="e_status" class="block mt-5 font-medium text-gray-300 rem--7">وضعیت <b class="text-red-600 rem--7">*</b> </label>
                <select id="e_status" v-model="e_status" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب وضعیت ---</option>
                    <option value="yes">فعال</option>
                    <option value="no">غیر فعال</option>
                </select>
            </div>
        </Modal>
        <Loading :loading="loading"></Loading>
        <Meta :key="getAllData" :title="`لیست ${ getAllData.contentTitle } ها`" description="" />
    </div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Modal from '@/components/Modal'
    import ChildrenCategory from "@/components/ChildrenCategory";
    import Loading from "@/components/Loading";
    import Error from "@/components/Error";
    import Meta from "@/components/Meta";
    import {mapGetters} from 'vuex'
    export default {
        name:'Category',
        components:{Input,Button,Modal,ChildrenCategory,Loading,Error,Meta},
        data(){
            return{
                category_id:null,
                status:null,
                title:null,
                e_category_id:null,
                e_status:null,
                e_title:null,
                isModalVisible: false,
                loading:false,
                id:null,
                isUpdate:false,
            }
        },
        created() {
            this.allData()
        },
        computed:{
             ...mapGetters(["getAllData","getErrors"])
        },
        methods:{
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`category?page=${page}`,false,['contentTitle']])
                this.loading = false
            },
            showCreateModal(){
                this.$refs.modal.toggleModal();
            },
            async insert(){
                this.emptyError()
                this.loading = true;
                let data=[
                    'category',
                    {title:this.title,category_id:this.category_id,status:this.status}
                ]
                await this.$store.dispatch("insert",data)
                await this.allData(false)
                this.loading = false
                this.clear();
            },
            async show(post_id){
                this.emptyError()
                this.clear();
                this.$refs.modal.toggleModal();
                this.loading = this.isUpdate = true;
                await this.$store.dispatch("show",[`category/${post_id}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    this.id=data.id;
                    this.e_category_id = data.parent_id.toString();
                    this.e_title = data.title;
                    this.e_status = data.status;
                    this.loading = false;
                }else{
                    this.$refs.modal.toggleModal();
                    this.loading = this.$store.state.isLoading;
                }

            },
            async update(){
                this.loading = true;
                let data = [
                    `category/${this.id}`,
                    {title:this.e_title, category_id:this.e_category_id,status:this.e_status}
                ]
                await this.$store.dispatch('update',data)
                await this.allData(false)
                this.loading = false
                this.closeModal();
            },
            async destroy(post_id){
                this.loading = false;
                await this.$store.dispatch('deleteRecord',[`category/${post_id}`,'چنانچه دسته فعالی دارای زیر دسته باشد تمامی زیر دسته ها حذف میشوند'])
                await this.allData(true)
                this.loading = false
            },
            emptyError(){
                this.$store.state.errors = [];
            },
            clear(){
                this.title = null;
                this.category_id = null;
                this.status = null;
                this.e_category_id=null;
                this.e_status=null;
                this.e_title=null;
            }
        }
    }
</script>
<style scope lang="scss">

    @media only screen and (max-width: 768px) {
        .tree-item a, i{
            font-size: .8rem !important;
        }
    }
    .tree{
        direction: ltr !important;
    }
    $gray-light: #ff003b;

    a{
        text-decoration: none;
        color: #ffcc00;
        display: inline-block;
        height: 100%;
    }

    .tree-parent{
        margin-top: 0.5rem;
        display: none;
    }
    .tree-parent.open{
        display: block;
    }
    .tree-item{
        position: relative;
        margin-left: 1.7rem;
        line-height: 2rem;
    }
    .tree-item:last-child{
        border: none;
    }
    .tree-item:before{
        content:'';
        display: block;
        border-top: 1px solid #5a5a5a !important;
        /*border-left: 1px solid #5a5a5a !important;*/
        height: 100%;
        width: 1rem;
        position: absolute;
        bottom: -1.1rem;
        left: -0.9rem;
        z-index: 1;
    }
    .tree-item:last-child:before{
        border-left: none;
    }
    .tree-item:first-child:after{
        content:'';
        display: block;
        border-left: 1px solid #5a5a5a;
        height: 100%;
        width: 1rem;
        position: absolute;
        bottom: 0.9rem;
        left: -0.9rem;
        z-index: 1;
    }
    .tree-item a i{
        margin: 0 .5rem !important;
        color: #3498db;
    }
    .tree-item a{
        font-size: 1.3rem;
        display: flex;
        align-items: center;
    }
    .tree-item a .fa-trash{
        color: red;
        font-size: 1.2rem;
    }
    .tree-item a .fa-edit{
        color: #8c22c2;
        font-size: 1.2rem;
    }

</style>