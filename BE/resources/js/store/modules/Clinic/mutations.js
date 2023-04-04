import * as mutations from './types/mutations';

export default {
    [mutations.SET_CLINICS]: (state, payload) => {
        state.clinics = payload;
    },
    [mutations.SET_CLINIC]: (state, payload) => {
        state.clinic = payload;
    },
    [mutations.SET_ERROR]: (state, errors) => {
        state.errors = errors;
    },
    [mutations.SET_DATA_SAVED]: (state, payload) => {
        state.dataSaved = payload;
    },
};
