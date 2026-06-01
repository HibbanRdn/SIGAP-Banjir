import L from 'leaflet';

function initAdminSpatialDetailMap() {
    const mapElement = document.getElementById('admin-spatial-detail-map');

    if (!mapElement || mapElement.dataset.mapInitialized === 'true') {
        return;
    }

    const longitude = Number.parseFloat(mapElement.dataset.longitude || '');
    const latitude = Number.parseFloat(mapElement.dataset.latitude || '');

    if (!Number.isFinite(longitude) || !Number.isFinite(latitude)) {
        mapElement.innerHTML = `
            <div class="flex h-full min-h-[430px] items-center justify-center bg-slate-50 p-6 text-center text-sm leading-6 text-slate-500">
                Koordinat belum tersedia untuk menampilkan mini map.
            </div>
        `;
        return;
    }

    mapElement.dataset.mapInitialized = 'true';

    const kind = normalizeMapType(mapElement.dataset.mapType);
    const map = L.map(mapElement, {
        center: [latitude, longitude],
        zoom: 15,
        scrollWheelZoom: false,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    const marker = L.marker([latitude, longitude], {
        icon: markerIcon(kind, { selected: true }),
        keyboard: true,
        title: mapElement.dataset.name || 'Lokasi data spasial',
        zIndexOffset: 1000,
    }).addTo(map);

    marker.bindPopup(buildPopup(kind, mapElement.dataset), {
        className: 'sigap-leaflet-popup',
        maxWidth: 280,
    });

    marker.openPopup();

    window.setTimeout(() => {
        map.invalidateSize();
        map.setView([latitude, longitude], 15);
    }, 250);

    window.addEventListener('resize', () => map.invalidateSize());
}

function normalizeMapType(value) {
    return {
        risk: 'risk',
        evacuation: 'evacuation',
        equipment: 'equipment',
    }[value] || 'risk';
}

function markerIcon(kind, options = {}) {
    const classes = ['leaflet-sigap-marker', `leaflet-sigap-marker-${kind}`];
    const metrics = markerMetrics(options);

    if (options.selected) {
        classes.push('leaflet-sigap-marker-selected');
    }

    if (options.recommended) {
        classes.push('leaflet-sigap-marker-recommended');
    }

    return L.divIcon({
        className: classes.join(' '),
        html: `
            <span class="leaflet-sigap-pin" aria-hidden="true">
                <span class="leaflet-sigap-pin-head">
                    ${markerSvg(kind)}
                </span>
            </span>
        `,
        iconSize: [metrics.width, metrics.height],
        iconAnchor: [metrics.width / 2, metrics.height - 2],
        popupAnchor: [0, -metrics.height + 7],
    });
}

function markerMetrics(options = {}) {
    if (options.selected) {
        return { width: 38, height: 44 };
    }

    if (options.recommended) {
        return { width: 36, height: 42 };
    }

    return { width: 34, height: 40 };
}

function markerSvg(kind) {
    const icons = {
        risk: `
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 4 21 20H3L12 4Z" />
                <path d="M12 9v4.5" />
                <path d="M12 17h.01" />
            </svg>
        `,
        evacuation: `
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m4 11 8-6 8 6" />
                <path d="M6.5 10.5V20h11V10.5" />
                <path d="M10 20v-5h4v5" />
            </svg>
        `,
        equipment: `
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 17h9.5l2-3.5H20" />
                <path d="M7 17l2.5-7H13l2.5 3.5" />
                <path d="M12 10l5-4" />
                <path d="M17 6h3l-1.5 3" />
                <path d="M6 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                <path d="M16 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
            </svg>
        `,
    };

    return icons[kind] || icons.risk;
}

function buildPopup(kind, data) {
    const config = {
        risk: {
            eyebrow: 'Titik Rawan Banjir',
            rows: [
                ['Risiko', label(data.riskLevel)],
                ['Kecamatan', label(data.district)],
                ['Status Data', label(data.dataStatus)],
            ],
        },
        evacuation: {
            eyebrow: 'Titik Evakuasi',
            rows: [
                ['Jenis', label(data.recordType)],
                ['Status', label(data.status)],
                ['Kapasitas', data.capacity ? `${escapeHtml(data.capacity)} orang` : '-'],
                ['Kecamatan', label(data.district)],
            ],
        },
        equipment: {
            eyebrow: 'Pos Alat Berat',
            rows: [
                ['Status', label(data.status)],
                ['Kecamatan', label(data.district)],
                ['Unit Tersedia', label(data.availableUnits)],
                ['Status Data', label(data.dataStatus)],
            ],
        },
    }[kind];

    return `
        <div class="map-popup-card">
            <p class="map-popup-eyebrow">${config.eyebrow}</p>
            <h3>${escapeHtml(data.name || 'Lokasi data spasial')}</h3>
            <dl>
                ${config.rows
                    .map(([term, value]) => `
                        <div>
                            <dt>${escapeHtml(term)}</dt>
                            <dd>${value}</dd>
                        </div>
                    `)
                    .join('')}
            </dl>
        </div>
    `;
}

function label(value) {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    return escapeHtml(String(value).replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase()));
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

initAdminSpatialDetailMap();
