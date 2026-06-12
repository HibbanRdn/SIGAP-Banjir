import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const BANDAR_LAMPUNG_CENTER = [-5.3971, 105.2668];

const basemapConfigs = {
    standard: {
        label: 'Standar',
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        options: {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        },
    },
    humanitarian: {
        label: 'Humanitarian',
        url: 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
        options: {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors, Tiles style by Humanitarian OpenStreetMap Team',
        },
    },
    satellite: {
        label: 'Satelit',
        url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        options: {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri',
        },
    },
};

const layerConfigs = {
    districtIntensity: {
        endpoint: '/api/v1/geojson/district-flood-intensity',
        label: 'Intensitas Kecamatan',
    },
    floodEvents: {
        endpoint: '/api/v1/geojson/flood-events',
        kind: 'flood',
        markerKey: 'floodEvents',
        label: 'Kejadian banjir',
    },
    floodRisks: {
        endpoint: '/api/v1/geojson/flood-risks',
        kind: 'risk',
        markerKey: 'floodRisks',
        label: 'Titik rawan banjir',
    },
    evacuations: {
        endpoint: '/api/v1/geojson/evacuation-points',
        kind: 'evacuation',
        markerKey: 'evacuations',
        label: 'Titik evakuasi',
    },
    equipment: {
        endpoint: '/api/v1/geojson/heavy-equipment-posts',
        kind: 'equipment',
        markerKey: 'equipment',
        label: 'Pos alat berat',
    },
};

function initPublicMap() {
    const mapElement = document.getElementById('public-map');

    if (!mapElement) {
        return;
    }

    const refs = {
        alert: document.getElementById('map-alert'),
        loadingOverlay: document.getElementById('map-loading-overlay'),
        layerLoading: document.getElementById('layer-loading'),
        floodList: document.getElementById('flood-events-list'),
        floodResultCount: document.getElementById('flood-result-count'),
        search: document.getElementById('flood-search'),
        statusFilter: document.getElementById('status-filter'),
        severityFilter: document.getElementById('severity-filter'),
        districtFilter: document.getElementById('district-filter'),
        resetFilters: document.getElementById('reset-filters'),
        selectedPanel: document.getElementById('selected-event-panel'),
        selectedName: document.getElementById('selected-event-name'),
        selectedMeta: document.getElementById('selected-event-meta'),
        recommendations: document.getElementById('recommendations-panel'),
        analysisLoading: document.getElementById('analysis-loading'),
        routePanel: document.getElementById('route-info-panel'),
        routeDestination: document.getElementById('route-destination'),
        routeProvider: document.getElementById('route-provider'),
        routeDistance: document.getElementById('route-distance'),
        routeDuration: document.getElementById('route-duration'),
        routeNote: document.getElementById('route-note'),
        basemapButtons: document.querySelectorAll('[data-basemap-mode]'),
        currentBasemapLabel: document.getElementById('current-basemap-label'),
        legendBasemapLabel: document.getElementById('legend-basemap-label'),
        districtLegend: document.getElementById('district-intensity-legend'),
        counts: {
            districtIntensity: document.getElementById('count-district-intensity'),
            floodEvents: document.getElementById('count-flood-events'),
            floodRisks: document.getElementById('count-flood-risks'),
            evacuations: document.getElementById('count-evacuations'),
            equipment: document.getElementById('count-equipment'),
        },
    };

    const state = {
        map: L.map(mapElement, {
            zoomControl: true,
            preferCanvas: false,
        }).setView(BANDAR_LAMPUNG_CENTER, 12),
        data: {},
        layers: {},
        markers: new Map(),
        selectedFloodId: null,
        selectedMarkerKey: null,
        recommendedKeys: new Set(),
        routeDestinationKey: null,
        routeLayer: null,
        baseLayer: null,
        baseMapKey: 'standard',
        failedBasemaps: new Set(),
    };

    configureMapPanes(state.map);
    setBaseMap(state, refs, 'standard');
    bindControls(state, refs);
    loadAllLayers(state, refs);
}

