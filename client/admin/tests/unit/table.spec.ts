import Vue from "vue";
import { mount } from "@vue/test-utils";
import Table from "@/components/Table.vue";
import store from "@/store/store";
import {
  createLocalVue,
  createApolloTestProvider,
  makeGetInitialState,
} from "./helper";
import "isomorphic-fetch";

const localVue = createLocalVue();
const apolloProvider = createApolloTestProvider();
const getInitialState = makeGetInitialState(store);

describe("Login", () => {
  beforeEach(() => {
    store.replaceState(getInitialState());
  });

  test("should render content correctly", () => {
    const wrapper = mount(Table, {
      store,
      localVue,
      apolloProvider,
    });
    // expect(wrapper.find('label[for="email"]').text()).toEqual("Username");
    expect(wrapper.findComponent(Table).exists()).toBe(true);
  });

  // test("can handle successful login when submit button is clicked", async function (done) {
  //   const elem = document.createElement("div");
  //   if (document.body) {
  //     document.body.appendChild(elem);
  //   }

  //   const wrapper = mount(Login, {
  //     store,
  //     localVue,
  //     apolloProvider,
  //     attachTo: elem,
  //   });
  //   const email = wrapper.find("#email");
  //   const passw = wrapper.find("#password");
  //   email.setValue("luke@example.com");
  //   passw.setValue("password");
  //   wrapper.find('button[type="submit"]').trigger("click");
  //   await Vue.nextTick();
  //   // @ts-ignore
  //   expect(store.state.session.authStatus).toEqual(true);
  //   wrapper.destroy();
  //   done();
  // });

  // test("can handle failed login when submit button is clicked", async function (done) {
  //   const elem = document.createElement("div");
  //   if (document.body) {
  //     document.body.appendChild(elem);
  //   }

  //   const wrapper = mount(Login, {
  //     store,
  //     localVue,
  //     apolloProvider,
  //     attachTo: elem,
  //   });
  //   const email = wrapper.find("#email");
  //   const passw = wrapper.find("#password");
  //   email.setValue("");
  //   passw.setValue("");
  //   wrapper.find('button[type="submit"]').trigger("click");

  //   await Vue.nextTick();
  //   // @ts-ignore
  //   expect(store.state.session.authStatus).toEqual(false);
  //   wrapper.destroy();
  //   done();
  // });
});
