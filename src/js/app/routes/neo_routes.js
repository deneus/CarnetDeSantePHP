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

    const invoke = Neon.sc.scriptParams = {
        scriptHash: hash,
        method: 'register',
        args: [
            Neon.sc.ContractParam.string('maclef'),
            Neon.sc.ContractParam.string('mavaleur')
        ]
    }

    const sb = new Neon.sc.ScriptBuilder();
    sb.emitAppCall(invoke.scriptHash, invoke.method, invoke.args, false);

    console.log(sb.str);
    //const param1 = Neon.default.create.contractParam('String', 'register')
    // This is a convenient way to convert an address to a reversed scriptHash that smart contracts use.
    //const param2 = Neon.default.create.contractParam('Array', ['clef', 'valeur'])

    //console.log(param1);
    //console.log(param2);


        const client = new Neon.rpc.RPCClient('http://test5.cityofzion.io:8880', '2.6.0');
        client.invokeScript(sb.str).then(response =>{
            res.send(response);
        }).catch(function (error) {
             console.error(error)
         });

        const storages = client.getStorage(hash, Neon.u.str2hexstring('clef')).then(response =>{
                console.log(response);
            }).catch(function(error){
                console.log(error);
            });

    });




};