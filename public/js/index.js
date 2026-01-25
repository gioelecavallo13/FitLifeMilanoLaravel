// ------------------- Contatori animati -------------------
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        let count = +counter.innerText;
        const increment = target / 200; // circa 0.5 secondi
        if(count < target) {
            counter.innerText = Math.ceil(count + increment);
            requestAnimationFrame(updateCount);
        } else {
            counter.innerText = target;
        }
    };
    updateCount();
});

// ------------------- Scroll banner -------------------
const scrollWrapper = document.getElementById('scrollBanner');
const stats = document.querySelectorAll('.stat');

let leftPos; 
const speed = 4; // px per frame
let targetPos = null;
let animationId = null;

// Stat da centrare (quella dei "Kg sollevati" → index 1)
const middleStat = stats[1];

function calculateTarget() {
    const screenCenter = window.innerWidth / 2;
    const statOffset = middleStat.offsetLeft + middleStat.offsetWidth / 2;
    return screenCenter - statOffset;
}

function startAnimation() {
    cancelAnimationFrame(animationId); // blocco eventuali animazioni precedenti

    leftPos = window.innerWidth; // ricomincia da destra
    targetPos = calculateTarget();

    function animateScrollBanner() {
        leftPos -= speed;

        if (leftPos <= targetPos) {
            leftPos = targetPos;
            scrollWrapper.style.left = leftPos + 'px';
            return; // stop
        }

        scrollWrapper.style.left = leftPos + 'px';
        animationId = requestAnimationFrame(animateScrollBanner);
    }

    animationId = requestAnimationFrame(animateScrollBanner);
}

// avvio iniziale
startAnimation();

// ricalcolo se cambia la dimensione della finestra
window.addEventListener('resize', () => {
    startAnimation();
});
