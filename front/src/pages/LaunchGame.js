import React, {useEffect, useState} from "react";
import {FightHistory, Select} from "../components";
import CharactersAPI from "../services/CharactersAPI";
import FightAPI from "../services/FightAPI";

function LaunchGame() {
    const [characters, setCharacters] = useState([])
    const [character, setCharacter] = useState({
        id: "",
        name: ""
    })
    const [winner, setWinner] = useState()
    const [histories, setHistories] = useState([])

    const handleChange = (e) => {
        const {options, selectedIndex, value} = e.target
        setCharacter({
            "id": value,
            "name": options[selectedIndex].innerHTML
        })
        console.log(character)
    }

    const handleSubmit = async event => {
        event.preventDefault()
        try {
            const histories = await FightAPI.launch(character.id)
            setHistories(histories[0].histories)
            setWinner(histories[0].winner.name)
            console.log(character)
        } catch (error) {
            console.log(error.result)
        }
    }

    const fetchCharacters = async () => {
        try {
            const data = await CharactersAPI.findAll()
            setCharacters(data)
            console.log(data)
        } catch (error) {
            console.log(error)
        }
    }

    useEffect(() => {
        fetchCharacters()
    }, [])



    return (<>
        <form onSubmit={handleSubmit}>
            <Select
                name="character"
                label="Choose a character"
                value={characters}
                onChange={handleChange}
            />
            <button className="btn btn-success">Play</button>
        </form>
        <FightHistory
            histories={histories}
            character={character}
            winner={winner}
        />
    </>);
}

export default LaunchGame