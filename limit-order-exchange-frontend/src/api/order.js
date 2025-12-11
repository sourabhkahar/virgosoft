import { api } from '@/api/axios.js';
export const order = () => {
    const http = api()
    const placeOrder = (data) => http.post('orders', data);
    const orders = (data) => http.get('orders', { params: data });
    const cancel = (id) => http.post(`orders/${id}/cancel`, );
    return {
        placeOrder,
        orders,
        cancel
    }
}