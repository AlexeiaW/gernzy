import { MutationPayload } from "vuex";

// Store
export interface RootState {
  session: SessionState;
}

export interface SessionState {
  email: string | null;
  token: string | null;
  user: {} | null;
  authStatus: Boolean;
}

export interface HistoryState {
  history: MutationPayload[];
}

export interface PluginOptions {
  persist?: boolean;
}
