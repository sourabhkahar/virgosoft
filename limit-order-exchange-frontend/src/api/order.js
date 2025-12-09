import { api } from '@/api/axios.js';
export const order = () => {
    const http = api()
    const placeOrder = (data) => http.post('order/store', data);
    return {
        placeOrder
    }
}