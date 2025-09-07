document.addEventListener('DOMContentLoaded', () => {
  const popup = document.getElementById('contactPopup');
  const popupClose = document.getElementById('popupClose');

  if (!popup || !popupClose) return; // veiligheid als elementen ontbreken

  // Popup tonen na 5 seconden
  setTimeout(() => {
    popup.classList.add('open');
  }, 5000);

  // Popup sluiten
  popupClose.addEventListener('click', () => {
    popup.classList.remove('open');
  });

  // Optioneel: sluiten met Escape-toets
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      popup.classList.remove('open');
    }
  });
});
