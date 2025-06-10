@extends('layouts.cliente')

@section('title', 'Ubicación de Super Market')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="bi bi-geo-alt-fill"></i> Super Market - Av. Chapultepec
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Mapa interactivo -->
                    <section class="bg-gray-50 py-16">
                        <div class="container mx-auto px-4">
                            <div class="max-w-5xl mx-auto">
                                <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden shadow-lg">
                                    <iframe 
                                        src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3762.8380997595673!2d-99.17187492501341!3d19.419399981857158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTnCsDI1JzA5LjgiTiA5OcKwMTAnMDkuNSJX!5e0!3m2!1ses!2smx!4v1743469186184!5m2!1ses!2smx"
                                        width="100%" 
                                        height="450" 
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="bi bi-shop"></i> Super Market Chapultepec</h4>
                            <p class="mb-1">
                                <i class="bi bi-geo-fill text-primary"></i> 
                                Av. Chapultepec 326, Roma Norte
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-building text-primary"></i> 
                                Cuauhtémoc, CDMX 06700
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-clock text-primary"></i> 
                                <strong>Horario:</strong><br>
                                Lunes a Domingo: 7:00 AM - 11:00 PM
                            </p>
                            <div class="mt-3">
                                <a href="https://maps.app.goo.gl/examplelink" 
                                   target="_blank" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-compass"></i> Abrir en Google Maps
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="bi bi-telephone"></i> Contacto</h4>
                            <p class="mb-1">
                                <i class="bi bi-phone text-primary"></i> 
                                <a href="tel:+525555123456">55 5512 3456</a>
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-envelope text-primary"></i> 
                                <a href="mailto:chapultepec@supermarket.com">chapultepec@supermarket.com</a>
                            </p>
                            
                            <hr class="my-3">
                            
                            <h5><i class="bi bi-truck"></i> Servicios</h5>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success"></i> Estacionamiento gratuito</li>
                                <li><i class="bi bi-check-circle text-success"></i> Entrega a domicilio</li>
                                <li><i class="bi bi-check-circle text-success"></i> Área de alimentos preparados</li>
                                <li><i class="bi bi-check-circle text-success"></i> Cajeros automáticos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const supermarketCoords = [19.4194, -99.1693];
    const zoomLevel = 17;  
    
    // Inicializar mapa
    const map = L.map('map').setView(supermarketCoords, zoomLevel);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    
    // Marcador personalizado
    const supermarketIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/2776/2776067.png',
        iconSize: [48, 48],
        iconAnchor: [24, 48],
        popupAnchor: [0, -48]
    });
    
    // Añadir marcador
    const marker = L.marker(supermarketCoords, {
        icon: supermarketIcon
    }).addTo(map);
    
    // Popup con información
    marker.bindPopup(`
        <div style="min-width: 220px">
            <h5 style="margin: 0; color: #2c3e50; font-size: 1.1rem">
                <img src="https://cdn-icons-png.flaticon.com/512/2776/2776067.png" 
                     width="20" style="vertical-align: text-top">
                <strong>Super Market</strong>
            </h5>
            <p style="margin: 5px 0; font-size: 0.9rem;">
                <i class="bi bi-geo-alt-fill" style="color: #e74c3c"></i> 
                Av. Chapultepec 326
            </p>
            <div style="display: flex; gap: 5px; margin-top: 8px">
                <a href="https://www.google.com/maps/dir/?api=1&destination=19.4194,-99.1693" 
                   target="_blank" 
                   style="flex: 1; padding: 5px; background: #3498db; color: white; 
                          text-align: center; border-radius: 4px; text-decoration: none; font-size: 0.8rem">
                    <i class="bi bi-compass"></i> Cómo llegar
                </a>
            </div>
        </div>
    `).openPopup();
    
    L.circle(supermarketCoords, {
        color: '#3498db',
        fillColor: '#2980b9',
        fillOpacity: 0.1,
        radius: 500
    }).addTo(map);
    
    L.control.locate({
        position: 'topright',
        strings: {
            title: "Mi ubicación actual"
        },
        locateOptions: {
            maxZoom: 16
        }
    }).addTo(map);
});
</script>

<style>
    /* Estilos personalizados */
    #map { 
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px !important;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    .leaflet-popup-content {
        margin: 12px 10px !important;
    }
    .bi-check-circle {
        margin-right: 5px;
    }
</style>
@endsection