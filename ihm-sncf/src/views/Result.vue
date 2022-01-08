<template>
<StepArticle
    id='result'
    title='Etape 4. Résultat'
    breadCrumbStep=4>
            <img src="@/assets/rotatephone.png" id="rotatePhone">
            <span id="resultData">
                <template v-if="apiResponse !== null">
                    <h2 class="green center" v-if="apiResponse.interesting">J&#39;ai intérêt à prendre la carte jeune</h2>
                    <h2 class="red center" v-if="!apiResponse.isInteresting">Je n&#39;ai pas intérêt à prendre la carte jeune</h2>
                    <h3 class="center" v-if="apiResponse.isInteresting">Je peux économiser {{ apiResponse.savedMoney }}€</h3>
                    <h4 class="center" v-if="apiResponse.isInteresting">Elle se rembourse en {{ apiResponse.isInterestingNb }} trajets</h4>
                    <h4 class="center" v-if="!apiResponse.isInteresting">Elle est intéressante au bout de {{ apiResponse.isInterestingNb }} trajets effectués</h4>
                    
                    <vue3-chart-js
                        :id="chart1.id"
                        :ref="null"
                        :type="chart1.type"
                        :data="chart1.data"
                        :options="chart1.options"
                    ></vue3-chart-js>

                    <vue3-chart-js
                        :id="chart2.id"
                        :ref="null"
                        :type="chart2.type"
                        :data="chart2.data"
                        :options="chart2.options"
                    ></vue3-chart-js>
                </template>
            </span>

            <ButtonStep msg='Recommencer' link='/' @click="checkForm" />
    </StepArticle>
</template>

<script>
import StepArticle from '@/components/StepArticle.vue';
import ButtonStep from '@/components/ButtonStep.vue';
import Vue3ChartJs from '@j-t-mcc/vue3-chartjs';

export default {
    name: 'Resultat',
    components: {
        StepArticle, ButtonStep, Vue3ChartJs
    },
    data() {
        return {
            apiResponse: null,
            chart1: {
                id: 'chart1',
                type: 'line',
                data: {
                    labels: null,
                    datasets: [
                        {
                            label: 'Tarif normal',
                            data: null,
                            borderColor: ['rgba(255,0,0,1)'],
                            backgroundColor: ['rgba(255,0,0,0.5)'],
                            yAxisID: 'y',
                            unitSuffix: "€",
                        },
                        {
                            label: 'Tarif carte jeune',
                            data: null,
                            borderColor: ['rgba(60,179,113,1)'],
                            backgroundColor: ['rgba(60,179,113,0.5)'],
                            yAxisID: 'y',
                            unitSuffix: "€",
                        },
                    ]
                },
                options: 
                {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    animations: {
                        radius: {
                            duration: 400,
                            easing: 'linear',
                            loop: (context) => context.active
                        }
                    },
                    hoverRadius: 12,
                    hoverBackgroundColor: 'yellow',
                    stacked: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Comparaison du tarif normal et jeune SNCF en fonction du nombre de voyages'
                        },
                        tooltips: {
                            callbacks: {
                                afterTitle: function(context) {
                                    return context.parsed.y + ' trajets';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Prix en euros'
                                },
                            },

                        x: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Nombre de trajets'
                            },
                            ticks: {
                                min: 1,
                                stepSize: 1
                            },
                        },
                    },
                },
            },
            chart2: {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                        {
                            label: 'Tarif normal',
                            data: null,
                            borderColor: ['rgba(255,0,0,1)'],
                            backgroundColor: ['rgba(255,0,0,0.5)'],
                            borderWidth: 2,
                            borderRadius: 5,
                            borderSkipped: false,
                        },
                        {
                            label: 'Tarif carte jeune',
                            data: null,
                            borderColor: ['rgba(60,179,113,1)'],
                            backgroundColor: ['rgba(60,179,113,0.5)'],
                            borderWidth: 2,
                            borderRadius: 5,
                            borderSkipped: false,
                        }
                    ]
                },               
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Tarifs des deux offres pour un trajet type'
                        },
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Prix en euros'
                            },
                        },
                    },
                },
            }
        }
    },
    watch: {
        apiResponse: function() {
            let trajets = [];
            let priceNormal = [];
            let priceDiscount = [];

            for(let i = 0; i < this.apiResponse['simulation'].length; i++) {
                trajets.push(i);
                priceNormal.push(this.apiResponse['simulation'][i]['normal']);
                priceDiscount.push(this.apiResponse['simulation'][i]['discount']);
            }

            this.chart1.data.labels = trajets;
            this.chart1.data.datasets[0].data = priceNormal;
            this.chart1.data.datasets[1].data = priceDiscount;

            this.chart2.data.datasets[0].data = [this.apiResponse.normalPrice];
            this.chart2.data.datasets[1].data = [this.apiResponse.pricePromo];
        },
    },
    methods: {
        checkForm: function(e) {
            e.preventDefault();

            // Reset du localStorage et redirection à l'accueil
            localStorage.clear();
            this.$router.push('/');
        }
    },
    created() {
        // On fait reculer le WF si l'utilisateur va à cette étape sans avoir effectué la précédente
        if(!localStorage.garesDepart || !localStorage.garesArrivee) {
        this.$router.push('step3'); // On fait avancer notre Workflow
        }

        if(localStorage.apiResponse) this.apiResponse = JSON.parse(localStorage.apiResponse); // Désérialisation
    },
}
</script>