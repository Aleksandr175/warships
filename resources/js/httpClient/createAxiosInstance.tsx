import axios, { AxiosInstance } from "axios";

export const createAxiosInstance = (): AxiosInstance => {
  const axiosInstance = axios.create({
    baseURL: "http://localhost/api",
    withCredentials: true,
  });

  return axiosInstance;
};
