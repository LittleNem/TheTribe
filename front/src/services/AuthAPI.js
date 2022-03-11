import axios from "axios";
import jwtDecode from "jwt-decode";

function authenticate(credentials) {
    return axios.post("https://localhost/api/login_check", credentials)
        .then(response => response.data.token)
        .then(token => {
            window.localStorage.setItem("authToken", token)
            setBearer(token)
            return true
        })
}

function setBearer(token) {
    axios.defaults.headers["Authorization"] = "Bearer " + token
}

function logout() {
    window.localStorage.removeItem("authToken")
    delete axios.defaults.headers["Authorization"]
}

function setup() {
    if (isAuthenticated()) {
        setBearer(window.localStorage.getItem("authToken"))
    }
}

function getCurrentUserId() {
    if (isAuthenticated()) {

    }
}

function isAuthenticated() {
    const token = window.localStorage.getItem("authToken")
    if (token) {
        const jwtData = jwtDecode(token)
        if (jwtData.exp * 1000 > new Date().getTime()) {
            return true
        }
    }
    return false
}

export default {
    authenticate,
    logout,
    setup,
    isAuthenticated
}