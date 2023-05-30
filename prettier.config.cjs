/** @type {import('prettier').Config} */
module.exports = {
    arrowParens: 'avoid',
    singleQuote: true,
    plugins: ['prettier-plugin-blade', 'prettier-plugin-tailwindcss'],
    pluginSearchDirs: false,
    overrides: [
        {
            files: ['*.blade.php'],
            options: {
                parser: 'blade',
            },
        },
    ],
};
