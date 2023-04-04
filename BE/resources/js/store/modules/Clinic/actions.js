import * as actions from './types/actions';
import * as mutations from './types/mutations';
import ClinicService from './../../../services/clinic/ClinicService.js';

export default {
    [actions.GET_CLINICS]: async ({ commit, dispatch }) => {
        try {
            const clinics = await ClinicService.getClinics();
            commit(mutations.SET_CLINICS, clinics);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.GET_CLINIC]: async ({ commit, dispatch }, id) => {
        try {
            const clinic = await ClinicService.getClinic(id);
            commit(mutations.SET_CLINIC, clinic);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.UPDATE_CLINIC]: async ({ commit, dispatch }, data) => {
        try {
            const clinic = await ClinicService.updateClinic(data);
            commit(mutations.SET_CLINIC, clinic);
            commit(mutations.SET_DATA_SAVED, true);
        } catch (error) {
            commit(mutations.SET_ERROR, error.response.data.errors);
        }
    },
    [actions.CREATE_CLINIC]: async ({ commit, dispatch }, data) => {
        try {
            const clinic = await ClinicService.createClinic(data);
            commit(mutations.SET_CLINIC, clinic);
            commit(mutations.SET_DATA_SAVED, true);
        } catch (error) {
            commit(mutations.SET_ERROR, error.response.data.errors);
        }
    },
};
