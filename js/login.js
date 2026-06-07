document.addEventListener('DOMContentLoaded', () => {
  'use strict'

  // Ahora sí, buscará los formularios cuando el HTML ya exista
  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})