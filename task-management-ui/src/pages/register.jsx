import React, { useState } from "react";
import { Link } from "react-router-dom";

export default function Register() {
    const [form, setForm] = useState({
        name: "",
        email: "",
        contact: "",
        password: "",
        confirmPassword: ""
    });
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (form.password !== form.confirmPassword) {
            alert("Passwords do not match!");
            return;
        }
        // Handle registration logic here
        alert(`Registered!\nName: ${form.name}\nEmail: ${form.email}\nContact: ${form.contact}`);
    };

    return (
        <div style={{ minHeight: "100vh", display: "flex", alignItems: "center", justifyContent: "center", background: "#f5f6fa" }}>
            <form onSubmit={handleSubmit} style={{ background: "#fff", padding: "2.5rem 2rem", borderRadius: "12px", boxShadow: "0 4px 24px rgba(0,0,0,0.08)", minWidth: 320, width: "100%", maxWidth: 400 }}>
                <h2 style={{ textAlign: "center", marginBottom: 24, color: "#222" }}>Register</h2>
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="name" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value={form.name}
                        onChange={handleChange}
                        required
                        placeholder="Enter your name"
                        style={{ width: "100%", padding: "10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16 , boxSizing: "border-box"}}
                    />
                </div>
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="email" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value={form.email}
                        onChange={handleChange}
                        required
                        placeholder="Enter your email"
                        style={{ width: "100%", padding: "10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16, boxSizing: "border-box" }}
                    />
                </div>
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="contact" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Contact</label>
                    <input
                        id="contact"
                        name="contact"
                        type="tel"
                        value={form.contact}
                        onChange={handleChange}
                        required
                        placeholder="Enter your contact number"
                        style={{ width: "100%", padding: "10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16, boxSizing: "border-box" }}
                    />
                </div>
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="password" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Password</label>
                    <div style={{ position: "relative" }}>
                        <input
                            id="password"
                            name="password"
                            type={showPassword ? "text" : "password"}
                            value={form.password}
                            onChange={handleChange}
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
                <div style={{ marginBottom: 18 }}>
                    <label htmlFor="confirmPassword" style={{ display: "block", marginBottom: 6, color: "#555", fontWeight: 500 }}>Confirm Password</label>
                    <div style={{ position: "relative" }}>
                        <input
                            id="confirmPassword"
                            name="confirmPassword"
                            type={showConfirmPassword ? "text" : "password"}
                            value={form.confirmPassword}
                            onChange={handleChange}
                            required
                            placeholder="Confirm your password"
                            style={{ width: "100%", padding: "10px 38px 10px 12px", border: "1px solid #ddd", borderRadius: 6, fontSize: 16, boxSizing: "border-box" }}
                        />
                        <span
                            onClick={() => setShowConfirmPassword(s => !s)}
                            style={{ position: "absolute", right: 10, top: "50%", transform: "translateY(-50%)", cursor: "pointer", color: "#888", fontSize: 15 }}
                            title={showConfirmPassword ? "Hide password" : "Show password"}
                        >
                            {showConfirmPassword ? "🙈" : "👁️"}
                        </span>
                    </div>
                </div>
                <button
                    type="submit"
                    style={{ width: "100%", padding: "12px 0", background: "#4f8cff", color: "#fff", border: "none", borderRadius: 6, fontWeight: 600, fontSize: 17, cursor: "pointer", boxShadow: "0 2px 8px rgba(79,140,255,0.08)" }}
                >
                    Register
                </button>
                <div style={{ marginTop: 18, textAlign: "center", color: "#888", fontSize: 14 }}>
                    Already have an account? <Link to="/login" style={{ color: "#4f8cff", textDecoration: "none" }}>Sign in</Link>
                </div>
            </form>
        </div>
    );
}
