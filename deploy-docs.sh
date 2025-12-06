#!/bin/bash

echo "🚀 Deploying API Documentation to GitHub Pages..."

# Generate latest swagger docs
php artisan l5-swagger:generate

# Create docs directory if not exists
mkdir -p docs

# Download Swagger UI
echo "📥 Downloading Swagger UI..."
cd docs
rm -rf swagger-ui-*
wget -q https://github.com/swagger-api/swagger-ui/archive/refs/tags/v5.10.0.zip
unzip -q v5.10.0.zip
cp -r swagger-ui-5.10.0/dist/* .
rm -rf swagger-ui-5.10.0 v5.10.0.zip

# Copy generated swagger files
echo "📄 Copying swagger files..."
cp ../storage/api-docs/api-docs.json ./swagger.json
cp ../storage/api-docs/api-docs.yaml ./swagger.yaml 2>/dev/null || echo "YAML not generated"

# Update index.html to use our swagger.json
echo "✏️  Updating Swagger UI configuration..."
sed -i 's|url: "https://eventconnect.swagger.io/v2/swagger.json"|url: "./swagger.json"|g' index.html

# Add custom title and description
cat > index.html.tmp << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management API Documentation</title>
    <link rel="stylesheet" type="text/css" href="./swagger-ui.css" />
    <link rel="icon" type="image/png" href="./favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./favicon-16x16.png" sizes="16x16" />
    <style>
        html { box-sizing: border-box; overflow: -moz-scrollbars-vertical; overflow-y: scroll; }
        *, *:before, *:after { box-sizing: inherit; }
        body { margin:0; padding:0; }
        .topbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .topbar .wrapper { padding: 20px; }
        .topbar .link { color: white; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="./swagger-ui-bundle.js" charset="UTF-8"></script>
    <script src="./swagger-ui-standalone-preset.js" charset="UTF-8"></script>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "./swagger.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                persistAuthorization: true,
                displayRequestDuration: true,
                filter: true,
                tryItOutEnabled: true
            });
        };
    </script>
</body>
</html>
EOF

mv index.html.tmp index.html

cd ..

echo "✅ Done! Documentation ready in /docs directory"
echo ""
echo "Next steps:"
echo "1. git add docs/"
echo "2. git commit -m 'Update API documentation'"
echo "3. git push origin main"
echo ""
echo "Then enable GitHub Pages:"
echo "Repository Settings → Pages → Source: main branch, /docs folder"