# 🚀 ArbaLabs | Global Orbital Operations

![ArbaLabs Mission Control](public/images/arbalabs.png)

An interactive and cinematic *Orbital Mission Control* dashboard prototype. This project is built to demonstrate real-time satellite constellation tracking, automated telemetry monitoring, and a dynamic multi-stage rocket launch simulation leading to a live orbital network injection.

## ✨ Key Features

- 🛰️ **Live Satellite Tracking:** Real-time orbit path visualization for active nodes (NODE X1, X2, X3) using **Leaflet.js**, seamlessly integrated with geopolitical country borders rendered via GeoJSON.
- ⏱️ **Cinematic Launch Sequence:** A multi-phase automated countdown transition (Standby Phase -> Launch/Liftoff Phase -> Upper Atmosphere Phase -> Success Phase).
- 📊 **Dynamic Telemetry Dashboard:** Automated baseline and active-ascent telemetry matrix (Altitude, Speed, Temperature, Voltage, Fuel Level) that adapts responsively depending on the current flight stage or selected satellite node.
- 📡 **Automated Constellation Injection:** Upon successful orbital insertion, a 4th next-generation satellite (**ARBAEDGE-1**) is dynamically injected into the live map with a custom neon orbit path and dedicated operational telemetry state.
- 💻 **Mission Control Event Logs & Live Chat:** Automated back-end command terminal emulation logging diagnostic severity levels (`[INFO]`, `[WARN]`, `[CRIT]`) alongside simulated crowd interaction feeds.

## 🛠️ Tech Stack

- **Backend Framework:** Laravel
- **UI & Styling:** Tailwind CSS & Custom CSS Animations
- **Map & Geospatial Engine:** Leaflet.js & ArcGIS World Imagery Layer
- **Core Interactivity & Logic:** Vanilla JavaScript (ES6)
- **Typography:** Space Grotesk (Google Fonts)

## 🎮 Presentation Demonstration Guide (Secret Trigger)

This dashboard includes an engineered simulation sequence perfect for live hackathon pitching:
1. Open the application dashboard; it defaults to the **Satellite Map** interface tracking 3 active orbiting nodes.
2. Click the **"Launch Cam"** button on the bottom-right corner of the map media display. The viewport will switch to the ground pad camera focusing on the standby vehicle (**ArbaLabs 1**).
3. **The Secret Trigger:** Click the **Fast Forward (▶)** button located directly to the right of the countdown digital clock.
4. The system timer will instantly truncate down to **T-10 seconds**.
5. Allow the countdown to reach `00:00:00:00`. Witness the automated cascading transition: a flashing *Red Alert* system grid, liftoff ignition sequence, atmospheric ascension at T+5s (swapping imagery and data to flight parameters), and orbital deployment at T+10s.
6. Toggle back to the **"Satellite Map"** view to demonstrate that the 4th asset (**ArbaEdge-1**) has been successfully registered, deployed, and animated within the live global network.

## ⚙️ Local Installation & Setup

Ensure you have PHP and Composer installed on your local environment.

```bash
# 1. Clone this repository
git clone [https://github.com/rashawnnn/arbalabs-orbital-mission-control.git](https://github.com/rashawnnn/arbalabs-orbital-mission-control.git)

# 2. Navigate to the project root directory
cd arbalabs-orbital-mission-control

# 3. Install backend dependencies via Composer
composer install

# 4. Configure environment files and generate application key
cp .env.example .env
php artisan key:generate

# 5. Boot up the local development server
php artisan serve