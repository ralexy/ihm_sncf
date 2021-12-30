<template>
<p class="center">
    <!--<router-link :to="link">-->
        <button>{{ msg }}</button>
    <!--</router-link>-->
</p>
</template>

<script>
export default {
  name: 'ButtonStep',
  props: {
    msg: String,
    link: String
  }
}
</script>