document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('clockCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    function draw() {
        const now = new Date();
        const h = now.getHours();
        const m = now.getMinutes();
        const s = now.getSeconds();
        const cx = canvas.width / 2, cy = canvas.height / 2, r = Math.min(cx, cy) - 10;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, 2 * Math.PI);
        ctx.fillStyle = '#78A2D2';
        ctx.fill();
        ctx.strokeStyle = '#FEFFAF';
        ctx.lineWidth = 3;
        ctx.stroke();

        for (let i = 0; i < 12; i++) {
            let ang = (i * 30 - 90) * Math.PI / 180;
            let x1 = cx + (r - 15) * Math.cos(ang);
            let y1 = cy + (r - 15) * Math.sin(ang);
            let x2 = cx + (r - 5) * Math.cos(ang);
            let y2 = cy + (r - 5) * Math.sin(ang);
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
        }

        
        let angHour = ((h % 12) * 30 + m * 0.5 - 90) * Math.PI / 180;
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.lineTo(cx + r * 0.5 * Math.cos(angHour), cy + r * 0.5 * Math.sin(angHour));
        ctx.lineWidth = 4;
        ctx.stroke();

        
        let angMin = (m * 6 + s * 0.1 - 90) * Math.PI / 180;
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.lineTo(cx + r * 0.7 * Math.cos(angMin), cy + r * 0.7 * Math.sin(angMin));
        ctx.lineWidth = 3;
        ctx.stroke();

       
        let angSec = (s * 6 - 90) * Math.PI / 180;
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.lineTo(cx + r * 0.8 * Math.cos(angSec), cy + r * 0.8 * Math.sin(angSec));
        ctx.strokeStyle = '#FEFFAF';
        ctx.lineWidth = 2;
        ctx.stroke();

       
        ctx.beginPath();
        ctx.arc(cx, cy, 5, 0, 2 * Math.PI);
        ctx.fillStyle = '#FEFFAF';
        ctx.fill();

        
        ctx.font = 'bold 12px Arial';
        ctx.fillStyle = '#FEFFAF';
        ctx.fillText(`${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`, cx, cy + 30);
    }
    draw();
    setInterval(draw, 1000);
});