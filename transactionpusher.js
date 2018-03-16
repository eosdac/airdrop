
//for web3@0.20.0

const bn = require('bignumber.js')
var fs = require('fs');
const web3x = require('web3')
var Tx = require('ethereumjs-tx');

web3 = new web3x(new web3x.providers.HttpProvider("http://localhost:8545"));
 
var count = web3.eth.getTransactionCount("sender address");
var abiArray = JSON.parse(fs.readFileSync('eosDAC.json', 'utf-8'));
var contractAddress = "0x7e9e431a0B8c4D532C745B1043c7FA29a48D4fBa";  //eosDAC contract
var contract = web3.eth.contract(abiArray).at(contractAddress);
var nance = web3.toHex(count);
var rawTransaction = {
    "from": "sender address",
    "nonce": nance,
    "gasPrice": "0x03B9ACA00",
    "gasLimit": "0x15F90",
    "to": contractAddress,
    "value": "0x0",
    "data": contract.transfer.getData("to address", amount, {from: "sender address"}),
    "chainId": 0x01
};

var privKey = new Buffer('private key', 'hex');
var tx = new Tx(rawTransaction);

tx.sign(privKey);
var serializedTx = tx.serialize();

web3.eth.sendRawTransaction('0x' + serializedTx.toString('hex'), function(err, hash) {
    if (!err)
        console.log(hash);
    else
        console.log(err);
});



