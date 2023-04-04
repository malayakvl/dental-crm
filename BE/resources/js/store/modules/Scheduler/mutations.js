import * as mutations from './types/mutations';

export default {
    [mutations.SET_CABINET]: (state, payload) => {
        state.cabinetId = payload;
    },
    [mutations.SET_DATE]: (state, payload) => {
        state.date = payload;
    },
    [mutations.SET_SHOW_MODAL]: (state, payload) => {
        state.showModal = payload;
    },
};
