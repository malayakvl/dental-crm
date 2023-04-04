import * as types from './types/getters';

export default {
    [types.GET_CABINETS]: state => state.cabinets,
    [types.GET_MODAL_CABINETS]: state => state.cabinetList,
    [types.GET_CABINET]: state => state.cabinet,
    [types.GET_ERRORS]: state => state.errors,
    [types.DATA_SAVED]: state => state.dataSaved,
};
