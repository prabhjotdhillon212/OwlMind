const express = require('express');
const path = require('path');
const app = express();

// Serve static files from /public
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.json());

// Example API route (if needed later)
app.post('/api/mood', (req, res) => {
  const moodData = req.body;
  // Save to DB or process...
  res.status(200).json({ message: 'Mood logged successfully' });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}`);
});
