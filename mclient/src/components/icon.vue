<template>  
  <div :class="className" v-bind:style="{ fill: logocolor, stroke: linecolor}" v-html="svg"></div>
</template>

<script>  
export default {  
  name: 'icon',
  props: ['logocolor', 'linecolor', 'className', 'svg']
};
</script>