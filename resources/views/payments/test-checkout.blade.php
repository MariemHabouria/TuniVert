
                <!DOCTYPE html>
                <html lang="en">
                <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Paiement • e‑DINAR</title>
                        <link href="/css/bootstrap.css" rel="stylesheet">
                        <link href="/css/style.css" rel="stylesheet">
                        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
                        <style>
                                body { background: #f8fafc; }
                                .payment-container {
                                        max-width: 800px; margin: 2rem auto; padding: 2rem; background: #fff;
                                        border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.08);
                                }
                                .payment-header { text-align: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb; }
                .header-top {
                    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
                    flex-wrap: wrap;
                }
                .edinar-badge { display: inline-flex; align-items: center; gap: .5rem; background: #f0fdf4; color: #065f46; border: 1px solid #10b981; border-radius: 999px; padding: .4rem .75rem; font-weight: 600; }
                .edinar-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block; }
                                .payment-method { border: 2px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; transition: all .3s; position: relative; display: block; }
                                .payment-method.selected { border-color: #10b981; background: #f0fdf4; }
                                .payment-icon { width: 60px; height: 60px; margin-right: 1rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; }
                                .edinar-icon { background: linear-gradient(135deg, #dc2626, #b91c1c); }
                                .payment-details { flex-grow: 1; }
                                .payment-name { font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: .25rem; }
                                .payment-description { color: #6b7280; font-size: .9rem; }
                                .payment-form { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #e5e7eb; }
                                .form-label { font-weight: 500; color: #374151; }
                                .field-note { font-size: .8rem; color: #6b7280; }
                                .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
                                .security-info { background: #f8fafc; padding: 1rem; border-radius: 6px; margin-top: 1.5rem; border-left: 4px solid #10b981; }
                                .btn-primary { background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; }
                .hero-illustration { background: linear-gradient(135deg, #fef3c7, #e0f2fe); border: 1px solid #fde68a; border-radius: 12px; padding: 1rem; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: center; }
                .hero-illustration img, .hero-illustration svg { max-width: 340px; width: 100%; height: auto; object-fit: contain; }
                        </style>
                </head>
                <body>
                <div class="container">
                    <div class="payment-container">
                                                <div class="payment-header">
                                                        <div class="header-top">
                                                                <div>
                                                                        <h2 class="mb-1">Paiement e‑DINAR</h2>
                                                                        <p class="text-muted mb-0">Aucune donnée réelle n’est transmise. Test local uniquement.</p>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-2">
                                                                        <span class="edinar-badge"><span class="dot"></span> e‑DINAR</span>
                                                                        <div class="badge bg-primary-subtle text-primary border border-primary">{{ number_format($amount, 3) }} TND</div>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="hero-illustration">
                                                    <img src="/img/d17.png" alt="e‑DINAR illustration" />
                                                </div>

                        <form method="POST" action="{{ route('payments.test.complete') }}" id="paymentForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="payment-method selected" data-method="edinar">
                                <div class="d-flex align-items-center">
                                    <div class="payment-icon edinar-icon"><i class="fas fa-credit-card"></i></div>
                                    <div class="payment-details">
                                        <div class="payment-name">E‑Dinar</div>
                                        <div class="payment-description">Payer avec votre carte e‑Dinar (simulation)</div>
                                    </div>
                                </div>

                                <div class="payment-form" id="edinar-form">
                                        @if ($errors->any())
                                            <div class="alert alert-danger py-2">
                                                <ul class="mb-0 ps-3 small">
                                                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label" for="edinar_card">Numéro de carte</label>
                                            <input type="text" class="form-control mono" id="edinar_card" name="card_number" placeholder="1234 5678 9012 3456" maxlength="23" inputmode="numeric" autocomplete="off" required>
                                            <div class="field-note">12 à 19 chiffres. Format libre pour test.</div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="edinar_expiry">Date d’expiration</label>
                                                <input type="text" class="form-control mono" id="edinar_expiry" name="expiry" placeholder="MM/YY" maxlength="5" autocomplete="off">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="edinar_cvv">CVV</label>
                                                <input type="password" class="form-control mono" id="edinar_cvv" name="cvv" placeholder="***" maxlength="4" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-1">
                                            <div class="col-md-6">
                                                <label class="form-label" for="edinar_pin">Code PIN</label>
                                                <input type="password" class="form-control mono" id="edinar_pin" name="pin" placeholder="****" maxlength="6" inputmode="numeric" autocomplete="off" required>
                                                <div class="field-note">4 à 6 chiffres (simulation).</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="edinar_cin">CIN</label>
                                                <input type="text" class="form-control mono" id="edinar_cin" name="cin" placeholder="01234567" maxlength="12" inputmode="numeric" autocomplete="off" required>
                                                <div class="field-note">8 chiffres (simulation).</div>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <div class="security-info">
                                <i class="fas fa-shield-alt icon text-success me-1"></i>
                                Vos informations sont utilisées uniquement pour la simulation locale.
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3" id="payButton">
                                <i class="fas fa-lock"></i> Payer {{ number_format($amount, 3) }} TND
                            </button>
                        </form>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const number = document.getElementById('edinar_card');
                    const expiry = document.getElementById('edinar_expiry');
                    const payButton = document.getElementById('payButton');

                    if (number) number.focus();

                    // Format card number groups of 4
                    number?.addEventListener('input', function(e){
                        let v = e.target.value.replace(/\s+/g,'').replace(/[^0-9]/g,'');
                        e.target.value = (v.match(/.{1,4}/g) || []).join(' ').substr(0, 23);
                    });

                    // Format expiry as MM/YY
                    expiry?.addEventListener('input', function(e){
                        let v = e.target.value.replace(/[^0-9]/g,'');
                        if (v.length >= 3){ v = v.substring(0,2) + '/' + v.substring(2,4); }
                        e.target.value = v.substring(0,5);
                    });

                    // Submit UX
                    document.getElementById('paymentForm')?.addEventListener('submit', function(){
                        payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement…';
                        payButton.disabled = true;
                    });
                });
                </script>
                </body>
                </html>
