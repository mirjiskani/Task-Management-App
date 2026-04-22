import api from "./api";

export const getUsers = () => api.get("/users");
export const addUser = (data) => api.post("/add-users", data);
export const editUser = (id, data) => api.put(`/users/${id}`, data);
export const deleteUser = (id) => api.delete(`/users/${id}`);
