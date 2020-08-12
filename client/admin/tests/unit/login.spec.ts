import Vue from "vue";
import { mount } from "@vue/test-utils";
import Login from "../../src/components/login.vue";
import createStore from "../../src/store/store";
import {
  createLocalVue,
  createApolloTestProvider,
  makeGetInitialState,
} from "./helper";

const localVue = createLocalVue();
const store = createStore();
const apolloProvider = createApolloTestProvider();
const getInitialState = makeGetInitialState(store);

describe("Login", () => {
  beforeEach(() => {
    store.replaceState(getInitialState());
  });

  test("should render content correctly", () => {
    const wrapper = mount(Login, {
      apolloProvider,
    });
    expect(wrapper.find('label[for="email"]').text()).toEqual("Username");
  });

  test("can handle successful login when submit button is clicked", async function (done) {
    const elem = document.createElement("div");
    if (document.body) {
      document.body.appendChild(elem);
    }

    const wrapper = mount(Login, {
      store,
      localVue,
      apolloProvider,
      attachTo: elem,
    });
    const email = wrapper.find("#email");
    const passw = wrapper.find("#password");
    email.setValue("luke@example.com");
    passw.setValue("password");
    wrapper.find('button[type="submit"]').trigger("click");

    await Vue.nextTick();
    // @ts-ignore
    expect(store.state.session.has_active_session).toEqual(true);
    wrapper.destroy();
    done();
  });

  test("can handle failed login when submit button is clicked", async function (done) {
    const elem = document.createElement("div");
    if (document.body) {
      document.body.appendChild(elem);
    }

    const wrapper = mount(Login, {
      store,
      localVue,
      apolloProvider,
      attachTo: elem,
    });
    const email = wrapper.find("#email");
    const passw = wrapper.find("#password");
    email.setValue("");
    passw.setValue("");
    wrapper.find('button[type="submit"]').trigger("click");

    await Vue.nextTick();
    // @ts-ignore
    expect(store.state.session.has_active_session).toEqual(false);
    wrapper.destroy();
    done();
  });
});
