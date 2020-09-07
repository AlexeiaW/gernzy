import { GetterTree, MutationTree, ActionTree, Module } from "vuex";
import { RootState, SessionState } from "./types";
import { apolloClient, onLogin, onLogout } from "@/vue-apollo";
import { LOGGED_IN_USER } from "@/graphql/queries";
import { LOGIN_USER, REGISTER_USER, LOGOUT_USER } from "@/graphql/mutations";

type SessionGetter = GetterTree<SessionState, RootState>;

const AUTH_TOKEN = "apollo-token";
const AUTH_USER = "user-login-data";

export const state: SessionState = {
  email: null,
  token: null,
  user: { is_admin: 0 },
  authStatus: false,
};

export const getters: SessionGetter = {
  isAuthenticated: (state) => !!state.token,
  authStatus: (state) => state.authStatus,
  user: (state) => state.user,
  isAdmin: (state) => state.user.is_admin,
};

export const mutations: MutationTree<SessionState> = {
  SET_TOKEN(state, token) {
    state.token = token;
  },
  LOGIN_USER(state, user) {
    state.authStatus = true;
    state.user = { ...user };
  },
  LOGOUT_USER(state) {
    state.authStatus = false;
    state.token = "";
    state.user = { is_admin: 0 };

    // Remove the user from local storage
    localStorage.removeItem(AUTH_USER);
  },
};

export const actions: ActionTree<SessionState, RootState> = {
  async register({ commit, dispatch }, authDetails) {
    try {
      const { data } = await apolloClient.mutate({
        mutation: REGISTER_USER,
        variables: { ...authDetails },
      });
      const token = data.createUser.token;
      commit("SET_TOKEN", token);
      localStorage.setItem(AUTH_TOKEN, token);
      dispatch("setUser");
    } catch (e) {
      console.log(e);
    }
  },
  async logIn({ commit, dispatch }, authDetails) {
    try {
      const { data } = await apolloClient.mutate({
        mutation: LOGIN_USER,
        variables: { ...authDetails },
      });

      const token = data.logIn.token;
      const user = data.logIn.user;

      commit("SET_TOKEN", token);

      // This also commits the token to local storage
      await onLogin(apolloClient, token);

      dispatch("setUser", user);
    } catch (e) {
      console.log("THE ERROR" + e);
    }
  },
  async setUser({ commit }, user) {
    try {
      commit("LOGIN_USER", user);
      localStorage.setItem(AUTH_USER, JSON.stringify(user));
    } catch (error) {
      console.log(error);
    }
  },
  async logOut({ commit }) {
    commit("LOGOUT_USER");

    // This also removes the token from local storage
    await onLogout(apolloClient);
  },
  async checkLoggedIn({ commit }) {
    if (
      localStorage.getItem(AUTH_TOKEN) != null &&
      localStorage.getItem(AUTH_USER) != null
    ) {
      let token = localStorage.getItem(AUTH_TOKEN) || "";
      let user = localStorage.getItem(AUTH_USER) || "";
      commit("SET_TOKEN", token);
      commit("LOGIN_USER", JSON.parse(user));
    }
  },
};

export const session: Module<any, any> = {
  state,
  getters,
  mutations,
  actions,
  namespaced: true,
};
