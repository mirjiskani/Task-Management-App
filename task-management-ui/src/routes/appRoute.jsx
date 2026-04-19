
import { Routes, Route, Navigate } from 'react-router-dom';
import Login from '../pages/login';
import Register from '../pages/register';
import Dashboard from '../pages/dashboard';

const protectRoute = (Component) => {
    return (props) => {
        const isAuthenticated = true; // Replace with actual authentication logic
        if (!isAuthenticated) {
            return <Navigate to="/login" />;
        }
        return <Component {...props} />;
    }
}

export default function appRoute() {
    return (
        <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />   
            <Route path="/dashboard" element={<Dashboard />} />
            {/* <Route path="/dashboard" element={protectRoute(Dashboard)} /> */}
            <Route path="*" element={<Navigate to="/login" />} />
        </Routes>
    );
}
