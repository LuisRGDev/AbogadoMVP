<?php

/*
|--------------------------------------------------------------------------
| Configuración editorial del despacho
|--------------------------------------------------------------------------
| Valores por defecto de textos e imágenes. Casi todo se puede sobrescribir
| desde el panel (Configuración del sitio); esto es el respaldo.
| Convención de énfasis en títulos: *palabra* se muestra en dorado e itálica.
*/

return [

    'admin' => [
        'name' => env('ADMIN_NAME', 'Administrador'),
        'email' => env('ADMIN_EMAIL', 'admin@despacho.test'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    // Buzón que recibe las solicitudes. Si está vacío se usa el correo público del despacho.
    'notify_email' => env('LEADS_NOTIFY_EMAIL'),

    'hero' => [
        'eyebrow' => 'Despacho jurídico · Ciudad de México',
        'title' => "Estrategia jurídica\npara decisiones\nque *importan.*",
        'text' => 'Brindamos asesoría jurídica estratégica a personas, empresas y organizaciones que requieren claridad, experiencia y una representación rigurosa.',
        'primary_cta' => 'Agendar consulta',
        'secondary_cta' => 'Explorar áreas de práctica',
        'trust' => ['Atención personalizada', 'Estrategia', 'Confidencialidad'],
    ],

    'stats' => [
        ['value' => '+15', 'label' => 'años de experiencia'],
        ['value' => '6', 'label' => 'áreas de práctica'],
        ['value' => '100%', 'label' => 'confidencialidad'],
        ['value' => '24 h', 'label' => 'tiempo de primera respuesta'],
    ],

    'about' => [
        'eyebrow' => 'Nosotros',
        'title' => 'Experiencia jurídica. *Visión estratégica.*',
        'text' => 'Somos un despacho jurídico que entiende que detrás de cada asunto hay una decisión importante. Nuestro trabajo consiste en analizar con rigor, anticipar escenarios y acompañar a nuestros clientes con claridad en cada etapa.',
    ],

    'principles' => [
        ['icon' => 'scale', 'title' => 'Filosofía', 'text' => 'Creemos en un ejercicio profesional sobrio, ético y orientado a la comprensión: el cliente debe entender su situación, sus alternativas y sus riesgos.'],
        ['icon' => 'target', 'title' => 'Enfoque de cada asunto', 'text' => 'Cada caso se estudia de manera individual, con una revisión técnica de los hechos, la normativa aplicable y los objetivos de quien nos consulta.'],
        ['icon' => 'lock', 'title' => 'Confidencialidad', 'text' => 'La información que nos comparte se trata con estricta reserva profesional, desde la primera conversación.'],
        ['icon' => 'people', 'title' => 'Comunicación clara', 'text' => 'Explicamos cada paso en lenguaje claro y mantenemos informado al cliente en las etapas relevantes de su asunto.'],
    ],

    'process' => [
        'eyebrow' => 'Cómo trabajamos',
        'title' => 'Claridad desde *el primer paso.*',
        'steps' => [
            ['num' => '01', 'title' => 'Escuchamos', 'text' => 'Entendemos el contexto y los objetivos.'],
            ['num' => '02', 'title' => 'Analizamos', 'text' => 'Evaluamos riesgos, alternativas y escenarios.'],
            ['num' => '03', 'title' => 'Diseñamos', 'text' => 'Construimos una estrategia jurídica.'],
            ['num' => '04', 'title' => 'Acompañamos', 'text' => 'Damos seguimiento durante todo el asunto.'],
        ],
    ],

    'bands' => [
        'home' => ['quote' => 'Rigor para analizar. Criterio para decidir. Discreción para acompañar.', 'cite' => 'Nuestra forma de trabajar'],
        'nosotros' => ['quote' => 'Cada asunto merece un análisis propio, no una fórmula.', 'cite' => 'Principio de trabajo'],
        'equipo' => ['quote' => 'Trabajo colegiado: varias miradas para una misma estrategia.', 'cite' => 'Cómo colaboramos'],
        'areas' => ['quote' => '¿Su asunto no encaja en una sola área? Lo revisamos de forma integral.', 'cite' => 'Enfoque integral'],
    ],

    'cta' => [
        'title' => 'Cuando una decisión importa, *la estrategia también.*',
        'text' => 'Conversemos sobre su situación y exploremos las alternativas disponibles.',
    ],

    'appointment' => [
        'slots' => [
            'manana' => 'Mañana (9:00 – 13:00)',
            'tarde' => 'Tarde (14:00 – 18:00)',
        ],
        'modes' => [
            'presencial' => 'Presencial en oficina',
            'videollamada' => 'Videollamada',
            'telefono' => 'Llamada telefónica',
        ],
        'max_days_ahead' => 90,
    ],

    // Valores de referencia de la calculadora laboral. Se pueden actualizar desde el panel;
    // verifique siempre el salario mínimo vigente en la CONASAMI.
    'calculator' => [
        'minimum_wage' => 315.04,
        'minimum_wage_border' => 440.87,
        'aguinaldo_days' => 15,
        'vacation_premium' => 0.25,
    ],

    // Rutas relativas a public/. Cada vista tiene su propia imagen para no repetirlas.
    'images' => [
        'hero' => 'img/hero.jpg',
        'about' => 'img/about.svg',
        'cta' => 'img/cta.svg',
        'areas_bg' => 'img/bg-facade.svg',
        'og' => 'img/og-cover.jpg',
        'pages' => [
            'nosotros' => 'img/bg-library.svg',
            'areas' => 'img/bg-facade.svg',
            'equipo' => 'img/bg-boardroom.svg',
            'experiencia' => 'img/bg-skyline.svg',
            'insights' => 'img/bg-stair.svg',
            'contacto' => 'img/bg-tower.svg',
            'legal' => 'img/bg-aerial.svg',
            'herramientas' => 'img/area-civil-mercantil.svg',
            'buscar' => 'img/bg-stair.svg',
            'error' => 'img/bg-aerial.svg',
        ],
        'bands' => [
            'home' => 'img/bg-skyline.svg',
            'nosotros' => 'img/bg-desk.svg',
            'equipo' => 'img/bg-table.svg',
            'areas' => 'img/bg-aerial.svg',
        ],
        // Portadas de respaldo para áreas nuevas sin imagen propia.
        'area_fallbacks' => ['img/bg-tower.svg', 'img/bg-library.svg', 'img/bg-boardroom.svg', 'img/bg-aerial.svg', 'img/bg-stair.svg', 'img/bg-skyline.svg'],
    ],
];
