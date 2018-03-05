var Neon = require('@cityofzion/neon-js');

module.exports = function(app, db) {
    app.post('/login', (req, res) => {
            res.setHeader('Content-Type', 'application/json');
             const account = Neon.wallet.isWIF(req.body.key);
            const object = new Object();
            if(account == true){
                const account = new Neon.wallet.Account(req.body.key);
                object.address = account.address;
                object.wif = account.wif;
            }
            res.send(JSON.stringify(object));
        });

    app.get('/register', (req, res) => {
        res.setHeader('Content-Type', 'application/json');
        const privateKey = Neon.wallet.generatePrivateKey();
        const wif = Neon.wallet.getWIFFromPrivateKey(privateKey);
        const register = new Object();
        register.privateKey = privateKey;
        register.wif = wif;
        res.send(JSON.stringify(register));
    });

    app.post('/getMaster', (req, res) => {
        res.setHeader('Content-Type', 'application/json');
        const pubAddress = req.body.address;
        const version = req.body.version;
        const url = req.body.url;
        const hash = req.body.hash;
       // const rpc = new Neon.rpc.RPCClient('testnet');
        //const storage = rpc.getStorage(hash, 'Key');
        console.log(storage);

       // res.send(JSON.stringify(storage));
    });




};