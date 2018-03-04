
const neoRoutes = require('./neo_routes');
module.exports = function(app, db) {
    neoRoutes(app, db);
    // Other route groups could go here, in the future
};