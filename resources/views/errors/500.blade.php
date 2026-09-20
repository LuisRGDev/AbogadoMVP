<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Error del servidor</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <section class="page-hero on-dark error-hero error-hero--plain">
        <div class="container page-hero__inner">
            <p class="error-hero__code" aria-hidden="true">500</p>
            <h1 class="page-hero__title">Algo <em>salió mal.</em></h1>
            <p class="page-hero__text">Tuvimos un problema al procesar su solicitud. Ya fue registrado; inténtelo de nuevo en unos minutos.</p>
            <div class="page-hero__actions"><a class="btn btn--gold" href="/"><span class="btn__label">Volver al inicio</span></a></div>
        </div>
    </section>
</body>
</html>
