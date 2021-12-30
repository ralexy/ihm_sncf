<template>
    <article :id="id">
            <h1>{{ title }}</h1>
            <Breadcrumb :step="breadCrumbStep" v-if="breadCrumbStep" />
            <slot>
            </slot>
    </article>
</template>

<script>
import Breadcrumb from "./Breadcrumb.vue";
export default {
    props: {
        id: String,
        title: String,
        breadCrumbStep: String,
    },
    components: { Breadcrumb }
}
</script>