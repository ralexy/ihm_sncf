<template>
    <article :id="id">
        <h1>{{ title }}</h1>
        <Breadcrumb v-if="breadCrumbEnabled !== false" />
        <span v-html="content"></span>
        <ButtonStep :msg='nextStepMsg' :link='nextStepLink' />
    </article>
</template>

<script>
import ButtonStep from "./ButtonStep.vue";
import Breadcrumb from "./Breadcrumb.vue";
export default {
    props: {
        id: String,
        title: String,
        content: String,
        nextStepMsg: String,
        nextStepLink: String,
        breadCrumbEnabled: Boolean,
    },
    components: { ButtonStep, Breadcrumb }
}
</script>