import * as mutations from './types/mutations';

export default {
    [mutations.SET_CABINETS]: (state, payload) => {
        state.cabinets = payload;
    },
    [mutations.SET_MODAL_CABINETS]: (state, payload) => {
        state.cabinetList = payload;
    },
    [mutations.SET_CABINET]: (state, payload) => {
        state.cabinet = payload;
    },
    [mutations.SET_ERROR]: (state, errors) => {
        state.errors = errors;
    },
    [mutations.SET_DATA_SAVED]: (state, payload) => {
        state.dataSaved = payload;
    },
};
