---
name: SIGAP Banjir
colors:
  surface: '#fbf8fa'
  surface-dim: '#dcd9db'
  surface-bright: '#fbf8fa'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f5f3f4'
  surface-container: '#f0edef'
  surface-container-high: '#eae7e9'
  surface-container-highest: '#e4e2e3'
  on-surface: '#1b1b1d'
  on-surface-variant: '#45474c'
  inverse-surface: '#303032'
  inverse-on-surface: '#f3f0f2'
  outline: '#75777d'
  outline-variant: '#c5c6cd'
  surface-tint: '#545f73'
  primary: '#091426'
  on-primary: '#ffffff'
  primary-container: '#1e293b'
  on-primary-container: '#8590a6'
  inverse-primary: '#bcc7de'
  secondary: '#0058be'
  on-secondary: '#ffffff'
  secondary-container: '#2170e4'
  on-secondary-container: '#fefcff'
  tertiary: '#1e1200'
  on-tertiary: '#ffffff'
  tertiary-container: '#35260c'
  on-tertiary-container: '#a38c6a'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d8e3fb'
  primary-fixed-dim: '#bcc7de'
  on-primary-fixed: '#111c2d'
  on-primary-fixed-variant: '#3c475a'
  secondary-fixed: '#d8e2ff'
  secondary-fixed-dim: '#adc6ff'
  on-secondary-fixed: '#001a42'
  on-secondary-fixed-variant: '#004395'
  tertiary-fixed: '#fadfb8'
  tertiary-fixed-dim: '#ddc39d'
  on-tertiary-fixed: '#271902'
  on-tertiary-fixed-variant: '#564427'
  background: '#fbf8fa'
  on-background: '#1b1b1d'
  surface-variant: '#e4e2e3'
  danger-coral: '#F87171'
  safe-teal: '#2DD4BF'
  resource-amber: '#FBBF24'
  surface-gray: '#F8FAFC'
  text-main: '#334155'
  text-muted: '#94A3B8'
typography:
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
  mono-stats:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: -0.02em
  mono-label:
    fontFamily: JetBrains Mono
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  margin-page: 24px
  gutter-grid: 16px
  panel-sidebar: 260px
  panel-explorer: 400px
  radius-base: 8px
---

