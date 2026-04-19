import { Table } from "../components/common/table";

export default function Dashboard() {
    const columns = [
        { header: "ID", accessor: "id" },
        { header: "Name", accessor: "name" },
        { header: "Email", accessor: "email" },
        { header: "Role", accessor: "role" },
        { header: "Task", accessor: "task" },
        { header: "Actions", accessor: "actions" }
    ];

    const data = [
        { id: 1, name: "Alice Johnson", email: "alice.johnson@example.com", role: "Admin", task: "Create Frontend UI" },
        { id: 2, name: "Bob Smith", email: "bob.smith@example.com", role: "User", task: "Create Backend API" }
    ];

    return (
        <div style={{ padding: 24, background: "#f5f6fa", minHeight: "100vh" }}>
            <h1 style={{ marginBottom: 24, color: "#222" }}>Task Management Dashboard</h1>
            <Table columns={columns} data={data} />
        </div>
    );
}       
    


            