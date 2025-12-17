

document.addEventListener("DOMContentLoaded", function () {

    
    temaYonetimi();

    
    if (document.querySelector('.slider-kapsayici')) {
        const anaSlider = new Slider('.slide', 5000); 
    }

   
    if (document.getElementById("gunun-sozu")) {
        sozGetir(); 
    }

    
    if (document.getElementById('sayac')) {
        sayaciBaslat();
    }
});


class Slider {
    constructor(selector, sure) {
        this.slides = document.querySelectorAll(selector);
        this.index = 0;
        this.sure = sure;
        this.interval = null;

        if (this.slides.length > 0) {
            this.init();
        }
    }

    init() {
        this.goster(this.index);
        this.otomatikBaslat();

        
        const solBtn = document.querySelector('.sol-btn');
        const sagBtn = document.querySelector('.sag-btn');

        if (solBtn) solBtn.addEventListener('click', () => { this.degistir(-1); });
        if (sagBtn) sagBtn.addEventListener('click', () => { this.degistir(1); });
    }

    goster(n) {
        
        if (n >= this.slides.length) this.index = 0;
        if (n < 0) this.index = this.slides.length - 1;

        
        this.slides.forEach(slide => {
            slide.classList.remove('aktif-slide');
            slide.style.display = 'none';
        });

        
        this.slides[this.index].style.display = 'block';
        this.slides[this.index].classList.add('aktif-slide');
    }

    degistir(n) {
        this.otomatikDurdur(); 
        this.index += n;
        this.goster(this.index);
        this.otomatikBaslat();
    }

    otomatikBaslat() {
        this.interval = setInterval(() => {
            this.index++;
            this.goster(this.index);
        }, this.sure);
    }

    otomatikDurdur() {
        clearInterval(this.interval);
    }
}


async function sozGetir() {
    const kutu = document.getElementById('gunun-sozu');
    const yazar = document.getElementById('soz-yazari');


    if (!kutu || !yazar) return;

    try {

        const hamVeri = [
            { "content": "Asla pes etme, mucizeler her gün olur.", "author": "Anonim" },
            { "content": "Bugün yapacağın egzersiz, yarın hissedeceğin güçtür.", "author": "Arnold Schwarzenegger" },
            { "content": "Sınırlarını zorla, çünkü sihir orada başlar.", "author": "Zirve Spor" },
            { "content": "Başlamak için mükemmel olmak zorunda değilsin.", "author": "Zig Ziglar" },
            { "content": "Vücudun, zihninin yapabileceğine inandığı her şeyi yapar.", "author": "Anonim" }
        ];


        const sanalApiUrl = 'data:application/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(hamVeri));

        const response = await fetch(sanalApiUrl);

        if (!response.ok) throw new Error("Veri işleme hatası");

        const sozlerListesi = await response.json();

        const rastgele = sozlerListesi[Math.floor(Math.random() * sozlerListesi.length)];

        kutu.innerHTML = `"${rastgele.content}"`;
        yazar.innerHTML = `- ${rastgele.author}`;

    } catch (error) {
        console.error("Söz yüklenemedi:", error);
        kutu.innerHTML = '"Asla pes etme!"';
        yazar.innerHTML = "- Zirve Spor";
    }
}


function tabloFiltrele() {
    const input = document.getElementById("tabloArama");
    const filter = input.value.toUpperCase();
    const table = document.querySelector("table");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) { 
        let td = tr[i].getElementsByTagName("td")[1]; 
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}



function temaYonetimi() {
    const temaButonu = document.getElementById("tema-btn");
    if (temaButonu) {
        const mevcutTema = localStorage.getItem("tema");
        if (mevcutTema === "koyu") {
            document.body.setAttribute("data-tema", "koyu");
            temaButonu.textContent = "☀️ Açık Mod";
        }
        temaButonu.addEventListener("click", function () {
            if (document.body.getAttribute("data-tema") === "koyu") {
                document.body.removeAttribute("data-tema");
                localStorage.setItem("tema", "acik");
                temaButonu.textContent = "🌙 Koyu Mod";
            } else {
                document.body.setAttribute("data-tema", "koyu");
                localStorage.setItem("tema", "koyu");
                temaButonu.textContent = "☀️ Açık Mod";
            }
        });
    }
}




window.haritaAc = function () {
    const modal = document.getElementById("haritaModal");
    if (modal) modal.style.display = "flex";
};

window.haritaKapat = function () {
    const modal = document.getElementById("haritaModal");
    if (modal) modal.style.display = "none";
};


window.sikayetAc = function () {
    const modal = document.getElementById("sikayetModal");
    if (modal) modal.style.display = "flex";
};

window.sikayetKapat = function () {
    const modal = document.getElementById("sikayetModal");
    if (modal) modal.style.display = "none";
};


window.onclick = function (event) {
    const haritaModal = document.getElementById("haritaModal");
    const sikayetModal = document.getElementById("sikayetModal");

    
    if (haritaModal && event.target == haritaModal) {
        haritaModal.style.display = "none";
    }

    
    if (sikayetModal && event.target == sikayetModal) {
        sikayetModal.style.display = "none";
    }
};

function sayaciBaslat() {
    const sayac = document.getElementById("sayac");
    if (!sayac) return;

    let hedef = new Date();
    hedef.setHours(24, 0, 0, 0);

    setInterval(() => {
        let simdi = new Date();
        let fark = hedef - simdi;
        if (fark <= 0) { hedef.setDate(hedef.getDate() + 1); fark = hedef - simdi; }

        let s = Math.floor((fark / 1000) % 60);
        let m = Math.floor((fark / 1000 / 60) % 60);
        let h = Math.floor((fark / (1000 * 60 * 60)) % 24);

        sayac.innerHTML = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
    }, 1000);
}


function dersleriFiltrele() {
    
    const input = document.getElementById("dersArama");
    const filter = input.value.toUpperCase();

    
    const dersler = document.querySelectorAll(".ders-item");

    
    dersler.forEach(ders => {
        
        const metin = ders.textContent || ders.innerText;

        
        if (metin.toUpperCase().indexOf(filter) > -1) {
            ders.style.display = ""; 
        } else {
            ders.style.display = "none"; 
        }
    });
}


