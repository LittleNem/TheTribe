import React  from "react";
import AuthAPI from "../../services/AuthAPI";
import {NavLink} from "react-router-dom";

const Header = ({isAuthenticated, onLogout, history}) => {
    const handleLogout = () => {
        AuthAPI.logout()
        onLogout(false)
        window.location.replace("/")
    }

    return (
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
            <div className="container-fluid">
                <a className="navbar-brand" href="/">The tribe game</a>
                <div className="navbar-text">
                    <button className="navbar-toggler">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse">
                        <ul className="navbar-nav d-flex">
                            <li className="nav-item active">
                                <a className="nav-link" href="/launch-game">Home</a>
                            </li>
                            <li className="nav-item active">
                                <NavLink className="nav-link" to="/characters">My characters</NavLink>
                            </li>
                            <li className="nav-item">
                                {(isAuthenticated &&
                                    <button onClick={handleLogout} className="btn btn-danger">Logout</button>
                                ) || (
                                    <NavLink className="btn btn-success" to="/">Login</NavLink>
                                )}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    )
}

export default Header