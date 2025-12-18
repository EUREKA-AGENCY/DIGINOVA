<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DIGINOVA Forum API - Documentation</title>

        <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    </head>
    <body>
        <div id="swagger-ui"></div>

        <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
        <script>
            window.addEventListener('load', () => {
                window.SwaggerUIBundle({
                    url: '{{ route('docs.api.spec') }}',
                    dom_id: '#swagger-ui',
                });
            });
        </script>
    </body>
</html>

