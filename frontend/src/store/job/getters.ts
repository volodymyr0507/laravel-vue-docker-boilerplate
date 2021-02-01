import { JobState } from './state';

export const fulfilledJobs = (
  state: JobState,
): Array<Record<string, number|string|object>> => state.fulfilledJobs;

export const unfulfilledJobs = (
  state: JobState,
): Array<Record<string, number|string|object>> => state.unfulfilledJobs;

export const fulfilledJobsMeta = (
  state: JobState,
): Record<string, number|string|object> => state.fulfilledJobsMeta;

export const unfulfilledJobsMeta = (
  state: JobState,
): Record<string, number|string|object> => state.unfulfilledJobsMeta;
