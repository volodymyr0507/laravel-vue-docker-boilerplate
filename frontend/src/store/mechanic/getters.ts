import { MechanicState } from './state';

export const mechanicProfile = (
  state: MechanicState,
): Record<string, number|string|object> => state.mechanicProfile;

export const services = (
  state: MechanicState,
): Array<Record<string, number|string|object>> => state.services;

export const servicesMeta = (
  state: MechanicState,
): Record<string, number|string|object> => state.servicesMeta;
