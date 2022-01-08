<template>
  <StepArticle
    id='step2'
    title='Etape 2. Saisie fréquence de voyage'
    breadCrumbStep=2>
      <form method="post" @submit="checkForm" autocomplete="off">
        <p class="center">Je compte voyager environ <input type="number" name="frequence" id="frequence" v-model="frequence" step="1" min="1" max="364"> fois par an</p>
        <ButtonStep msg='Passer à l&#39;étape suivante' link='step3'/>
      </form>
    </StepArticle>
</template>

<script>
import StepArticle from '@/components/StepArticle.vue'
import ButtonStep from "@/components/ButtonStep.vue";
export default {
  name: 'Step1',
  components: {
    StepArticle, ButtonStep
  },
  data() {
    return {
      frequence: 1,
    }
  },
  watch: {
    frequence: function(val) {
      // Si les valeurs saisies sont fantaisistes on les reset
      if(val < 0 || val > 364) {
        this.frequence = 1;
      }
    }
  },
  methods: {
    checkForm: function(e) {
      e.preventDefault();

      localStorage.frequence = this.frequence;
      this.$router.push('step3'); // On fait avancer notre Workflow
    }
  },
  mounted() {
    if(localStorage.frequence) this.frequence = localStorage.frequence;
  },
  created() {
        // On fait reculer le WF si l'utilisateur va à cette étape sans avoir effectué la précédente
    if(!localStorage.garesDepart || !localStorage.garesArrivee) {
      this.$router.push('step1'); // On fait avancer notre Workflow
    }
  }
}
</script>