import axios from 'axios';

const apiUrl = process.env.REACT_APP_API_BASE_URL;

const apiService = axios.create({
    baseURL: apiUrl,
    headers: {
        'Content-Type': 'application/json',
    },
});

export const getUsers = () => apiService.get('/api/users');
export const getGroups = () => apiService.get('/api/groups');
export const createUserGroup = (user, groupIds) => apiService.patch(`/api/users/${user.userid}`, {
    groups: groupIds.map(groupId => `${groupId}`),
}, {
    headers: {
        'Content-Type': 'application/merge-patch+json',
    },
});
