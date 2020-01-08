<template>
    <div class="outer">
        <div class="loader-div">
            <ring-loader class="custom-class" color="#2dce89" ></ring-loader>
        </div>
    </div>
    
</template>

<style>
    .outer{
        height: 100%;
        width: 100%;
        position: fixed;
        background-color: rgba(255,255,255,0.7);
        z-index: 10000;
        text-align: center;
        right:0px;
    }
    .sk-spinner[data-v-39432f99]{
        margin-top: 200px;
    }
    .loader-div{
        position: absolute;
        height: 15%;
        width: 5%;
        margin: 0 auto;
        top: 50%;
        left: 50%;
    }
</style>

<script>
import { RingLoader } from '@saeris/vue-spinners'
export default {
    components:{
       RingLoader
    }
    
}
</script>
