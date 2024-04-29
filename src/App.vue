<template>
  <v-app>
    <v-main>
      <CriteresNomCompte></CriteresNomCompte>
      <Suspense><Organigramme/></Suspense>
      <ListeEmployes/>
    </v-main>
    <AppFooter />
  </v-app>
</template>

<script setup>
  import CriteresNomCompte from '@/components/CriteresNomCompte.vue'
  import Organigramme from '@/components/Organigramme.vue'
  import ListeEmployes from '@/components/ListeEmployes.vue'
  import { data } from '@/stores/data.js'
  let lesData = data()
</script>