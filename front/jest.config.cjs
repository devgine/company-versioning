module.exports = {
    transform: {'^.+\\.[jt]sx?$': 'ts-jest'},
    testEnvironment: 'node',
    testRegex: '/tests/.*\\.(test|spec)?\\.(ts|tsx)$',
    moduleFileExtensions: ['ts', 'tsx', 'js', 'jsx', 'json', 'node'],
    //transformIgnorePatterns: ['/node_modules/'],
    //collectCoverage: true,
    // todo: fix coverage App.tsx and dataProvider
    collectCoverageFrom: [
        'src/**/*.{js,jsx,ts,tsx}',
        '!src/common/dataProvider.tsx',
        '!src/App.{js,jsx,ts,tsx}'
    ],
    coverageDirectory: '<rootDir>/coverage',
    // todo: activate this coverage threshold lines
    coverageThreshold: {
        global: {
            //branches: 80,
            //functions: 80,
            lines: 80,
            //statements: -10,
        },
    },
};