# DESIGN.md
# SIGAP Banjir Bandar Lampung
## Project Identity
Name:
SIGAP Banjir Bandar Lampung
Full name:
Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung
Project type:
Web-based Geographic Information System for flood mitigation and response in Bandar Lampung.
Core purpose:
The system maps flood risk points, active flood events, evacuation points, heavy equipment posts, evacuation routes, and nearest-location recommendations based on spatial data.
This is an academic GIS project, but the interface should feel like a polished modern civic product.
## Design Direction
Main concept:
Civic Flood Response Map Explorer with Modern Component System
The UI should feel:
- calm, structured, and trustworthy
- civic but not bureaucratic
- academic but not outdated
- modern but not trendy
- friendly but not playful
- data-oriented but not cold
- map-first but not visually empty
- polished but not overly decorative
Avoid:
- generic AI dashboard look
- old government-style admin panel
- startup landing page vibe
- excessive gradients
- dramatic disaster imagery
- heavy shadows
- neon colors
- emoji
- 3D illustration
- cluttered layout
## Technology Target
The final implementation will use:
- Laravel Blade
- Tailwind CSS
- Leaflet
- PostgreSQL + PostGIS
Do not design as React, Next.js, or shadcn/ui.
However, use modern component principles similar to shadcn-style design:
- clean reusable components
- subtle borders
- consistent radius
- clear spacing
- strong typography hierarchy
- good hover/focus/active states
- calm neutral palette
- polished interaction details
## Typography
Primary font:
Plus Jakarta Sans
Use it for:
- headings
- body text
- navigation
- buttons
- forms
- cards
- tables
- map popups
- dashboard content
Technical font:
JetBrains Mono
Use it only for:
- numbers
- statistics
- coordinates
- distance
- route duration
- IDs
- metadata
- technical values
Do not use JetBrains Mono for the whole interface.
## Color Direction
Use a mature civic palette.
Color ratio:
- 70% neutral
- 20% navy/blue
- 10% accent colors
Core colors:
- Civic Navy for identity and structure
- Civic Blue for primary action and focus state
- Muted Red or Coral for flood danger and active flood events
- Green or Teal for evacuation/safe status
- Amber or Gold for heavy equipment/resources
- White cards
- Very light gray background
- Slate/dark neutral text
- Muted gray metadata text
Color rules:
- Do not overuse red.
- Red is only for flood/danger/severity.
- Blue is for primary action and focus.
- Navy anchors the layout but should not fill everything.
- Use muted and calm tones.
- Avoid overly saturated colors.
## Logo Usage
Logo files:
- logo_utama.png: main horizontal logo
- half_logo.png: compact icon logo
Use main logo for:
- login page
- sidebar brand
- header
- README-style brand area
Use compact logo for:
- favicon
- collapsed sidebar
- small app icon
- marker-style brand accent if needed
Logo should not be distorted.
Use white or light container if logo is placed on dark background.
## Icon Style
Use one icon style only.
Preferred style:
Lucide-style line icons.
Icon rules:
- simple line icons
- consistent stroke
- 16px, 18px, or 20px
- currentColor or muted text color
- no colorful random icons
- no 3D icons
- no emoji
Suggested icons:
- Dashboard: LayoutDashboard
- Map: Map
- Flood: Waves or AlertTriangle
- Flood risk: AlertTriangle
- Evacuation: ShieldCheck or Home
- Heavy equipment: Truck or Construction
- Route: Route
- Layer: Layers
- Search: Search
- Filter: SlidersHorizontal
- Add: Plus
- Edit: Pencil
- Delete: Trash2
- Detail: Eye
- Save: Save
- Logout: LogOut
- Coordinate: MapPin or Crosshair
- Distance: Ruler
- Duration: Clock
## Main Screens to Design
Design the following screens:
1. Public Map Explorer
2. Admin Login
3. Admin Dashboard
4. Flood Event Management Table
5. Add/Edit Flood Event Form
6. Flood Event Detail
7. Nearest Evacuation Recommendation Panel
8. Nearest Heavy Equipment Recommendation Panel
9. Public/Admin Map View
10. Heavy Equipment Management
11. Evacuation Point Management
## Public Map Explorer
Use a split layout.
Left panel:
- brand/header
- search bar
- filter chips
- layer toggles
- result count
- result cards
- empty state
- reset filter button
Right area:
- large Leaflet-style map
- custom markers
- marker popup
- legend
- route layer
- selected marker state
Map layers:
- flood risk points
- flood events
- evacuation points
- heavy equipment posts
- evacuation route
- optional impact radius
Interaction:
- selecting a list item highlights the marker
- selecting a marker opens a compact popup
- active item has a clear border/action color
- loading state uses skeleton
- empty results show a helpful message and reset button
## Admin Dashboard
Dashboard should feel like a modern product dashboard.
Layout:
- sidebar navigation
- topbar
- statistic cards
- quick actions
- status data section
- recent flood events
- heavy equipment availability
Statistic cards:
- small icon
- label
- number
- short hint
- JetBrains Mono for numeric values
- subtle border
- minimal shadow
- clickable only when relevant
Suggested dashboard cards:
- Active Flood Events
- Flood Risk Points
- Active Evacuation Points
- Active Heavy Equipment Posts
- Available Equipment Units
- Data Need Validation
## Sidebar
Sidebar should feel modern and product-like.
Rules:
- clean brand area
- icon + label navigation
- active state clear
- subtle hover
- consistent item height
- logout separated at bottom
- not a plain dark block
- no excessive decoration
Menu items:
- Dashboard
- Map
- Flood Risk Points
- Flood Events
- Evacuation Points
- Heavy Equipment Posts
- Equipment Types
- Spatial Analysis
- Data Sources
- Logout
## Flood Event Detail
This page is the center of the spatial analysis workflow.
Components:
- event name
- status badge
- severity badge
- location summary
- mini map or map section
- water depth
- occurred time
- reported time
- source metadata
- action buttons:
  - Find Nearest Evacuation
  - Find Nearest Heavy Equipment
  - Show Evacuation Route
  - Edit Data
