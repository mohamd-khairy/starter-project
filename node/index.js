// const express = require("express");
// const app = express();
// const http = require("http");
// const server = http.createServer(app);
// const { Server } = require("socket.io");
// const io = new Server(server);
// const bodyParser = require("body-parser");
// const cors = require("cors");
// // configure body-parser middleware to parse incoming request bodies

// app.use(cors("*"));
// app.use(bodyParser.urlencoded({ extended: true }));
// app.use(bodyParser.json());
// app.use(express.json());
const express = require("express");
var app = require("express")();
var server = require("http").Server(app);
const bodyParser = require("body-parser");
var io = require("socket.io")(server);
app.use(bodyParser.urlencoded({ limit: "2147483648mb", extended: true }));
app.use(bodyParser.json({ limit: "2147483648mb" }));
app.use(express.json());
app.use(express.static("public"));

require("./routes/api.js")(app, io);
const port = process.env.PORT || 3000;
server.listen(port, () => {
    console.log("listening on *:" + port);
});
