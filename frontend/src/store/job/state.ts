export type JobState = {
  fulfilledJobs: Array<Record<string, number|string|object>>;
  unfulfilledJobs: Array<Record<string, number|string|object>>;
  fulfilledJobsMeta: Record<string, number|string|object>;
  unfulfilledJobsMeta: Record<string, number|string|object>;
};

const state: JobState = {
  fulfilledJobs: [],
  unfulfilledJobs: [],
  fulfilledJobsMeta: {},
  unfulfilledJobsMeta: {},
};

export default state;
