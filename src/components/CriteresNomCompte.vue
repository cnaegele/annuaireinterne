<template>
    Nom | Compte(VDL) :&nbsp;<input class="criteres" type="text" v-model="criteresNomCompte" @keyup="rechercheEmploye "/>
</template>

<script setup>
    import { data } from '@/stores/data.js'
    import { listeEmployesParNom } from '@/axioscalls.js'
    import { listeEmployesParCompte } from '@/axioscalls.js'
    import { ref } from "vue"
    let criteresNomCompte = ref("")
    let lesData = data()

    function rechercheEmploye() {
        const critere = criteresNomCompte.value
        let nbrCharMin = 3
        if (critere.length >= 1) {
            if (critere.substring(0,1) == "*") {
                nbrCharMin = 4    
            }
        }
        if (critere.length >= nbrCharMin) {
            if (critere.substring(0, 3).toUpperCase() != "VDL" ) {
                getlisteEmployeParNom(critere)    
            } else if (critere.length == 8) {
                getlisteEmployeParCompte(critere)
            }
        }
    }

    async function getlisteEmployeParCompte(libelle) {
        lesData.listeEmployes = await listeEmployesParCompte(libelle)
        //alert(JSON.stringify(lesData.listeEmployes))
    }

    async function getlisteEmployeParNom(libelle) {
        lesData.listeEmployes = await listeEmployesParNom(libelle)
        //alert(JSON.stringify(lesData.listeEmployes))
    }
</script>