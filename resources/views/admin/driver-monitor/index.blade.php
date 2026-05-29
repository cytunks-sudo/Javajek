@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

<style>
.driver-stat-card {
    background: white;
    border-radius: 18px;
    padding: 14px 18px;
    box-shadow: 0 8px 25px rgba(0,0,0,.08);
    margin-bottom: 14px;
}

.driver-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
}

.dot-online { background: #22c55e; }
.dot-busy { background: #3b82f6; }
.dot-offline { background: #9ca3af; }

.driver-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.driver-modal {
    background: #fff;
    width: 92%;
    max-width: 420px;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 20px 50px rgba(0,0,0,.25);
}

.driver-modal h4 {
    margin-bottom: 12px;
    font-weight: 700;
}

.driver-info-row {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    padding: 9px 0;
    font-size: 14px;
}

.driver-info-row span:first-child {
    color: #6b7280;
}

.btn-close-modal {
    border: none;
    background: #ef4444;
    color: white;
    border-radius: 12px;
    padding: 10px 16px;
    width: 100%;
    margin-top: 18px;
}
</style>

<div class="container-fluid">

    <h4>Monitoring Driver</h4>
    <p>Pantau posisi driver online secara live</p>

    <div class="driver-stat-card">
        <span class="driver-dot dot-online"></span>
        Driver Online: <b id="driverCount">0</b>
    </div>

    <div id="driverMap" style="height:75vh;width:100%;border:1px solid #ddd;border-radius:18px;"></div>

</div>

<div class="driver-modal-overlay" id="driverModalOverlay">
    <div class="driver-modal">
        <h4 id="modalDriverName">Detail Driver</h4>

        <div class="driver-info-row">
            <span>HP</span>
            <b id="modalDriverPhone">-</b>
        </div>

        <div class="driver-info-row">
            <span>Kendaraan</span>
            <b id="modalDriverVehicle">-</b>
        </div>

        <div class="driver-info-row">
            <span>Plat Nomor</span>
            <b id="modalDriverPlate">-</b>
        </div>

        <div class="driver-info-row">
            <span>Status</span>
            <b id="modalDriverStatus">-</b>
        </div>

        <div class="driver-info-row">
            <span>Latitude</span>
            <b id="modalDriverLat">-</b>
        </div>

        <div class="driver-info-row">
            <span>Longitude</span>
            <b id="modalDriverLng">-</b>
        </div>

        <div class="driver-info-row">
            <span>Update</span>
            <b id="modalDriverUpdate">-</b>
        </div>

        <button type="button" class="btn-close-modal" onclick="closeDriverModal()">
            Tutup
        </button>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let map = L.map('driverMap').setView([-6.5380055, 111.0459620], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let markers = {};
    let firstLoad = true;

    function getMarkerColor(status) {
        if (status === 'online') return '#22c55e';
        if (status === 'busy') return '#3b82f6';
        return '#9ca3af';
    }

    function createMarkerIcon(status, vehicleType) {
    let color = getMarkerColor(status);
    let statusIcon = vehicleType === 'mobil' ? '🚗' : '🛵';

    return L.divIcon({
        className: '',
        html: `
            <div style="
                width: 34px;
                height: 34px;
                background: ${color};
                border: 4px solid white;
                border-radius: 50%;
                box-shadow: 0 6px 18px rgba(0,0,0,.35);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
            ">
                ${statusIcon}
            </div>
        `,
        iconSize: [34, 34],
        iconAnchor: [17, 17],
        popupAnchor: [0, -18],
    });
}

    function openDriverModal(driver) {
        document.getElementById('modalDriverName').innerText = driver.name ?? 'Driver';
        document.getElementById('modalDriverPhone').innerText = driver.phone ?? '-';
        document.getElementById('modalDriverVehicle').innerText = driver.vehicle_type ?? '-';
        document.getElementById('modalDriverPlate').innerText = driver.plate_number ?? '-';
        document.getElementById('modalDriverStatus').innerText = driver.status ?? '-';
        document.getElementById('modalDriverLat').innerText = driver.latitude ?? '-';
        document.getElementById('modalDriverLng').innerText = driver.longitude ?? '-';
        document.getElementById('modalDriverUpdate').innerText = driver.updated_at ?? '-';

        document.getElementById('driverModalOverlay').style.display = 'flex';
    }

    window.closeDriverModal = function () {
        document.getElementById('driverModalOverlay').style.display = 'none';
    }

    window.showDriverDetail = function (driver) {
        openDriverModal(driver);
    }

    function loadDrivers() {
        fetch("{{ route('admin.driver.monitor.data') }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById('driverCount').innerText = data.drivers.length;

                let activeIds = [];

                data.drivers.forEach(driver => {
                    activeIds.push(String(driver.id));

                    let lat = Number(driver.latitude);
                    let lng = Number(driver.longitude);

                    if (driver.vehicle_type === 'mobil') {
                        lng = lng + 0.00015;
                    }

                    if (driver.vehicle_type === 'motor') {
                        lng = lng - 0.00015;
                    }
                    let icon = createMarkerIcon(driver.status, driver.vehicle_type);

                    let popup = `
                        <b>${driver.name}</b><br>
                        ${driver.vehicle_type ?? '-'} - ${driver.plate_number ?? '-'}<br>
                        <button onclick='showDriverDetail(${JSON.stringify(driver)})'
                            style="
                                margin-top:8px;
                                border:none;
                                background:#f97316;
                                color:white;
                                border-radius:8px;
                                padding:6px 10px;
                                cursor:pointer;
                            ">
                            Detail Driver
                        </button>
                    `;

                    if (markers[driver.id]) {
                        markers[driver.id].setLatLng([lat, lng]);
                        markers[driver.id].setIcon(icon);
                        markers[driver.id].setPopupContent(popup);
                    } else {
                        markers[driver.id] = L.marker([lat, lng], { icon: icon })
                            .addTo(map)
                            .bindPopup(popup);
                    }
                });

                Object.keys(markers).forEach(id => {
                    if (!activeIds.includes(id)) {
                        map.removeLayer(markers[id]);
                        delete markers[id];
                    }
                });

                if (firstLoad && data.drivers.length > 0) {
                    let firstDriver = data.drivers[0];

                    map.setView([
                        Number(firstDriver.latitude),
                        Number(firstDriver.longitude)
                    ], 14);

                    firstLoad = false;
                }

                setTimeout(function () {
                    map.invalidateSize();
                }, 300);
            });
    }

    loadDrivers();
    setInterval(loadDrivers, 5000);
});
</script>
@endsection