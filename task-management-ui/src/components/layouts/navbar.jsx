import React, { useState } from "react";
import { Link } from "react-router-dom";

export default function Navbar() {
    const [open, setOpen] = useState(false);
    // Dummy authentication state. Replace with real auth logic.
    const [isAuthenticated, setIsAuthenticated] = useState(() => {
        // Optionally, check localStorage or context for real auth
        return false;
    });

    const handleLogout = () => {
        // Clear auth state (replace with real logic)
        setIsAuthenticated(false);
        // Optionally, redirect to login
    };

    return (
        <nav style={{ background: "#4f8cff", padding: "0.5rem 0", boxShadow: "0 2px 8px rgba(79,140,255,0.08)" }}>
            <div style={{ maxWidth: 1200, margin: "0 auto", display: "flex", alignItems: "center", justifyContent: "space-between", padding: "0 1.5rem" }}>
                <div style={{ color: "#fff", fontWeight: 700, fontSize: 22, letterSpacing: 1 }}>
                    Task Manager
                </div>
                <button
                    onClick={() => setOpen(o => !o)}
                    style={{
                        display: "none",
                        background: "none",
                        border: "none",
                        color: "#fff",
                        fontSize: 28,
                        cursor: "pointer",
                        marginLeft: 16
                    }}
                    className="navbar-toggle"
                    aria-label="Toggle navigation"
                >
                    ☰
                </button>
                <div className="navbar-links" style={{ display: "flex", gap: 24 }}>
                    {isAuthenticated ? (
                        <>
                            <Link to="/" style={{ color: "#fff", textDecoration: "none", fontWeight: 500 }}>Dashboard</Link>
                            <span onClick={handleLogout} style={{ color: "#fff", textDecoration: "none", fontWeight: 500, cursor: "pointer" }}>Logout</span>
                        </>
                    ) : (
                        <>
                            <Link to="/dashboard" style={{ color: "#fff", textDecoration: "none", fontWeight: 500 }}>Dashboard</Link>   
                            <Link to="/users" style={{ color: "#fff", textDecoration: "none", fontWeight: 500 }}>Users</Link>   
                            <Link to="/login" style={{ color: "#fff", textDecoration: "none", fontWeight: 500 }}>Login</Link>
                            <Link to="/register" style={{ color: "#fff", textDecoration: "none", fontWeight: 500 }}>Register</Link>
                        </>
                    )}
                </div>
            </div>
            <style>{`
                @media (max-width: 700px) {
                    .navbar-links {
                        display: ${open ? "flex" : "none"};
                        flex-direction: column;
                        gap: 0;
                        background: #4f8cff;
                        position: absolute;
                        top: 56px;
                        left: 0;
                        width: 100vw;
                        padding: 1rem 0;
                        z-index: 1000;
                    }
                    .navbar-toggle {
                        display: block !important;
                    }
                }
            `}</style>
        </nav>
    );
}
