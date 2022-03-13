import axios from "axios";

const apiUrl = "https://localhost/api/"

function findAll(canPlay = false) {
    return axios.get(apiUrl + "characters")
        .then(response => {
            if (canPlay) {
                return response.data["hydra:member"].filter(function(value, index, arr){
                    return new Date() > new Date(value.delay) || !!!value.delay;
                });
            }

            return response.data["hydra:member"]

        })
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

const characterFunctionsList = {
    findAll,
    delete: deleteCharacter,
    getCharacter,
    add : addCharacter,
    edit: editCharacter
}

export default characterFunctionsList