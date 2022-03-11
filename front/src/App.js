import * as React from "react";
import { Routes, Route, Navigate, Outlet } from "react-router-dom";
import {Header} from "./components";
import {CharacterList, CharacterPage, LoginPage, LaunchGame} from "./pages";
import "./App.css";
import authAPI from "./services/AuthAPI";
import {useState} from "react";

authAPI.setup()

function App() {
    const [isAuthenticated, setIsAuthenticated] = useState(authAPI.isAuthenticated())

    function PrivateOutlet() {
        return isAuthenticated ? <Outlet /> : <Navigate to="/" />;
    }

    return (

    <div className="App">
        <Header isAuthenticated={isAuthenticated} onLogout={setIsAuthenticated}/>
        <div className="container">
            <main className="pt-4 p-2 justify-content-center">
                <Routes>
                    <Route path="/" element={<LoginPage
                        onLogin={setIsAuthenticated}
                        isAuthenticated={isAuthenticated}
                    />} />
                    <Route path="/launch-game" element={<PrivateOutlet />}>
                        <Route path="" element={<LaunchGame />} />
                    </Route>
                    <Route path="/characters" element={<PrivateOutlet />}>
                        <Route path="" element={<CharacterList />} />
                    </Route>
                    <Route path="/characters/:id" element={<PrivateOutlet />}>
                        <Route path="" element={<CharacterPage />} />
                    </Route>
                </Routes>
            </main>
        </div>
    </div>
    );
}

export default App;
