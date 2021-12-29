<template>
<StepArticle
    id='step0'
    title='Simulateur de carte jeune 18/28 ans'
    content=''
    nextStepMsg='Démarrer la simulation'
    nextStepLink='step1'
 />
</template>

<script>
import StepArticle from '@/components/StepArticle.vue'
export default {
  name: 'Step0',
  components: {
    StepArticle,
  }
}
</script>