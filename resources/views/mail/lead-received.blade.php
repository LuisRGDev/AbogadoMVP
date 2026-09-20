<!DOCTYPE html>
<html lang="es">
<body style="margin:0;padding:24px;background:#f5f1e8;font-family:Arial,Helvetica,sans-serif;color:#0b1220;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e3dccb;">
        <tr><td style="background:#0b1220;padding:20px 28px;color:#c5a46d;font-size:12px;letter-spacing:.2em;text-transform:uppercase;">
            {{ $contact->type->value === 'appointment' ? 'Nueva solicitud de cita' : 'Nueva consulta desde el sitio web' }}
        </td></tr>
        <tr><td style="padding:28px;">
            <h1 style="margin:0 0 18px;font-size:22px;font-weight:normal;">{{ $contact->name }}</h1>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.6;">
                <tr><td style="color:#6b7280;width:130px;padding:4px 0;">Correo</td><td><a href="mailto:{{ $contact->email }}" style="color:#0b1220;">{{ $contact->email }}</a></td></tr>
                @if($contact->phone)<tr><td style="color:#6b7280;padding:4px 0;">Teléfono</td><td>{{ $contact->phone }}</td></tr>@endif
                @if($contact->area)<tr><td style="color:#6b7280;padding:4px 0;">Área de interés</td><td>{{ $contact->area }}</td></tr>@endif
                @if($contact->preferred_date)
                    <tr><td style="color:#6b7280;padding:4px 0;">Fecha preferida</td><td>{{ format_date($contact->preferred_date) }}</td></tr>
                    <tr><td style="color:#6b7280;padding:4px 0;">Horario</td><td>{{ $contact->slotLabel() }}</td></tr>
                    <tr><td style="color:#6b7280;padding:4px 0;">Modalidad</td><td>{{ $contact->modeLabel() }}</td></tr>
                @endif
            </table>
            <p style="margin:22px 0 6px;color:#6b7280;font-size:12px;text-transform:uppercase;letter-spacing:.14em;">Mensaje</p>
            <p style="margin:0;font-size:15px;line-height:1.7;white-space:pre-line;">{{ $contact->message }}</p>
            <p style="margin:28px 0 0;"><a href="{{ url('/admin/contacts/'.$contact->id) }}" style="display:inline-block;background:#c5a46d;color:#0b1220;text-decoration:none;padding:12px 22px;font-size:13px;font-weight:bold;letter-spacing:.08em;">Abrir en el panel</a></p>
        </td></tr>
    </table>
</body>
</html>
