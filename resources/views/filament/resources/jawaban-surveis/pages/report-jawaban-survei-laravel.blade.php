@if ($print)
    <style>
        :root {
            color-scheme: only light;
        }

        html {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            background: #fff !important;
        }

        .fi-topbar,
        .fi-sidebar,
        .fi-breadcrumbs,
        .fi-header,
        [data-filament-header],
        [data-slot="header"],
        .fi-actions {
            display: none !important;
        }

        .fi-main,
        .fi-simple-layout,
        .container,
        main,
        .fi-section {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .fi-section,
        .fi-card,
        .avoid-break {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        canvas {
            width: 1200px !important;
            height: 480px !important;
        }

        .sticky {
            position: static !important;
        }
    </style>
    <script>
        window.__PDF_READY__ = false;

        const forceLightTheme = () => {
            const root = document.documentElement;
            root.classList.remove('dark');
            root.dataset.theme = 'filament';
            root.setAttribute('data-mode', 'light');

            document.body?.classList.add('bg-white');
        };

        const markReady = () => {
            forceLightTheme();
            window.__PDF_READY__ = true;
        };

        window.addEventListener('load', () => {
            forceLightTheme();
            setTimeout(markReady, 1200);
        });
    </script>
@endif
