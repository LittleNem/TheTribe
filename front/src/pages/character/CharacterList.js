import React, { useEffect, useState } from "react";
import {Pagination} from "../../components";
import charactersAPI from "../../services/CharactersAPI";
import {Link} from "react-router-dom";

function CharacterList() {
    const charactersPerPage = 10
    const [characters, setCharacters] = useState([])
    const [currentPage, setCurrentPage] = useState(1)

    const fetchCharacters = async () => {
        try {
            const data = await charactersAPI.findAll()
            setCharacters(data)
        } catch (error) {
            console.log(error.response)
        }
    }
    useEffect( () => {
        fetchCharacters();
    }, []);

    const handleDelete = async id => {
        const originalCharacters = [...characters]
        setCharacters(characters.filter(character => character.id !== id))
        try {
            await charactersAPI.delete(id)
        } catch (error) {
            setCharacters(originalCharacters)
        }
    }

    const handlePageChange = (page) => {
        setCurrentPage(page)
    }

    return (
        <>
            <div className="d-flex justify-content-between align-items-center">
                <h1>Characters list</h1>
                <Link className="btn btn-success" to="/characters/new">Add a character</Link>
            </div>

            <table className="table table-hover">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th className="text-center">Rank</th>
                        <th className="text-center">Skill Point</th>
                        <th className="text-center">Health</th>
                        <th className="text-center">Attack</th>
                        <th className="text-center">Defense</th>
                        <th className="text-center">Magik</th>
                        <th>Unable to play until</th>
                        <th/>
                    </tr>
                </thead>
                <tbody>
                    {characters.map(character => (
                        <tr key={character.id}>
                            <td>{character.id}</td>
                            <td><Link to={"/characters/" + character.id}>{character.name}</Link></td>
                            <td className="text-center">{character.rank}</td>
                            <td className="text-center">{character.skillPoints}</td>
                            <td className="text-center">{character.health}</td>
                            <td className="text-center">{character.attack}</td>
                            <td className="text-center">{character.defense}</td>
                            <td className="text-center">{character.magik}</td>
                            <td className="text-center">{character.delay}</td>
                            <td></td>
                            <td>
                                <button
                                    onClick={() => handleDelete(character.id)}
                                    className="btn btn-danger"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
            <div className="row">
                <Pagination currentPage={currentPage} itemsPerPage={charactersPerPage} length={characters.length} onPageChanged={handlePageChange} />
            </div>

        </>

    )
}

export default CharacterList