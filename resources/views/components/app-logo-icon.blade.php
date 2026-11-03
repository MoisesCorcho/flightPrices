<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" {{ $attributes }}>
  <defs>
    <radialGradient id="app-logo-bg" cx="50%" cy="38%" r="68%">
      <stop offset="0%" stop-color="#3d2bee" />
      <stop offset="50%" stop-color="#2417a8" />
      <stop offset="100%" stop-color="#0f0947" />
    </radialGradient>
    <linearGradient id="app-logo-sweep" x1="0%" y1="100%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#38bdf8" stop-opacity="0" />
      <stop offset="50%" stop-color="#38bdf8" stop-opacity="0.08" />
      <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.32" />
    </linearGradient>
    <linearGradient id="app-logo-plane" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" />
      <stop offset="100%" stop-color="#f1f5f9" />
    </linearGradient>
  </defs>

  <rect width="512" height="512" rx="112" fill="url(#app-logo-bg)" />
  <circle cx="256" cy="256" r="186" fill="#38bdf8" fill-opacity="0.035" />
  <path d="M 256 256 L 388 124 A 186 186 0 0 0 256 70 Z" fill="url(#app-logo-sweep)" />
  <line x1="256" y1="256" x2="388" y2="124" stroke="#38bdf8" stroke-width="2" stroke-opacity="0.6" stroke-linecap="round" />
  <circle cx="256" cy="256" r="186" fill="none" stroke="#38bdf8" stroke-width="2.2" stroke-opacity="0.28" stroke-dasharray="6 8" />
  <circle cx="256" cy="256" r="126" fill="none" stroke="#38bdf8" stroke-width="1.8" stroke-opacity="0.35" />
  <circle cx="256" cy="256" r="66" fill="none" stroke="#38bdf8" stroke-width="1.5" stroke-opacity="0.45" stroke-dasharray="3 5" />
  <line x1="50" y1="256" x2="462" y2="256" stroke="#38bdf8" stroke-width="1.2" stroke-opacity="0.24" stroke-dasharray="4 6" />
  <line x1="256" y1="50" x2="256" y2="462" stroke="#38bdf8" stroke-width="1.2" stroke-opacity="0.24" stroke-dasharray="4 6" />
  <circle cx="348" cy="152" r="5" fill="#38bdf8" />
  <circle cx="348" cy="152" r="2" fill="#ffffff" />
  <circle cx="170" cy="316" r="3" fill="#38bdf8" fill-opacity="0.5" />

  <g transform="translate(256, 256) rotate(45)">
    <path d="M 0 -76 C 4 -66, 9 -34, 9 -14 L 76 22 C 80 24, 80 28, 75 29 L 60 31 L 13 18 L 11 48 L 30 64 C 32 66, 32 68, 28 69 L 21 70 L 4 62 L 3 72 C 3 74, -3 74, -3 72 L -4 62 L -21 70 C -25 71, -27 68, -26 66 L -9 48 L -11 18 L -60 31 C -64 32, -67 28, -65 25 L -76 22 C -80 21, -78 19, -74 18 L -9 -14 C -9 -34, -4 -66, 0 -76 Z" fill="url(#app-logo-plane)" />
    <path d="M 0 -68 L 0 64" stroke="#cbd5e1" stroke-width="1.2" stroke-opacity="0.6" stroke-linecap="round" />
    <path d="M 0 -48 L 0 -36" stroke="#2417a8" stroke-width="2.4" stroke-linecap="round" stroke-opacity="0.9" />
  </g>
</svg>
