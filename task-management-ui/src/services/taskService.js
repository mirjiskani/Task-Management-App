import api from "./api";

export const getTasks = () => api.get("/tasks");
export const addTask = (data) => api.post("/add-tasks", data);
export const editTask = (id, data) => api.put(`/tasks/${id}`, data);
export const deleteTask = (id) => api.delete(`/tasks/${id}`);
