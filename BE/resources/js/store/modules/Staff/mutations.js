import * as mutations from './types/mutations';

export default {
    [mutations.SET_STAFF]: (state, payload) => {
        state.staff = payload;
    },
    [mutations.SET_USERS]: (state, payload) => {
        state.users = payload;
    },
    [mutations.SET_DATA_SAVED]: (state, payload) => {
        state.dataSaved = payload;
    },
    [mutations.SET_PATIENTS]: (state, payload) => {
        state.patients = payload;
    },
};
