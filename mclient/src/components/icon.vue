<template>  
  <div :class="className" v-bind:style="{ fill: logocolor}" v-html="svg"></div>
</template>

<script>  
export default {  
  name: 'icon',
  props: ['logocolor', 'className', 'svg']
};
</script>