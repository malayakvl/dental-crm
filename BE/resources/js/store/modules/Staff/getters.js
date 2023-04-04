import * as types from './types/getters';

export default {
    [types.GET_STAFF]: state => state.staff,
    [types.GET_ERRORS]: state => state.errors,
    [types.DATA_SAVED]: state => state.dataSaved,
    [types.GET_USERS]: state => state.users,
    [types.GET_PATIENTS]: state => state.patients,
};
