import { User } from "@/types/Models";
import { UserStoreRequest, UserSigninRequest } from "@/types/Requests";
import router from "@/router";

export type State = {
  user: null | User;
};

const state = {
  user: null,
};

const getters = {
  check: (state: State): boolean => !!state.user,
  userName: (state: State): string => (state.user ? state.user.name : ""),
};

const mutations = {
  setUser(state: State, user: any): void {
    state.user = user;
  },
};

const actions = {
  async signup(context: any, data: UserStoreRequest) {
    await window.axios
      .post("/api/register", data)
      .then((response: any) => {
        context.commit("setUser", response.data);
        context.commit(
          "error/setErrors",
          {
            messages: null,
            status: null,
          },
          { root: true }
        );
        router.push("/");
      })
      .catch((err) => {
        if (err.response.status === 422) {
          context.commit(
            "error/setErrors",
            {
              messages: err.response.data.errors,
              status: err.response.status,
            },
            { root: true }
          );
        }
      });
  },

  async signin(context: any, data: UserSigninRequest) {
    await window.axios
      .post("/api/login", data)
      .then((response: any) => {
        context.commit("setUser", response.data);
        context.commit(
          "error/setErrors",
          {
            messages: null,
            status: null,
          },
          { root: true }
        );
        router.push("/");
      })
      .catch((err) => {
        if (err.response.status === 422) {
          context.commit(
            "error/setErrors",
            {
              messages: err.response.data.errors,
              status: err.response.status,
            },
            { root: true }
          );
        } else {
          context.commit("error/setErrors", {
            messages: err.response,
            status: err.response.status,
          });
        }
      });
  },

  async signout(context: any) {
    await window.axios
      .post("/api/logout")
      .then((response: any) => {
        context.commit("setUser", null);
        router.push("/signin");
      })
      .catch((err) => {
        context.commit(
          "error/setErrors",
          {
            messages: err.response,
            status: err.response.status,
          },
          { root: true }
        );
      });
  },

  async currentUser(context: any) {
    await window.axios
      .get("/api/currentUser")
      .then((response: any) => {
        const user = response.data || null;
        context.commit("setUser", user);
      })
      .catch((err: any) => {
        context.commit(
          "error/setErrors",
          {
            messages: err.response.data.errors,
            status: err.response.status,
          },
          { root: true }
        );
      });
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