Recommendation sections:
- nearest evacuation card
- nearest heavy equipment list
- route information panel
Use JetBrains Mono for:
- distance
- duration
- coordinates
- technical metadata
## Recommendation Cards
Nearest evacuation card:
- name
- type
- capacity
- status
- distance
- action to show route
- action to view on map
Nearest heavy equipment card:
- post name
- available equipment types
- available quantity
- status
- distance
- badge for nearest result
Do not show recommendations as plain tables only.
Use polished cards or ranked lists.
## Form Design
Forms should be sectioned and comfortable.
Use cards/sections:
1. Basic Information
2. Location and Coordinates
3. Status and Severity
4. Data Source
5. Notes
Coordinate helper text:
- Latitude example: -5.xxxx
- Longitude example: 105.xxxx
- PostGIS uses longitude, latitude
Form rules:
- clear labels
- helper text for technical fields
- error message near field
- consistent buttons
- focus state visible
- optional map picker area
- sticky action bar for long forms if needed
## Table Design
Tables should be clean and readable.
Rules:
- muted table header
- soft row hover
- badge for status/severity/data status
- ghost or outline action buttons
- search and filter area above table
- empty state if no data
- horizontal scroll on mobile
- avoid dense rows
Common columns:
- Name
- District
- Status
- Severity/Risk
- Data Status
- Verified
- Updated At
- Actions
## Button System
Button variants:
- Primary
- Secondary
- Outline
- Ghost
- Destructive
- Link
States:
- default
- hover
- active
- focus-visible
- disabled
- loading
Rules:
- primary only for main action
- destructive only for delete
- ghost for small icon action
- no heavy shadow
- subtle hover
- clear focus ring
Button labels:
- Add Flood Event
- Save Changes
- Apply Filter
- Reset Filter
- Find Nearest Evacuation
- Find Heavy Equipment
- Show Route
- View on Map
- Cancel
## Cards
Cards should not feel like empty white boxes.
Rules:
- header, content, footer/action if relevant
- subtle border
- light shadow only if needed
- small label or eyebrow text
- icon if useful
- hover only for clickable cards
- good internal spacing
## Badges and Status
Use badges for:
- flood status
- severity
- risk level
- evacuation status
- equipment status
- data status
- verification status
- nearest recommendation
Badge colors:
- active flood: red/coral
- handled flood: blue
- receded flood: gray
- evacuation active: green/teal
- full evacuation: amber/red
- heavy equipment: amber
- dummy/simulation data: muted neutral/amber
- verified: green
- unverified: gray/amber
## Loading and Empty States
Loading:
- skeleton for cards/lists/tables
- small spinner only for button actions
- no full-screen loading unless necessary
Empty state:
- small line icon
- short title
- short explanation
- relevant action
Examples:
- No flood events yet
- No points match the filter
- No active evacuation point available
- No heavy equipment available
- Route has not been generated
## Motion
Use subtle modern motion.
Duration:
- button/nav/input: 120–160ms
- card/panel: 160–220ms
- page content: 180–260ms
Allowed:
- subtle fade
- subtle slide
- skeleton pulse
- smooth map pan/zoom
Avoid:
- bounce
- parallax
- neon glow
- large scale effects
- looping decorative animation
Respect reduced motion preferences.
## Microcopy
Use clear Indonesian labels in the final app, but the design can use English labels if needed.
Preferred Indonesian microcopy:
- Tambah Kejadian Banjir
- Simpan Perubahan
- Terapkan Filter
- Reset Filter
- Cari Evakuasi Terdekat
- Cari Pos Alat Berat
- Tampilkan Rute Evakuasi
- Lihat di Peta
- Data simulasi akademik
- Koordinat perlu divalidasi
- Tidak ada titik evakuasi aktif
- Provider rute tidak merespons
Use careful wording:
- “Rute referensi”, not official route
- “Rekomendasi berdasarkan jarak spasial”, not final decision
- “Radius terdampak simulasi”, not official disaster zone
## Responsive Behavior
Desktop:
- sidebar visible
- split map layout
- dashboard grid
- full table
Tablet:
- compact sidebar
- collapsible map panel
Mobile:
- sidebar drawer
- map explorer uses bottom sheet
- table horizontal scroll
- forms one column
- popup should not cover entire map
## Accessibility
Rules:
- good text contrast
- visible focus state
- icon-only buttons need labels
- do not rely only on color for status
- badges must include text
- forms must have clear labels
- interactive elements should be large enough
- map should have list/panel alternative
## Anti AI-Slop Rules
Do not create:
- generic AI dashboard
- random gradients
- random bright colors
- dramatic flood disaster art
- old government admin panel
- overdecorated layout
- meaningless icons
- oversized hero section
- card-only boring layout
- default map marker without visual thought
The design should feel intentional, civic, modern, polished, and useful.