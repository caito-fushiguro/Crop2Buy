const express = require('express');
const app = express();
const port = process.env.PORT || 3000; // Use the PORT environment variable or 3000 as the default

// Define a simple route
app.get('/', (req, res) => {
  res.send('Hello, this is your web-based system!');
});

// Start the server
app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
