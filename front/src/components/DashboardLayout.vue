<template>
    <div id="snackbar"></div>
    <div class="container-fluid !p-0 !m-0">
      <div class="flex">
          <aside :class="['box !m-0 !rounded-none !bg-[#1c1c22] p-3 min-h-screen w-72', aside ? '!block' : 'hidden']">
              <div class="mt-8" v-if="menu !== null">
                  <ul class="flex flex-col gap-3" v-if="isAdmin">
                      <PanelAsideItem :title="menu.category.title" :icon="menu.category.icon" :subItems="[{'title':menu.category.subItem[0], 'link':{name:'Category'}}]" />
                      <PanelAsideItem :title="menu.user.title" :icon="menu.user.icon" :subItems="[{'title':menu.user.subItem[0], 'link':{name:'User'}}]" />
                      <PanelAsideItem :notify="true" :notifyCount="reportLinkCount" :title="menu.video.title" :icon="menu.video.icon" :subItems="[
                          {'title':menu.video.subItem[0], 'link':{name:'Movie'}},
                          {'title':menu.video.subItem[1], 'link':{name:'MovieLink'}},
                          {'title':menu.video.subItem[2], 'link':{name:'MovieTag'}},
                          {'title':menu.video.subItem[3], 'link':{name:'ReportLink'},'notify':true, 'count':reportLinkCount},
                        ]" />
                      <PanelAsideItem :title="menu.plan.title" :icon="menu.plan.icon" :subItems="[{'title':menu.plan.subItem[0], 'link':{name:'Plan'}}]" />
                      <PanelAsideItem :title="menu.discount.title" :icon="menu.discount.icon" :subItems="[{'title':menu.discount.subItem[0], 'link':{name:'Discount'}}]" />
                      <PanelAsideItem :notify="true" :notifyCount="cartCount+checkoutCount" :title="menu.bank.title" :icon="menu.bank.icon" :subItems="[
                          {'title':menu.bank.subItem[0], 'link':{name:'Cart'},'notify':true, 'count':cartCount},
                          {'title':menu.bank.subItem[1], 'link':{name:'BankPortal'}},
                          {'title':menu.bank.subItem[2], 'link':{name:'WalletTransaction'}},
                          {'title':menu.bank.subItem[3], 'link':{name:'Checkout'},'notify':true, 'count':checkoutCount},
                      ]" />
                      <PanelAsideItem :title="menu.vip.title" :icon="menu.vip.icon" :subItems="[{'title':menu.vip.subItem[0], 'link':{name:'Vip'}}]" />
                      <PanelAsideItem :notify="true" :notifyCount="commentCount" :title="menu.comment.title" :icon="menu.comment.icon" :subItems="[{'title':menu.comment.subItem[0], 'link':{name:'Comment'}}]" />
                      <PanelAsideItem :title="menu.page.title" :icon="menu.page.icon" :subItems="[{'title':menu.page.subItem[0], 'link':{name:'Page'}}]" />
                      <PanelAsideItem :title="menu.configSite.title" :icon="menu.configSite.icon" :subItems="[{'title':menu.configSite.subItem[0], 'link':{name:'ConfigSite'}}]" />
                      <PanelAsideItem :title="menu.slider.title" :icon="menu.slider.icon" :subItems="[{'title':menu.slider.subItem[0], 'link':{name:'Slider'}}]" />

                  </ul>
                  <ul v-else class="flex flex-col gap-3">
                      <PanelAsideItem :title="menu.userWallet.title" :icon="menu.userWallet.icon" :subItems="[{'title':menu.userWallet.subItem[0], 'link':{name:'UserWalletTransaction'}},{'title':menu.userWallet.subItem[1], 'link':{name:'UserCheckout'}}]" />
                      <PanelAsideItem :title="menu.userCart.title" :icon="menu.userCart.icon" :subItems="[{'title':menu.userCart.subItem[0], 'link':{name:'UserCart'}}]" />
                      <PanelAsideItem :title="menu.userVip.title" :icon="menu.userVip.icon" :subItems="[{'title':menu.userVip.subItem[0], 'link':{name:'UserVip'}}]" />
                      <PanelAsideItem :title="menu.watchList.title" :icon="menu.watchList.icon" :subItems="[{'title':menu.watchList.subItem[0], 'link':{name:'UserWatchList'}}]" />
                  </ul>
              </div>
          </aside>
          <div class="flex flex-col w-full">
              <header class="box !rounded-none !m-0 p-3 w-full flex items-center justify-between">
                  <div class="flex items-center gap-5 fm:flex-col fm:gap-3 fm:items-start">
                      <span  @click="aside = !aside" class="cursor-pointer">
                          <span v-if="aside"><i class="fas fa-bars text-2xl fm:text-lg text-crimson-200"></i></span>
                          <span v-else><i class="fas fa-times text-2xl fm:text-lg text-crimson-200"></i></span>
                      </span>
                      <span class="rem--6">
                          وضعیت اکانت: <b :class="['rem--6', isActive ? 'text-green-500' : 'text-red-500']">{{isActive ? 'فعال' : 'غیر فعال'}}</b>
                      </span>
                      <span class="rem--7" v-show="!isAdmin">
                          موجودی کیف پول: <b :class="['text-green-500 rem--7']">{{walletAmount}}</b> ریال
                      </span>
                  </div>
                  <div class="relatvie w-[15%] fm:w-[30%] group cursor-pointer">
                      <div class="flex items-center justify-center gap-2 p-2 w-full">
                          <span class="rem--7">{{name}}</span>
                          <span><i class="text-crimson-100 rem--7 fa fa-chevron-down"></i></span>
                      </div>
                      <div class="absolute p-2 w-[15%] fm:w-[30%] bg-black rounded group-hover:block hidden opacity-70">
                          <ul class="flex flex-col gap-2">
                              <li class="hover:bg-gray-900 p-2 rounded cursor-pointer text-gray-300"><router-link :to="{name:isAdmin ? 'Profile' : 'UserProfile'}" class="rem--7 hover:text-crimson-200 !font-bold">پروفایل</router-link></li>
                              <li class="hover:bg-gray-900 p-2 rounded cursor-pointer text-gray-300"><button @click="logout()" class="rem--7 hover:text-crimson-200 !font-bold">خروج</button></li>
                          </ul>
                      </div>
                  </div>
              </header>
              <main>
                <router-view/>
              </main>
          </div>
      </div>
      <div class="" v-if="config !== null">
          <Footer :config="config"></Footer>
      </div>
    </div>
