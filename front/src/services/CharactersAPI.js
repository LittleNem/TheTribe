import axios from "axios";

const apiUrl = "https://localhost/api/"

function findAll() {
    return axios.get(apiUrl + "characters")
        .then(response => response.data["hydra:member"])
}

function deleteCharacter(id) {
    return axios.delete(apiUrl + "characters/" + id)
}


function getCharacter(id) {
    return axios.get(apiUrl + "characters/" + id)
        .then(response => response.data)
}

function addCharacter(character) {
    const newCharacter = axios.post(apiUrl + "characters", character)
        .then(response => response.data)
    return newCharacter
}

function editCharacter(id, character) {
    const newCharacter = axios.put(apiUrl + "characters/" + id, character)
        .then(response => response.data)
    return newCharacter
}

export default {
    findAll,
    delete: deleteCharacter,
    getCharacter,
    add : addCharacter,
    edit: editCharacter
}