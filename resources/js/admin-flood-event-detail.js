import L from 'leaflet';

function initAdminFloodEventDetail() {
    const mapElement = document.getElementById('admin-flood-event-map');

    if (!mapElement) {
        return;
    }

    const longitude = Number.parseFloat(mapElement.dataset.longitude || '');
    const latitude = Number.parseFloat(mapElement.dataset.latitude || '');

    const refs = {
        resourcesLoading: document.getElementById('admin-detail-resources-loading'),
        resourcesError: document.getElementById('admin-detail-resources-error'),
        evacuationsList: document.getElementById('admin-detail-evacuations-list'),
        equipmentList: document.getElementById('admin-detail-equipment-list'),
        routeEmpty: document.getElementById('admin-detail-route-empty'),
        routePanel: document.getElementById('admin-detail-route-panel'),
        routeLoading: document.getElementById('admin-detail-route-loading'),
        routeError: document.getElementById('admin-detail-route-error'),
        routeDestination: document.getElementById('admin-detail-route-destination'),
        routeProvider: document.getElementById('admin-detail-route-provider'),
        routeDistance: document.getElementById('admin-detail-route-distance'),
        routeDuration: document.getElementById('admin-detail-route-duration'),
        routeNote: document.getElementById('admin-detail-route-note'),
    };

    const config = {
        id: mapElement.dataset.floodEventId,
        name: mapElement.dataset.floodEventName || 'Kejadian banjir',
        longitude,
        latitude,
        nearestResourcesUrl: mapElement.dataset.nearestResourcesUrl,
        routeNearestUrl: mapElement.dataset.routeNearestUrl,
        routeEvacuationBaseUrl: mapElement.dataset.routeEvacuationBaseUrl,
    };

    if (!Number.isFinite(longitude) || !Number.isFinite(latitude)) {
        showInlineError(refs.resourcesError, 'Koordinat kejadian banjir belum tersedia.');
        return;
    }

    const state = {
        map: L.map(mapElement, {
            zoomControl: true,
            preferCanvas: false,
        }).setView([latitude, longitude], 15),
        sourceKey: `flood:${config.id}`,
        focusedMarkerKey: `flood:${config.id}`,
        recommendedKeys: new Set(),
        markers: new Map(),
        routeLayer: null,
        routeDestinationKey: null,
    };

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(state.map);

    mapElement.dataset.basemap = 'standard';
    addMarker(state, 'flood', state.sourceKey, {
        id: config.id,
        name: config.name,
        longitude,
        latitude,
        meta: 'Lokasi kejadian banjir',
    });
    refreshMarkerIcons(state);

    bindDetailActions(state, refs, config);
    loadResources(state, refs, config);
}

