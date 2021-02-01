import { Commit, Dispatch } from 'vuex';
import axios, { AxiosResponse } from 'axios';
import * as mutationTypes from './mutationTypes';

export const register = async (
  { dispatch }: { dispatch: Dispatch },
  details: Record<string, string>) => {
  await axios.get('sanctum/csrf-cookie');
  await axios.post('sanctum/register', details);

  return dispatch('user');
};

export const login = async (
  { dispatch }: { dispatch: Dispatch },
  credentials: Record<string, string>) => {
  await axios.get('sanctum/csrf-cookie');
  await axios.post('sanctum/login', credentials);

  return dispatch('user');
};

export const logout = async ({ commit }: { commit: Commit }) => {
  await axios.post('sanctum/logout');

  commit(mutationTypes.SET_AUTHENTICATED, false);
  commit(mutationTypes.SET_USER, {});
};

export const user = (
  { commit }: { commit: Commit },
) => axios.get('api/v1/user')
  .then((response: AxiosResponse) => {
    commit(mutationTypes.SET_AUTHENTICATED, true);
    commit(mutationTypes.SET_USER, response.data.data);
  }).catch(() => {
    commit(mutationTypes.SET_AUTHENTICATED, false);
    commit(mutationTypes.SET_USER, {});
  });
