import { Commit } from 'vuex';
import axios from 'axios';
import * as mutationTypes from './mutationTypes';

export const getFulfilledJobs = async (
  { commit }: { commit: Commit },
  { page = 1 }: { fulfilled: boolean; page: number },
) => {
  const { data } = await axios.get(`api/v1/drivers/jobs/fulfilled?page=${page}&fulfilled=true`);

  commit(mutationTypes.SET_FULFILLED_JOBS, data.data);
  commit(mutationTypes.SET_FULFILLED_JOBS_META, data.meta);
};

export const getUnfulfilledJobs = async (
  { commit }: { commit: Commit },
  { page = 1 }: { fulfilled: boolean; page: number },
) => {
  const { data } = await axios.get(`api/v1/drivers/jobs/fulfilled?page=${page}&fulfilled=false`);

  commit(mutationTypes.SET_UNFULFILLED_JOBS, data.data);
  commit(mutationTypes.SET_UNFULFILLED_JOBS_META, data.meta);
};
