/* eslint-env node */
module.exports = {
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
    ],
    parser: '@typescript-eslint/parser',
    parserOptions: { project: ['./tsconfig.eslint.json'] },
    plugins: ['@typescript-eslint'],
    root: true,
    ignorePatterns: [
        'src/**/*.test.ts',
        'src/frontend/generated/*',
    ]
};
