

import React, { useState } from "react";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

import { Table } from "../components/common/table";
import { TaskForm } from "../components/common/task-form";
import { getTasks, addTask, editTask, deleteTask } from "../services/taskService";

export default function Dashboard() {
    const [showTaskForm, setShowTaskForm] = useState(false);
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


    const TaskList = async () => {
        try {
            const response = await getTasks();
            // Handle response data (e.g., set state with tasks)
        } catch (error) {
            toast.error("Failed to fetch tasks");
        }
    }


    const handleAddTask = async (values) => {
        try {
            console.log("Submitting new task:", values);
            await addTask(values);
            setShowTaskForm(false);
        } catch (error) {
            toast.error("Failed to add task");
        }
    }


    const handleDeleteTask = async (id) => {
        try {
            await deleteTask(id);
            // Optionally update UI to remove the deleted task
        } catch (error) {
            toast.error("Failed to delete task");
        }
    }


    const handleEditTask = async (id, updatedValues) => {
        try {
            await editTask(id, updatedValues);
            // Optionally update UI with the edited task
        } catch (error) {
            toast.error("Failed to edit task");
        }
    }

    
    return (
        <div style={{ padding: 24, background: "#f5f6fa", minHeight: "100vh" }}>
            <h1 style={{ marginBottom: 24, color: "#222" }}>Task Management Dashboard</h1>
            <button
                style={{ marginBottom: 16, padding: "8px 16px", background: "#1976d2", color: "#fff", border: "none", borderRadius: 4, cursor: "pointer" }}
                onClick={() => setShowTaskForm(true)}
            >
                Add New Task
            </button>
            <Table columns={columns} data={data} />

            {showTaskForm && (
                <div style={{
                    position: "fixed",
                    top: 0,
                    left: 0,
                    width: "100vw",
                    height: "100vh",
                    background: "rgba(0,0,0,0.3)",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    zIndex: 1000
                }}>
                    <div style={{ position: "relative" }}>
                        <button
                            onClick={() => setShowTaskForm(false)}
                            style={{
                                position: "absolute",
                                top: -32,
                                right: 0,
                                background: "#fff",
                                border: "none",
                                fontSize: 24,
                                cursor: "pointer"
                            }}
                            aria-label="Close"
                        >
                            &times;
                        </button>
                        <TaskForm onSubmit={handleAddTask} />
                    </div>
                </div>
            )}
        <ToastContainer position="top-right" autoClose={3000} hideProgressBar={false} newestOnTop closeOnClick pauseOnFocusLoss draggable pauseOnHover />
        </div>
    );
}



