  document.querySelectorAll('.toggle-feedback').forEach(button => {
    button.addEventListener('click', () => {
      const form = button.closest('.history-item').querySelector('.feedback-form');
      form.style.display = form.style.display === 'block' ? 'none' : 'block';
    });
  });

  