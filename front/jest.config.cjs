module.exports = {
    transform: {
        '^.+\\.[jt]sx?$': [
            'ts-jest',
            {
                diagnostics: {
                    ignoreCodes: [1343]
                },
                astTransformers: {
                    before: [
                        {
                            path: 'node_modules/ts-jest-mock-import-meta',  // or, alternatively, 'ts-jest-mock-import-meta' directly, without node_modules.
                            options: {
                                metaObjectReplacement: {
                                    env: {
                                        VITE_FAKER_REST: 1,
                                    }
                                }
                            }
                        }
                    ]
                }
            }
        ]
    },
    testEnvironment: 'node',
    testRegex: '/tests/.*\\.(test|spec)?\\.(ts|tsx)$',
    moduleFileExtensions: ['ts', 'tsx', 'js', 'jsx', 'json', 'node'],
    //transformIgnorePatterns: ['/node_modules/'],
    //collectCoverage: true,
    collectCoverageFrom: [
        'src/**/*.{js,ts}',
    ],
    coverageDirectory: '<rootDir>/coverage',
    coverageThreshold: {
        global: {
            //branches: 80,
            //functions: 80,
            lines: 80,
            //statements: -10,
        },
    },
};
