<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Bank Transfer Donation</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #14532d; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #166534; }
        .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
        .success { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
    </style>
</head>
<body>
    <h1>🏦 Test Bank Transfer Donation Email</h1>
    <p>This form will test bank transfer donations and send real emails to your inbox.</p>
    
    <form id="donationForm">
        <div class="form-group">
            <label for="email">Your Email Address:</label>
            <input type="email" id="email" name="email" value="hamoudachkir@yahoo.fr" required>
        </div>
        
        <div class="form-group">
            <label for="amount">Donation Amount (TND):</label>
            <input type="number" id="amount" name="amount" value="25.00" min="1" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label for="anonymous">Anonymous Donation:</label>
            <select id="anonymous" name="anonymous">
                <option value="0">No - Show my name</option>
                <option value="1">Yes - Anonymous</option>
            </select>
        </div>
        
        <button type="submit">🚀 Test Bank Transfer Email</button>
    </form>
    
    <div id="result"></div>

    <script>
        document.getElementById('donationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('email', document.getElementById('email').value);
            formData.append('montant', document.getElementById('amount').value);
            formData.append('moyen_paiement', 'virement_bancaire');
            formData.append('is_anonymous', document.getElementById('anonymous').value);
            
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<p>⏳ Testing bank transfer email...</p>';
            
            try {
                const response = await fetch('/test-bank-donation', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.innerHTML = `
                        <div class="result success">
                            <h3>✅ Bank Transfer Email Test Successful!</h3>
                            <p><strong>Donation ID:</strong> ${result.donation_id}</p>
                            <p><strong>Amount:</strong> ${result.amount} TND</p>
                            <p><strong>Transaction ID:</strong> ${result.transaction_id}</p>
                            <p><strong>Email Sent To:</strong> ${result.email_sent_to}</p>
                            <p>📧 <strong>Check your email inbox for the donation receipt!</strong></p>
                            ${result.badges ? `<p>🏆 <strong>New Badges:</strong> ${result.badges}</p>` : ''}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="result error">
                            <h3>❌ Test Failed</h3>
                            <p><strong>Error:</strong> ${result.error}</p>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="result error">
                        <h3>❌ Network Error</h3>
                        <p><strong>Error:</strong> ${error.message}</p>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>