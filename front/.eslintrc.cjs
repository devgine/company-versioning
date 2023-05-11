/* eslint-env node */
module.exports = {
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        //'plugin:@typescript-eslint/recommended-requiring-type-checking',
    ],
    parser: '@typescript-eslint/parser',
    parserOptions: {
        project: ['./tsconfig.eslint.json'],
    },
    plugins: ['@typescript-eslint'],
    root: true,
    ignorePatterns: [
        'tests',
        'src/frontend/generated/*',
    ]
};
