<template>
<thead class="block lg:table-header-group xl:table-header-group 2xl:table-header-group md:table-header-group">
    <tr class="border border-green-900 lg:border-none xl:border-none 2xl:border-none md:border-none block lg:table-row 2xl:table-row xl:table-row md:table-row absolute -top-full lg:top-auto 2xl:top-auto xl:top-auto md:top-auto  -left-full lg:left-auto xl:left-auto 2xl:left-auto md:left-auto  lg:relative xl:relative 2xl:relative md:relative ">
        <th class="bg-black p-2 text-white font-bold lg:border xl:border 2xl:border md:border lg:border-green-900 xl:border-green-900 2xl:border-green-900 md:border-green-900 text-center block lg:table-cell xl:table-cell 2xl:table-cell md:table-cell rem--7" v-for="(title, i) in titles" :key="i">{{title}}</th>
    </tr>
</thead>

</template>
<script>
export default {
    name:'Thead',
    props:['titles']
}
</script>