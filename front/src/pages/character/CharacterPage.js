import React, {useEffect, useState} from "react";
import {Field} from "../../components";
import {Link, useParams} from "react-router-dom";
import charactersAPI from "../../services/CharactersAPI";
import CharactersAPI from "../../services/CharactersAPI";

const CharacterPage = (props) => {
    const [character, setCharacter] = useState({
        "name": "",
        // "health": "",
        // "skill": "",
        // "attack": "",
        // "createdAt": "",
        // "defense": "",
        // "magik": "",
        "rank": "",
        "skillPoints": ""
    })
    const params = useParams()
    const id = params.id ?? "new"

    const [errors, setError] = useState({
        "name": "",
    })

    const handleChange = ({ currentTarget}) => {
        const { name, value } = currentTarget

        setCharacter({...character, [name] : value })
    }

    const fetchCharacter = async id => {
        try {
            const data = (await charactersAPI
                .getCharacter(id))
            console.log(data)
            setCharacter(data)
        } catch (error) {
            console.log(error.result)
        }

    }

    useEffect(() => {
        if (id != "new") {
            fetchCharacter(id)
        }
    }, [id])

    const handleSubmit = async event => {
        event.preventDefault()
        try {
            const result = id === "new" ?
                (await CharactersAPI.add(character)) :
                (await CharactersAPI.edit(id, character))
        } catch (error) {
            console.log(error.result)
        }
    }

    return (<>

        <h1>{id === "new" && "Create a character" || character.name + " LvL " + character.rank}</h1>
        <form onSubmit={handleSubmit}>
            <Field
                name="name"
                label="Name"
                placeholder="Give a name to ur character"
                onChange={handleChange}
                value={character.name}
                error={errors.name}
            />
            <div className="row p-4">
                Skill points to use : {character.skillPoints}
            </div>
            {/*<Field*/}
            {/*    name="skillPoints"*/}
            {/*    label="Skill points"*/}
            {/*    type="int"*/}
            {/*    onChange={handleChange}*/}
            {/*    value={character.skillPoints}*/}
            {/*    error={errors.name}*/}
            {/*/>*/}


            <div className="form-group pt-3">
                <Link to="/characters" className="btn btn-link">
                    Back to characters list
                </Link>
                <button className="btn btn-success">Save</button>
            </div>
        </form>
    </>)
}

export default CharacterPage