import axios from 'axios';

let _accessToken = ''; // Get access token from local storage

export const setAccessToken = (token) => {
    _accessToken = token;
}

const api = axios.create({
    baseURL: 'http://localhost:8000/api', // Replace with your API base URL
    withCredentials: false, // Don't include cookies to avoid CORS credential issues
})

const interceptRequest = (config) => {
    // Add any request interceptors here (e.g., add auth token)
    if(_accessToken) {
        config.headers['Authorization'] = `Bearer ${_accessToken}`;
    }
    return config;
};

const interceptRequestError = (error) => {
    // Handle request error here
    return Promise.reject(error);
};

api.interceptors.request.use(interceptRequest, interceptRequestError);

api.interceptors.response.use(
    (response) => {
        // Handle successful responses here 
        return response;
    },
    (error) => {
        // Handle response errors here (e.g., log out on 401)   
        if (error.response && error.response.status === 401) {
            // Handle unauthorized access (e.g., redirect to login)
            console.error('Unauthorized access - redirecting to login');
            // You can add logic to redirect to login page here
        }   
        return Promise.reject(error);
    }
);

export default api;