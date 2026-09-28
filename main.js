async function loadSteamData() {
    try {
        const response = await fetch('steam-proxy.php');

        if (!response.ok) {
            throw new Error(`HTTP chyba! Status: ${response.status}`);
        }

        const data = await response.json();

        document.getElementById('username').textContent = data?.profile?.name ?? 'Neznámý uživatel';
        document.getElementById('avatar').src = data?.profile?.avatar ?? '';

       
        const statusNames = {
            0: 'Offline',
            1: 'Online',
            2: 'Busy',
            3: 'Away',
            4: 'Snooze',
            5: 'Looking to trade',
            6: 'Looking to play'
        };

        const statusElement = document.getElementById('online-status');
        const status = data?.profile?.status ?? 0;
        console.log('status:', data.profile.status, document.getElementById('online-status'));
        statusElement.textContent = statusNames[status] ?? 'Neznámý';
        statusElement.style.color = status === 0 ? 'gray' : 'limegreen';
       

       /* const banElement = document.getElementById('ban-status');
        if (data.bans.has_ban) {
            banElement.textContent = `Banned! (Celkem: ${data.bans.total_bans})`;
            banElement.style.color = 'red';
        } else {
            banElement.textContent = `Čistý účet bez banů (Celkem: ${data.bans.total_bans})`;
            banElement.style.color = 'green';
        }*/

    } catch (error) {
        console.error('Při načítání dat nastala chyba:', error);
        document.getElementById('username').textContent = 'Chyba při načítání';
    }
}

loadSteamData();