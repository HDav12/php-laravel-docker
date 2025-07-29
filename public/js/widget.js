document.addEventListener('DOMContentLoaded', () => {
  const widget = document.getElementById('widgetContainer');

  // Na 10 s de knop tonen
  setTimeout(() => widget.classList.add('active'), 7500);

  // Bij klikken doorverwijzen
  widget.addEventListener('click', () => {
    window.location.href = '/iframe.php';
  });
});
