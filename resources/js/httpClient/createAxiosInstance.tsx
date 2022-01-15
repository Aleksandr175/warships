import axios, { AxiosInstance } from "axios";

export const createAxiosInstance = (): AxiosInstance => {
    const axiosInstance = axios.create({
        baseURL: "http://127.0.0.1:8000",
        withCredentials: true,
    });

    return axiosInstance;
};
