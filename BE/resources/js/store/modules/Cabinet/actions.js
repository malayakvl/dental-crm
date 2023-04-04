import * as actions from './types/actions';
import * as mutations from './types/mutations';
import CabinetService from './../../../services/cabinet/CabinetService.js';

export default {
    [actions.GET_CABINETS]: async ({ commit, dispatch }) => {
        try {
            const cabinets = await CabinetService.getCabinets();
            commit(mutations.SET_CABINETS, cabinets);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.GET_MODAL_CABINETS]: async ({ commit, dispatch }) => {
        try {
            const cabinets = await CabinetService.getCabinets();
            commit(mutations.SET_MODAL_CABINETS, cabinets);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.GET_CABINET]: async ({ commit, dispatch }, id) => {
        try {
            const cabinet = await CabinetService.getCabinet(id);
            commit(mutations.SET_CABINET, cabinet);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.UPDATE_CABINET]: async ({ commit, dispatch }, data) => {
        try {
            await CabinetService.updateCabinet(data);
            commit(mutations.SET_DATA_SAVED, true);
        } catch (error) {
            commit(mutations.SET_ERROR, error.response.data.errors);
        }
    },
    [actions.CREATE_CABINET]: async ({ commit, dispatch }, data) => {
        try {
            await CabinetService.createCabinet(data);
            commit(mutations.SET_DATA_SAVED, true);
        } catch (error) {
            commit(mutations.SET_ERROR, error.response.data.errors);
        }
    },
};
