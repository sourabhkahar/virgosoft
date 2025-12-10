import { api } from '@/api/axios.js';
export const order = () => {
    const http = api()
    const placeOrder = (data) => http.post('orders', data);
    const orders = (data) => http.get('orders', { params: data });
    return {
        placeOrder,
        orders
    }
}