import * as types from './types/getters';

export default {
    [types.GET_CABINET]: state => state.cabinetId,
    [types.GET_DATE]: state => state.date,
    [types.GET_SHOW_MODAL]: state => state.showModal,
};
