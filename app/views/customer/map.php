<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'map';
$title = 'Dive Sites Map';

include __DIR__ . '/../layouts/header.php'; 
?>

<!-- Leaflet CSS at JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .map-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .map-header { text-align: center; margin-bottom: 20px; }
    .map-header h1 { color: #14243d; margin: 0 0 10px 0; font-size: 36px; }
    .map-header p { color: #666; font-size: 16px; }
    
    #map { width: 100%; height: 600px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 4px solid white;}
    
    .leaflet-popup-content-wrapper { border-radius: 8px; }
</style>

<div class="map-container">
    <div class="map-header">
        <h1>Explore Our Dive Sites</h1>
        <p>Visit us at Small La Laguna Beach, Sabang, Puerto Galera. Click the markers to see our dive locations.</p>
    </div>
    <div id="map"></div>
</div>

<script>
    // 1. Initialize the map centered sa La Laguna Villas (Small La Laguna Beach)
    var map = L.map('map').setView([13.52371, 120.97103], 15);

    // 2. Load the OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // 3. Define your dive sites (Pwede mong palitan ang coordinates base sa totoong dive spots)
    var diveSites = [
        {
            name: "La Laguna Villas (LLV Divers)",
            coords: [13.52371, 120.97103],
            desc: "Our premier luxury dive resort located at Small La Laguna Beach. Home of LLV Divers.",
            services: "Discover Scuba Diving - ₱3,500"
        },
        {
            name: "Sabang Bay",
            coords: [13.52123, 120.97490],
            desc: "A diver's paradise with rich marine life. Just a short boat ride from our resort.",
            services: "Fun Dive (2 Dives) - ₱4,500"
        },
        {
            name: "Coral Garden",
            coords: [13.52558, 120.94677],
            desc: "Perfect for beginners. Shallow waters with vibrant corals.",
            services: "Night Dive Experience - ₱4,000"
        }
    ];

    // 4. Loop through dive sites and add markers
    diveSites.forEach(function(site) {
        var marker = L.marker(site.coords).addTo(map);
        
        var popupContent = `
            <div style="font-family: inherit; min-width: 200px;">
                <h3 style="margin: 0 0 5px 0; color: #14243d;">${site.name}</h3>
                <p style="margin: 0 0 10px 0; color: #666; font-size: 13px;">${site.desc}</p>
                <p style="margin: 0; font-weight: bold; color: #ff7900;">${site.services}</p>
                <br>
                <a href="/diveservices" style="display: inline-block; background: #14243d; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold;">Book Service</a>
            </div>
        `;
        
        marker.bindPopup(popupContent);
    });
</script>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>