var Neon = require('@cityofzion/neon-js');
var neonjs = Neon.default

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
        const hash = req.body.hash;
        const key =  req.body.key;

        const props = {
            scriptHash: hash// Scripthash for the contract
        }

        const script = Neon.default.create.script(props);

        const client = new Neon.rpc.RPCClient('testnet', '2.6.0');
        //const storage = rpc.getStorage(hash, 'Key'); //TODO: Debug the call
        const sb = new Neon.sc.ScriptBuilder();
        // Build script to call 'name' from contract at 5b7074e873973a6ed3708862f219a6fbf4d1c411
        sb.emitAppCall(hash);

        console.log(sb.str);
        console.log(script);


        // Test the script with invokescript
        console.log('test');
        client.invokeScript(hash).then(res =>{
            console.log(res);
        });
    });




};