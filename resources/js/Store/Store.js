import { createStore } from "vuex";
import axios from "axios";

export default createStore({
    state() {
        return {
            userData: [],
            companyData: [],
            errors: [],
            token: localStorage.getItem("_token") || 0,
            pageLink: 0,
            editUserData: [],
        };
    },
    mutations: {
        SET_USER(state, dbData) {
            state.userData = dbData;
        },
        UPDATE_USER_PROFILE(state, profile) {
            state.userData = profile;
        },
        DELETE_USER_PROFILE(state, id) {
            var index = state.userData.indexOf(id);
            return state.userData.splice(index);
        },
        GET_COMPANY(state, company) {
            state.companyData = company;
        },
        SET_COMPANY: (state, { company }) => {
            state.companyData = company;
        },
        ERROR: (state, { error }) => {
            state.errors = error;
        },
        UPDATE_TOKEN(state, payload) {
            state.token = payload;
        },
        PAGE_LINK(state, count) {
            state.pageLink = count;
        },
        EDIT_USER(state,edit){
            state.editUserData = edit;
        }
    },
    actions: {
        SET_TOKEN(context, payload) {
            localStorage.setItem("_token", payload);
            context.commit("UPDATE_TOKEN", payload);
        },
        REMOVE_TOKEN(context) {
            localStorage.removeItem("_token");
            context.commit("UPDATE_TOKEN", 0);
        },
        UPDATE_USER_PROFILE: async function ({ commit }, payload) {
            const env = import.meta.env.VITE_APP_VERSION;
            const url = env + `api/user/${payload.id}`;
            commit("ERROR", []);
            await axios.patch(url, payload.model).then(
                (response) => {
                    if (response.data.status == 201) {
                        commit("UPDATE_USER_PROFILE", {
                            profile: response.data.updateData,
                        });
                    } else {
                        commit("ERROR", { error: response.data.error });
                    }
                },
                (err) => {
                    console.log(err);
                }
            );
        },
        CREATE_COMPANY: async function ({ commit }, payload) {
            const env = import.meta.env.VITE_APP_VERSION;
            commit("ERROR", []);
            await axios
                .post(env + "api/company-create", payload.model)
                .then((response) => {
                    if (response.data.status == 201) {
                        commit("SET_COMPANY", {
                            company: response.data.results,
                        });
                    } else {
                        commit("ERROR", { error: response.data.error });
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        DELETE_USER: async function ({ commit }, payload) {
            await axios
                .delete(`api/user/${payload.id}`)
                .then((response) => {
                    commit("DELETE_USER_PROFILE", response.data.id);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        PAGE_LINK: function ({ commit }, payload) {
            commit("PAGE_LINK", payload);
        },
        EDIT_USER_DATA: function ({ state,commit }, payload) {
            const data = state.userData.filter((data) => data.id == payload.id);
            commit('EDIT_USER',data)
        },
    },
    getters: {
        userData(state) {
            return state.userData;
        },
        companyData(state) {
            return state.companyData;
        },
        errors(state) {
            return state.errors;
        },
        getToken(state) {
            return state.token;
        },
        pageLink(state) {
            return state.pageLink;
        },
        editUserData(state) {
            return state.editUserData;
        },
    },
});
