const { getConfig } = require('./helpers');
const { connect } = require('amqplib');
const axios = require('axios');
const qs = require('qs');
const _ = require('lodash');
const jsdom = require('jsdom');
const {stringify} = require("qs");

// Testing WebSockets

const path = require('path');
const express = require('express');
const SocketServer = require('ws').Server;
const app = express();

app.set('port', process.env.PORT || 3000);

const server = app.listen(app.get('port'), ()=>{
  console.log('Server on Port' + app.get('port'));
})

const wss = new SocketServer({server})

wss.on('connection', function(ws){
  console.log('Connection Stablished From Server');

  wss.on('change', (data) => {
    console.log('Change Detected from Inside')
    ws.send(data);
  })

  
})


const client_id = 'kdqoHWRZ1YB5M99QVrtc9p4v2kxSct89';
const client_secret = 'IqHYGkvrsN7eFfRe';


let baseUrl = 'https://prod.hs1api.com';

const args = process.argv.slice(2);

if(args.length > 0 && args[0] === 'qa'){
  console.log('Running in qa');
  baseUrl = 'https://test.hs1api.com';
}

const apiTokenUrl = `${baseUrl}/oauth/client_credential/accesstoken`;


(async () => {
  try {

   // console.log('running with client_id', getConfig('client_id', client_id));

    // First get a normal api token just like you're using the normal Ascend Rest API
    const {data: {access_token}} = await axios({
        method: 'POST',
        headers: { 'content-type': 'application/x-www-form-urlencoded' },
        data: qs.stringify({
          client_id: getConfig('client_id', client_id),
          client_secret: getConfig('client_secret', client_secret)
        }),
        url: apiTokenUrl,
        params: {
          'grant_type': 'client_credentials'
        }
      });

    console.log(`The api access_token is ${access_token}`);

    // Put the access token and organization in the axios default headers so we don't need to keep sending it manually for all requests
    axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
    
    // Now use the existing rest api to fetch a new streaming api url
    // (there is another endoint to get a secure web sockets stomp protocol stream api connection)
    const {data: {url: streamApiUrl, exchanges, queues, routingKeys, user}} = await axios.get(`${baseUrl}/ascend-streaming-api/url`);

    // streamApiUrl contains all of the authentication and where the location of the stream api is
    
    // exchanges and queues are the rabbitmq exchange and queue patterns your client_id has access to
    // it's how we make sure client only access data they should be able to
    console.log(`Exchanges: ${exchanges}`);
    console.log(`Queues: ${queues}`);       
    console.log(`Stream api url: ${streamApiUrl}`);

    // If you look at the streamApiUrl, you will notice that the user is actually a JWT that you can look at in jwt.io
    // It contains informatio about what you have access to in the Stream API - although much of that information is exposed
    // like the exchanges etc. shown above for convenience - so clients don't need to parse the JWT user manually
    


    // How to set up a queue to listen for creates, updates or deletes in the api

    // This example uses amqplib but other options exist for NodeJS and there are amqp libraries for most languages
    // Other libraries for other languages will do things a little differently but all share the same basic concepts of 
    // exchanges, queues, bindings etc.
    // https://www.rabbitmq.com/devtools.html
    
    // First create a connection
    const streamApiConnection = await connect(streamApiUrl);

    // Register for any connection errors
    streamApiConnection.on('error', async (err) => {
      console.error('Error in the main stream api connection: ', err);
      // Probably want some logging and a paused retry to re-establish the connection after a minute
    });

    // Next create a channel
    const channel = await streamApiConnection.createChannel();

    // Now bind a queue that you can listen to
    // You should add a "." with something afterwards to name your queues
    // The names should be reused / consistent through your applications
    // You can only create a single queue with this name - queue names are globally unique (for your account)
    //const queueName = `${queues}.testqueue-${Math.random().toString().substring(3, 10)}`;
    const queueName = `${queues}.testqueue-${Math.random().toString().substring(3, 10)}`;

    // Assert the queue into existence
    // The queue in the example below will be deleted as soon as the process stops running
    // If you set durable: true, autoDelete: false, exclusive: false, the queue will be retained in the Ascend rabbitmq cluster
    // even after the process or connections stopped.  Messages will continue to accumulate in the queue until
    // you start consuming them again. Ascend will purge these queues after a period of time - otherwise we would run out of disk space.  
    // Be very careful how many unique queues (by the queueName) you create that are durable and make sure you responsibly listen to those queues
    // so you don't end up clogging up the rabbit cluster
    await channel.assertQueue(queueName, {durable: false, autoDelete: true, exclusive: true});


    // Bind the queue to to your exchange - otherwise no data will flow to your queue
    // Best practice is to only bind to routing keys that you will actually listen to - otherwise you will see quite a bit of
    // data flowing to your queue that you might throw away

    // RabbitMQ topic routing keys follow the pattern: 
    // orgId.locationId.domainType.operationType

    // In this example, you will only get messages for the OperatoryV1 and PatientV1 domain types
    await channel.bindQueue(queueName, exchanges, '*.*.OperatoryV1.*');
    await channel.bindQueue(queueName, exchanges, '*.*.PatientV1.*');
    await channel.bindQueue(queueName, exchanges, '*.*.AppointmentV1.*');

    // todo - add any other domain types types (with the versions you are using) or you will not recieve those types of updates

    // It's very bad practice to bind to *.*.*.* unless you truly want to listen to all domain types
    // The system will be flooded with messages that are thrown away.    

    // Now that the queue is created and bound to the right exchange routing keys, you can
    // listen to the queue for any new data that flows through it
    await channel.consume(queueName, (message) => {``
      console.log('\nReceived a message from stream API: ');

      // uncomment to see entire payload
      // console.log(JSON.stringify(message));

      // Useful fields in the payload
      const routingKey = _.get(message, 'fields.routingKey') || '';
      const [orgId, locationId, domainType, operation] = routingKey.split('.');
      console.log(`  routingKey=${routingKey}`);
      console.log(`  domainType=${domainType}`); // probably a useful field to look at in a message

      // Here is the actual message payload
      const payload = JSON.parse(message.content.toString('UTF-8'));
      sendToSoft(payload);
      wss.emit('change', "Data from Api")
      // Note that you will only see the fields that have changed.  If a field hasn't been updated, you should not see it in the payload
      console.log(`  payload=${JSON.stringify(payload)}`);      
    });

    console.log('Listening to the stream api.  You need to use the normal rest api to save something like a Patient to see data flow.')

  }
  catch(err){
    console.error('Error in the example program: ', err);
  }
})();

    function sendToSoft(payload) {

        axios({
            method: 'post',
            url: 'http://192.168.1.9/Calendar/Calendar/calendar/apiData.php',
            data: qs.stringify({
                data : payload,
                
            })
        }).then((response) => {
            console.log(response.data)
        });
   }

