<template>
<StepArticle
    id='result'
    title='Etape 4. Résultat'
    breadCrumbStep=4>
        <h2 class="green center">J&#39;ai intérêt à prendre la carte jeune</h2>
            <h3 class="green center">Je peux économiser XX€ avec</h3>
            <h4 class="green center">Elle se rembourse en XX trajets</h4>

            {{ apiResponse }}

            <div style="width: 70%; float: left;">
            <canvas id="chart1"></canvas>
            </div>   
            
            <div style="width: 30%; float: right;">
            <canvas id="chart2"></canvas>
            </div>

            <div style="clear: both;"></div>
            <ButtonStep msg='Recommencer' link='step1'/>
    </StepArticle>
</template>

<script>
import StepArticle from '@/components/StepArticle.vue'
import ButtonStep from "@/components/ButtonStep.vue";
export default {
  name: 'Resultat',
  components: {
    StepArticle, ButtonStep
  },
  data() {
      return {
          apiResponse: null,
      }
  },
  mounted() {
      if(localStorage.apiResponse) this.apiResponse = localStorage.apiResponse;
  },
}
</script>
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data1 = {
    labels: [1, 2, 3, 4, 5],
    datasets: [
        {
            label: 'Tarif normal',
            data: [100, 96, 84, 76, 69],
            borderColor: ['rgba(255,0,0,1)'],
            backgroundColor: ['rgba(255,0,0,0.5)'],
            yAxisID: 'y',
            unitSuffix: "€",
        },
        {
            label: 'Tarif carte jeune',
            data: [100, 9, 8, 6, 6],
            borderColor: ['rgba(60,179,113,1)'],
            backgroundColor: ['rgba(60,179,113,0.5)'],
            yAxisID: 'y',
            unitSuffix: "€",
        },
    ]
    };

    const config1 = {
        type: 'line',
        data: data1,
        options: 
        {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
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
    };

    const data2 = {
        labels: ["Carte SNCF", "Trajets", "Différence de tarif"],
        datasets: [
            {
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f"],
            data: [49,127,12]
            }
        ]
    };

    const config2 = {
        type: 'doughnut',
        data: data2,
        options: {
            title: {
                display: true,
                text: 'Décomposition du tarif jeune',
                floating: true,
            }
        }
    };
    
    const chart1 = new Chart(document.getElementById('chart1'), config1);
    const chart2 = new Chart(document.getElementById('chart2'), config2);
  </script>-->