// Interatividade mínima: validação de formulário, smooth scroll e eventos simples
(function(){
  'use strict'

  // Smooth scroll for internal links
  document.querySelectorAll('a[href^="#"]').forEach(function(anchor){
    anchor.addEventListener('click', function(e){
      var href = this.getAttribute('href');
      if(href.length>1){
        e.preventDefault();
        document.querySelector(href).scrollIntoView({behavior:'smooth'});
      }
    });
  });

  // Contact form validation + success modal
  var contactForm = document.getElementById('contactForm');
  contactForm.addEventListener('submit', function(e){
    e.preventDefault();
    if(!contactForm.checkValidity()){
      contactForm.classList.add('was-validated');
      return;
    }
    // Simula envio: mostrar modal de sucesso usando Bootstrap Modal
    var modalHtml = '<div class="modal fade" id="successModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Mensagem enviada</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">Obrigado! Responderemos por e-mail.</div></div></div></div>';
    var div = document.createElement('div'); div.innerHTML = modalHtml; document.body.appendChild(div);
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    successModal._element.addEventListener('hidden.bs.modal', function(){
      successModal._element.remove();
    });
    contactForm.reset(); contactForm.classList.remove('was-validated');
  });

  // Newsletter simple handler
  var newsForm = document.getElementById('newsletterForm');
  newsForm.addEventListener('submit', function(e){
    e.preventDefault();
    var email = document.getElementById('newsEmail');
    if(!email.checkValidity()){ email.classList.add('is-invalid'); return; }
    email.classList.remove('is-invalid');
    // close modal and show tiny toast
    var newsletterModal = bootstrap.Modal.getInstance(document.getElementById('newsletterModal'));
    if(newsletterModal) newsletterModal.hide();
    var toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.innerHTML = '<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="me-auto">CuboMag</strong><small>agora</small><button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast"></button></div><div class="toast-body">Inscrição confirmada: '+email.value+'</div></div>';
    document.body.appendChild(toast);
    setTimeout(function(){ toast.remove(); }, 3500);
    newsForm.reset();
  });

  // Example event: click on Comprar preenche o formulário automaticamente
  document.querySelectorAll('[data-product]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var product = this.getAttribute('data-product');
      // preenche mensagem e rola para o formulário
      var msg = document.getElementById('message');
      msg.value = 'Gostaria de orçamento para: '+product;
      document.getElementById('name').focus();
      document.querySelector('#contato').scrollIntoView({behavior:'smooth'});
    });
  });

})();
