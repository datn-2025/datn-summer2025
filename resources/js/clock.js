function startCurrentClock() {
    const daysOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];

    const updateClock = () => {
        const now = new Date();
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');

        const day = daysOfWeek[now.getDay()];
        const date = now.getDate().toString().padStart(2, '0');
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const year = now.getFullYear();

        document.getElementById('clock-time').innerText = `${hh}:${mm}:${ss}`;
        document.getElementById('clock-date').innerText = `${day}, ${date}/${month}/${year}`;
    };

    updateClock();
    setInterval(updateClock, 1000);
}

document.addEventListener('DOMContentLoaded', startCurrentClock);