function configureMapPanes(map) {
    const districtPane = map.createPane('districtIntensityPane');
    districtPane.style.zIndex = 360;
    districtPane.style.pointerEvents = 'auto';
}

function bindControls(state, refs) {
    document.querySelectorAll('[data-layer-toggle]').forEach((toggle) => {
        toggle.addEventListener('change', () => {
            const layerName = toggle.dataset.layerToggle;
            const layer = state.layers[layerName];

            if (toggle.checked && layer) {
                layer.addTo(state.map);
            }

            if (!toggle.checked && layer) {
                state.map.removeLayer(layer);
            }

            syncDistrictLegend(refs);
        });
    });

    syncDistrictLegend(refs);

    refs.basemapButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setBaseMap(state, refs, button.dataset.basemapMode);
        });
    });

    refs.search?.addEventListener('input', () => renderFloodEvents(state, refs));
    refs.statusFilter?.addEventListener('change', () => renderFloodEvents(state, refs));
    refs.severityFilter?.addEventListener('change', () => renderFloodEvents(state, refs));
    refs.districtFilter?.addEventListener('change', () => renderFloodEvents(state, refs));
    refs.resetFilters?.addEventListener('click', () => {
        refs.search.value = '';
        refs.statusFilter.value = '';
        refs.severityFilter.value = '';
        refs.districtFilter.value = '';
        renderFloodEvents(state, refs);
    });

    refs.floodList?.addEventListener('click', (event) => {
        const card = event.target.closest('[data-flood-card-id]');

        if (!card) {
            return;
        }

        selectFloodEvent(state, refs, card.dataset.floodCardId);
    });

    document.addEventListener('click', (event) => {
        const mapAction = event.target.closest('[data-map-action]');

        if (mapAction) {
            const floodId = mapAction.dataset.floodId;
            selectFloodEvent(state, refs, floodId);

            if (mapAction.dataset.mapAction === 'analysis-evacuation') {
                loadAnalysis(state, refs, 'evacuation');
            }

            if (mapAction.dataset.mapAction === 'analysis-equipment') {
                loadAnalysis(state, refs, 'equipment');
            }

            if (mapAction.dataset.mapAction === 'analysis-resources') {
                loadAnalysis(state, refs, 'resources');
            }

            if (mapAction.dataset.mapAction === 'route') {
                loadRoute(state, refs);
            }
        }

        const analysisAction = event.target.closest('[data-analysis-action]');

        if (analysisAction) {
            loadAnalysis(state, refs, analysisAction.dataset.analysisAction);
        }

        const routeAction = event.target.closest('[data-route-action]');

        if (routeAction) {
            loadRoute(state, refs);
        }

        const focusAction = event.target.closest('[data-focus-layer]');

        if (focusAction) {
            focusMarker(state, `${focusAction.dataset.focusLayer}:${focusAction.dataset.focusId}`, true);
        }

        const routeToEvacuation = event.target.closest('[data-route-evacuation-id]');

        if (routeToEvacuation) {
            loadRoute(state, refs, routeToEvacuation.dataset.routeEvacuationId);
        }
    });
}

function setBaseMap(state, refs, key, options = {}) {
    const config = basemapConfigs[key] || basemapConfigs.standard;
    const selectedKey = basemapConfigs[key] ? key : 'standard';

    if (state.baseLayer) {
        state.map.removeLayer(state.baseLayer);
    }

    state.baseMapKey = selectedKey;
    state.map.getContainer().dataset.basemap = selectedKey;
    updateBasemapControls(refs, selectedKey);

    const tileLayer = L.tileLayer(config.url, {
        ...config.options,
        detectRetina: true,
    });

    tileLayer.on('tileerror', () => {
        if (selectedKey === 'standard' || state.failedBasemaps.has(selectedKey)) {
            return;
        }

        state.failedBasemaps.add(selectedKey);
        showAlert(refs, `Basemap ${config.label} gagal dimuat. Gunakan mode Standar.`);
        setBaseMap(state, refs, 'standard', { silent: true });
    });

    tileLayer.addTo(state.map);
    state.baseLayer = tileLayer;

    if (!options.silent) {
        hideAlert(refs);
    }
}

