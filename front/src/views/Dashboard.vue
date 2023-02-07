<template>
    <div class="mt-5 flex flex-col items-center justify-center bg-[#1c1c22] px-5">
        <div v-if="isAdmin" class="flex flex-col bg-[#1c1c22] shadow-2xl rounded-3xl w-full">
          <div :class="['grid grid-cols-3 fm:grid-cols-1', this.$store.state.backendAside ? 'fm:hidden' : 'fm:visible']" v-if="userChart !== null && vipTransactionChart !== null && movieChart !== null">
            <div class="">
                <Line v-bind:chartData="userChart" v-bind:chartOptions="{}" />
            </div>
            <div class="">
                <Line v-bind:chartData="movieChart" v-bind:chartOptions="{}" />
            </div>
            <div class="">
                <Bar v-bind:chartData="vipTransactionChart" v-bind:chartOptions="{}" />
            </div>
          </div>
        </div>
        <div class="flex flex-col box w-full mt-12 mb-16">
            <div class="mt-10 px-3 mb-3">
                <div class="mb-3 mr-2">
                    <span class="rem--8">اخرین اشتراک های خریداری شده</span>
                </div>
                <table class="w-full border-collapse lg:table 2xl:table xl:lg:table md:table">
                    <Thead :titles="['موبایل','اشتراک','مبلغ','از کارت','تخفیف','روش پرداخت','کد پیگیری','تاریخ پایان','در تاریخ']" />
                    <tbody class="block lg:table-row-group xl:table-row-group 2xl:table-row-group md:table-row-group">
                        <tr v-for="item in vipTransactions" :key="item.id"
                            :class="['border border-grey-500 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row xl:table-row 2xl:table-row md:table-row ',item.isExpire ? 'bg-green-200' : 'bg-red-200']">
                            <Td title="موبایل" class="rem--7">{{item.mobile}} </Td>
                            <Td title="اشتراک" class="rem--7">{{item.plan}} </Td>
                            <Td title="مبلغ" class="rem--7">{{item.price}} ریال</Td>
                            <Td title="از کارت" class="rem--7">{{item.cart}}</Td>
                            <Td title="تخفیف" class="rem--7">{{item.discount}} % </Td>
                            <Td title="روش پرداخت" class="rem--7">{{item.type}}</Td>
                            <Td title="کد پیگیری" class="rem--7">{{item.transaction_id}}</Td>
                            <Td title="تاریخ پایان" class="rem--7">{{item.expire}}</Td>
                            <Td title="در تاریخ" class="rem--7">{{item.created_at}} </Td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    import Line from '@/components/charts/Line'
    import Bar from '@/components/charts/Bar'
    import Thead from '@/components/Thead'
    import Td from '@/components/Td'
    export default {
        name:'Dashboard',
        components:{Line,Bar,Thead,Td},
        data(){
            return{
                userChart:null,
                vipTransactionChart:null,
                movieChart:null,
                vipTransactions:[],
                width:200
            }
        },
        mounted() {
            this.chartData();
        },
        computed:{
            isAdmin(){
                return localStorage.getItem('access') === 'admin';
            }
        },
        methods:{
            async chartData(){
                await this.$store.dispatch('allData',['dashboard',false]);
                let data = await this.$store.getters.getAllData;
                this.vipTransactions = data.vipTransactions;
                this.userChart = this.setChartData(data[0],'کابران');
                this.vipTransactionChart = this.setChartData(data[1],'اشتراک ها','#9EFF00');
                this.movieChart = this.setChartData(data[2],'فیلم و سریال ها','#0070FF');

            },
            setChartData(data,label,bgColor='#f87979'){
                return {
                    labels: data.labels,
                    datasets: [
                        {
                        label: label,
                        backgroundColor: bgColor,
                        data: data.dataset
                        }
                    ]
                }
            }
        }
    }
</script>