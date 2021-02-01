export type MechanicState = {
  mechanicProfile: Record<string, number|string|object>;
  services: Array<Record<string, number|string|object>>;
  servicesMeta: Record<string, number|string|object>;
}

const state: MechanicState = {
  mechanicProfile: {},
  services: [],
  servicesMeta: {},
};

export default state;
