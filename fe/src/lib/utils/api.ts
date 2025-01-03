import axios from 'axios';

export const api = axios.create({
	baseURL: 'http://localhost:8000/api/v1',
	headers: {
		Accept: 'application/json',
		'Content-Type': 'multipart/form-data'
	}
});

export const store = 'http://localhost:8000/storage/uploads/';
