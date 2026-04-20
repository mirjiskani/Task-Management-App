import React from "react";

export function TaskForm({ onSubmit, initialData = {} }) {
    const [name, setName] = React.useState(initialData.name || "");
    const [email, setEmail] = React.useState(initialData.email || "");
    const [role, setRole] = React.useState(initialData.role || "");
    const [task, setTask] = React.useState(initialData.task || "");

    return (
        <form onSubmit={e => { e.preventDefault(); onSubmit({ name, email, role, task }); }} style={{ display: "flex", flexDirection: "column", gap: 12, background: "#fff", padding: 24, borderRadius: 8, boxShadow: "0 4px 24px rgba(0,0,0,0.08)", width: 800, minWidth: 350, maxWidth: "90vw" }}>
            <input type="text" placeholder="Name" value={name} onChange={e => setName(e.target.value)} style={{ padding: "8px 12px", border: "1px solid #ccc", borderRadius: 4 }} required />
            <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} style={{ padding: "8px 12px", border: "1px solid #ccc", borderRadius: 4 }} required />
            <input type="text" placeholder="Role" value={role} onChange={e => setRole(e.target.value)} style={{ padding: "8px 12px", border: "1px solid #ccc", borderRadius: 4 }} required />
            <input type="text" placeholder="Task" value={task} onChange={e => setTask(e.target.value)} style={{ padding: "8px 12px", border: "1px solid #ccc", borderRadius: 4 }} required />
            <button type="submit" style={{ padding: "8px 16px", background: "#1976d2", color: "#fff", border: "none", borderRadius: 4, cursor: "pointer" }}>Submit</button>
        </form>
    );
}


