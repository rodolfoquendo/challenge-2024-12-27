<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="SwaggerUI" />
    <title>Afluenta Languages: Docs</title>
    <link rel="icon" href="https://afluenta.com/favicon.ico">
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
  </head>
  <body>
  <div id="swagger-ui"></div>
  <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin></script>
  <script>
    window.onload = () => {
      window.ui = SwaggerUIBundle({
        url: '<?=env('APP_URL')?>/swagger.yml',
        dom_id: '#swagger-ui',
        presets: [
          SwaggerUIBundle.presets.apis,
        ],
      });
    };
  </script>
  </body>
</html>