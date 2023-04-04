import * as actions from './types/actions';
import * as mutations from './types/mutations';
import SchedulerService from './../../../services/scheduler/SchedulerService.js';

export default {
    [actions.GET_EVENTS]: async ({ commit, dispatch }, data) => {
        try {
            const events = await SchedulerService.getEvents(data);
            commit(mutations.SET_EVENTS, events);
        } catch (error) {
            dispatch(error);
        }
    },
    [actions.ADD_EVENT]: async ({ commit, dispatch }, data) => {
        try {
            const events = await SchedulerService.createEvent(data);
            commit(mutations.SET_EVENTS, events);
        } catch (error) {
            dispatch(error);
        }
    },
};

