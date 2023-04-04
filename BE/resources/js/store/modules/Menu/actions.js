import * as actions from './types/actions';
import * as mutations from './types/mutations';
import MenuService from './../../../services/menu/MenuService.js';


export default {
    [actions.GET_MENU]: async ({ commit, dispatch }) => {
        try {
            const menu = await MenuService.getMenu();
            commit(mutations.SET_MENU, menu);
        } catch (error) {
            dispatch(error);
        }
    },
};
