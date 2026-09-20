/* =========================================================================
   CONFIGURACIÓN CENTRAL — TODO EL CONTENIDO EDITABLE VIVE AQUÍ
   -------------------------------------------------------------------------
   · Cambie textos, datos de contacto, abogados, áreas, etc. sin tocar los
     componentes (js/app.js) ni los estilos.
   · Los campos marcados con `demo: true` son CONTENIDO DE DEMOSTRACIÓN y
     deben sustituirse por información real y verificable antes de publicar.
   · Estructura pensada para migrar fácilmente a Laravel (config/site.php o
     tabla en BD), React o Next.js (export default / JSON).
   ========================================================================= */
window.SITE = {

  /* ------------------------------ Despacho ------------------------------ */
  firm: {
    name: '[NOMBRE DEL DESPACHO]',
    tagline: 'Estrategia jurídica para decisiones que importan.',
    shortDescription:
      'Despacho jurídico en Ciudad de México que brinda asesoría estratégica a personas, empresas y organizaciones con claridad, criterio y rigor.',
    url: 'https://www.despacho-ejemplo.com',      // REEMPLAZAR con el dominio real
    year: 2026,
    locale: 'es-MX'
  },

  /* ------------------------------- Contacto ------------------------------ */
  contact: {
    phone: '+52 55 0000 0000',
    email: 'contacto@despacho.com',
    whatsapp: {
      number: '+52 55 0000 0000',                 // ← ÚNICA variable del número de WhatsApp
      message: 'Hola, me gustaría solicitar información para una consulta jurídica.',
      tooltip: '¿Necesitas orientación? Escríbenos.'
    },
    address: {
      line1: '[Calle y número]',
      line2: '[Colonia], [Alcaldía]',
      city: 'Ciudad de México',
      state: 'CDMX',
      postalCode: '00000',
      country: 'México',
      mapsQuery: 'Ciudad de México, México'      // Consulta para “Cómo llegar” / Google Maps
    },
    hours: 'Lunes–Viernes · 9:00–18:00',
    social: [
      { name: 'LinkedIn',  url: '#' },             // REEMPLAZAR con enlaces reales
      { name: 'Instagram', url: '#' },
      { name: 'Facebook',  url: '#' }
    ]
  },

  /* ---------------------------- Formulario ---------------------------- */
  form: {
    // 'demo'     → simula el envío (sin backend).
    // 'mailto'   → abre el cliente de correo con los datos.
    // 'endpoint' → hace POST JSON a `endpoint` (Laravel route, Formspree, etc.).
    mode: 'demo',
    endpoint: ''
  },

  /* ------------------------------ Navegación ----------------------------- */
  // Cada enlace es una VISTA distinta de la aplicación (ruta por hash).
  // Al migrar: '#/nosotros' → '/nosotros', '#/areas' → '/areas', etc.
  nav: [
    { label: 'Inicio',              href: '#/' },
    { label: 'Nosotros',            href: '#/nosotros' },
    { label: 'Áreas de práctica',   href: '#/areas' },
    { label: 'Equipo',              href: '#/equipo' },
    { label: 'Experiencia',         href: '#/experiencia' },
    { label: 'Insights',            href: '#/insights' },
    { label: 'Contacto',            href: '#/contacto' }
  ],
  footerNav: [
    { label: 'Inicio',            href: '#/' },
    { label: 'Nosotros',          href: '#/nosotros' },
    { label: 'Áreas de práctica', href: '#/areas' },
    { label: 'Equipo',            href: '#/equipo' },
    { label: 'Insights',          href: '#/insights' },
    { label: 'Contacto',          href: '#/contacto' }
  ],
  ctaLabel: 'Agendar consulta',

  /* --------------------------------- SEO --------------------------------- */
  seo: {
    title: '[NOMBRE DEL DESPACHO] | Despacho jurídico en Ciudad de México',
    description:
      'Asesoría jurídica estratégica en Ciudad de México para personas, empresas y organizaciones: derecho corporativo, litigio, laboral, inmobiliario, civil y mercantil, y propiedad intelectual.',
    ogImage: 'assets/og-cover.jpg',
    // Publique datos de LocalBusiness (dirección, teléfono) SOLO cuando sean reales.
    includeLocalBusinessData: false
  },

  /* ------------------------------ Imágenes de fondo ------------------------------
     Las imágenes principales se definen aquí; las de cada vista están en `pages` y `bands`. Para usar fotografías (p. ej. generadas con IA)
     basta con copiar el archivo a /assets y cambiar la ruta (.jpg, .webp o .svg).
     Consulte assets/PROMPTS-IMAGENES.md para prompts sugeridos. El sistema aplica
     automáticamente degradados de lectura, por lo que cualquier foto oscura funciona.
     ------------------------------------------------------------------------------- */
  images: {
    hero:        'assets/hero.svg',
    about:       'assets/about.svg',
    cta:         'assets/cta.svg',
    areasBg:     'assets/bg-facade.svg'
  },

  /* ------------------------ Cabeceras de cada vista ------------------------ */
  pages: {
    nosotros:    { eyebrow: 'Nosotros',     title: 'Un despacho construido sobre <em>criterio y confianza.</em>', text: 'Conozca nuestra forma de trabajo, nuestros principios y lo que puede esperar al colaborar con nosotros.', image: 'assets/bg-library.svg', alt: 'Biblioteca jurídica con estantes de libros bajo luz cálida' },
    areas:       { eyebrow: 'Áreas de práctica', title: 'Especialidades para <em>cada decisión.</em>', text: 'Seis áreas de práctica con un enfoque común: análisis riguroso, estrategia clara y comunicación constante.', image: 'assets/bg-facade.svg', alt: 'Fachada clásica de columnas de un edificio institucional' },
    equipo:      { eyebrow: 'Equipo', title: 'Personas que <em>respaldan cada asunto.</em>', text: 'Profesionales con formación sólida y un compromiso claro con la atención personalizada.', image: 'assets/bg-boardroom.svg', alt: 'Sala de juntas con vista a la ciudad al atardecer' },
    experiencia: { eyebrow: 'Experiencia', title: 'Trayectoria y <em>voz de nuestros clientes.</em>', text: 'Una muestra de cómo presentamos asuntos representativos y la opinión de quienes han trabajado con nosotros.', image: 'assets/bg-skyline.svg', alt: 'Horizonte urbano al atardecer' },
    insights:    { eyebrow: 'Insights', title: 'Criterio jurídico <em>para decidir mejor.</em>', text: 'Artículos y análisis de carácter informativo sobre temas relevantes para personas y empresas.', image: 'assets/bg-stair.svg', alt: 'Marcos arquitectónicos convergentes hacia una luz cálida' },
    contacto:    { eyebrow: 'Contacto', title: 'Hablemos de <em>lo que importa.</em>', text: 'Solicite una primera conversación. Le responderemos en el horario de atención.', image: 'assets/bg-tower.svg', alt: 'Fachada de un rascacielos vista desde abajo' },
    legal:       { image: 'assets/bg-aerial.svg', alt: 'Plano urbano estilizado' }
  },
  // Bandas de imagen con frase (reutilizables entre vistas)
  bands: {
    home:     { image: 'assets/bg-skyline.svg', quote: 'Rigor para analizar. Criterio para decidir. Discreción para acompañar.', cite: 'Nuestra forma de trabajar' },
    nosotros: { image: 'assets/bg-library.svg', quote: 'Cada asunto merece un análisis propio, no una fórmula.', cite: 'Principio de trabajo' },
    equipo:   { image: 'assets/bg-boardroom.svg', quote: 'Trabajo colegiado: varias miradas para una misma estrategia.', cite: 'Cómo colaboramos' },
    areas:    { image: 'assets/bg-aerial.svg', quote: '¿Su asunto no encaja en una sola área? Lo revisamos de forma integral.', cite: 'Enfoque integral' }
  },

  /* ---------------------------------- Hero --------------------------------- */
  hero: {
    eyebrow: 'Despacho jurídico · Ciudad de México',
    headlineLines: ['Estrategia jurídica', 'para decisiones', 'que <em>importan.</em>'],
    text: 'Brindamos asesoría jurídica estratégica a personas, empresas y organizaciones que requieren claridad, experiencia y una representación rigurosa.',
    primaryCta: 'Agendar consulta',
    secondaryCta: 'Explorar áreas de práctica',
    trust: ['Atención personalizada', 'Estrategia', 'Confidencialidad'],
    imageAlt: 'Pasillo de columnas de un edificio institucional en perspectiva, con luz cálida al fondo'
  },

  /* ---------------------------- Barra de confianza --------------------------- */
  // demo: el dato de años de experiencia es un PLACEHOLDER: verifique antes de publicar.
  trust: [
    { label: 'Trayectoria', text: '+15 años de experiencia', demo: true },
    { label: 'Servicio',    text: 'Atención personalizada' },
    { label: 'Enfoque',     text: 'Estrategia jurídica integral' },
    { label: 'Ubicación',   text: 'Ciudad de México · México' }
  ],

  /* -------------------------------- Nosotros ------------------------------- */
  about: {
    eyebrow: 'Nosotros',
    title: 'Experiencia jurídica. <em>Visión estratégica.</em>',
    lead: 'Somos un despacho jurídico que entiende que detrás de cada asunto hay una decisión importante. Nuestro trabajo consiste en analizar con rigor, anticipar escenarios y acompañar a nuestros clientes con claridad en cada etapa.',
    principles: [
      { title: 'Filosofía',
        text: 'Creemos en un ejercicio profesional sobrio, ético y orientado a la comprensión: el cliente debe entender su situación, sus alternativas y sus riesgos.' },
      { title: 'Enfoque de cada asunto',
        text: 'Cada caso se estudia de manera individual, con una revisión técnica de los hechos, la normativa aplicable y los objetivos de quien nos consulta.' },
      { title: 'Atención personalizada',
        text: 'Comunicación directa, tiempos de respuesta razonables y un interlocutor claro durante todo el proceso.' },
      { title: 'Confidencialidad',
        text: 'Tratamos la información con discreción y conforme a nuestro Aviso de Privacidad y a los deberes profesionales aplicables.' }
    ],
    cta: 'Conocer el despacho',
    caption: 'Rigor · Criterio · Discreción',
    imageAlt: 'Composición arquitectónica de arcos concentricos en tonos azul noche y champán'
  },

  /* ------------------------------ Áreas de práctica ---------------------------- */
  // Cada área puede convertirse en su propia página SEO: /areas/<slug>
  areasIntro: {
    eyebrow: 'Áreas de práctica',
    title: 'Asesoría especializada, <em>con criterio</em> propio.',
    text: 'Seis áreas de práctica que cubren las necesidades jurídicas más frecuentes de personas y empresas.'
  },
  areas: [
    {
      slug: 'corporativo', num: '01', icon: 'building',
      title: 'Derecho Corporativo',
      short: 'Estructuración, gobierno corporativo y operaciones societarias para empresas en cada etapa de su desarrollo.',
      seoTitle: 'Derecho Corporativo en Ciudad de México',
      seoDescription: 'Asesoría en constitución de sociedades, contratos corporativos, gobierno corporativo y operaciones societarias en Ciudad de México.',
      overview: 'Acompañamos a empresas y socios en la planeación jurídica de sus decisiones: desde la elección de la estructura adecuada hasta la documentación y ejecución de operaciones societarias. Buscamos que cada acto corporativo cuente con soporte jurídico claro y ordenado.',
      matters: ['Constitución y estructuración de sociedades', 'Convenios entre socios y accionistas', 'Actas, libros y cumplimiento corporativo', 'Contratos comerciales y corporativos', 'Reorganizaciones y operaciones societarias'],
      needs: ['Definir la estructura jurídica más adecuada para un proyecto', 'Ordenar la documentación societaria de la empresa', 'Regular la relación entre socios de forma clara', 'Contar con revisión previa antes de una operación relevante'],
      process: [
        ['Diagnóstico', 'Revisamos el contexto, la estructura actual y los objetivos.'],
        ['Propuesta', 'Presentamos alternativas jurídicas y sus implicaciones.'],
        ['Documentación', 'Redactamos y negociamos los instrumentos necesarios.'],
        ['Seguimiento', 'Acompañamos la implementación y el cumplimiento posterior.']
      ],
      faqs: [
        ['¿Cuándo conviene revisar la estructura societaria?', 'Es recomendable hacerlo ante el ingreso de nuevos socios, cambios de actividad, nuevas inversiones o antes de operaciones relevantes. La revisión debe adaptarse a cada caso.'],
        ['¿Pueden ayudar con documentación corporativa atrasada?', 'Podemos revisar el estado de la documentación y proponer una ruta para su regularización, sujeto al análisis del caso.']
      ]
    },
    {
      slug: 'litigio', num: '02', icon: 'columns',
      title: 'Litigio y Controversias',
      short: 'Representación rigurosa y estrategia procesal en controversias judiciales y alternativas de solución.',
      seoTitle: 'Litigio y Controversias en Ciudad de México',
      seoDescription: 'Representación y estrategia procesal en controversias civiles y mercantiles, así como mecanismos alternativos de solución de conflictos.',
      overview: 'Analizamos cada controversia con una visión estratégica: evaluamos el marco jurídico, las pruebas disponibles y las alternativas de solución, incluyendo la negociación cuando resulta conveniente. La representación se conduce con rigor técnico y comunicación constante con el cliente.',
      matters: ['Juicios civiles y mercantiles', 'Estrategia procesal y ofrecimiento de pruebas', 'Medios de impugnación', 'Negociación y mediación', 'Cobranza judicial y extrajudicial'],
      needs: ['Evaluar la viabilidad y los riesgos de una controversia', 'Contar con representación en un procedimiento judicial', 'Explorar una solución negociada antes de litigar', 'Preparar la defensa ante una reclamación'],
      process: [
        ['Evaluación', 'Estudiamos hechos, documentos y el marco jurídico aplicable.'],
        ['Estrategia', 'Definimos la ruta procesal y los escenarios posibles.'],
        ['Representación', 'Conducimos el procedimiento con seguimiento puntual.'],
        ['Informe', 'Mantenemos informado al cliente en cada etapa relevante.']
      ],
      faqs: [
        ['¿Todo conflicto debe llegar a juicio?', 'No necesariamente. En muchos casos conviene explorar la negociación o la mediación. La mejor ruta depende de los hechos, los documentos y los objetivos del cliente.'],
        ['¿Pueden anticipar el resultado de un caso?', 'Ningún despacho serio puede garantizar resultados. Podemos ofrecer un análisis profesional de riesgos y alternativas con la información disponible.']
      ]
    },
    {
      slug: 'laboral', num: '03', icon: 'people',
      title: 'Derecho Laboral',
      short: 'Prevención, cumplimiento y defensa en materia laboral para empleadores y personas trabajadoras.',
      seoTitle: 'Derecho Laboral en Ciudad de México',
      seoDescription: 'Asesoría preventiva y representación en materia laboral: contratos, cumplimiento, terminación de relaciones de trabajo y procedimientos.',
      overview: 'Brindamos asesoría preventiva y representación en asuntos laborales, con especial atención a la claridad documental y al cumplimiento normativo. Trabajamos con empleadores que buscan ordenar sus prácticas y con personas que requieren orientación sobre sus derechos.',
      matters: ['Contratos individuales y colectivos', 'Reglamentos y políticas internas', 'Terminación de relaciones de trabajo', 'Procedimientos y conciliación laboral', 'Auditoría de cumplimiento laboral'],
      needs: ['Formalizar la relación laboral con documentación adecuada', 'Prevenir contingencias mediante una revisión interna', 'Recibir orientación ante una terminación laboral', 'Contar con representación en un procedimiento'],
      process: [
        ['Escucha', 'Conocemos el contexto laboral y la documentación existente.'],
        ['Análisis', 'Evaluamos obligaciones, riesgos y alternativas.'],
        ['Plan de acción', 'Proponemos una ruta preventiva o de defensa.'],
        ['Acompañamiento', 'Damos seguimiento hasta la conclusión del asunto.']
      ],
      faqs: [
        ['¿Atienden tanto a empresas como a personas trabajadoras?', 'Revisamos cada asunto de manera individual y, en su caso, evaluamos la posibilidad de atenderlo conforme a nuestros criterios profesionales.'],
        ['¿Qué documentos conviene reunir para una primera revisión?', 'Contratos, recibos de pago, comunicaciones relevantes y cualquier documento que describa la relación de trabajo.']
      ]
    },
    {
      slug: 'inmobiliario', num: '04', icon: 'house',
      title: 'Derecho Inmobiliario',
      short: 'Seguridad jurídica en la adquisición, arrendamiento, desarrollo y regularización de inmuebles.',
      seoTitle: 'Derecho Inmobiliario en Ciudad de México',
      seoDescription: 'Revisión jurídica de inmuebles, contratos de compraventa y arrendamiento, y asesoría en operaciones inmobiliarias en Ciudad de México.',
      overview: 'Apoyamos a compradores, vendedores, arrendadores, arrendatarios y desarrolladores en operaciones inmobiliarias, con énfasis en la revisión previa de la situación jurídica del inmueble y en la correcta documentación de cada transacción.',
      matters: ['Revisión jurídica de inmuebles (due diligence)', 'Compraventa y promesas de compraventa', 'Arrendamiento comercial y habitacional', 'Regularización y contratos de desarrollo', 'Controversias inmobiliarias'],
      needs: ['Verificar la situación jurídica de un inmueble antes de adquirirlo', 'Redactar o revisar un contrato de arrendamiento', 'Estructurar una operación inmobiliaria', 'Atender una controversia relacionada con un inmueble'],
      process: [
        ['Revisión', 'Analizamos títulos, antecedentes y documentación.'],
        ['Identificación', 'Señalamos riesgos y aspectos por atender.'],
        ['Contratación', 'Elaboramos y negociamos los instrumentos.'],
        ['Cierre', 'Acompañamos la formalización de la operación.']
      ],
      faqs: [
        ['¿Qué conviene revisar antes de comprar un inmueble?', 'Entre otros aspectos, la titularidad, gravámenes, antecedentes registrales y situación fiscal y administrativa del inmueble. La revisión debe adaptarse a cada operación.'],
        ['¿Pueden revisar un contrato de arrendamiento ya redactado?', 'Sí, podemos revisar el documento y señalar puntos que convenga aclarar o modificar antes de firmar.']
      ]
    },
    {
      slug: 'civil-mercantil', num: '05', icon: 'document',
      title: 'Derecho Civil y Mercantil',
      short: 'Contratos, obligaciones, sucesiones y operaciones comerciales con soporte técnico y visión práctica.',
      seoTitle: 'Derecho Civil y Mercantil en Ciudad de México',
      seoDescription: 'Elaboración y revisión de contratos, asesoría en obligaciones, sucesiones y operaciones mercantiles en Ciudad de México.',
      overview: 'Atendemos asuntos civiles y mercantiles de personas y empresas, desde la redacción y revisión de contratos hasta la planeación patrimonial y la atención de controversias, con una comunicación clara sobre alcances, riesgos y alternativas.',
      matters: ['Redacción y revisión de contratos', 'Obligaciones y responsabilidad civil', 'Títulos de crédito y operaciones mercantiles', 'Sucesiones y planeación patrimonial', 'Asesoría contractual continua para empresas'],
      needs: ['Revisar un contrato antes de firmarlo', 'Formalizar un acuerdo comercial', 'Planear la transmisión de un patrimonio', 'Atender un incumplimiento contractual'],
      process: [
        ['Contexto', 'Entendemos la operación y a las partes involucradas.'],
        ['Revisión', 'Analizamos obligaciones, riesgos y alternativas.'],
        ['Redacción', 'Elaboramos o ajustamos los documentos.'],
        ['Seguimiento', 'Apoyamos su ejecución y eventuales ajustes.']
      ],
      faqs: [
        ['¿Conviene revisar un contrato aunque parezca sencillo?', 'Sí. Aun los contratos breves pueden contener obligaciones, plazos o penalizaciones relevantes que conviene entender antes de firmar.'],
        ['¿Ofrecen asesoría continua a empresas?', 'Podemos acordar esquemas de acompañamiento acordes a las necesidades de cada organización.']
      ]
    },
    {
      slug: 'propiedad-intelectual', num: '06', icon: 'mark',
      title: 'Propiedad Intelectual',
      short: 'Protección y gestión de marcas, derechos de autor y activos intangibles de personas y empresas.',
      seoTitle: 'Propiedad Intelectual en Ciudad de México',
      seoDescription: 'Asesoría en registro y protección de marcas, derechos de autor, licencias y gestión de activos intangibles.',
      overview: 'Ayudamos a creadores y empresas a identificar, proteger y gestionar sus activos intangibles: marcas, obras y otros signos distintivos. Nuestra asesoría busca que la protección jurídica acompañe el crecimiento del negocio.',
      matters: ['Registro y renovación de marcas', 'Derechos de autor y obras', 'Contratos de licencia y cesión', 'Estrategia de protección de activos intangibles', 'Atención de infracciones y requerimientos'],
      needs: ['Proteger el nombre o el logotipo de un negocio', 'Formalizar la titularidad de una obra o desarrollo', 'Licenciar o ceder derechos de forma ordenada', 'Atender un posible uso indebido de una marca'],
      process: [
        ['Identificación', 'Detectamos los activos susceptibles de protección.'],
        ['Estrategia', 'Definimos la ruta de protección más adecuada.'],
        ['Trámite', 'Gestionamos registros y documentos.'],
        ['Vigilancia', 'Damos seguimiento a vigencias y renovaciones.']
      ],
      faqs: [
        ['¿Cuándo conviene registrar una marca?', 'Idealmente antes de invertir de forma importante en su uso comercial. Podemos revisar tu caso y explicar las alternativas disponibles.'],
        ['¿Pueden ayudar con contratos de licencia?', 'Sí, elaboramos y revisamos contratos de licencia y cesión de derechos, adaptados a cada proyecto.']
      ]
    }
  ],

  /* ------------------------------- Cómo trabajamos ---------------------------- */
  process: {
    eyebrow: 'Cómo trabajamos',
    title: 'Claridad desde <em>el primer paso.</em>',
    steps: [
      { num: '01', title: 'Escuchamos',  text: 'Entendemos el contexto y los objetivos.' },
      { num: '02', title: 'Analizamos',  text: 'Evaluamos riesgos, alternativas y escenarios.' },
      { num: '03', title: 'Diseñamos',   text: 'Construimos una estrategia jurídica.' },
      { num: '04', title: 'Acompañamos', text: 'Damos seguimiento durante todo el proceso.' }
    ]
  },

  /* ---------------------------------- Equipo --------------------------------- */
  // demo: PERFILES DE DEMOSTRACIÓN. No representan personas ni credenciales reales.
  team: {
    eyebrow: 'Equipo',
    title: 'El equipo detrás <em>de la estrategia.</em>',
    text: 'Perfiles de ejemplo: sustituya nombres, fotografías y datos por información real y verificable.'
  },
  attorneys: [
    {
      slug: 'socio-1', demo: true, image: 'assets/portrait-1.svg',
      name: '[Nombre y Apellido]', position: 'Socio(a) Director(a)',
      areas: ['Derecho Corporativo', 'Derecho Civil y Mercantil'],
      bioShort: '[Biografía breve. Describa formación, enfoque profesional y tipo de asuntos que atiende.]',
      bio: ['[Biografía completa, primer párrafo. Describa la trayectoria y el enfoque profesional de la persona, evitando adjetivos superlativos o promesas de resultados.]',
            '[Segundo párrafo. Puede incluir tipos de asuntos que atiende, sectores con los que trabaja y forma de colaborar con los clientes.]'],
      education: ['[Licenciatura en Derecho — Universidad]', '[Posgrado o especialidad — Institución]'],
      credentials: '[Cédula profesional / credenciales verificables]',
      experience: ['[Cargo — Organización / Despacho, periodo]', '[Cargo — Organización / Despacho, periodo]'],
      memberships: ['[Colegio o asociación profesional, si aplica]'],
      languages: ['Español', '[Idioma adicional, si aplica]'],
      linkedin: '#'
    },
    {
      slug: 'socio-2', demo: true, image: 'assets/portrait-2.svg',
      name: '[Nombre y Apellido]', position: 'Socio(a)',
      areas: ['Litigio y Controversias', 'Derecho Laboral'],
      bioShort: '[Biografía breve. Describa formación, enfoque profesional y tipo de asuntos que atiende.]',
      bio: ['[Biografía completa, primer párrafo.]', '[Segundo párrafo.]'],
      education: ['[Licenciatura en Derecho — Universidad]', '[Posgrado o especialidad — Institución]'],
      credentials: '[Cédula profesional / credenciales verificables]',
      experience: ['[Cargo — Organización / Despacho, periodo]', '[Cargo — Organización / Despacho, periodo]'],
      memberships: ['[Colegio o asociación profesional, si aplica]'],
      languages: ['Español', '[Idioma adicional, si aplica]'],
      linkedin: '#'
    },
    {
      slug: 'asociado-1', demo: true, image: 'assets/portrait-3.svg',
      name: '[Nombre y Apellido]', position: 'Asociado(a) Senior',
      areas: ['Derecho Inmobiliario', 'Propiedad Intelectual'],
      bioShort: '[Biografía breve. Describa formación, enfoque profesional y tipo de asuntos que atiende.]',
      bio: ['[Biografía completa, primer párrafo.]', '[Segundo párrafo.]'],
      education: ['[Licenciatura en Derecho — Universidad]', '[Posgrado o especialidad — Institución]'],
      credentials: '[Cédula profesional / credenciales verificables]',
      experience: ['[Cargo — Organización / Despacho, periodo]', '[Cargo — Organización / Despacho, periodo]'],
      memberships: ['[Colegio o asociación profesional, si aplica]'],
      languages: ['Español', '[Idioma adicional, si aplica]'],
      linkedin: '#'
    }
  ],

  /* -------------------------------- Experiencia -------------------------------- */
  // demo: NO SON CASOS REALES. Sustituir por casos autorizados y revisar su
  // publicación conforme a las reglas de publicidad profesional aplicables.
  experience: {
    eyebrow: 'Experiencia',
    title: 'Asuntos atendidos con <em>rigor y método.</em>',
    text: 'Ejemplos de la estructura con la que se presentarán los asuntos representativos del despacho.',
    demoLabel: 'CASO DE DEMOSTRACIÓN — REEMPLAZAR CON INFORMACIÓN REAL',
    disclaimer: 'La información presentada en esta sección deberá sustituirse por casos y resultados reales previamente autorizados para su publicación.'
  },
  cases: [
    { demo: true, matter: 'Operación corporativa', area: 'Derecho Corporativo',
      challenge: '[Descripción breve y anonimizada del reto que enfrentó el cliente.]',
      strategy: '[Descripción breve del enfoque jurídico adoptado.]',
      result: '[Descripción factual del resultado, sin promesas ni garantías y previamente autorizada.]' },
    { demo: true, matter: 'Controversia contractual', area: 'Litigio y Controversias',
      challenge: '[Descripción breve y anonimizada del reto que enfrentó el cliente.]',
      strategy: '[Descripción breve del enfoque jurídico adoptado.]',
      result: '[Descripción factual del resultado, sin promesas ni garantías y previamente autorizada.]' },
    { demo: true, matter: 'Operación inmobiliaria', area: 'Derecho Inmobiliario',
      challenge: '[Descripción breve y anonimizada del reto que enfrentó el cliente.]',
      strategy: '[Descripción breve del enfoque jurídico adoptado.]',
      result: '[Descripción factual del resultado, sin promesas ni garantías y previamente autorizada.]' }
  ],

  /* ------------------------------- Testimonios ------------------------------- */
  // demo: TESTIMONIOS DE DEMOSTRACIÓN — NO SON DE CLIENTES REALES.
  testimonialsIntro: { eyebrow: 'Testimonios', title: 'La experiencia <em>de quienes confían.</em>' },
  testimonials: [
    { demo: true, quote: 'La atención fue clara, profesional y estratégica durante todo el proceso.',
      author: 'Testimonio de demostración', area: 'Derecho Corporativo' },
    { demo: true, quote: 'Nos explicaron con claridad cada alternativa y los riesgos de cada decisión.',
      author: 'Testimonio de demostración', area: 'Derecho Civil y Mercantil' },
    { demo: true, quote: 'Una comunicación constante y un análisis riguroso en cada etapa del asunto.',
      author: 'Testimonio de demostración', area: 'Litigio y Controversias' }
  ],

  /* --------------------------------- Insights --------------------------------- */
  insightsIntro: {
    eyebrow: 'Insights', title: 'Insights <em>jurídicos.</em>',
    text: 'Análisis y criterios prácticos para tomar decisiones informadas. Contenido de carácter informativo.'
  },
  articleDisclaimer: 'Este artículo tiene fines exclusivamente informativos y no constituye asesoría legal ni crea una relación abogado-cliente. Cada situación requiere un análisis individual.',
  // demo: fechas y textos de ejemplo. Sustituir por publicaciones reales.
  articles: [
    {
      slug: 'estructurar-operacion-corporativa', demo: true, image: 'assets/article-1.svg',
      category: 'Corporativo', date: '2026-05-12', readTime: '6 min',
      title: 'Claves para estructurar correctamente una operación corporativa',
      excerpt: 'Antes de firmar, conviene ordenar objetivos, partes, riesgos y documentación. Una guía de puntos de revisión.',
      seoDescription: 'Puntos clave para planear y documentar una operación corporativa: objetivos, partes, riesgos, documentación y ejecución.',
      body: [
        ['', 'Una operación corporativa —desde la entrada de un nuevo socio hasta una reorganización— suele involucrar decisiones con efectos duraderos. Por ello, la planeación jurídica previa es tan importante como la negociación comercial.'],
        ['Defina objetivos y partes', 'El primer paso es precisar qué se busca lograr y quiénes intervienen. Una descripción clara evita ambigüedades que después se convierten en controversias.'],
        ['Identifique riesgos y alternativas', 'Cada estructura tiene implicaciones distintas en materia societaria, contractual, fiscal y operativa. Conviene analizarlas antes de elegir una ruta.'],
        ['Documente con precisión', 'Los instrumentos deben reflejar fielmente lo acordado: obligaciones, plazos, condiciones, mecanismos de terminación y solución de controversias.'],
        ['Planee la ejecución', 'Una operación bien estructurada considera también los actos posteriores: autorizaciones, registros internos y seguimiento del cumplimiento de lo pactado.']
      ]
    },
    {
      slug: 'que-revisar-antes-de-firmar-un-contrato', demo: true, image: 'assets/article-2.svg',
      category: 'Contratos', date: '2026-04-28', readTime: '5 min',
      title: 'Qué revisar antes de firmar un contrato',
      excerpt: 'Partes, objeto, plazos, penalizaciones y terminación: una lista de aspectos que conviene entender antes de comprometerse.',
      seoDescription: 'Aspectos esenciales que conviene revisar antes de firmar un contrato: partes, objeto, plazos, penalizaciones, terminación y solución de controversias.',
      body: [
        ['', 'Firmar un contrato implica asumir derechos y obligaciones que pueden tener consecuencias importantes. Leer con atención y comprender cada cláusula es una medida básica de prevención.'],
        ['Partes y objeto', 'Verifique que las partes estén correctamente identificadas y que el objeto del contrato describa con precisión lo que se acordó.'],
        ['Plazos, pagos y penalizaciones', 'Revise fechas, montos, formas de pago y consecuencias del incumplimiento. Las penalizaciones deben ser claras y proporcionales.'],
        ['Terminación y renovación', 'Entienda cómo y cuándo puede terminar el contrato, y si existen renovaciones automáticas o avisos previos que deban cumplirse.'],
        ['Solución de controversias', 'Identifique la ley aplicable y el mecanismo previsto para resolver diferencias, ya que influyen directamente en los costos y tiempos de un eventual conflicto.']
      ]
    },
    {
      slug: 'riesgos-juridicos-para-empresas', demo: true, image: 'assets/article-3.svg',
      category: 'Empresas', date: '2026-04-10', readTime: '7 min',
      title: 'Riesgos jurídicos que una empresa debería identificar',
      excerpt: 'Una mirada preventiva a los frentes contractual, laboral, societario y regulatorio que suelen requerir atención.',
      seoDescription: 'Riesgos jurídicos frecuentes en las empresas y cómo abordarlos de manera preventiva: contratos, relaciones laborales, documentación societaria y cumplimiento.',
      body: [
        ['', 'La prevención jurídica suele ser más eficiente que la reacción. Identificar a tiempo los riesgos permite tomar decisiones informadas y evitar contingencias evitables.'],
        ['Riesgo contractual', 'Contratos incompletos, desactualizados o sin formalizar son una fuente frecuente de conflicto con clientes, proveedores y socios.'],
        ['Riesgo laboral', 'La falta de documentación adecuada y de políticas internas claras puede generar contingencias en la relación con las personas trabajadoras.'],
        ['Riesgo societario', 'Libros, actas y convenios entre socios deben mantenerse ordenados y actualizados para sustentar las decisiones de la empresa.'],
        ['Riesgo regulatorio', 'Cada sector tiene obligaciones específicas. Una revisión periódica ayuda a identificar áreas que requieren atención.']
      ]
    }
  ],

  /* ------------------------------------ FAQ ------------------------------------ */
  faqIntro: { eyebrow: 'Preguntas frecuentes', title: 'Respuestas <em>claras.</em>',
    text: 'Si no encuentra la respuesta que busca, escríbanos y con gusto le orientaremos.' },
  faqs: [
    ['¿Cómo puedo agendar una consulta?', 'Puede solicitarlo mediante el formulario de contacto, por teléfono, por correo electrónico o por WhatsApp. Le responderemos para coordinar una primera conversación en el horario de atención.'],
    ['¿Qué información debo proporcionar en el primer contacto?', 'Basta con su nombre, un medio de contacto, el área de interés y una descripción general de su necesidad. Le pedimos no incluir información sensible ni documentos en el primer contacto; los solicitaremos si es necesario.'],
    ['¿Atienden consultas empresariales?', 'Sí. Atendemos a empresas y organizaciones en asuntos corporativos, contractuales, laborales, inmobiliarios y de propiedad intelectual, entre otros.'],
    ['¿Trabajan con clientes fuera de Ciudad de México?', 'Podemos evaluar asuntos de clientes ubicados en otras ciudades o países, sujeto a la naturaleza del asunto y a la posibilidad de atenderlo adecuadamente. La comunicación puede realizarse de forma remota.'],
    ['¿Cómo funciona el proceso de contratación?', 'Tras la primera conversación y el análisis inicial, se propone el alcance de los servicios y sus condiciones. La relación profesional se formaliza mediante un contrato de prestación de servicios.'],
    ['¿La información enviada mediante el formulario es confidencial?', 'Tratamos los datos conforme a nuestro Aviso de Privacidad. Sin embargo, enviar el formulario no constituye por sí mismo una relación abogado-cliente; por ello le pedimos no incluir detalles sensibles de su caso en este primer contacto.']
  ],

  /* ---------------------------------- CTA final --------------------------------- */
  cta: {
    title: 'Cuando una decisión importa, <em>la estrategia también.</em>',
    text: 'Conversemos sobre su situación y exploremos las alternativas disponibles.',
    primary: 'Agendar consulta',
    secondary: 'Contactar por WhatsApp'
  },

  /* ---------------------------------- Contacto ---------------------------------- */
  contactSection: {
    eyebrow: 'Contacto', title: 'Iniciemos una <em>conversación.</em>',
    text: 'Cuéntenos de forma general su necesidad. Nos pondremos en contacto para coordinar una primera conversación.',
    submit: 'Solicitar contacto',
    consent: 'He leído el',
    consentLink: 'Aviso de Privacidad',
    disclaimer: 'Enviar este formulario no constituye por sí mismo una relación abogado-cliente. Por favor, no incluya información sensible ni detalles confidenciales de su caso.',
    success: 'Hemos recibido su solicitud. Nos pondremos en contacto en el horario de atención.'
  },

  /* ---------------------------------- Legal ---------------------------------- */
  legal: {
    footerDisclaimer: 'La información de este sitio tiene carácter informativo y publicitario y no constituye asesoría legal. La relación abogado-cliente se establece únicamente mediante un acuerdo formal de servicios.',
    pages: {
      privacidad: {
        title: 'Aviso de Privacidad',
        sections: [
          ['Responsable', 'El responsable del tratamiento de sus datos personales es [NOMBRE DEL DESPACHO], con domicilio en [DOMICILIO].'],
          ['Datos que recabamos', 'Nombre, correo electrónico, teléfono, área de interés y el mensaje que usted nos comparta mediante el formulario de contacto.'],
          ['Finalidades', 'Atender su solicitud de contacto, coordinar una primera conversación y dar seguimiento a su consulta.'],
          ['Derechos', 'Usted puede ejercer los derechos que la legislación aplicable le reconoce sobre sus datos personales mediante solicitud enviada a [CORREO DE PRIVACIDAD].'],
          ['Cambios', 'Cualquier modificación a este aviso se publicará en este sitio.']
        ]
      },
      terminos: {
        title: 'Términos y condiciones',
        sections: [
          ['Uso del sitio', 'El contenido de este sitio es informativo. Su consulta no genera obligaciones para el despacho ni para el usuario.'],
          ['Propiedad intelectual', 'Los contenidos, marcas y materiales de este sitio pertenecen a [NOMBRE DEL DESPACHO] o se utilizan con autorización.'],
          ['Enlaces de terceros', 'Este sitio puede contener enlaces a sitios de terceros sobre los cuales el despacho no ejerce control.'],
          ['Legislación aplicable', '[Defina la legislación y jurisdicción aplicables.]']
        ]
      },
      disclaimer: {
        title: 'Disclaimer legal',
        sections: [
          ['Carácter informativo', 'La información publicada en este sitio es de carácter general e informativo y no constituye asesoría legal ni sustituye el análisis de un caso concreto.'],
          ['Relación abogado-cliente', 'El envío de un formulario, correo o mensaje no establece por sí mismo una relación abogado-cliente. Esta relación se formaliza únicamente mediante un acuerdo de servicios.'],
          ['Ausencia de garantías', 'Los resultados de asuntos anteriores no garantizan resultados futuros. Cada asunto es distinto.'],
          ['Contenido de demostración', 'Cualquier contenido marcado como demostración deberá reemplazarse por información real antes de su publicación.']
        ]
      }
    }
  }
};
