<template>
     <div>
        <v-menu
            open-on-hover
            v-for="(direction, index) in vdLOrganigrammeLDAP"
            :key="index"
        >
            <template v-slot:activator="{ props }">
                <v-btn
                    color="primary"
                    size="small"
                    v-bind="props"
                    v-on:click="getlisteEmployeParDirection(direction.libelle)"
                >
                    {{ direction.libelle }}
                </v-btn>
            </template>
            <v-list>
                <v-list-item
                    v-for="(service, index) in direction.services"
                    :key="index"
                >
                    <v-list-item-title>
                        <v-menu
                            open-on-hover
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    color="primary"
                                    size="small"
                                    v-bind="props"
                                    v-on:click="getlisteEmployeParService(service.libelle)"
                                >
                                {{ service.libelle }}
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item
                                    v-for="(sousservice, index) in service.sousservices"
                                    :key="index"
                                >
                                    <v-list-item-title v-on:click="getlisteEmployeParSousService(service.libelle + '@' + sousservice.libelle)">{{ sousservice.libelle }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>           
                    </v-list-item-title>     
               </v-list-item>
            </v-list>
       </v-menu>
    </div>
</template>

<script setup>
    import { data } from '@/stores/data.js'
    import { getVdLOrganigrammeLDAPDirections } from '@/axioscalls.js'
    import { listeEmployesParDirection } from '@/axioscalls.js'
    import { listeEmployesParService } from '@/axioscalls.js'
    import { listeEmployesParSousService } from '@/axioscalls.js'
    const vdLOrganigrammeLDAP = await getVdLOrganigrammeLDAPDirections()
    let lesData = data()

    async function getlisteEmployeParDirection(libelle) {
        lesData.listeEmployes = await listeEmployesParDirection(libelle)
        //alert(JSON.stringify(lesData.listeEmployes))
    }
    async function getlisteEmployeParService(libelle) {
        lesData.listeEmployes = await listeEmployesParService(libelle)
        //alert(JSON.stringify(lesData.listeEmployes))
    }
    async function getlisteEmployeParSousService(libelle) {
        lesData.listeEmployes = await listeEmployesParSousService(libelle)
        //alert(JSON.stringify(lesData.listeEmployes))
    }
</script>