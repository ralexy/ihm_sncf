<template>
    <StepArticle
        id='step3'
        title='Etape 3. Saisie Horaire de voyage'
        nextStepMsg='Afficher le résultat'
        nextStepLink='result'
        breadCrumbStep=3>
    <form method="post" @submit="checkForm">
        <p class="center">Je voyage en général le                     
            <select name="day" id="day" v-model="day" required>
                <template v-for="(item, key) in days">
                    <option :value="key" v-if="key != day" :key="'1option' + item.key">{{ item }}</option>
                    <option :value="key" v-else :key="'2option' + item.key" selected="selected">{{ item }}</option>
                </template>
            </select>

            vers

            <select name="day" id="hour" v-model="hour" required>
                <template v-for="(item, key) in hours">
                    <option :value="key" v-if="key != hour" :key="'1option' + item.key">{{ item }}</option>
                    <option :value="key" v-if="key == hour" selected="selected" :key="'2option' + item.key">{{ item }}</option>
                </template>
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
  },
  data() { 
    return {
        day: null,
        hour: null,
        days: {
            0 : 'Je ne sais pas',
            1: 'Lundi',
            2: 'Mardi',
            3: 'Mercredi',
            4: 'Jeudi' ,
            5: 'Vendredi',
            6: 'Samedi',
            7 : 'Dimanche',
        },
        hours: {
            0 : '0h00',
            100: '1h00',
            200: '2h00',
            300: '3h00',
            400: '4h00',
            500: '5h00',
            600: '6h00',
            700: '7h00',
            800: '8h00',
            900: '9h00',
            1000: '10h00',
            1100: '11h00',
            1200: '12h00',
            1300: '13h00',
            1400: '14h00',
            1500: '15h00',
            1600: '16h00',
            1700: '17h00',
            1800: '18h00',
            1900: '19h00',
            2000: '20h00',
            2100: '21h00',
            2200: '22h00',
            2300: '23h00',
        },
    }
  },
  methods: {
        checkForm: function(e) {
            e.preventDefault();

            if(this.day !== null && this.hour !== null) {
                localStorage.day = this.day;
                localStorage.hour = this.hour;
              
                // On peut faire notre requête pour récupérer nos informations finales...
                this.axios
                    .get('http://127.0.0.1/ihm_php/?action=getPrices&origin=' + localStorage.depart + "&destination=" + localStorage.arrivee + "&day=" + this.day + "&hour=" + this.hour + "&frequence=" + localStorage.frequence)
                    .then(response => {
                        localStorage.apiResponse = response.data;
                        this.$router.push('result');
                    });
            }
        }
    },
    created() {
        if(localStorage.day) this.day = localStorage.day;
        if(localStorage.hour) this.hour = localStorage.hour;

        console.log(this.hour);
        console.log(this.day);
    }
}
</script>