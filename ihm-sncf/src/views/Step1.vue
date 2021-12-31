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
      e.preventDefault();

      // Si on a une gare de départ et d'arrivée notre formulaire est OK et notre retour API aussi.
      // On sauvegarde notre retour API dans le local storage au cas où l'utilisateur retourne en arrière dans le WF
      if(this.depart && this.arrivee) {
        localStorage.garesDepart = JSON.stringify(this.garesDepart); // Sérialisation
        localStorage.garesArrivee = JSON.stringify(this.garesArrivee);
        localStorage.depart = this.depart;
        localStorage.arrivee = this.arrivee;
        this.$router.push('step2');
      }
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

      // Si notre gare de départ n'est pas nulle on parcourt la liste des gares de départ
      // Et on regarde si l'utilisateur a cliqué sur une suggestion (si c'est le cas on aura un match)
      if(this.garesDepart !== null) {
        for(let i = 0; i < this.garesDepart.length; i++) {
          let gareCourante = JSON.parse(JSON.stringify(this.garesDepart[i]));

          // Si on a un match on récupère toutes les gares d'arrivée desservies par cette gare.
          if(gareCourante['nameGare'] == val) {
            this.axios
              .get('http://127.0.0.1/ihm_php/?action=suggestDestinationStation&nameGare=' + this.depart)
              .then(response => (this.garesArrivee = response.data));            
          }
        }
      }
    }
  },
  mounted() {
    if(localStorage.depart) this.depart = localStorage.depart;
    if(localStorage.arrivee) this.arrivee = localStorage.arrivee;
    if(localStorage.garesDepart) this.garesDepart = JSON.parse(localStorage.garesDepart); // Désérialisation
    if(localStorage.garesArrivee) this.garesArrivee = JSON.parse(localStorage.garesArrivee);
  }
}
</script>