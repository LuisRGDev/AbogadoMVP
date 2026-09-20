@php
    $lines = '';
    for ($x = -100; $x < 1700; $x += 92) {
        $lines .= '<line x1="'.$x.'" y1="0" x2="'.($x + 60).'" y2="560" stroke="#C5A46D" stroke-opacity=".11"/>';
    }
    for ($y = 30; $y < 560; $y += 78) {
        $lines .= '<line x1="0" y1="'.$y.'" x2="1600" y2="'.($y - 22).'" stroke="#C5A46D" stroke-opacity=".11"/>';
    }
@endphp
<svg viewBox="0 0 1600 560" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Mapa estilizado de la ciudad (marcador de posición)">
    <rect width="1600" height="560" fill="#101828"/>
    <polygon points="120,330 420,300 460,470 180,520" fill="#C5A46D" fill-opacity=".05"/><polygon points="1050,60 1330,90 1290,240 1010,200" fill="#C5A46D" fill-opacity=".05"/>
    {!! $lines !!}
    <line x1="-40" y1="520" x2="1640" y2="70" stroke="#C5A46D" stroke-opacity=".36" stroke-width="7"/><line x1="-40" y1="520" x2="1640" y2="70" stroke="#101828" stroke-opacity=".8" stroke-width="2"/>
    <line x1="700" y1="-10" x2="900" y2="580" stroke="#C5A46D" stroke-opacity=".26" stroke-width="4"/><line x1="0" y1="250" x2="1600" y2="210" stroke="#C5A46D" stroke-opacity=".26" stroke-width="4"/>
</svg>
<span class="map__pin" aria-hidden="true"><i></i><svg width="34" height="44" viewBox="0 0 34 44"><path d="M17 43S32 29 32 16a15 15 0 1 0-30 0c0 13 15 27 15 27z" fill="#C5A46D"/><circle cx="17" cy="16" r="5.5" fill="#0B1220"/></svg></span>
