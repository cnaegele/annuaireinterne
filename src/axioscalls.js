import axios from 'axios'
let g_devurl = ''
if (import.meta.env.DEV) {
    g_devurl = 'https://mygolux.lausanne.ch'    
}
const g_pathurl = '/public/annuaireinterne/server/'
console.log("g_devurl: " + g_devurl)

export async function getVdLOrganigrammeLDAPDirections() {
    const urlor = `${g_devurl}${g_pathurl}directions_services_h.php`
    const response = await axios.get(urlor)
        .catch(function (error) {
            return traiteAxiosError(error)
        })
    const vdLOrganigrammeLDAP = response.data
    console.log(vdLOrganigrammeLDAP)
    return vdLOrganigrammeLDAP
}

export async function listeEmployesParDirection(libelle) {
    const urlle = `${g_devurl}${g_pathurl}users_liste_direction.php`
    const params = new URLSearchParams([['direction', libelle]])
    const response = await axios.get(urlle, {params})
        .catch(function (error) {
            traiteAxiosError(error)
        })      
    const listeEmployes = transformResultats(response.data)
    //console.log(listeEmployes)
    return listeEmployes
}

export async function listeEmployesParService(libelle) {
    const urlle = `${g_devurl}${g_pathurl}users_liste_service.php`
    const params = new URLSearchParams([['service', libelle]])
    const response = await axios.get(urlle, {params})
        .catch(function (error) {
            traiteAxiosError(error)
        })      
    const listeEmployes = transformResultats(response.data)
    //console.log(listeEmployes)
    return listeEmployes
}

export async function listeEmployesParSousService(libelle) {
    const urlle = `${g_devurl}${g_pathurl}users_liste_sousservice.php`
    const params = new URLSearchParams([['sousservice', libelle]])
    const response = await axios.get(urlle, {params})
        .catch(function (error) {
            traiteAxiosError(error)
        })      
    const listeEmployes = transformResultats(response.data)
    //console.log(listeEmployes)
    return listeEmployes
}

export async function listeEmployesParNom(libelle) {
    const urlle = `${g_devurl}${g_pathurl}users_liste_displayname.php`
    const params = new URLSearchParams([['displayname', libelle]])
    const response = await axios.get(urlle, {params})
        .catch(function (error) {
            traiteAxiosError(error)
        })      
    const listeEmployes = transformResultats(response.data)
    console.log(listeEmployes)
    return listeEmployes
}

export async function listeEmployesParCompte(libelle) {
    const urlle = `${g_devurl}${g_pathurl}users_liste_samaccountname.php`
    const params = new URLSearchParams([['samaccountname', libelle]])
    const response = await axios.get(urlle, {params})
        .catch(function (error) {
            traiteAxiosError(error)
        })      
    const listeEmployes = transformResultats(response.data)
    //console.log(listeEmployes)
    return listeEmployes
}

export async function dataEmploye(compte) {
    const urlle = `${g_devurl}${g_pathurl}user_data.php`
    const params = new URLSearchParams([['samaccountname', compte]])
    const response = await axios.get(urlle, {params})
        .catch(function (error) {
            traiteAxiosError(error)
        })      
    const dataEmploye = response.data
    console.log(dataEmploye)
    return dataEmploye
}

function transformResultats(resultats) {
    let itemsResultat = []
        resultats.forEach(resultat => {
        const item = {
            Nom: resultat.user.displayname,       
            Téléphone: resultat.user.telephonenumber,       
            Couriel: resultat.user.mail,       
            Compte: resultat.user.samaccountname,       
            Direction: resultat.user.extensionattribute1,       
            Service: resultat.user.department,       
            Unité: resultat.user.extensionattribute3,       
            Fonction: resultat.user.extensionattribute10       
        }  
        itemsResultat.push(item) 
    })
    return itemsResultat
}

function traiteAxiosError(error) {
    if (error.response) {
        return `${error.response.data}<br>${error.response.status}<br>${error.response.headers}`    
    } else if (error.request.responseText) {
        return error.request.responseText
    } else {
        return error.message
    }
}