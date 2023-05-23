/** @type {import('prettier').Config} */
module.exports = {
    arrowParens: 'avoid',
    singleQuote: true,
    plugins: ['@shufo/prettier-plugin-blade'],
    overrides: [
        {
            files: ['*.blade.php'],
            options: {
                parser: 'blade',
            },
        },
    ],
};
