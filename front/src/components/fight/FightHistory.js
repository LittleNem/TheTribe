import React from "react";

const FightHistory = ({
  histories,
  character,
  winner
}) => {
    return (
        <div>
            {histories.map(history =>
                <div className="row">
                    <div>ROUND {history.round}</div>
                    <span>
                        {history.character.name} turn : dice value [{history.diceValue}],
                        damages [{history.damage}],
                        opponent health [{history.opponentHealthValue}]
                    </span>
                </div>
            )}
            {winner &&
                <div id={winner} style={character.name !== winner && {color: "red"} || {color: "green"}}>
                    {winner} won !
                </div>
            }
        </div>
    )
}

export default FightHistory