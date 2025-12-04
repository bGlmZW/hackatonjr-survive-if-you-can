const messages = [
"⚠️ Un homme se trouve devant chez vous",
"⚠️ Explosion près de votre position",
"⚠️ Attention à la sirène...",
"⚠️ Meurtre signalé près de votre position",
"⚠️ Ne reste pas là trop longtemps",
"⚠️ Pillage en cours"
];

function showBubble() {
    const bubble = document.getElementById('bubble');

    const randomIndex = Math.floor(Math.random() * messages.length);
    const message = messages[randomIndex];

    bubble.style.display = 'block';
    bubble.textContent = message;
  
    setTimeout(() => bubble.style.display = 'none', 4000);

    const randomDelay = Math.random() * (20000 - 10000) + 10000;
    setTimeout(showBubble, randomDelay);
}

setTimeout(showBubble, 5000);