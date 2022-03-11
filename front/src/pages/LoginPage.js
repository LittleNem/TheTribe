import React, {useState} from "react";
import AuthAPI from "../services/AuthAPI";
import {useLocation, useNavigate} from "react-router-dom";
import {Field} from "../components";

const LoginPage = ({ onLogin, isAuthenticated }) => {
    const navigate = useNavigate();
    const location = useLocation();

    const from = location.state?.from?.pathname || "/characters";
    const [credentials, setCredentials] = useState({
        username: "",
        password: ""
    })
    const [error, setError] = useState("")

    const handleChange = (event) => {
        const {value, name} = event.currentTarget
        setCredentials({...credentials, [name]: value})
    }

    const handleSubmit = async event => {
        event.preventDefault()
        try {
            await AuthAPI.authenticate(credentials)
            setError("")
            onLogin(true)
            navigate(from, { replace: true });
        } catch (error) {
            setError("Vos identifiants sont erronés")
        }
    }

    return (
        <div className="col-md-8">
            <h1>Connexion</h1>
            <form onSubmit={handleSubmit}>
                <Field
                    label="Email"
                    name="username"
                    type="email"
                    onChange={handleChange}
                    value={credentials.username}
                    placeholder="Email address"
                />
                <Field
                    label="Password"
                    name="password"
                    type="password"
                    error={error}
                    onChange={handleChange}
                    value={credentials.password}
                />
                <div className="form-group pt-2">
                    <button type="submit" className="btn btn-success">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    );
};

export default LoginPage;
