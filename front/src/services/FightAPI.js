import axios from "axios";

const urlApi = "https://localhost/api/"
function launch(id) {
    return axios.get(urlApi + "characters/" + id + "/opponent")
        .then((result) => {
            const opponentId = result.data["hydra:member"][0]
            console.log(result)
            return startFight(id, opponentId.id)
        })
}

function startFight(id1, id2) {
    return axios.post(urlApi + "fights/launch",
        {idOpponent1: id1, idOpponent2: id2})
        .then(result => result.data["hydra:member"])
}

function fightHistory(id1, id2) {

}

export default {
    launch
}