import * as actions from './types/actions';
import * as mutations from './types/mutations';
import StaffService from './../../../services/staff/StaffService.js';

export default {
    [actions.GET_STAFF]: async ({ commit, dispatch }) => {
        try {
            const staff = await StaffService.getStaff();
            commit(mutations.SET_STAFF, staff);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.GET_PATIENTS]: async ({ commit, dispatch }, data) => {
        try {
            const staff = await StaffService.getPatients(data);
            commit(mutations.SET_PATIENTS, staff);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.GET_LIST]: async ({ commit, dispatch }) => {
        try {
            const staff = await StaffService.getStaffList();
            commit(mutations.SET_STAFF, staff);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.SET_COLOR]: async ({ commit, dispatch }, data) => {
        try {
            await StaffService.setColor(data);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.SET_PERMISSION]: async ({ commit, dispatch }, data) => {
        try {
            const staff = await StaffService.setPermission(data);
            commit(mutations.SET_STAFF, []);
            commit(mutations.SET_STAFF, staff);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.SET_ROLE]: async ({ commit, dispatch }, data) => {
        try {
            await StaffService.setRole(data);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.FIND_STAFF]: async ({ commit, dispatch }, data) => {
        try {
            const users = await StaffService.findStaff(data);
            commit(mutations.SET_USERS, users);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.INVITE_STAFF]: async ({ commit, dispatch }, data) => {
        try {
            await StaffService.inviteStaff(data);
            commit(mutations.SET_DATA_SAVED, true);
        } catch (error) {
            dispatch(error);
        }
    },
};