function bindDetailActions(state, refs, config) {
    document.addEventListener('click', (event) => {
        const focusButton = event.target.closest('[data-admin-detail-focus-layer]');

        if (focusButton) {
            focusMarker(
                state,
                `${focusButton.dataset.adminDetailFocusLayer}:${focusButton.dataset.adminDetailFocusId}`,
            );
            return;
        }

        const routeButton = event.target.closest('[data-admin-detail-route-evacuation-id]');

        if (routeButton) {
            loadRoute(state, refs, config, routeButton.dataset.adminDetailRouteEvacuationId);
            return;
        }

        const nearestRouteButton = event.target.closest('[data-admin-detail-route-nearest]');

        if (nearestRouteButton) {
            loadRoute(state, refs, config);
            return;
        }

        const reloadButton = event.target.closest('[data-admin-detail-reload-resources]');

        if (reloadButton) {
            loadResources(state, refs, config);
            return;
        }

        const scrollTarget = event.target.closest('[data-admin-detail-scroll]');

        if (scrollTarget) {
            const target = document.getElementById(`admin-detail-${scrollTarget.dataset.adminDetailScroll}-section`);
            target?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
}

async function loadResources(state, refs, config) {
    showResourcesLoading(refs);
    clearResourceMarkers(state);

    try {
        const result = await fetchJson(config.nearestResourcesUrl);

        if (!result.success) {
            throw new Error(result.message || 'Rekomendasi spasial gagal dimuat.');
        }

        const evacuations = result.data?.nearest_evacuations || [];
        const equipmentPosts = result.data?.nearest_equipment_posts || [];

        renderEvacuations(refs, evacuations);
        renderEquipment(refs, equipmentPosts);
        addRecommendationMarkers(state, evacuations, equipmentPosts);
        hideInlineError(refs.resourcesError);
        hideElement(refs.resourcesLoading);

        if (evacuations.length === 0 && equipmentPosts.length === 0) {
            showInlineError(refs.resourcesError, 'Belum ada resource aktif yang sesuai untuk kejadian ini.');
        }
    } catch (error) {
        hideElement(refs.resourcesLoading);
        renderEvacuations(refs, []);
        renderEquipment(refs, []);
        showInlineError(refs.resourcesError, error.message || 'Rekomendasi spasial gagal dimuat.');
    }
}

function renderEvacuations(refs, evacuations) {
    if (!refs.evacuationsList) {
        return;
    }

    if (evacuations.length === 0) {
        refs.evacuationsList.innerHTML = emptyState('Belum ada titik evakuasi aktif yang sesuai.');
        return;
    }

    refs.evacuationsList.innerHTML = evacuations
        .slice(0, 3)
        .map((item) => `
            <article class="sig-reveal rounded-xl border border-slate-200 bg-white p-3 shadow-soft">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-primary">#${escapeHtml(item.rank)} ${escapeHtml(item.name)}</p>
                        <p class="mt-1 text-xs text-slate-500">${escapeHtml(label(item.type))} · ${escapeHtml(item.district || '-')}</p>
                    </div>
                    <span class="font-technical shrink-0 text-xs font-bold text-secondary">${escapeHtml(item.distance_label || '-')}</span>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                    <div class="rounded-lg bg-slate-50 p-2">
                        <p class="text-slate-500">Kapasitas</p>
                        <p class="font-technical font-semibold text-primary">${escapeHtml(item.capacity ?? '-')}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-2">
                        <p class="text-slate-500">Status</p>
                        <p class="font-semibold text-teal-700">${escapeHtml(label(item.status))}</p>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" class="sig-button sig-button-outline px-3 py-1.5 text-xs" data-admin-detail-focus-layer="evacuations" data-admin-detail-focus-id="${escapeHtml(item.id)}">Lihat di Peta</button>
                    <button type="button" class="sig-button sig-button-primary px-3 py-1.5 text-xs" data-admin-detail-route-evacuation-id="${escapeHtml(item.id)}">Tampilkan Rute</button>
                </div>
            </article>
        `)
        .join('');
}

function renderEquipment(refs, equipmentPosts) {
    if (!refs.equipmentList) {
        return;
    }

    if (equipmentPosts.length === 0) {
        refs.equipmentList.innerHTML = emptyState('Belum ada pos alat berat aktif dengan unit tersedia.');
        return;
    }

    refs.equipmentList.innerHTML = equipmentPosts
        .slice(0, 3)
        .map((item) => {
            const equipment = (item.available_equipment || [])
                .map((unit) => `${label(unit.type)} ${unit.available_quantity}/${unit.quantity}`)
                .join(', ') || 'Unit tersedia';

            return `
                <article class="sig-reveal rounded-xl border border-slate-200 bg-white p-3 shadow-soft">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-primary">#${escapeHtml(item.rank)} ${escapeHtml(item.name)}</p>
                            <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.district || '-')}</p>
                        </div>
                        <span class="font-technical shrink-0 text-xs font-bold text-secondary">${escapeHtml(item.distance_label || '-')}</span>
                    </div>
                    <p class="mt-3 rounded-lg bg-yellow-50 px-2 py-1.5 text-xs leading-5 text-yellow-800">${escapeHtml(equipment)}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button type="button" class="sig-button sig-button-outline px-3 py-1.5 text-xs" data-admin-detail-focus-layer="equipment" data-admin-detail-focus-id="${escapeHtml(item.id)}">Lihat di Peta</button>
                    </div>
                </article>
            `;
        })
        .join('');
}

function addRecommendationMarkers(state, evacuations, equipmentPosts) {
    const boundsItems = [];

    evacuations.slice(0, 3).forEach((item) => {
        const key = `evacuations:${item.id}`;
        state.recommendedKeys.add(key);
        addMarker(state, 'evacuation', key, {
            ...item,
            meta: `${label(item.type)} · ${item.distance_label || '-'}`,
        });
        boundsItems.push([item.latitude, item.longitude]);
    });

    equipmentPosts.slice(0, 3).forEach((item) => {
        const key = `equipment:${item.id}`;
        state.recommendedKeys.add(key);
        addMarker(state, 'equipment', key, {
            ...item,
            meta: `${item.district || '-'} · ${item.distance_label || '-'}`,
        });
        boundsItems.push([item.latitude, item.longitude]);
    });

    boundsItems.push([state.markers.get(state.sourceKey).item.latitude, state.markers.get(state.sourceKey).item.longitude]);
    refreshMarkerIcons(state);

    const bounds = L.latLngBounds(boundsItems);

    if (bounds.isValid() && boundsItems.length > 1) {
        state.map.fitBounds(bounds, {
            padding: [34, 34],
            maxZoom: 14,
        });
    }
}

async function loadRoute(state, refs, config, evacuationId = null) {
    const url = evacuationId
        ? `${config.routeEvacuationBaseUrl}/${evacuationId}`
        : config.routeNearestUrl;

    showRouteLoading(refs);

    try {
        const result = await fetchJson(url);

        if (!result.success) {
            throw new Error(result.message || 'Rute referensi gagal dimuat.');
        }

        drawRoute(state, refs, result.data);
        hideInlineError(refs.routeError);
    } catch (error) {
        hideElement(refs.routeLoading);
        showElement(refs.routeEmpty);
        hideElement(refs.routePanel);
        showInlineError(refs.routeError, routeErrorMessage(error));
    }
}

function drawRoute(state, refs, route) {
    if (state.routeLayer) {
        state.map.removeLayer(state.routeLayer);
    }

    if (state.routeDestinationKey) {
        state.recommendedKeys.delete(state.routeDestinationKey);
    }

    const routeFeature = {
        type: 'Feature',
        geometry: route.geometry,
        properties: {},
    };

    const outline = L.geoJSON(routeFeature, {
        interactive: false,
        style: {
            color: '#ffffff',
            weight: 9,
            opacity: 0.88,
            lineCap: 'round',
            lineJoin: 'round',
        },
    });

    const main = L.geoJSON(routeFeature, {
        interactive: false,
        style: {
            color: '#0058be',
            weight: 5,
            opacity: 0.96,
            dashArray: '10 8',
            lineCap: 'round',
            lineJoin: 'round',
        },
    });

    state.routeLayer = L.featureGroup([outline, main]).addTo(state.map);
    state.routeDestinationKey = route.destination?.id ? `evacuations:${route.destination.id}` : null;

    if (route.destination?.id && !state.markers.has(state.routeDestinationKey)) {
        addMarker(state, 'evacuation', state.routeDestinationKey, {
            id: route.destination.id,
            name: route.destination.name,
            longitude: route.destination.longitude,
            latitude: route.destination.latitude,
            meta: route.distance_label || 'Tujuan evakuasi',
        });
    }

    if (state.routeDestinationKey) {
        state.recommendedKeys.add(state.routeDestinationKey);
        state.focusedMarkerKey = state.routeDestinationKey;
    }

    refreshMarkerIcons(state);

    const bounds = state.routeLayer.getBounds();

    if (bounds.isValid()) {
        state.map.fitBounds(bounds, {
            padding: [42, 42],
            maxZoom: 16,
        });
    }

    hideElement(refs.routeLoading);
    hideElement(refs.routeEmpty);
    hideInlineError(refs.routeError);
    showElement(refs.routePanel);

    refs.routeDestination.textContent = route.destination?.name || 'Titik evakuasi';
    refs.routeProvider.textContent = (route.provider || 'osrm').toUpperCase();
    refs.routeDistance.textContent = route.distance_label || '-';
    refs.routeDuration.textContent = route.duration_label || '-';
    refs.routeNote.textContent = route.note || 'Rute ini bersifat referensi.';
}

function addMarker(state, kind, key, item) {
    if (state.markers.has(key)) {
        state.map.removeLayer(state.markers.get(key).marker);
    }

    const marker = L.marker([item.latitude, item.longitude], {
        icon: markerIcon(kind, {
            selected: key === state.sourceKey || key === state.focusedMarkerKey,
            recommended: state.recommendedKeys.has(key),
        }),
        keyboard: true,
        title: item.name,
        zIndexOffset: markerZIndex(kind, {
            selected: key === state.sourceKey || key === state.focusedMarkerKey,
            recommended: state.recommendedKeys.has(key),
        }),
    }).addTo(state.map);

    marker.bindPopup(buildPopup(kind, item), {
        className: 'sigap-leaflet-popup',
        minWidth: 220,
    });

    marker.on('click', () => {
        state.focusedMarkerKey = key;
        refreshMarkerIcons(state);
    });

    state.markers.set(key, {
        marker,
        kind,
        item,
    });

    return marker;
}

function clearResourceMarkers(state) {
    state.markers.forEach((entry, key) => {
        if (key !== state.sourceKey) {
            state.map.removeLayer(entry.marker);
            state.markers.delete(key);
        }
    });

    state.recommendedKeys.clear();
    state.focusedMarkerKey = state.sourceKey;
}

function focusMarker(state, key) {
    const entry = state.markers.get(key);

    if (!entry) {
        return;
    }

    state.focusedMarkerKey = key;
    refreshMarkerIcons(state);
    state.map.setView(entry.marker.getLatLng(), 16, { animate: true });
    entry.marker.openPopup();
}

function refreshMarkerIcons(state) {
    state.markers.forEach((entry, key) => {
        const options = {
            selected: key === state.sourceKey || key === state.focusedMarkerKey,
            recommended: state.recommendedKeys.has(key),
        };

        entry.marker.setIcon(markerIcon(entry.kind, options));
        entry.marker.setZIndexOffset(markerZIndex(entry.kind, options));
    });
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
        flood: `
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M3 15.5c2.25-2 4.5-2 6.75 0s4.5 2 6.75 0 3.75-1.9 5.25-.6" />
                <path d="M3 19.5c2.25-2 4.5-2 6.75 0s4.5 2 6.75 0 3.75-1.9 5.25-.6" />
                <path d="M8 11.5 12 4l4 7.5" />
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

    return icons[kind] || icons.flood;
}

function markerZIndex(kind, options = {}) {
    if (options.selected) {
        return 1000;
    }

    if (options.recommended) {
        return 900;
    }

    return {
        flood: 700,
        evacuation: 620,
        equipment: 560,
    }[kind] || 500;
}

function buildPopup(kind, item) {
    const title = {
        flood: 'Kejadian Banjir',
        evacuation: 'Titik Evakuasi',
        equipment: 'Pos Alat Berat',
    }[kind] || 'Lokasi';

    return `
        <div class="map-popup-card">
            <p class="map-popup-eyebrow">${escapeHtml(title)}</p>
            <h3>${escapeHtml(item.name)}</h3>
            <dl>
                <div><dt>Info</dt><dd>${escapeHtml(item.meta || '-')}</dd></div>
                ${item.distance_label ? `<div><dt>Jarak</dt><dd>${escapeHtml(item.distance_label)}</dd></div>` : ''}
                ${item.status ? `<div><dt>Status</dt><dd>${escapeHtml(label(item.status))}</dd></div>` : ''}
            </dl>
        </div>
    `;
}

async function fetchJson(url) {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
        },
    });
    const data = await response.json().catch(() => null);

    if (!response.ok) {
        throw new Error(data?.message || 'Request API gagal.');
    }

    return data;
}

function showResourcesLoading(refs) {
    showElement(refs.resourcesLoading);
    hideInlineError(refs.resourcesError);
}

function showRouteLoading(refs) {
    showElement(refs.routeLoading);
    hideInlineError(refs.routeError);
    hideElement(refs.routeEmpty);
    hideElement(refs.routePanel);
}

function showInlineError(element, message) {
    if (!element) {
        return;
    }

    element.textContent = message;
    element.classList.remove('hidden');
}

function hideInlineError(element) {
    if (!element) {
        return;
    }

    element.textContent = '';
    element.classList.add('hidden');
}

function showElement(element) {
    element?.classList.remove('hidden');
}

function hideElement(element) {
    element?.classList.add('hidden');
}

function emptyState(message) {
    return `
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-500">
            ${escapeHtml(message)}
        </div>
    `;
}

function routeErrorMessage(error) {
    const message = error.message || 'Rute referensi gagal dimuat.';

    if (message.includes('Provider routing tidak merespons')) {
        return 'Provider routing sedang tidak merespons. Silakan coba kembali.';
    }

    return message;
}

function label(value) {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    return String(value)
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

initAdminFloodEventDetail();
