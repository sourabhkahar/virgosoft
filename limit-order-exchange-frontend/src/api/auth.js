import { api } from '@/api/axios.js';
export const useAuth = () => {
    const http = api()
    const register = (data) => http.post('register', data);
    const login = (data) => http.post('login', data);
    const logout = () => http.post('logout');
    const profile = () => http.get('profile');
    return {
        register,
        login,
        logout,
        profile
    }
}