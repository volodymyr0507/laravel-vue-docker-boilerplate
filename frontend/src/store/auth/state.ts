export type AuthState = {
  authenticated: boolean;
  user: Record<string, string>;
}

const state: AuthState = {
  authenticated: false,
  user: {},
};

export default state;
