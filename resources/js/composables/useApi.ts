import axios from 'axios';

export const useApi = () => {
  return async (url: string, options: any = {}) => {
    const config = {
      url: `/api${url}`,
      method: options.method || 'GET',
      data: options.body,
      headers: {
        Accept: 'application/json',
        ...options.headers
      }
    };
    return axios(config);
  }
}
