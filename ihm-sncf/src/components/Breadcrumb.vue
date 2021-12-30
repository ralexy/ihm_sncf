<template>
    <!-- On switche l'état de notre breadcrumb selon l'étape, on ne peut pas faire avancer le workflow sans compléter le formulaire (ça n'aurait pas de sens sinon) -->
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ul>
            <li v-if="step == 1"><span class="step active">1</span> <span aria-current="page" class="stepTextActive hide">Saisie du trajet</span></li>
            <li v-else><router-link to="step1"><span class="step">1</span> <span class="hide">Saisie du trajet</span></router-link></li>

            <li v-if="step == 2"><span class="step active">2</span> <span aria-current="page" class="stepTextActive hide">Saisie fréquence de voyage</span></li>
            <li v-if="step < 2"><span class="step">2</span> <span class="hide">Saisie fréquence de voyage</span></li>
            <li v-if="step > 2"><router-link to="step2"><span class="step">2</span> <span class="hide">Saisie fréquence de voyage</span></router-link></li>


            <li v-if="step == 3"><span class="step active">3</span> <span aria-current="page" class="stepTextActive hide">Saisie horaire de voyage</span></li>
            <li v-if="step < 3"><span class="step">3</span> <span class="hide">Saisie horaire de voyage</span></li>
            <li v-if="step > 3"><router-link to="step3"><span class="step">3</span> <span class="hide">Saisie horaire de voyage</span></router-link></li>
            
            <!-- Cas particulier, on a accès au résultat qu'en validant l'étape 3 -->
            <li v-if="step == 4"><span class="step active">4</span> <span aria-current="page" class="stepTextActive hide">Résultat</span></li>
            <li v-else><span class="step">4</span> <span class="hide">Résultat</span></li>
        </ul>
    </nav>
</template>

<script>
export default {
  name: 'BreadCrumb',
  props: {
    step: String
  }
}
</script>