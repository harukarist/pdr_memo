const express = require('express');
const port = process.env.PORT || 5000;
const app = express();
const path = require('path')
app.use(express.static(__dirname + "/public/"));
app.listen(port);
