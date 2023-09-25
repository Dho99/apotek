const express = require('express');


const app = express();

const server = require('http').createServer(app);

const io = require('socket.io')(server, {
    cors: {
        origin: "*"
    }
});

io.on('connection', (socket) => {
    console.log('Connection estabilished');

    socket.on('receiveFromClient', (message, userLevel) => {
        console.log(message);
        console.log(userLevel);
    });


    socket.on('disconnect', (socket) => {
        console.log("connection disconnected");
    });
});

server.listen(3000, () => {
    console.log('Server running');
});
