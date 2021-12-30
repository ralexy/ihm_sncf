<template>
    <StepArticle
        id='step3'
        title='Etape 3. Saisie Horaire de voyage'
        nextStepMsg='Afficher le résultat'
        nextStepLink='result'
        breadCrumbStep=3>
    <form method="post">
        <p class="center">Je voyage en général le                     
            <select name="day" id="day">
                <option value="0">Je ne sais pas</option>
                <option value="1" selected>Lundi</option>
                <option value="2">Mardi</option>
                <option value="3">Mercredi</option>
                <option value="4">Jeudi</option>
                <option value="5">Vendredi</option>
                <option value="6">Samedi</option>
                <option value="7">Dimanche</option>
            </select>

        vers                     

            <select name="arrivee" id="arrivee">
                <option value="">Je ne sais pas</option>                    
                <option value="0" selected>0h00</option>
                <option value="100">1h00</option>
                <option value="200">2h00</option>
                <option value="300">3h00</option>
                <option value="400">4h00</option>
                <option value="500">5h00</option>
                <option value="600">6h00</option>
                <option value="700">7h00</option>
                <option value="800">8h00</option>
                <option value="900">9h00</option>
                <option value="1000">10h00</option>
                <option value="1100">11h00</option>
                <option value="1200">12h00</option>
                <option value="1300">13h00</option>
                <option value="1400">14h00</option>
                <option value="1500">15h00</option>
                <option value="1600">16h00</option>
                <option value="1700">17h00</option>
                <option value="1800">18h00</option>
                <option value="1900">19h00</option>
                <option value="2000">20h00</option>
                <option value="2100">21h00</option>
                <option value="2200">22h00</option>
                <option value="2300">23h00</option>
            </select>
        </p>
        <ButtonStep msg='Afficher le résultat' link='result'/>
        </form>    
    </StepArticle>
</template>

<script>
import StepArticle from '@/components/StepArticle.vue'
import ButtonStep from "@/components/ButtonStep.vue";
export default {
  name: 'Step3',
  components: {
    StepArticle, ButtonStep
  }
}
</script>