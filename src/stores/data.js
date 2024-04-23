import { defineStore } from 'pinia';
import { ref } from 'vue'
export const data = defineStore({
    id: 'iddata',
    state: () => ({
        listeEmployes: ref([]),
        dataEmploye: ref({}),
        titreEmploye: ref(''),
        nomEmploye: ref(''),
        descriptionEmploye: ref(''),
        telephoneEmploye: ref(''),
        courielEmploye: ref(''),
        compteEmploye: ref(''),
        companieEmploye: ref(''),
        directionEmploye: ref(''),
        serviceEmploye: ref(''),
        uniteEmploye: ref(''),
        fonctionEmploye: ref(''),
        compteEmploye: ref(''),
        telephoneEmploye: ref(''),
        courielEmploye: ref(''),
        dateCreationEmploye: ref(''),
        dateDernierLoginEmploye: ref(''),
        groupesEmploye: ref('')
    })
})
