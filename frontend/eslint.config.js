import vue from "eslint-plugin-vue";
import tsParser from "@typescript-eslint/parser";
import tsPlugin from "@typescript-eslint/eslint-plugin";
import prettierPlugin from "eslint-plugin-prettier";

export default [{
    files: ["**/*.ts", "**/*.vue"],
    languageOptions: {
        parser: tsParser,
        parserOptions: {
            ecmaVersion: 2020,
            sourceType: "module",
        },
    },
    plugins: {
        "@typescript-eslint": tsPlugin,
        vue: vue,
        prettier: prettierPlugin,
    },
    rules: {
        // Vue 3 recommended
        "vue/no-unused-vars": "warn",
        "vue/no-multiple-template-root": "off",
        "vue/no-v-html": "off",
        // TypeScript recommended
        "@typescript-eslint/no-unused-vars": "warn",
        "@typescript-eslint/explicit-module-boundary-types": "off",
        // Prettier
        "prettier/prettier": "warn",
    },
    processor: vue.processors[".vue"],
}, ];