document.addEventListener('DOMContentLoaded', () => {
  const popup = document.getElementById('contactPopup');
  const popupClose = document.getElementById('popupClose');

  // Popup tonen na 15 seconden
  setTimeout(() => {
    popup.style.display = 'block';
  }, 5000);

  // Popup sluiten
  popupClose.addEventListener('click', () => {
    popup.style.display = 'none';
  });
});
