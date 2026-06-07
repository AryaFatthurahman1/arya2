import axios, { AxiosInstance, AxiosResponse } from 'axios';
import type { ApiResponse } from '../types';

const api: AxiosInstance = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
});

api.interceptors.response.use(
    (response: AxiosResponse) => response,
    (error) => {
        if (error.response?.status === 401) {
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export async function fetchData<T>(url: string): Promise<ApiResponse<T>> {
    const response = await api.get(url);
    return response.data;
}

export async function postData<T>(url: string, data: unknown): Promise<ApiResponse<T>> {
    const response = await api.post(url, data);
    return response.data;
}

export async function updateData<T>(url: string, data: unknown): Promise<ApiResponse<T>> {
    const response = await api.put(url, data);
    return response.data;
}

export async function deleteData<T>(url: string): Promise<ApiResponse<T>> {
    const response = await api.delete(url);
    return response.data;
}

export default api;