function updateBasemapControls(refs, key) {
    const labelText = basemapConfigs[key]?.label || basemapConfigs.standard.label;

    if (refs.currentBasemapLabel) {
        refs.currentBasemapLabel.textContent = labelText;
    }

    if (refs.legendBasemapLabel) {
        refs.legendBasemapLabel.textContent = labelText;
    }

    refs.basemapButtons.forEach((button) => {
        const active = button.dataset.basemapMode === key;

        button.classList.toggle('map-basemap-button-active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
}

async function loadAllLayers(state, refs) {
    setLayerLoading(refs, true);

    try {
        const entries = await Promise.all(
            Object.entries(layerConfigs).map(async ([name, config]) => [name, await fetchGeoJson(config.endpoint)]),
        );

        entries.forEach(([name, data]) => {
            state.data[name] = data;
        });

        fillDistrictOptions(state, refs);
        renderDistrictIntensity(state, refs);
        renderFloodEvents(state, refs);
        renderStaticLayer(state, refs, 'floodRisks');
        renderStaticLayer(state, refs, 'evacuations');
        renderStaticLayer(state, refs, 'equipment');
        fitToVisibleData(state);
        hideAlert(refs);
    } catch (error) {
        showAlert(refs, error.message || 'Layer peta gagal dimuat.');
    } finally {
        setLayerLoading(refs, false);
    }
}

async function fetchGeoJson(url) {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/geo+json, application/json',
        },
    });

    const data = await response.json();

    if (!response.ok || data.type !== 'FeatureCollection' || !Array.isArray(data.features)) {
        throw new Error('Layer peta gagal dimuat.');
    }

    return data;
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

function renderDistrictIntensity(state, refs) {
    const data = state.data.districtIntensity || emptyCollection();
    const totalDistricts = data.features.length;
    const districtsWithEvents = data.features
        .filter((feature) => Number(feature.properties?.total_events || 0) > 0)
        .length;

    removeLayer(state, 'districtIntensity');

    if (refs.counts.districtIntensity) {
        refs.counts.districtIntensity.textContent = `${districtsWithEvents}/${totalDistricts}`;
    }

    const layer = L.geoJSON(data, {
        pane: 'districtIntensityPane',
        style: districtStyle,
        onEachFeature: (feature, polygon) => {
            polygon.bindTooltip(buildDistrictTooltip(feature.properties), {
                className: 'sigap-district-tooltip',
                direction: 'top',
                sticky: true,
                opacity: 0.96,
            });
            polygon.bindPopup(buildDistrictPopup(feature.properties), {
                className: 'sigap-leaflet-popup',
                minWidth: 240,
            });
            polygon.on({
                mouseover: () => {
                    polygon.setStyle(districtStyle(feature, true));
                    polygon.bringToFront();
                },
                mouseout: () => {
                    polygon.setStyle(districtStyle(feature));
                },
            });
        },
    });

    state.layers.districtIntensity = layer;

    if (isLayerEnabled('districtIntensity')) {
        layer.addTo(state.map);
    }

    syncDistrictLegend(refs);
}

function districtStyle(feature, hover = false) {
    const colors = districtColors(feature.properties?.color_key);

    return {
        color: hover ? '#0f172a' : colors.stroke,
        fillColor: colors.fill,
        fillOpacity: hover ? Math.min(colors.fillOpacity + 0.11, 0.42) : colors.fillOpacity,
        opacity: hover ? 0.9 : 0.72,
        weight: hover ? 2 : 1.15,
        lineCap: 'round',
        lineJoin: 'round',
    };
}

function districtColors(colorKey) {
    return {
        none: {
            fill: '#cbd5e1',
            stroke: '#94a3b8',
            fillOpacity: 0.18,
        },
        low: {
            fill: '#22c55e',
            stroke: '#15803d',
            fillOpacity: 0.22,
        },
        medium: {
            fill: '#f59e0b',
            stroke: '#b45309',
            fillOpacity: 0.28,
        },
        high: {
            fill: '#f87171',
            stroke: '#dc2626',
            fillOpacity: 0.32,
        },
    }[colorKey] || {
        fill: '#cbd5e1',
        stroke: '#94a3b8',
        fillOpacity: 0.18,
    };
}

function renderStaticLayer(state, refs, name) {
    const data = state.data[name] || emptyCollection();
    const config = layerConfigs[name];

    removeLayer(state, name);
    removeMarkers(state, config.markerKey);
    refs.counts[name].textContent = data.features.length;

    const layer = L.geoJSON(data, {
        pointToLayer: (feature, latLng) => createMarker(state, config, feature, latLng),
        onEachFeature: (feature, marker) => {
            marker.bindPopup(buildPopup(config.kind, feature.properties), {
                className: 'sigap-leaflet-popup',
                minWidth: 230,
            });
        },
    });

    state.layers[name] = layer;

    if (isLayerEnabled(name)) {
        layer.addTo(state.map);
    }
}

function renderFloodEvents(state, refs) {
    const allData = state.data.floodEvents || emptyCollection();
    const filtered = allData.features.filter((feature) => passesFloodFilter(feature.properties, refs));
    const collection = {
        type: 'FeatureCollection',
        features: filtered,
    };

    removeLayer(state, 'floodEvents');
    removeMarkers(state, 'floodEvents');
    refs.counts.floodEvents.textContent = filtered.length;
    refs.floodResultCount.textContent = `${filtered.length} data`;

    const layer = L.geoJSON(collection, {
        pointToLayer: (feature, latLng) => createMarker(state, layerConfigs.floodEvents, feature, latLng),
        onEachFeature: (feature, marker) => {
            marker.bindPopup(buildPopup('flood', feature.properties), {
                className: 'sigap-leaflet-popup',
                minWidth: 250,
            });
            marker.on('click', () => selectFloodEvent(state, refs, feature.properties.id, { openPopup: false }));
        },
    });

    state.layers.floodEvents = layer;

    if (isLayerEnabled('floodEvents')) {
        layer.addTo(state.map);
    }

    renderFloodEventList(state, refs, filtered);
    refreshMarkerIcons(state);
}

function createMarker(state, config, feature, latLng) {
    const key = `${config.markerKey}:${feature.properties.id}`;
    const marker = L.marker(latLng, {
        icon: markerIcon(config.kind),
        keyboard: true,
        title: feature.properties.name,
        zIndexOffset: markerZIndex(config.kind),
    });

    state.markers.set(key, {
        marker,
        kind: config.kind,
        feature,
    });

    return marker;
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

function refreshMarkerIcons(state) {
    state.markers.forEach((entry, key) => {
        const options = {
            selected: key === state.selectedMarkerKey,
            recommended: state.recommendedKeys.has(key),
        };

        entry.marker.setIcon(markerIcon(entry.kind, {
            selected: options.selected,
            recommended: options.recommended,
        }));
        entry.marker.setZIndexOffset(markerZIndex(entry.kind, options));
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
        risk: 500,
    }[kind] || 500;
}

function passesFloodFilter(properties, refs) {
    const search = refs.search.value.trim().toLowerCase();
    const status = refs.statusFilter.value;
    const severity = refs.severityFilter.value;
    const district = refs.districtFilter.value;

    if (status && properties.status !== status) {
        return false;
    }

    if (severity && properties.severity_level !== severity) {
        return false;
    }

    if (district && properties.district !== district) {
        return false;
    }

    if (!search) {
        return true;
    }

    return [
        properties.name,
        properties.address,
        properties.district,
        properties.subdistrict,
        properties.status,
        properties.severity_level,
    ]
        .filter(Boolean)
        .some((value) => String(value).toLowerCase().includes(search));
}

function renderFloodEventList(state, refs, features) {
    if (features.length === 0) {
        refs.floodList.innerHTML = '<div class="rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-500">Tidak ada kejadian banjir sesuai filter.</div>';
        return;
    }

    refs.floodList.innerHTML = features.map((feature) => {
        const properties = feature.properties;
        const selected = String(properties.id) === String(state.selectedFloodId);

        return `
            <button type="button" data-flood-card-id="${escapeHtml(properties.id)}" class="map-result-card ${selected ? 'map-result-card-selected' : ''}">
                <span class="min-w-0">
                    <span class="block truncate text-sm font-bold text-primary">${escapeHtml(properties.name)}</span>
                    <span class="mt-1 block truncate text-xs text-slate-500">${escapeHtml(properties.district || '-')} · ${escapeHtml(properties.subdistrict || '-')}</span>
                </span>
                <span class="flex shrink-0 flex-col items-end gap-1">
                    <span class="${badgeClass('flood', properties.status)}">${escapeHtml(label(properties.status))}</span>
                    <span class="font-technical text-[11px] text-slate-400">${escapeHtml(label(properties.severity_level))}</span>
                </span>
            </button>
        `;
    }).join('');
}

function selectFloodEvent(state, refs, floodId, options = {}) {
    if (!floodId) {
        return;
    }

    const feature = (state.data.floodEvents?.features || [])
        .find((item) => String(item.properties.id) === String(floodId));

    if (!feature) {
        return;
    }

    state.selectedFloodId = String(floodId);
    state.selectedMarkerKey = `floodEvents:${floodId}`;
    refs.selectedPanel.classList.remove('hidden');
    refs.selectedName.textContent = feature.properties.name;
    refs.selectedMeta.textContent = `${label(feature.properties.status)} · ${label(feature.properties.severity_level)} · ${feature.properties.district || '-'}`;
    refs.recommendations.innerHTML = '<div class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-500">Pilih aksi rekomendasi untuk kejadian ini.</div>';
    renderFloodEvents(state, refs);
    focusMarker(state, state.selectedMarkerKey, options.openPopup !== false);
}

function focusMarker(state, key, openPopup = true) {
    const entry = state.markers.get(key);

    if (!entry) {
        return;
    }

    const latLng = entry.marker.getLatLng();
    state.map.flyTo(latLng, Math.max(state.map.getZoom(), 14), {
        duration: 0.55,
    });

    if (openPopup) {
        entry.marker.openPopup();
    }
}

async function loadAnalysis(state, refs, type) {
    if (!state.selectedFloodId) {
        showAlert(refs, 'Pilih kejadian banjir terlebih dahulu.');
        return;
    }

    const endpoints = {
        evacuation: `/api/v1/analysis/flood-events/${state.selectedFloodId}/nearest-evacuation`,
        equipment: `/api/v1/analysis/flood-events/${state.selectedFloodId}/nearest-equipment`,
        resources: `/api/v1/analysis/flood-events/${state.selectedFloodId}/nearest-resources`,
    };

    setAnalysisLoading(refs, true);

    try {
        const result = await fetchJson(endpoints[type]);

        if (!result.success) {
            throw new Error(result.message || 'Rekomendasi belum tersedia.');
        }

        renderRecommendations(state, refs, type, result.data);
        hideAlert(refs);
    } catch (error) {
        refs.recommendations.innerHTML = errorPanel(error.message || 'Rekomendasi belum tersedia.');
    } finally {
        setAnalysisLoading(refs, false);
    }
}

function renderRecommendations(state, refs, type, data) {
    state.recommendedKeys.clear();

    if (type === 'resources') {
        const evacuations = data.nearest_evacuations || [];
        const equipment = data.nearest_equipment_posts || [];
        markRecommendations(state, 'evacuations', evacuations);
        markRecommendations(state, 'equipment', equipment);
        refs.recommendations.innerHTML = [
            recommendationSection('Titik Evakuasi Terdekat', 'evacuations', evacuations),
            recommendationSection('Pos Alat Berat Terdekat', 'equipment', equipment),
        ].join('');
    }

    if (type === 'evacuation') {
        const recommendations = data.recommendations || [];
        markRecommendations(state, 'evacuations', recommendations);
        refs.recommendations.innerHTML = recommendationSection('Titik Evakuasi Terdekat', 'evacuations', recommendations);
    }

    if (type === 'equipment') {
        const recommendations = data.recommendations || [];
        markRecommendations(state, 'equipment', recommendations);
        refs.recommendations.innerHTML = recommendationSection('Pos Alat Berat Terdekat', 'equipment', recommendations);
    }

    refreshMarkerIcons(state);
}

function recommendationSection(title, layer, items) {
    if (!items.length) {
        return `
            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <p class="text-sm font-bold text-primary">${escapeHtml(title)}</p>
                <p class="mt-2 text-sm text-slate-500">Tidak ada rekomendasi yang sesuai.</p>
            </div>
        `;
    }

    return `
        <div class="space-y-2">
            <p class="text-sm font-bold text-primary">${escapeHtml(title)}</p>
            ${items.map((item) => recommendationCard(layer, item)).join('')}
        </div>
    `;
}

function recommendationCard(layer, item) {
    const isEvacuation = layer === 'evacuations';
    const summary = isEvacuation
        ? `${label(item.type)} · Kapasitas ${item.capacity ?? '-'}`
        : `${(item.available_equipment || []).map((unit) => `${label(unit.type)} ${unit.available_quantity}/${unit.quantity}`).join(', ') || 'Unit tersedia'}`;

    return `
        <article class="rounded-xl border border-slate-200 bg-white p-3 shadow-soft">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-primary">#${escapeHtml(item.rank)} ${escapeHtml(item.name)}</p>
                    <p class="mt-1 truncate text-xs text-slate-500">${escapeHtml(item.district || '-')} · ${escapeHtml(summary)}</p>
                </div>
                <span class="font-technical shrink-0 text-xs font-bold text-secondary">${escapeHtml(item.distance_label || '-')}</span>
            </div>
            <div class="mt-3 flex flex-wrap gap-2">
                <button type="button" class="sig-button sig-button-outline px-3 py-1.5 text-xs" data-focus-layer="${layer}" data-focus-id="${escapeHtml(item.id)}">Lihat di Peta</button>
                ${isEvacuation ? `<button type="button" class="sig-button sig-button-primary px-3 py-1.5 text-xs" data-route-evacuation-id="${escapeHtml(item.id)}">Tampilkan Rute</button>` : ''}
            </div>
        </article>
    `;
}

function markRecommendations(state, layer, items) {
    items.forEach((item) => {
        state.recommendedKeys.add(`${layer}:${item.id}`);
    });
}

async function loadRoute(state, refs, evacuationId = null) {
    if (!state.selectedFloodId) {
        showAlert(refs, 'Pilih kejadian banjir terlebih dahulu.');
        return;
    }

    const url = evacuationId
        ? `/api/v1/routing/flood-events/${state.selectedFloodId}/to-evacuation/${evacuationId}`
        : `/api/v1/routing/flood-events/${state.selectedFloodId}/to-nearest-evacuation`;

    refs.routePanel.classList.remove('hidden');
    refs.routeDestination.textContent = 'Memuat rute...';
    refs.routeDistance.textContent = '-';
    refs.routeDuration.textContent = '-';
    refs.routeNote.textContent = 'Mengambil rute referensi dari provider routing.';

    try {
        const result = await fetchJson(url);

        if (!result.success) {
            throw new Error(result.message || 'Rute tidak ditemukan oleh provider routing.');
        }

        drawRoute(state, refs, result.data);
        hideAlert(refs);
    } catch (error) {
        showAlert(refs, error.message || 'Rute tidak ditemukan oleh provider routing.');
        refs.routeDestination.textContent = 'Rute belum tersedia';
        refs.routeNote.textContent = error.message || 'Provider routing tidak merespons.';
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

    if (state.routeDestinationKey) {
        state.recommendedKeys.add(state.routeDestinationKey);
        refreshMarkerIcons(state);
    }

    const bounds = state.routeLayer.getBounds();

    if (bounds.isValid()) {
        state.map.fitBounds(bounds, {
            padding: [40, 40],
            maxZoom: 16,
        });
    }

    refs.routePanel.classList.remove('hidden');
    refs.routeDestination.textContent = route.destination?.name || 'Titik evakuasi';
    refs.routeProvider.textContent = (route.provider || 'osrm').toUpperCase();
    refs.routeDistance.textContent = route.distance_label || '-';
    refs.routeDuration.textContent = route.duration_label || '-';
    refs.routeNote.textContent = route.note || 'Rute ini bersifat referensi.';
}

function buildPopup(kind, properties) {
    if (kind === 'flood') {
        return `
            <div class="map-popup-card">
                <p class="map-popup-eyebrow">Kejadian Banjir</p>
                <h3>${escapeHtml(properties.name)}</h3>
                <dl>
                    <div><dt>Status</dt><dd>${escapeHtml(label(properties.status))}</dd></div>
                    <div><dt>Severity</dt><dd>${escapeHtml(label(properties.severity_level))}</dd></div>
                    <div><dt>Kecamatan</dt><dd>${escapeHtml(properties.district || '-')}</dd></div>
                    ${properties.water_depth_cm ? `<div><dt>Kedalaman</dt><dd>${escapeHtml(properties.water_depth_cm)} cm</dd></div>` : ''}
                    <div><dt>Data</dt><dd>${escapeHtml(label(properties.data_status))}</dd></div>
                </dl>
                <div class="map-popup-actions">
                    <button type="button" data-map-action="analysis-resources" data-flood-id="${escapeHtml(properties.id)}">Cari Resource</button>
                    <button type="button" data-map-action="route" data-flood-id="${escapeHtml(properties.id)}">Tampilkan Rute</button>
                </div>
            </div>
        `;
    }

    if (kind === 'evacuation') {
        return `
            <div class="map-popup-card">
                <p class="map-popup-eyebrow">Titik Evakuasi</p>
                <h3>${escapeHtml(properties.name)}</h3>
                <dl>
                    <div><dt>Tipe</dt><dd>${escapeHtml(label(properties.type))}</dd></div>
                    <div><dt>Status</dt><dd>${escapeHtml(label(properties.status))}</dd></div>
                    <div><dt>Kapasitas</dt><dd>${escapeHtml(properties.capacity || '-')}</dd></div>
                    <div><dt>Kecamatan</dt><dd>${escapeHtml(properties.district || '-')}</dd></div>
                </dl>
            </div>
        `;
    }

    if (kind === 'equipment') {
        const available = properties.available_units ?? 0;
        const summary = (properties.equipment_summary || [])
            .map((unit) => `${label(unit.type)} ${unit.available_quantity}/${unit.quantity}`)
            .slice(0, 3)
            .join(', ');

        return `
            <div class="map-popup-card">
                <p class="map-popup-eyebrow">Pos Alat Berat</p>
                <h3>${escapeHtml(properties.name)}</h3>
                <dl>
                    <div><dt>Status</dt><dd>${escapeHtml(label(properties.status))}</dd></div>
                    <div><dt>Kecamatan</dt><dd>${escapeHtml(properties.district || '-')}</dd></div>
                    <div><dt>Tersedia</dt><dd>${escapeHtml(available)} unit</dd></div>
                    <div><dt>Alat</dt><dd>${escapeHtml(summary || '-')}</dd></div>
                </dl>
            </div>
        `;
    }

    return `
        <div class="map-popup-card">
            <p class="map-popup-eyebrow">Titik Rawan</p>
            <h3>${escapeHtml(properties.name)}</h3>
            <dl>
                <div><dt>Risiko</dt><dd>${escapeHtml(label(properties.risk_level))}</dd></div>
                <div><dt>Kecamatan</dt><dd>${escapeHtml(properties.district || '-')}</dd></div>
                <div><dt>Data</dt><dd>${escapeHtml(label(properties.data_status))}</dd></div>
            </dl>
        </div>
    `;
}

function buildDistrictTooltip(properties) {
    return `
        <div class="map-district-tooltip">
            <strong>${escapeHtml(properties.district || '-')}</strong>
            <span>${escapeHtml(properties.total_events ?? 0)} kejadian · ${escapeHtml(properties.intensity_label || '-')}</span>
        </div>
    `;
}

function buildDistrictPopup(properties) {
    return `
        <div class="map-popup-card">
            <p class="map-popup-eyebrow">Intensitas Kecamatan</p>
            <h3>${escapeHtml(properties.district || '-')}</h3>
            <dl>
                <div><dt>Total kejadian</dt><dd>${escapeHtml(properties.total_events ?? 0)}</dd></div>
                <div><dt>Kejadian aktif</dt><dd>${escapeHtml(properties.active_events ?? 0)}</dd></div>
                <div><dt>Kritis aktif</dt><dd>${escapeHtml(properties.critical_active_events ?? 0)}</dd></div>
                <div><dt>Kategori</dt><dd>${escapeHtml(properties.intensity_label || '-')}</dd></div>
            </dl>
        </div>
    `;
}

function fillDistrictOptions(state, refs) {
    const districts = [...new Set((state.data.floodEvents?.features || [])
        .map((feature) => feature.properties.district)
        .filter(Boolean))]
        .sort((a, b) => a.localeCompare(b));

    refs.districtFilter.innerHTML = '<option value="">Semua</option>' + districts
        .map((district) => `<option value="${escapeHtml(district)}">${escapeHtml(district)}</option>`)
        .join('');
}

function fitToVisibleData(state) {
    const group = L.featureGroup();

    Object.values(state.layers).forEach((layer) => {
        layer.eachLayer((marker) => group.addLayer(marker));
    });

    if (group.getLayers().length > 0) {
        state.map.fitBounds(group.getBounds(), {
            padding: [36, 36],
            maxZoom: 13,
        });
    }
}

function removeLayer(state, name) {
    if (state.layers[name]) {
        state.map.removeLayer(state.layers[name]);
        delete state.layers[name];
    }
}

function removeMarkers(state, markerKey) {
    [...state.markers.keys()].forEach((key) => {
        if (key.startsWith(`${markerKey}:`)) {
            state.markers.delete(key);
        }
    });
}

function isLayerEnabled(name) {
    return document.querySelector(`[data-layer-toggle="${name}"]`)?.checked ?? true;
}

function syncDistrictLegend(refs) {
    if (!refs.districtLegend) {
        return;
    }

    refs.districtLegend.classList.toggle('hidden', !isLayerEnabled('districtIntensity'));
}

function setLayerLoading(refs, loading) {
    refs.layerLoading.textContent = loading ? 'memuat' : 'aktif';
    refs.loadingOverlay.classList.toggle('hidden', !loading);
}

function setAnalysisLoading(refs, loading) {
    refs.analysisLoading.classList.toggle('hidden', !loading);
}

function showAlert(refs, message) {
    refs.alert.textContent = message;
    refs.alert.classList.remove('hidden');
}

function hideAlert(refs) {
    refs.alert.textContent = '';
    refs.alert.classList.add('hidden');
}

function errorPanel(message) {
    return `<div class="rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700">${escapeHtml(message)}</div>`;
}

function emptyCollection() {
    return {
        type: 'FeatureCollection',
        features: [],
    };
}

function label(value) {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    return String(value).replaceAll('_', ' ');
}

function badgeClass(kind, value) {
    if (kind === 'flood' && value === 'aktif') {
        return 'sig-badge border border-red-100 bg-red-50 text-red-700';
    }

    if (kind === 'flood' && value === 'ditangani') {
        return 'sig-badge border border-blue-100 bg-blue-50 text-blue-700';
    }

    return 'sig-badge border border-slate-200 bg-slate-50 text-slate-600';
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPublicMap);
} else {
    initPublicMap();
}
