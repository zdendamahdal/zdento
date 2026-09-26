async function loadSteamProfile() {
    try {
        // Voláme NÁŠ backend, ne přímo Steam
        const response = await fetch('steam-proxy.php');
        if (!response.ok) throw new Error('Chyba při načítání backendu');

        const data = await response.json();
        const player = data.response.players[0]; // Steam vrací pole hráčů

        // Vykreslení dat do stránky
        const container = document.getElementById('profile');
        container.innerHTML = `
            <img src="${player.avatarfull}" alt="${player.personaname}">
            <h2>${player.personaname}</h2>
            <p>Status: ${player.personastate === 1 ? 'Online' : 'Offline'}</p>
        `;
    } catch (error) {
        console.error('Chyba:', error);
        document.getElementById('profile').innerText = 'Nepodařilo se načíst profil.';
    }
}

loadSteamProfile();