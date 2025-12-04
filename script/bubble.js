function displayBubble(event = null, message) {
    if (event) {
        event.preventDefault();
    }

    const bubble = document.getElementById('bubble');
    bubble.style.display = 'block';

    bubble.textContent = message;
  
    setTimeout(() => bubble.style.display = 'none', 4000);
}