</template>
<script>
import PanelAsideItem from '@/components/PanelAsideItem'
import Footer from '@/components/Footer'
import axios from "../plugins/axios";
export default{
    name:'DashboardLayout',
    components:{PanelAsideItem,Footer},
    data(){
        return{
            url:process.env.VUE_APP_API,
            api:process.env.VUE_APP_API+'/api/',
            aside:true,
            isActive:false,
            menu:null,
            config:null,
            cartCount:0,
            checkoutCount:0,
            commentCount:0,
            walletAmount:0,
            reportLinkCount:0
        }
    },
    mounted(){
        this.getMenuData();
    },
    computed:{
        isAdmin(){
            return localStorage.getItem('access') === 'admin';
        },
        avatar(){
            return this.url+localStorage.getItem('avatar');
        },
        name(){
            return localStorage.getItem('name')
        }
    },
    methods:{
        async getMenuData(){
            await axios.get(this.api+'menu').then(resp=>{
                let data = resp.data.data;
                this.menu = data.menu;
                this.config = data.config;
                this.checkoutCount = data.checkoutCount;
                this.cartCount = data.cartCount;
                this.reportLinkCount = data.reportLinkCount;
                this.commentCount = data.commentCount;
                this.walletAmount = data.walletAmount;
                this.isActive = data.isActive
            })
            console.log(this.commentCount)
        },
        logout(){
            this.$store.dispatch('logout',['logout']);
        }
    }
}
</script>