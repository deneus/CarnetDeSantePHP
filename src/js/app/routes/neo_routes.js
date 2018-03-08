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

        var testnet = 'http://test5.cityofzion.io:8880';
        var account = new Neon.wallet.Account("PRIVATE KEY");

        var fromAddrScriptHash = Neon.wallet.getScriptHashFromAddress(account.address);
        console.log(account.address);



        const filledBalance = Neon.api.neonDB.getBalance('TestNet', account.address).then(balance => {
            console.log(balance);

            const invoke = Neon.sc.scriptParams = {
                scriptHash: hash,
                method: 'register',
                args: [
                    Neon.sc.ContractParam.string('maclef2'),
                    Neon.sc.ContractParam.string('mavaleur2')
                ]
            }

            const sb = new Neon.sc.ScriptBuilder();
            sb.emitAppCall(invoke.scriptHash, invoke.method, invoke.args, false);
            const vm_script = sb.str;

            const intents         = [
                {
                    assetId     : Neon.CONST.ASSET_ID.GAS,
                    value     : 2,
                    scriptHash: hash
                }
            ];
            const transaction     = Neon.create.invocationTx(balance, intents, vm_script, 1);

            let signed_tx                 = tx.signTransaction(transaction, 'PRIVATE KEY');
            let serialized_transaction     = tx.serializeTransaction(signed_tx)

            rpc.Query.sendRawTransaction(serialized_transaction).execute(testnet).then(data => console.log(data));
        })
        // const client = new Neon.rpc.RPCClient(testnet, '2.6.0');
        // client.invokeScript(sb.str).then(response =>{
        //     res.send(response);
        // }).catch(function (error) {
        //      console.error(error)
        //  });

        // const storages = client.getStorage(hash, Neon.u.str2hexstring('clef')).then(response =>{
        //         console.log(response);
        //     }).catch(function(error){
        //         console.log(error);
        //     });
        res.send('test');

    });




};