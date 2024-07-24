import React, { useState, useEffect } from 'react';
import { getUsers, getGroups, createUserGroup } from '../services/apiService';

const UserGroupManager = () => {
    const [users, setUsers] = useState([]);
    const [groups, setGroups] = useState([]);
    const [selectedUser, setSelectedUser] = useState(null);
    const [selectedGroups, setSelectedGroups] = useState([]);
    const [userGroupsCheckState, setUserGroupsCheckState] = useState({});
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const usersResponse = await getUsers();
                const groupsResponse = await getGroups();
                console.log('Users response:', usersResponse.data);
                console.log('Groups response:', groupsResponse.data);
                setUsers(usersResponse.data['hydra:member'] || []);
                console.log(users);
                setGroups(groupsResponse.data['hydra:member'] || []);
            } catch (err) {
                setError('Error loading data');
                console.error(err);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const handleUserChange = (e) => {
        const userId = parseInt(e.target.value, 10);
        const selectedUser = users.find(user => user.userid === userId);

        // Устанавливаем все чекбоксы 'невыбранными'
        let userGroupsCheckState = groups.reduce((state, group) => {
            state[group.groupid] = false;
            return state;
        }, {});

        selectedUser.groups.forEach(groupLink => {
            const groupId = groupLink.split("/").pop();
            userGroupsCheckState[groupId] = true;
        });

        setSelectedUser(selectedUser);
        setUserGroupsCheckState(userGroupsCheckState);
    };

    const handleGroupChange = (e) => {
        const groupId = e.target.value;
        const isChecked = e.target.checked;

        setUserGroupsCheckState(prevState => ({
            ...prevState,
            [groupId]: isChecked
        }));
    };

    const handleSubmit = () => {
        if (selectedUser) {
            const selectedGroups = Object.keys(userGroupsCheckState)
                .filter(groupId => userGroupsCheckState[groupId]);

            const selectedGroupsUrls = selectedGroups.map(groupId => `/api/groups/${groupId}`);

            createUserGroup(selectedUser, selectedGroupsUrls)
                .then(() => {
                    alert('User groups updated');
                    const updatedSelectedUser = {
                        ...selectedUser,
                        groups: selectedGroupsUrls,
                    };

                    setSelectedUser(updatedSelectedUser);

                    const updatedUsers = users.map(user =>
                        user.userid === selectedUser.userid ? updatedSelectedUser : user
                    );
                    setUsers(updatedUsers);
                })
                .catch((error) => {
                    console.error('Error updating user groups:', error);
                    alert('Error updating user groups');
                });
        }
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

    return (
        <div>
            <h1>Manage User Roles</h1>
            <div>
                {users.length === 0 ? (
                    <div>No users available</div>
                ) : (
                    <>
                        <select onChange={handleUserChange}>
                            <option value="">Select User</option>
                            {users.map(user => (
                                <option key={user.userid} value={user.userid}>
                                    {user.name}
                                </option>
                            ))}
                        </select>
                        <div>
                            {groups.length === 0 ? (
                                <p>No groups available</p>
                            ) : (
                                groups.map(group => (
                                    <label key={group.groupid}>
                                        <input
                                            type="checkbox"
                                            value={group.groupid}
                                            checked={userGroupsCheckState[group.groupid]}
                                            onChange={handleGroupChange}
                                        />
                                        {group.groupname}
                                    </label>
                                ))
                            )}
                        </div>
                        <button onClick={handleSubmit}>Save</button>
                    </>
                )}
            </div>
        </div>
    );
};

export default UserGroupManager;
