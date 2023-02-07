<template>
    <div class="mt-5 flex flex-col items-center justify-center px-5">
        <div class="flex flex-col w-full min-h-screen rounded bg-[#1c1c22]">

          <div class="mt-4 self-right text-xl sm:text-sm text-gray-800">
            <ul class="inline-flex ">
              <li class="mr-2 text-gray-200 rem--7"> <router-link :to="{name:'Dashboard'}" class="items-center uppercase leading-snug text-gray-200 opacity-75  hover:no-underline">داشبورد</router-link> <i class="fa fa-chevron-left pt-1 rem--7"></i></li>
              <li class="mr-2 text-gray-500 rem--7">لیست {{getAllData.contentTitle}} ها</li>
            </ul>
          </div>

          <div class="mt-10 px-3 mb-3">
            <div class="mb-3 mr-2 flex fm:flex-col justify-between items-center">
                <Button text="اضافه کردن" color="green" @click="create()"></Button>
              <div class="ml-2 fm:mt-3">
                  <Input type="search" v-debounce:1s="search" :debounce-events="['keydown']" id="search" title="جستجو: متن خود را وارد کنید" :required="false" />
              </div>
            </div>
            <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                <Thead :titles="['نام و نام خانوادگی','موبایل','نقش','ایمیل','وضعیت','تاریخ ثبت نام','عملیات']" />
                <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                    <tr v-for="item in getAllData" :key="item.id" class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row">
                        <Td title="نام و نام خانوادگی" class="rem--7">{{item.name}}</Td>
                        <Td title="موبایل" class="rem--7">{{item.mobile}}</Td>
                        <Td title="نقش" class="rem--7">{{item.access}}</Td>
                        <Td title="ایمیل" class="rem--7">{{item.email}}</Td>
                        <Td title="وضعیت" class="rem--7">{{item.status}}</Td>
                        <Td title="تاریخ ثبت نام" class="rem--7">{{item.created_at}}</Td>
                        <Td title="عملیات">
                          <div class="flex items-center justify-center">
                            <Button text="" color="blue" v-tooltip="'ویرایش'" @click="show(`${item.id}`)"><i class="fas fa-edit text-blue-700"></i></Button>
                            <span v-if="item.is_admin">
                                <Button v-if="$store.state.user.access === 'admin' && item.id != $store.state.user.id" v-tooltip="'سطح دسترسی'" text="" color="green" @click="showPermission(`${item.id}`)"><i class="fas fa-user text-green-700"></i></Button>
                                <Button v-else text="" color="black" v-tooltip="'سطح دسترسی'"><i class="fas fa-user text-black"></i></Button>

                            </span>
                            <span v-else>
                                <Button text="" v-tooltip="'سطح دسترسی'" @click="showAlert()" color="yellow"><i class="fas fa-user text-yellow-700"></i></Button>
                            </span>
                            <Button v-if="item.id != $store.state.user.id" v-tooltip="'حذف'" @click="destroy(`${item.id}`)" text="" color="red"><i class="fas fa-trash text-red-700"></i></Button>
                            <Button v-else text="" v-tooltip="'حذف'" color="black"><i class="fas fa-trash text-black"></i></Button>
                          </div>
                        </Td>
                    </tr>
                </tbody>
            </table>
            <div v-if="next !== null || previous !== null">
              <Paginate :current="current" :next="next" :previous="previous" :total="total"></Paginate>
            </div>
          </div>
        </div>
        <Modal :title="isUpdate ? `ویرایش ${getAllData.contentTitle}` : `ثبت ${getAllData.contentTitle} جدید`" :save="isUpdate ? 'ثبت تغییرات' : 'ثبت'" ref="modal" @callback="isUpdate ? update() : insert()">
            <div><Error :errors="getErrors"></Error></div>
            <Input v-model="name" id="name" title="نام و نام خانوادگی"></Input>
            <Input v-model="mobile" id="mobile" title="موبایل"></Input>
            <Input type="email" v-model="email" id="email" title="ایمیل"></Input>
            <Input type="password" v-model="password" id="password" title="رمز عبور" :placeholder="isUpdate ? ' اگر تغییر نمیدهید خالی بگذارید ' : 'رمز عبور'"></Input>
            <div>
                <label for="access" class="block mt-5 font-medium text-gray-300 rem--7">نقش <b class="text-red-600 rem--7">*</b> </label>
                <select id="access" v-model="access" class="bg-[#1c1c22]  border-b border-b-green-700 text-gray-300 text-lg rem--7 focus:outline-none block w-full">
                    <option value="null" disabled selected>--- انتخاب نقش ---</option>
                    <option value="admin">مدیر سایت</option>
                    <option value="user">کاربر سایت</option>
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
        </Modal>
        <Modal v-if="getAllData.hasOwnProperty('menu')" width="w-[60%]" title="ویرایش سطوح دسترسی" save="ثبت تغییرات" @callback="updatePermission()" ref="permission">
            <div><Error :errors="getErrors"></Error></div>
            <Permission :title="getAllData.menu.category.title" table="category" :perms="categoryPerm"></Permission>
            <Permission :title="getAllData.menu.user.title" table="user" :perms="userPerm"></Permission>
            <Permission :title="getAllData.menu.video.contentTitle[0]" table="movie" :perms="moviePerm"></Permission>
            <Permission :title="getAllData.menu.video.contentTitle[1]" table="movielink" :perms="movieLinkPerm"></Permission>
            <Permission :title="getAllData.menu.video.contentTitle[2]" table="movietag" :perms="tagPerm"></Permission>
            <Permission :title="getAllData.menu.video.contentTitle[3]" table="reportlink" :perms="ReportLinkPerm"></Permission>
            <Permission :title="getAllData.menu.plan.title" table="plan" :perms="planPerm"></Permission>
            <Permission :title="getAllData.menu.discount.title" table="discount" :perms="discountPerm"></Permission>
            <Permission :title="getAllData.menu.bank.contentTitle[0]" table="cart" :perms="cartPerm"></Permission>
            <Permission :title="getAllData.menu.bank.contentTitle[1]" table="bankportal" :perms="bankPortalPerm"></Permission>
            <Permission :title="getAllData.menu.bank.contentTitle[2]" table="wallettransaction" :perms="walletTransactionPerm"></Permission>
            <Permission :title="getAllData.menu.bank.contentTitle[3]" table="checkout" :perms="checkoutPerm"></Permission>
            <Permission :title="getAllData.menu.vip.title" table="viptransaction" :perms="vipTransactionPerm"></Permission>
            <Permission :title="getAllData.menu.comment.title" table="comment" :perms="commentPerm"></Permission>
            <Permission :title="getAllData.menu.page.title" table="page" :perms="pagePerm"></Permission>
            <Permission :title="getAllData.menu.slider.title" table="slider" :perms="SliderPerm"></Permission>
        </Modal>
        <Meta :key="getAllData" :title="`لیست ${getAllData.contentTitle} ها` " description="" />
        <Loading :loading="loading" />
    </div>
