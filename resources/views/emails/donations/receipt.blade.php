@php
    $date = $donation->date_don instanceof \Illuminate\Support\Carbon ? $donation->date_don : \Illuminate\Support\Carbon::parse($donation->date_don);
    $event = $eventTitle ?? 'General Donation';
    $methodMap = [
        'paymee' => 'e‑Dinar',
        'test' => 'e‑Dinar',
        'carte' => 'Card',
        'paypal' => 'PayPal',
        'virement_bancaire' => 'Bank Transfer',
    ];
    $method = $methodMap[$donation->moyen_paiement] ?? ucfirst($donation->moyen_paiement);
@endphp

<div style="font-family: Arial, Helvetica, sans-serif; background:#f6f8fb; padding:24px; color:#0f172a;">
  <div style="max-width:640px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">
    <div style="background:#14532d; color:#fff; padding:20px 24px;">
      <h2 style="margin:0; font-size:20px;">Reçu de don – {{ $amountStr }}</h2>
      <p style="margin:6px 0 0; opacity:.9;">Code: <strong>{{ $code }}</strong></p>
    </div>
    <div style="padding:24px;">
      <p>Bonjour {{ $donation->user->name ?? 'Cher donateur' }},</p>
      <p>Merci pour votre don. Voici les détails de votre transaction:</p>
      <ul style="line-height:1.7;">
        <li><strong>Montant:</strong> {{ $amountStr }}</li>
        <li><strong>Événement:</strong> {{ $event }}</li>
        <li><strong>Méthode:</strong> {{ $method }}</li>
        @if($donation->transaction_id)
          <li><strong>Transaction:</strong> {{ $donation->transaction_id }}</li>
        @endif
        <li><strong>Date:</strong> {{ $date->format('d/m/Y H:i') }}</li>
      </ul>

      <div style="margin-top:24px; text-align:center;">
        <div style="display:inline-block; padding:20px; background:#ffffff; border:2px solid #14532d; border-radius:12px;">
          <h3 style="margin:0 0 12px; color:#14532d; font-size:16px;">📱 QR Code de votre don</h3>
          <div style="font-family: monospace; font-size:18px; font-weight:bold; color:#14532d; background:#f8fafc; padding:12px; border-radius:6px; letter-spacing:2px;">
            {{ $code }}
          </div>
          <p style="margin:12px 0 0; font-size:12px; color:#64748b;">
            � QR code scannable disponible en pièce jointe<br/>
            Présentez ce code lors des événements TuniVert
          </p>
        </div>
      </div>

      <p style="margin-top:24px;">Avec gratitude,<br/>L’équipe Tunivert</p>
    </div>
    <div style="background:#f1f5f9; padding:12px 24px; font-size:12px; color:#64748b;">
      Cet email a été envoyé automatiquement. Merci de ne pas y répondre.
    </div>
  </div>
</div>
