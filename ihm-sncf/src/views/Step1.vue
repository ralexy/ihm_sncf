<template>
  <StepArticle
      id='step1'
      title='Etape 1. Saisie du trajet'
      breadCrumbStep=1>
    <form @submit="checkForm" autocomplete="off">
      <p class="center">
          <input list="depart" v-model="depart" placeholder="Gare de départ" name="depart" required />
          <datalist id="depart">
              <option v-bind:value="gareDepart.nameGare" v-for="gareDepart in garesDepart" :key="gareDepart.id">{{ gareDepart.nameGare }}</option>
          </datalist>
          &nbsp;
          <select name="arrivee" v-model="arrivee" id="arrivee" required>
              <option selected disabled>Choisir une gare d'arrivée</option>
              <option v-bind:value="gareArrivee.nameGareDestination" v-for="gareArrivee in garesArrivee" :key="gareArrivee.id">{{ gareArrivee.nameGareDestination }}</option>
          </select>
      </p>
      <p class="center min"><sup>* La carte jeune m'est inutile si je ne trouve pas mon trajet habituel.</sup></p>
      <ButtonStep msg='Passer à l&#39;étape suivante' />
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
      depart: null,
      arrivee: null,
      garesDepart: null,
      garesArrivee: null,
    }
  },
  methods: {
    checkForm: function(e) {

      if(this.depart && this.arrivee) {
        console.log(this.depart + '\n' + this.arrivee);
        //this.$router.push('step2');

        // Stockage local vars et go step 2 :-)
      }

      console.log(e);
      e.preventDefault();
    }
  },
  watch: {
    depart: function(val) {
      if(val.length >= 3) {
        this.axios
          .get('http://127.0.0.1/ihm_php/?action=suggestOriginStation&nameGare=' + this.depart)
          .then(response => (this.garesDepart = response.data));
      } else {
        this.garesDepart = null;
        this.garesArrivee = null;
      }

      if(this.garesDepart !== null) {
        for(let i = 0; i < this.garesDepart.length; i++) {
          let gareCourante = JSON.parse(JSON.stringify(this.garesDepart[i]));

          /**
           * La valeur sélectionnée correspond à une gare
           * On peut avancer
           */
          if(gareCourante['nameGare'] == val) {
            this.axios
              .get('http://127.0.0.1/ihm_php/?action=suggestDestinationStation&nameGare=' + this.depart)
              .then(response => (this.garesArrivee = response.data));            
          }
        }
      }
    }
  },
}
</script>