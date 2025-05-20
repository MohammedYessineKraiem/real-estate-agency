// admin.js

function viewMessage(id) {
    const messageContent = document.getElementById('messageContent');
    const messageDetails = {
        1: "Bonjour, j'aimerais en savoir plus sur la réservation que j'ai effectuée. Pouvez-vous me fournir plus de détails sur le processus ?",
        2: "Pouvez-vous confirmer ma commande, s'il vous plaît ? Je suis impatient de recevoir la confirmation.",
        3: "J'ai quelques questions concernant les paiements. Pouvez-vous m'aider à comprendre le processus de facturation ?",
        4: "Merci pour votre aide. J'ai reçu ma réservation avec succès et tout est parfait !",
        5: "Je suis intéressé par l'offre de la maison à vendre, pouvez-vous m'envoyer plus de détails ?"
    };

    messageContent.innerHTML = messageDetails[id];
}

function sendReply() {
    const reply = document.getElementById('reply').value;
    if (reply.trim() !== "") {
        alert("Réponse envoyée : " + reply);
        document.getElementById('reply').value = ""; // Clear the textarea
    } else {
        alert("Veuillez entrer une réponse avant d'envoyer.");
    }
}

function logoutConfirmation() {
    const confirmLogout = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    if (confirmLogout) {
        window.location.href = 'LoginAdmin.html';
    }
}
