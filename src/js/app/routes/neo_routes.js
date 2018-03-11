var Neon = require('@cityofzion/neon-js');
var neonjs = Neon.default;
const wif = 'KxTroEL5Ztq4oZDPhHeZq58kUdSmP3DRBwdK2L4hH78X6rteJmoc';

module.exports = function (app, db) {
    app.post('/login', (req, res) => {
        res.setHeader('Content-Type', 'application/json');
    const account = Neon.wallet.isWIF(req.body.key);
    const object = new Object();
    if (account == true) {
        const account = new Neon.wallet.Account(req.body.key);
        object.address = account.address;
        object.wif = req.body.key;
    }
    res.send(JSON.stringify(object));
    });

    app.get('/register', (req, res) => {
            res.setHeader('Content-Type', 'application/json');
        const privateKey = Neon.wallet.generatePrivateKey();
        const wifU = Neon.wallet.getWIFFromPrivateKey(privateKey);
        const register = new Object();
        register.privateKey = privateKey;
        register.wif = wifU;
        const account = new Neon.wallet.Account(wifU);
        register.address = account.address;
        res.send(JSON.stringify(register));
    }) ;

    app.post('/registerMaster', (req, res) => {

        res.setHeader('Content-Type', 'application/json');
    const hash = req.body.hash;
    const NEOaddress = req.body.NEOaddress;
    const valueIPFS = req.body.ipfsMaster;
    const method = req.body.methodContract;

    const testnet = 'http://test4.cityofzion.io:8880';
    const account = new Neon.wallet.Account(wif);
    const privateKey = Neon.wallet.getPrivateKeyFromWIF(wif);


    const filledBalance = Neon.api.neonDB.getBalance('TestNet', account.address).then(balance => {

            const invoke = Neon.sc.scriptParams = {
                scriptHash: hash,
                method: 'register',
                args: [
                    Neon.sc.ContractParam.string(NEOaddress),
                    Neon.sc.ContractParam.string(valueIPFS)
                ]
            }

            const sb = new Neon.sc.ScriptBuilder();
    sb.emitAppCall(invoke.scriptHash, invoke.method, invoke.args, false);
    const vm_script = sb.str;

    const intents = [
        {
            assetId: Neon.CONST.ASSET_ID.GAS,
            value: 0.001,
            scriptHash: hash
        }
    ];
    const transaction = Neon.default.create.invocationTx(balance, intents, vm_script, 1);

    const signed_tx = Neon.tx.signTransaction(transaction, privateKey);
    const serialized_transaction = Neon.tx.serializeTransaction(signed_tx);

    Neon.rpc.Query.sendRawTransaction(serialized_transaction).execute(testnet).then(data => {res.send(JSON.stringify(data))
}) ;
   // const client = new Neon.rpc.RPCClient(testnet, '2.6.0');
    // client.invokeScript(sb.str).then(response =>{
    //     res.send(response);
    // }).catch(function (error) {
    //      console.error(error)
    //  });

})
    ;

})
    ;

    app.post('/stopDelegate', (req, res) => {

        res.setHeader('Content-Type', 'application/json');
    const hash = req.body.hash;
    const NEOaddress = req.body.NEOaddress;
    const timestamp = req.body.timestamp;


    const testnet = 'http://test4.cityofzion.io:8880';
    const account = new Neon.wallet.Account(wif);
    const privateKey = Neon.wallet.getPrivateKeyFromWIF(wif);
    const publicKey = Neon.wallet.getPublicKeyFromPrivateKey(privateKey);

    console.log(publicKey);

    const filledBalance = Neon.api.neonDB.getBalance('TestNet', account.address).then(balance => {

            const invoke = Neon.sc.scriptParams = {
                scriptHash: hash,
                method: 'stopDelegate',
                args: [
                    Neon.sc.ContractParam.string(NEOaddress)
                ]
            }

            const sb = new Neon.sc.ScriptBuilder();
    sb.emitAppCall(invoke.scriptHash, invoke.method, invoke.args, false);
    const vm_script = sb.str;

    const intents = [
        {
            assetId: Neon.CONST.ASSET_ID.GAS,
            value: 0.001,
            scriptHash: hash
        }
    ];
    const transaction = Neon.default.create.invocationTx(balance, intents, vm_script, 1);

    const signed_tx = Neon.tx.signTransaction(transaction, privateKey);
    const serialized_transaction = Neon.tx.serializeTransaction(signed_tx);

    Neon.rpc.Query.sendRawTransaction(serialized_transaction).execute(testnet).then(data => {res.send(JSON.stringify(data))
}) ;
    const client = new Neon.rpc.RPCClient(testnet, '2.6.0');
    // client.invokeScript(sb.str).then(response =>{
    //     res.send(response);
    // }).catch(function (error) {
    //      console.error(error)
    //  });

    /*const storage = client.getStorage(hash, Neon.u.str2hexstring('maclef2')).then(response =>{
     console.log(response);
     }).catch(function(error){
     console.log(error);
     });*/

})
    ;

})
    ;


    app.post('/getMaster', (req, res) => {
        res.setHeader('Content-Type', 'application/json');
    const address = req.body.NEOaddress;
    const hash = req.body.hash;
    const testnet = 'http://test4.cityofzion.io:8880';
    const client = new Neon.rpc.RPCClient(testnet, '2.6.0');
    const storage = client.getStorage(hash, Neon.u.str2hexstring(address)).then(response =>{
            res.send(JSON.stringify(response));
        }).catch(function(error){
        console.log(error);
     });
    });


};