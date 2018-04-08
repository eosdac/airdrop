var fs = require('fs');
var Tx = require('ethereumjs-tx');
const Web3 = require('web3');
var BigNumber = require('bignumber.js');
const web3 = new Web3('https://mainnet.infura.io/<api-key>');
var abiArray = <eosDAC contract abi>
const privateKey = <private key>;
const contractAddress = <eosDAC contract address>;
var myAddress = <airdrop sender>;


async function sender( enonce, destAddress, ammo) { 	


  var transferAmount = new BigNumber(ammo * 1000000000000000000);
     console.log(`transfer ammo: ${transferAmount}`);
 
  let count = 0; 
  let balance = 0; 
  // Determine the nonce

      count = await web3.eth.getTransactionCount(myAddress);
      console.log(`num transactions so far: ${count}`);
 
  count = count + enonce;

   var contract = new web3.eth.Contract(abiArray, contractAddress, { from: myAddress });


  	balance = await contract.methods.balanceOf(myAddress).call();
  	console.log(`Balance before send: ${balance}`);

  var rawTransaction = {
      "from": myAddress,
      "nonce": "0x" + count.toString(16),
      "gasPrice": "0x003B9ACA00",
      "gasLimit": "0x250CA",
      "to": contractAddress,
      "value": "0x0",
      "data": contract.methods.transfer(destAddress, transferAmount).encodeABI(),
      "chainId": 0x01
  };

  var privKey = new Buffer(privateKey, 'hex');
  var tx = new Tx(rawTransaction);
  tx.sign(privKey);
  var serializedTx = tx.serialize();

  console.log(`Attempting to send signed tx:  ${serializedTx.toString('hex')}`);
    var receipt = await web3.eth.sendSignedTransaction('0x' + serializedTx.toString('hex'));
    logger.debug(`Receipt info:  ${JSON.stringify(receipt, null, '\t')}`);
    //console.log('Complete - ${destAddress} ${transferAmount}');

}


var log4js = require('log4js');
log4js.configure({
  appenders: { trans: { type: 'file', filename: 'logs/trans1_80.log' } },
  categories: { default: { appenders: ['trans'], level: 'debug' } }
});
 
const logger = log4js.getLogger('trans');

sender(0, 'address0', amount0 );
sender(1, 'address1', amount1 );
sender(2, 'address2', amount2 );
.
.
.
sender(n, 'addressn', amountn );

