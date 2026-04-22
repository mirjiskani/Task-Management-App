import { useState, useEffect } from "react";
import { Table } from "../components/common/table";
import { getUsers } from "../services/usersService";

export default function Users() {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const columns = [
        { header: "ID", accessor: "id" },
        { header: "Name", accessor: "name" },
        { header: "Email", accessor: "email" },
        { header: "Role", accessor: "role" },
        { header: "Actions", accessor: "actions" }
    ];

    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = async () => {
        try {
            setLoading(true);
            const response = await getUsers();
            
            // Handle different response structures
            let usersData = [];
            if (response.data) {
                // Axios response structure
                usersData = response.data.users || response.data;
            } else {
                // Direct response
                usersData = response.users || response;
            }
            
            
            const formattedUsers = usersData.map(user => ({
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role,
                actions: { id: user.id }
            }));
            
            setUsers(formattedUsers);
            setError(null);
        } catch (error) {
            console.error("Failed to fetch users:", error);
            setError("Failed to load users. Please try again.");
            setUsers([]);
        } finally {
            setLoading(false);
        }
    };

    const handleEdit = (id) => {
        console.log("Edit user:", id);
        // TODO: Implement edit functionality
    };

    const handleDelete = (id) => {
        console.log("Delete user:", id);
        // TODO: Implement delete functionality
    };

    if (loading) {
        return (
            <div style={{ padding: 24 }}>
                <h1>Users</h1>
                <div>Loading users...</div>
            </div>
        );
    }

    if (error) {
        return (
            <div style={{ padding: 24 }}>
                <h1>Users</h1>
                <div style={{ color: "red" }}>{error}</div>
                <button onClick={fetchUsers} style={{ marginTop: 16, padding: "8px 16px", background: "#1976d2", color: "#fff", border: "none", borderRadius: 4, cursor: "pointer" }}>
                    Retry
                </button>
            </div>
        );
    }

    return (
        <div style={{ padding: 24 }}>
            <h1>Users</h1>
            <Table 
                columns={columns} 
                data={users} 
                onEdit={handleEdit}
                onDelete={handleDelete}
            />
        </div>
    );
}

