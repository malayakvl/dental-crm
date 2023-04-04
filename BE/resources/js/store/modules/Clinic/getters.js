import * as types from './types/getters';

export default {
    [types.GET_CLINICS]: state => state.clinics,
    [types.GET_CLINIC]: state => state.clinic,
    [types.GET_ERRORS]: state => state.errors,
    [types.DATA_SAVED]: state => state.dataSaved,
};
