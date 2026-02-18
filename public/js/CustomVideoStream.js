const WebSocket = require('ws')
const EventEmitter = require('events')
const STREAM_MAGIC_BYTES = "jsmp"
const Mpeg1Muxer = require('./mpeg1muxer')
const https = require('https');
const fs = require('fs');
const optionsSsl = {
    key: fs.readFileSync('/etc/letsencrypt/live/park.itr.am/privkey.pem'),
    cert: fs.readFileSync('/etc/letsencrypt/live/park.itr.am/fullchain.pem'),
};

class VideoStream extends EventEmitter {

    constructor(options) {
        super(options)
        this.name = options.name
        this.url = options.url
        this.width = options.width
        this.height = options.height
        this.port = options.port
        this.stream = void 0
        this.stream2Socket()
    }

    stream2Socket() {
        const httpsServer = https.createServer(optionsSsl);
        const wss = new WebSocket.Server({ server: httpsServer });

        httpsServer.listen(this.port, () => {
            console.log(`Secure WebSocket server is running on port ${this.port}`);
        });

        wss.on('connection', (socket) => {
            console.log(`New connection: ${this.name}`);

            let streamHeader = Buffer.alloc(8);
            streamHeader.write(STREAM_MAGIC_BYTES);
            streamHeader.writeUInt16BE(this.width, 4);
            streamHeader.writeUInt16BE(this.height, 6);
            socket.send(streamHeader);

            socket.on('close', () => {
                console.log(`${this.name} disconnected !`);
            });
        });

        this.on('camdata', (data) => {
            wss.clients.forEach((client) => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(data);
                }
            });
        });
    }

    start() {
        this.mpeg1Muxer = new Mpeg1Muxer({ url: this.url });
        this.mpeg1Muxer.on('mpeg1data', (data) => {
            this.emit('camdata', data);
        });

        let gettingInputData = false;
        let inputData = [];

        this.mpeg1Muxer.on('ffmpegError', (data) => {
            data = data.toString();
            if (data.indexOf('Input #') !== -1) { gettingInputData = true }
            if (gettingInputData) {
                inputData.push(data);
                let size = data.match(/\d+x\d+/);
                if (size != null) {
                    size = size[0].split('x');
                    if (this.width == null) { this.width = parseInt(size[0], 10) }
                    if (this.height == null) { this.height = parseInt(size[1], 10) }
                }
            }
        });
        this.mpeg1Muxer.on('ffmpegError', (data) => {
            process.stderr.write(data);
        });
        return this;
    }

    stop(serverCloseCallback) {
        wss.close(serverCloseCallback);
        this.mpeg1Muxer.stop();
    }
}

module.exports = VideoStream;
