<?php include "header.php" ?>


  <!-- PRODUTOS: CARROSSEL + GRID -->
  <section id="produtos" class="py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Produtos em destaque</h2>
      </div>
    </div>

      <!-- Grid produtos -->
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
          <div class="card h-100">
            <img src="img/3x3.jpg" class="card-img-top" alt="3x3">
            <div class="card-body">
              <h5 class="card-title">Cubo 3x3 - Speed</h5>
              <p class="card-text">R$ 80,90</p>
              <button class="btn btn-brand w-100" data-product="Cubo 3x3 - Speed">Comprar</button>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100">
            <img src="img/4x4.jpg" class="card-img-top" alt="4x4">
            <div class="card-body">
              <h5 class="card-title">Cubo 4x4 - Competition</h5>
              <p class="card-text">R$ 140,90</p>
              <button class="btn btn-brand w-100" data-product="Cubo 4x4 - Competition">Comprar</button>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100">
            <img src="img/2x2.png" class="card-img-top" alt="speed">
            <div class="card-body">
              <h5 class="card-title">Cubo 2x2 - Pocket</h5>
              <p class="card-text">R$ 59,90</p>
              <button class="btn btn-brand w-100" data-product="Cubo 2x2 - Pocket">Comprar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- CONTATO / FORMULÁRIO FUNCIONAL 
  <section id="contato" class="py-5">
    <div class="container">
      <h3 class="mb-3">Contato / Orçamento</h3>
      <form id="contactForm" novalidate>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" required minlength="2">
            <div class="invalid-feedback">Por favor informe seu nome (min 2 caracteres).</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">E-mail inválido.</div>
          </div>
          <div class="col-12">
            <label class="form-label">Assunto</label>
            <select class="form-select" id="subject" required>
              <option value="">Escolha...</option>
              <option>Orçamento</option>
              <option>Reparo / Lubrificação</option>
              <option>Informações sobre o produto</option>
            </select>
            <div class="invalid-feedback">Escolha um assunto.</div>
          </div>
          <div class="col-12">
            <label class="form-label">Mensagem</label>
            <textarea class="form-control" id="message" rows="4" required minlength="10"></textarea>
            <div class="invalid-feedback">Escreva pelo menos 10 caracteres.</div>
          </div>
          <div class="col-12 d-grid">
            <button class="btn btn-brand btn-lg" type="submit">Enviar mensagem</button>
          </div>
        </div>
      </form>
    </div>
  </section>-->


  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/scripts.js"></script>
</body>
</html>

<?php include "footer.php" ?>
