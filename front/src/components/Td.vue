<template>
    <td class="p-2 lg:border xl:border 2xl:border md:border lg:border-green-900 xl:border-green-900 2xl:border-green-900 md:border-green-900 lg:text-center xl:text-center 2xl:text-center md:text-center sm:text-right block lg:table-cell xl:table-cell 2xl:table-cell md:table-cell">
        <span class="inline-block w-1/3 lg:hidden xl:hidden 2xl:hidden md:hidden font-bold rem--7">{{ title }}: </span>
        <slot></slot>
    </td>
</template>
<script>
export default {
    name:'Td',
    props:['title']
}
</script>