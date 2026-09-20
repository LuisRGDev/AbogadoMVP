<!DOCTYPE html>
<html lang="es">
<body style="margin:0;padding:24px;background:#f5f1e8;font-family:Arial,Helvetica,sans-serif;color:#0b1220;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e3dccb;">
        <tr><td style="background:#0b1220;padding:20px 28px;color:#c5a46d;font-size:12px;letter-spacing:.2em;text-transform:uppercase;">{{ site()->name() }}</td></tr>
        <tr><td style="padding:28px;font-size:15px;line-height:1.7;">
            <h1 style="margin:0 0 16px;font-size:22px;font-weight:normal;">Hola, {{ \Illuminate\Support\Str::of($contact->name)->explode(' ')->first() }}.</h1>
            <p style="margin:0 0 14px;">Recibimos su {{ $contact->type->value === 'appointment' ? 'solicitud de cita' : 'consulta' }} y la revisaremos con la confidencialidad que merece. Le responderemos dentro del horario de atención: <strong>{{ site()->hours() }}</strong>.</p>
            @if($contact->preferred_date)
                <p style="margin:0 0 14px;padding:14px 18px;background:#f5f1e8;border-left:3px solid #c5a46d;">
                    Cita solicitada: <strong>{{ format_date($contact->preferred_date) }}</strong>, {{ \Illuminate\Support\Str::lower((string) $contact->slotLabel()) }} · {{ \Illuminate\Support\Str::lower((string) $contact->modeLabel()) }}.<br>
                    Le confirmaremos por este medio la fecha y hora definitivas.
                </p>
            @endif
            <p style="margin:0 0 14px;">Si su asunto es urgente, puede escribirnos o llamarnos{{ site()->phone() ? ' al '.site()->phone() : '' }}.</p>
            <p style="margin:24px 0 0;font-size:12px;color:#6b7280;">Este mensaje es una confirmación automática. Enviar el formulario no constituye por sí mismo una relación abogado-cliente.</p>
        </td></tr>
    </table>
</body>
</html>
