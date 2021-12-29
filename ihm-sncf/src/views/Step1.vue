<template>
<StepArticle
    id='step1'
    title='Etape 1. Saisie du trajet'
    content='
                <form method="post">
                <p class="center">
                    <input list="depart" placeholder="Gare de départ" name="depart" />
                    <datalist id="depart">
                        <option value="Amiens">
                        <option value="Paris">
                        <option value="Caen">
                        <option value="Dieppe">
                        <option value="Lille">
                        <option value="Marseille">
                    </datalist>
    
                    <select name="arrivee" id="arrivee">
                        <option value="">Choisir une gare d&#39;arrivée</option>
                        <option value="RUE">RUE</option>
                        <option value="CAEN">CAEN</option>
                        <option value="LESQUIN">LESQUIN</option>
                        <option value="NICE">NICE</option>
                    </select>
                </p>

                <p class="center min"><sup>* La carte jeune m&#39;est inutile si je ne trouve pas mon trajet habituel.</sup></p>
            </form>
    '
    nextStepMsg='Passer à l&#39;étape suivante'
    nextStepLink='step2'
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