</template>
<script>
    import Input from '@/components/Input'
    import Button from '@/components/Button'
    import Thead from '@/components/Thead'
    import Td from '@/components/Td'
    import Modal from '@/components/Modal'
    import Error from '@/components/Error'
    import Paginate from '@/components/Paginate'
    import Loading from '@/components/Loading'
    import Meta from '@/components/Meta'
    import Permission from '@/components/Permission'
    import Alert from '@/plugins/alert'
    import axios from '@/plugins/axios'
    import {mapGetters, mapMutations, mapState} from "vuex";
    export default {
        name:'User',
        components:{Input,Button,Thead,Td,Modal,Meta,Error,Paginate,Loading,Permission},
        data(){
            return{
                api:process.env.VUE_APP_API+'/api/',
                id:null,
                isUpdate:false,
                loading:false,
                model:'user',
                name:null,
                email:null,
                mobile:null,
                password:null,
                access:null,
                status:null,
                permissions:[],
                isPermissionVisible:false,
                categoryPerm:[],
                userPerm:[],
                moviePerm:[],
                movieLinkPerm:[],
                tagPerm:[],
                planPerm:[],
                discountPerm:[],
                cartPerm:[],
                bankPortalPerm:[],
                walletTransactionPerm:[],
                checkoutPerm:[],
                vipTransactionPerm:[],
                commentPerm:[],
                pagePerm:[],
                SliderPerm:[],
                ReportLinkPerm:[],
                user:null
            }
        },
        created() {
            this.allData()
        },
        computed:{
            ...mapGetters(["getAllData","getErrors"]),
            ...mapState(["current","next","previous","total"])
        },
        methods:{
            ...mapMutations(["toEnglishDigits"]),
            async allData(loading=true,page=1){
                this.loading = loading
                await this.$store.dispatch('allData',[`${this.model}?page=${page}`,true,['contentTitle','menu']])
                this.loading = false
            },
            create(){
                this.emptyError();
                this.clear();
                this.isUpdate = false;
                this.$refs.modal.toggleModal();
            },
            async insert(){
                this.loading = true;
                let data=[
                    this.model,
                    this.setData()
                ]
                await this.$store.dispatch("insert",data)
                await this.allData(false,this.current)
                this.loading = false;
                if(this.$store.state.clear){
                    this.clear();
                }
            },
            async show(post_id){
                this.emptyError();
                this.clear();
                this.loading = this.isUpdate = true;
                this.$refs.modal.toggleModal();
                await this.$store.dispatch("show",[`${this.model}/${post_id}`])
                let data = this.$store.getters.getSingleData;
                if(data !== null){
                    this.id=data.id;
                    this.name = data.name;
                    this.email = data.email;
                    this.mobile = data.mobile;
                    this.access = data.access;
                    this.status = data.status;
                    this.loading = false;
                }else{
                    this.$refs.modal.toggleModal();
                    this.loading = this.$store.state.isLoading;
                }
            },
            async update(){
                this.loading = true;
                let data = [
                    `${this.model}/${this.id}`,
                    this.setData()
                ]
                await this.$store.dispatch('update',data)
                await this.allData(false,this.current)
                this.loading = false
            },
            async destroy(post_id){
                await this.$store.dispatch('deleteRecord',[`${this.model}/${post_id}`,''])
                await this.allData(true,this.current)
                this.loading = false
            },
            async search(text){
                this.loading = true;
                await this.$store.dispatch('search',[`${this.model}-search`,{search:text},['contentTitle','menu']]);
                this.loading = false;
            },
            async showPermission(post_id){
                this.loading = true;
                this.$refs.permission.toggleModal();
                await axios.get(this.api+'user-permission/'+post_id).then(resp=>{
                    this.permissions = resp.data.data;
                    this.user = resp.data.user;
                    this.permissions.filter((item)=>{
                        item.name.includes('category') ? this.categoryPerm.push(item.name) : '';
                        item.name.includes('user') ? this.userPerm.push(item.name) : '';
                        item.name.includes('movie') ? this.moviePerm.push(item.name) : '';
                        item.name.includes('movielink') ? this.movieLinkPerm.push(item.name) : '';
                        item.name.includes('movietag') ? this.tagPerm.push(item.name) : '';
                        item.name.includes('plan') ? this.planPerm.push(item.name) : '';
                        item.name.includes('discount') ? this.discountPerm.push(item.name) : '';
                        item.name.includes('cart') ? this.cartPerm.push(item.name) : '';
                        item.name.includes('bankportal') ? this.bankPortalPerm.push(item.name) : '';
                        item.name.includes('wallettransaction') ? this.walletTransactionPerm.push(item.name) : '';
                        item.name.includes('checkout') ? this.checkoutPerm.push(item.name) : '';
                        item.name.includes('viptransaction') ? this.vipTransactionPerm.push(item.name) : '';
                        item.name.includes('comment') ? this.commentPerm.push(item.name) : '';
                        item.name.includes('page') ? this.pagePerm.push(item.name) : '';
                        item.name.includes('reportlink') ? this.ReportLinkPerm.push(item.name) : '';
                        item.name.includes('slilder') ? this.SliderPerm.push(item.name) : '';
                        this.$store.state.allPermissions.push(item.name);
                    })
                })
                this.loading = false;

            },
            async updatePermission(){
                this.loading = true;
                let data=[
                    `user-update-permission/${this.user.id}`,
                    {permissions: this.$store.state.allPermissions}
                ]
                await this.$store.dispatch("update",data)
                this.loading = false;
                this.$refs.permission.toggleModal();
            },
            setData(){
                return {
                    name: this.name,
                    email: this.email,
                    mobile: this.mobile,
                    password: this.password,
                    access: this.access,
                    status: this.status
                }
            },
            clear(){
                this.name = null;
                this.email = null;
                this.mobile = null;
                this.password = null;
                this.access = null;
                this.status = null;
            },
            showAlert(){
                Alert.show('هشدار','برای ویرایش سطوح دسترسی لازم از کاربر مدیر سایت شود','warning',6000)
            },
            emptyError(){
                this.$store.state.errors = [];
            }
        }
    }
</script>