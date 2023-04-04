import Vue from "vue";
import Vuex from "vuex";
import ClinicModule from "./modules/Clinic";
import StaffModule from "./modules/Staff";
import MenuModule from "./modules/Menu";
import CabinetModule from "./modules/Cabinet";
import SchedulerModule from "./modules/Scheduler";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        showLoader: true,
        clinic: null,
        clinicId: null,
        user: null,
        noticeCnt: 0,
        roles: []
    },
    mutations: {
        setClinic(state, payload) {
            state.clinic = payload;
        },
        setClinicId(state, payload) {
            state.clinicId = payload;
        },
        exitClinic(state) {
            state.clinicId = null;
        },
        setRole(state, payload) {
            state.roles = payload;
        },
        setUser(state, user) {
            state.user = user;
        },
        loaderShow: function (state) {
            state.showLoader = true;
        },
        loaderHide: function (state) {
            state.showLoader = false;
        },
        setNoticeCnt: function (state, payload) {
            state.noticeCnt = payload;
        },
    },
    actions: {
        async loadUser({ commit }) {
            const res = await fetch("/api/user/load", { redirect: "manual" });
            if (res.ok) {
                const user = await res.json();
                commit("setUser", user);
                commit("setClinic", user.default_clinic);
                commit("setClinicId", user.default_clinic.id);
            } else {
                commit("setUser", null);
                commit("setClinic", null);
            }
        },
        async entranceClinic({commit}, clinicId) {
            const res = await fetch(`/api/clinic/login/${clinicId}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.ok) {
                const data = await res.json();
                commit("setClinic", data);
                commit("setClinicId", data.id);
            }
        }
    },
    modules: {
        Clinic: ClinicModule,
        Staff: StaffModule,
        Menu: MenuModule,
        Cabinet: CabinetModule,
        Scheduler: SchedulerModule
    },
});
