import './bootstrap';

// Font Awesome (di-host lokal, pengganti CDN cdnjs)
import '@fortawesome/fontawesome-free/css/all.min.css';

// Font Figtree (di-host lokal, pengganti fonts.bunny.net)
import '@fontsource/figtree/400.css';
import '@fontsource/figtree/500.css';
import '@fontsource/figtree/600.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
