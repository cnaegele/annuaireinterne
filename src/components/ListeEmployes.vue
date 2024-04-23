<template>
    <div>
        <v-data-table
            :items="lesData.listeEmployes"
            :items-per-page="25"
            :hide-no-data="true"
            density="compact"
            @click:row="getDataEmploye"
        >
        </v-data-table>
        
    </div>

  <!-- Essai en cours -->
  <v-dialog max-width="1000">
  <template v-slot:activator="{ props: activatorProps }">
    <div style="display: none;">
    <v-btn
      id="btnActiveCard"
      v-bind="activatorProps"
    ></v-btn>
  </div>
  </template>

  <template v-slot:default="isActive">
    <v-card>
      <v-card-actions>
        <span class="cardTitre">Informations détaillées</span>
        <v-spacer></v-spacer>

        <v-btn
          text="Fermer"
          variant="tonal"
          @click="closeCard"
        ></v-btn>
      </v-card-actions>

      <v-card-text>
        <div>{{ lesData.titreEmploye }}</div>
        <div>{{ lesData.nomEmploye }}</div>
        <div>{{ lesData.descriptionEmploye }}</div>
        <div>{{ lesData.telephoneEmploye }}</div>
        <div>{{ lesData.courielEmploye }}</div>
        <div>{{ lesData.compteEmploye }}</div>
        <div>{{ lesData.companieEmploye }}</div>
        <div>{{ lesData.directionEmploye }}</div>
        <div>{{ lesData.serviceEmploye }}</div>
        <div>{{ lesData.uniteEmploye }}</div>
        <div>{{ lesData.fonctionEmploye }}</div>
        <div>{{ lesData.dateCreationEmploye }}</div>
        <div>{{ lesData.dateDernierLoginEmploye }}</div>
      </v-card-text>
      <v-spacer></v-spacer>
      <v-card-text>
        <div>Groupes: <span class="listeGroupes">{{ lesData.groupesEmploye }}</span></div>
      </v-card-text>

    </v-card>
  </template>
</v-dialog>
<!-- Fin essai en cours -->

</template>

<script setup>
   import { data } from '@/stores/data.js'
   import { dataEmploye } from '@/axioscalls.js'
   let lesData = data()

    async function getDataEmploye(item, row) {
      lesData.dataEmploye = await dataEmploye(row.item.Compte)
      console.log(lesData.dataEmploye)
      if (lesData.dataEmploye[0].hasOwnProperty('title')) {
        lesData.titreEmploye = `Titre: ${lesData.dataEmploye[0].title[0]}`
      } else {
        lesData.titreEmploye = ''  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('displayname')) {
        lesData.nomEmploye = `Nom: ${lesData.dataEmploye[0].displayname[0]}`
      } else {
        lesData.nomEmploye = 'Nom: -'  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('description')) {
        lesData.descriptionEmploye = `Description: ${lesData.dataEmploye[0].description[0]}`
      } else {
        lesData.descriptionEmploye = 'Description: -'  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('telephonenumber')) {
        lesData.telephoneEmploye = `Téléphone: ${lesData.dataEmploye[0].telephonenumber[0]}`
      } else {
        lesData.telephoneEmploye = ''  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('mail')) {
        lesData.courielEmploye = `Couriel: ${lesData.dataEmploye[0].mail[0]}`
      } else {
        lesData.courielEmploye = ''  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('samaccountname')) {
        lesData.compteEmploye = `Compte: ${lesData.dataEmploye[0].samaccountname[0]}`
      } else {
        lesData.compteEmploye = ''  
      }
      lesData.companieEmploye = ''
      if (lesData.dataEmploye[0].hasOwnProperty('company')) {
        if (lesData.dataEmploye[0].company[0] != 'Ville de Lausanne')
          lesData.companieEmploye = `Entreprise: ${lesData.dataEmploye[0].company[0]}`
      }
      if (lesData.dataEmploye[0].hasOwnProperty('extensionattribute1')) {
        lesData.directionEmploye = `Direction: ${lesData.dataEmploye[0].extensionattribute1[0]}`
      } else {
        lesData.directionEmploye = 'Direction: -'  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('department')) {
        lesData.serviceEmploye = `Service: ${lesData.dataEmploye[0].department[0]}`
      } else {
        lesData.serviceEmploye = 'Service: -'
      } 
      if (lesData.dataEmploye[0].hasOwnProperty('extensionattribute3')) {
        lesData.uniteEmploye = `Unité: ${lesData.dataEmploye[0].extensionattribute3[0]}`
      } else {
        lesData.uniteEmploye = ''  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('extensionattribute10')) {
        lesData.fonctionEmploye = `Fonction: ${lesData.dataEmploye[0].extensionattribute10[0]}`
      } else {
        lesData.fonctionEmploye = ''  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('extensionattribute10')) {
        lesData.fonctionEmploye = `Fonction: ${lesData.dataEmploye[0].extensionattribute10[0]}`
      } else {
        lesData.fonctionEmploye = ''  
      }
      if (lesData.dataEmploye[0].hasOwnProperty('whencreated')) {
        lesData.dateCreationEmploye = `Date création: ${lesData.dataEmploye[0].whencreated[0]}`
      } else {
        lesData.dateCreationEmploye = 'Date création: - '  
      }
      lesData.dateDernierLoginEmploye = ''
      if (lesData.dataEmploye[0].hasOwnProperty('lastlogon')) {
        if (lesData.dataEmploye[0].hasOwnProperty('lastlogon') != '0') {
          lesData.dateDernierLoginEmploye = `Date dernier login: ${lesData.dataEmploye[0].lastlogon[0]}`
        }
      }
      if (lesData.dataEmploye[0].hasOwnProperty('memberof')) {
        lesData.groupesEmploye = lesData.dataEmploye[0].memberof
      } else {
        lesData.groupesEmploye = '' 
      }
      console.log(lesData.groupesEmploye)
      document.getElementById("btnActiveCard").click()
      //alert(JSON.stringify(lesData.dataEmploye))
    }

    function closeCard() {
      document.getElementById("btnActiveCard").click()    
    }
</script>