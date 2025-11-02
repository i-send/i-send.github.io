// -----------------------------
// script.js
// -----------------------------

// Taux de conversion approximatif vers MGA
const rates = { usd: 4500, eur: 4900, cad: 3300 };

// Récupération du formulaire et du bloc de prévisualisation
const form = document.getElementById('sendForm');
const preview = document.getElementById('preview');

if(form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche l'envoi réel du formulaire (simulation)

    const data = new FormData(form);
    const senderName = data.get('senderName');
    const senderEmail = data.get('senderEmail');
    const senderCountry = data.get('senderCountry');
    const receiverName = data.get('receiverName');
    const paymentMethod = data.get('paymentMethod');
    const receiverPhone = data.get('receiverPhone');
    const amount = parseFloat(data.get('amount'));
    const currency = data.get('currency');
    const note = data.get('note') || '-';

    // Calcul montant en MGA
    const amountMGA = (amount * rates[currency]).toLocaleString();

    // Prévisualisation
    preview.innerHTML = `
      <h3>Prévisualisation de votre transfert</h3>
      <p><strong>Expéditeur :</strong> ${senderName} (${senderEmail}) - ${senderCountry}</p>
      <p><strong>Bénéficiaire :</strong> ${receiverName}</p>
      <p><strong>Mode de réception :</strong> ${paymentMethod}</p>
      <p><strong>Numéro destinataire / Agence :</strong> ${receiverPhone}</p>
      <p><strong>Montant :</strong> ${amount} ${currency.toUpperCase()} (~${amountMGA} MGA)</p>
      <p><strong>Message :</strong> ${note}</p>
      <p style="color:#b8860b; font-weight:bold;">Cliquez sur "Envoyer" pour confirmer (simulation)</p>
    `;
    preview.style.display = 'block';
  });
}
