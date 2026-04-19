import React, { useState } from "react";
import { Link } from "react-router-dom";

export default function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        // Handle login logic here
        alert(`Email: ${email}\nPassword: ${password}`);
    };

    return (
        <div style={{ minHeight: "100vh", display: "flex", alignItems: "center", justifyContent: "center", background: "#f5f6fa" }}>
            <form onSubmit={handleSubmit} style={{ background: "#fff", padding: "2.5rem 2rem", borderRadius: "12px", boxShadow: "0 4px 24px rgba(0,0,0,0.08)", minWidth: 320, width: "100%", maxWidth: 380 }}>
                <h2 style={{ textAlign: "center", marginBottom: 24, color: "#222" }}>Sign In</h2>
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="email" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Email</label>
                    <input
                        id="email"
                        type="email"
                        value={email}
                        onChange={e => setEmail(e.target.value)}
                        required
                        placeholder="Enter your email"
                        style={{ width: "100%", padding: "10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16, boxSizing: "border-box" }}
                    />
                </div>
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="password" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Password</label>
                    <div style={{ position: "relative" }}>
                        <input
                            id="password"
                            type={showPassword ? "text" : "password"}
                            value={password}
                            onChange={e => setPassword(e.target.value)}
                            required
                            placeholder="Enter your password"
                            style={{ width: "100%", padding: "10px 38px 10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16, boxSizing: "border-box" }}
                        />
                        <span
                            onClick={() => setShowPassword(s => !s)}
                            style={{ position: "absolute", right: 10, top: "50%", transform: "translateY(-50%)", cursor: "pointer", color: "#888", fontSize: 15 }}
                            title={showPassword ? "Hide password" : "Show password"}
                        >
                            {showPassword ? "🙈" : "👁️"}
                        </span>
                    </div>
                </div>
                <button
                    type="submit"
                    style={{ width: "100%", padding: "12px 0", background: "#4f8cff", color: "#fff", border: "none", borderRadius: 6, fontWeight: 600, fontSize: 17, cursor: "pointer", boxShadow: "0 2px 8px rgba(79,140,255,0.08)" }}
                >
                    Login
                </button>
                <div style={{ marginTop: 18, textAlign: "center", color: "#888", fontSize: 14 }}>
                    Don't have an account? <Link to="/register" style={{ color: "#4f8cff", textDecoration: "none" }}>Sign up</Link>      
                </div>
            </form>
        </div>
    );
}
