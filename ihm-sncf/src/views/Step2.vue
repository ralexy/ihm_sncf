<template>
<StepArticle
    id='step2'
    title='Etape 2. Saisie fréquence de voyage'
    content='
        <form method="post">
                <p class="center">Je compte voyager environ <input type="number" name="frequence" id="frequence" value="1" step="1" min="1" max="364" oninput="if (this.value > 364 || this.value < 0) this.value = "";"> fois par an</p>
        </form>
    '
    nextStepMsg='Passer à l&#39;étape suivante'
    nextStepLink='step3'
    breadCrumbEnabled
 />
</template>

<script>
import StepArticle from '@/components/StepArticle.vue'
export default {
  name: 'Step1',
  components: {
    StepArticle,
  }
}
</script>