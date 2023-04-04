import * as mutations from './types/mutations';

export default {
    [mutations.SET_MENU]: (state, payload) => {
        state.menu = payload;
    },
};
