const sharp = require('sharp');
const fs = require('fs');

// Read the SVG file
const svgBuffer = fs.readFileSync('images/favicon.svg');

// Convert SVG to PNG
sharp(svgBuffer)
  .png()
  .resize(32, 32)
  .toFile('images/favicon.png')
  .then(() => {
    console.log('Favicon converted successfully!');
  })
  .catch(err => {
    console.error('Error converting favicon:', err);
  }); 