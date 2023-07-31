const mysql = require("mysql");
const { body, validationResult } = require("express-validator");

// var connection = mysql.createConnection({
//     host: "localhost",
//     user: "root",
//     password: "root",
//     // password: "WakebExtension@db@2021##",
//     database: "drones",
// });

// connection.connect();

module.exports = function (app, io) {
    app.get("/", (req, res) => res.sendFile("./index.html"));

    app.get("/getFlights", (req, res) => {
        return get("SELECT * FROM flights", res);
    });

    const isArray = (value) => Array.isArray(value);

    const validateData = [
        body("location_id").notEmpty().withMessage("location_id is required"),
        body("status").notEmpty().withMessage("status is required"),
        body("drone_id").notEmpty().withMessage("drone_id is required"),
        body("image").notEmpty().withMessage("images is required"),
        body("flight_id").notEmpty().withMessage("flight_id is required"),
        // body("images").notEmpty().withMessage("images must be an array"),
    ];

    app.post("/addFlight", validateData, (req, res) => {
        console.log("before validation", Date.now());
        const errors = validationResult(req);
        console.log("after validation", Date.now());
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array()[0].msg });
        }
        var request = req.body;

        // if (!("flight_id" in request) || request.flight_id == null) {
        //     return addFlight(request, res);
        // } else {
        //     console.log("second", Date.now());

        /******* fire live stream event ******* */
        droneLiveStreamEvent(request, request.flight_id);

        return res.status(200).send({
            message: "Data added to database",
            flight_id: request.flight_id,
            location_id: request.location_id,
            drone_id: request.drone_id,
            status: request.status,
        });
        // }
    });

    function addFlight(request, res) {
        const { location_id, drone_id, date } = request;

        const data = { location_id, drone_id, date };

        var sql = "INSERT INTO flights SET ? ";

        connection.query(sql, data, (error, results, fields) => {
            if (error) {
                res.status(500).send("Error adding data to database");
            } else {
                /********************* fire the event ****************************** */
                droneLiveStreamEvent(request, results.insertId);

                res.status(200).send({
                    message: "Data added to database",
                    flight_id: results.insertId,
                    status: request.status,
                });
            }
        });
    }

    async function droneLiveStreamEvent(request, flight_id = null) {
        var image = request.image;
        var drone_id = request.drone_id;
        var location_id = request.location_id;
        var status = request.status;

        console.log(Date.now(), " before socket emited");
        const data = { image, status, flight_id, drone_id, location_id };
        console.log(data);
        await io.emit("itemAdded", data);
        console.log(Date.now(), " after socket emited");
    }

    function get(sql, res) {
        connection.query(sql, function (error, results) {
            if (error) throw error;

            console.log(results);

            res.json(results);
        });
    }
};
