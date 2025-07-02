import { defineStore } from "pinia";
import axios from "axios";

const API_URL = "http://localhost:8000/api";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("token") || "",
    user: null as any,
    loading: false,
    error: "",
  }),
  actions: {
    async login(email: string, password: string) {
      this.loading = true;
      this.error = "";
      try {
        const res = await axios.post(`${API_URL}/login`, { email, password });
        this.token = res.data.token;
        localStorage.setItem("token", this.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
        this.error = "";
      } catch (e: any) {
        this.error = "Login failed";
      } finally {
        this.loading = false;
      }
    },
    async register(name: string, email: string, password: string) {
      this.loading = true;
      this.error = "";
      try {
        const res = await axios.post(`${API_URL}/register`, {
          name,
          email,
          password,
        });
        this.token = res.data.token;
        localStorage.setItem("token", this.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
        this.error = "";
      } catch (e: any) {
        this.error = "Registration failed";
      } finally {
        this.loading = false;
      }
    },
    logout() {
      this.token = "";
      localStorage.removeItem("token");
      delete axios.defaults.headers.common["Authorization"];
    },
    // Persistent login: call this on app start
    init() {
      const token = localStorage.getItem("token");
      if (token) {
        this.token = token;
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      }
    },
  },
});
