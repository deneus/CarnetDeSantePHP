var Neon = require('@cityofzion/neon-js');

module.exports = function(app, db) {
    app.get('/register', (req, res) => {

        app.get('/login', (req, res) => {
        console.log('test');
    res.send('test');
    // You'll create your note here.
    //res.setHeader('Content-Type', 'application/json');
    //console.log(req);
    // console.log(res);
    // const account = Neon.wallet.isPrivateKey(req.key);
    //const object = new Object();
    //if(account == true){
    //  const account = Neon.wallet.Account(req.key);
    //object.address = account.address;
    //}
    //res.send(JSON.stringify(object));
});

        res.setHeader('Content-Type', 'application/json');
        const privateKey = Neon.wallet.generatePrivateKey();
        const wif = Neon.wallet.getWIFFromPrivateKey(privateKey);
        const register = new Object();
        register.privateKey = privateKey;
        register.wif = wif;
        res.send(JSON.stringify(register));
    });




};