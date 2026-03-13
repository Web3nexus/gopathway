import path from "path"
import react from "@vitejs/plugin-react"
import { defineConfig } from "vite"

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },
  build: {
    outDir: "../backend/public",
    emptyOutDir: false,
  },
  server: {
    proxy: {
      "/api": {
        target: "https://gopathwayweb.test",
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: "localhost",
      },
      "/sanctum": {
        target: "https://gopathwayweb.test",
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: "localhost",
      },
    },
  },
})
