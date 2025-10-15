document.addEventListener("DOMContentLoaded", () => {
    console.log("Sistem Bengkel siap digunakan");

    const judulHeader = document.querySelector("header h1");
    if (judulHeader) {
        const teksAsli = judulHeader.textContent;
        judulHeader.textContent = "";
        let i = 0;
        const ketik = setInterval(() => {
            judulHeader.textContent += teksAsli.charAt(i);
            i++;
            if (i >= teksAsli.length) clearInterval(ketik);
        }, 60);
    }

    const footer = document.querySelector("footer");
    if (footer) {
        let aktifGlow = false;
        setInterval(() => {
            aktifGlow = !aktifGlow;
            footer.style.textShadow = aktifGlow
                ? "0 0 12px #00aaff, 0 0 24px #00aaff"
                : "none";
        }, 1200);
    }

    const kartuBengkel = document.querySelectorAll(".card");
    kartuBengkel.forEach(kartu => {
        kartu.addEventListener("mousemove", e => {
            const rect = kartu.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            kartu.style.transform = `rotateY(${x / 25}deg) rotateX(${y / -25}deg)`;
        });
        kartu.addEventListener("mouseleave", () => {
            kartu.style.transform = "rotateY(0deg) rotateX(0deg)";
        });
    });

    const tombolUtama = document.querySelectorAll(".btn");
    tombolUtama.forEach(btn => {
        btn.addEventListener("click", e => {
            const ripple = document.createElement("span");
            ripple.classList.add("ripple");
            btn.appendChild(ripple);

            const rect = btn.getBoundingClientRect();
            ripple.style.left = `${e.clientX - rect.left}px`;
            ripple.style.top = `${e.clientY - rect.top}px`;

            setTimeout(() => ripple.remove(), 600);
        });
    });
});