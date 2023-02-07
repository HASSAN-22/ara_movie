<template>
    <div class="owl-carousel owl-theme ltr" :id="id">
        <slot></slot>
    </div>
</template>
<script>
export default {
    name:'Owl',
    props:{
        item:Array,
        id:String,
        loop:{
            type:Boolean,
            default:true
        }
    },
    created(){
        setTimeout(()=>{
            let owl = $(`#${this.id}`);
            this.carousel(owl,this.item);

        },500)
    },
    methods:{
        carousel(owl,item){
            owl.owlCarousel({
                loop:this.loop,
                margin:20,
                responsiveClass:true,
                autoplay:true, 
                autoplayTimeout:2000,
                autoplayHoverPause:true,
                responsive:{
                    0:{
                        items:item[0],
                        nav:false,
                        loop:this.loop
                    },
                    600:{
                        items:item[1],
                        nav:false,
                        loop:this.loop
                    },
                    1000:{
                        items:item[2],
                        nav:false,
                        loop:this.loop
                    }
                }
            });

        }
    }
}
</script>
