import API from './api';

export const login = async (username, password) => {
    try {
        const response = await API.post('/auth/login', { username, password });
        const { accessToken } = response.data;
        return accessToken;
    } catch (error) {
        console.error('Login failed:', error);
        throw error;
    }   
};

export const register = async (formData) => {
        const { username, password, email,contact } = formData;
    try {
        const response = await API.post('/auth/register', { username, password, email, contact });   
        return response.data;
    } catch (error) {
        console.error('Registration failed:', error);
        throw error;
    }
};
export const logout = async () => {
    try {
        await API.post('/auth/logout');
    } catch (error) {
        console.error('Logout failed:', error);
        throw error;
    }
};  