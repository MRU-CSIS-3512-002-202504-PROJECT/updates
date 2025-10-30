# Project Updates

Here be various files meant to update your Project. Because things change.

## Changelog

### 2025-10-30
- Added files and instructions for getting ESLint & Prettier into a project workspace.

### 2025-10-07
- Coretools now has `redirect()` helper method.
- Router now does simple authorization.

### 2025-09-22
- Updated compose.yml to have container names that won't conflict with lab/lec ones.

### 2025-09-21
- Added config/php/php.ini
    - Introduced key/value to disables ability to use short php tags (`<? ?>).
- Added www/core
    - Router and DatabaseHelper classes added.
- Added www/database
    - config.php and DatabaseQueries class.


## Getting ESLint and Prettier Working in Your Project

If you want to use ESLint and Prettier while coding in VS Code on your project, take the following steps:

1. Install [Node.js](https://nodejs.org/en/download) on your LOCAL machine.
2. Copy the `package.json`, .eslintrc.json`, and `.prettierrc` files from this repository into the root of your project directory.
3. Run `npm install` in a VS Code terminal. (You should only need to do this once for any given machine.)

If you run into any issues, pleas let me know.



