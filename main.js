// FIXME: 
console.log("script se spustil")

async function loadSteamData() {
    try {
        const response = await fetch('steam-proxy.php');
        // FIXME:
        

        if (!response.ok) {
            throw new Error(`HTTP chyba! Status: ${response.status}`);
        }

        const data = await response.json();
            console.log("data dorazila")
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


        const created = data.profile.created;   // číslo, např. 1325376000

        if (created) {
            const statusElement = document.getElementById('date');
            const date = new Date(created * 1000);              // JS chce milisekundy, proto * 1000
            const text = date.toLocaleDateString('cs-CZ');
            statusElement.textContent = `datum vytvoření ${text}` ?? 'Neznámý';   
            console.log("element-date", document.getElementById('date'))
            //FIXME:
            console.log("datum", text)   // např 1. 1. 2012
            // teď to text dosaď do HTML, třeba přes element.textContent = text;
}

        const totalHours = data?.totalHours;
        console.log('totalHours:', totalHours);

            if (totalHours !== undefined) {
                document.getElementById('hours').textContent = `Celkem odehráno: ${totalHours} h`;
            }
       

       

    } catch (error) {
        console.error('Při načítání dat nastala chyba:', error);
        document.getElementById('username').textContent = 'Chyba při načítání';
    }
}


loadSteamData();