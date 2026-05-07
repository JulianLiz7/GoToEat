<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script>
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary":                   "#9d4300",
                "primary-container":         "#f97316",
                "on-primary":                "#ffffff",
                "on-primary-container":      "#582200",
                "secondary":                 "#006c49",
                "secondary-container":       "#6cf8bb",
                "on-secondary":              "#ffffff",
                "on-secondary-container":    "#00714d",
                "tertiary":                  "#006398",
                "tertiary-container":        "#00a2f4",
                "on-tertiary":               "#ffffff",
                "background":                "#f8f9ff",
                "surface":                   "#f8f9ff",
                "surface-variant":           "#d3e4fe",
                "surface-container":         "#e5eeff",
                "surface-container-low":     "#eff4ff",
                "surface-container-high":    "#dce9ff",
                "surface-container-lowest":  "#ffffff",
                "on-background":             "#0b1c30",
                "on-surface":                "#0b1c30",
                "on-surface-variant":        "#584237",
                "outline":                   "#8c7164",
                "outline-variant":           "#e0c0b1",
                "error":                     "#ba1a1a",
                "error-container":           "#ffdad6",
                "on-error":                  "#ffffff",
                "inverse-surface":           "#213145",
                "inverse-on-surface":        "#eaf1ff",
            },
            fontFamily: {
                "heading": ["Sora", "sans-serif"],
                "body":    ["Inter", "sans-serif"],
            },
            fontSize: {
                "h1":         ["48px", { lineHeight:"1.2",  letterSpacing:"-0.02em", fontWeight:"700" }],
                "h2":         ["36px", { lineHeight:"1.3",  letterSpacing:"-0.01em", fontWeight:"600" }],
                "h3":         ["24px", { lineHeight:"1.4",  fontWeight:"600" }],
                "body-lg":    ["18px", { lineHeight:"1.6",  fontWeight:"400" }],
                "body-md":    ["16px", { lineHeight:"1.5",  fontWeight:"400" }],
                "body-sm":    ["14px", { lineHeight:"1.5",  fontWeight:"400" }],
                "label-caps": ["12px", { lineHeight:"1",    letterSpacing:"0.05em", fontWeight:"600" }],
            },
        },
    },
}
</script>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; }
    [x-cloak] { display: none !important; }
</style>
