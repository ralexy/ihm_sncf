<template>
  <StepArticle
    id='step0'
    title='Simulateur de carte jeune 18/28 ans'>
      <router-link to="step1">
        <ButtonStep msg='Démarrer la simulation'/>
      </router-link>
    </StepArticle>
</template>

<script>
import StepArticle from '@/components/StepArticle.vue'
import ButtonStep from "@/components/ButtonStep.vue";

export default {
  name: 'Step0',
  components: {
    StepArticle, ButtonStep,
  }
}
</script>