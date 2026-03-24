<section class="contact">
    <div class="container">
        <h1>Me contacter</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="contact-wrapper">
            <div class="contact-info">
                <h2>Informations</h2>
                <div class="info-item">
                    <h3>📧 Email</h3>
                    <p><a href="mailto:rakielsamuel9@gmail.com" style="color:inherit;">rakielsamuel9@gmail.com</a></p>
                </div>
                <div class="info-item">
                    <h3>📞 Téléphone</h3>
                    <p><a href="tel:+237689910193" style="color:inherit;">+237 689 910 193</a></p>
                </div>
                <div class="info-item">
                    <h3>📍 Localisation</h3>
                    <p>Ngaoundéré, Cameroun</p>
                </div>
                <div class="social-links">
                    <h3>Réseaux sociaux</h3>
                    <div class="social-icons">
                        <a href="https://github.com/JAVA275" target="_blank" title="GitHub">
                            GitHub
                        </a>
                        <a href="https://www.facebook.com/rakiel.samuel" target="_blank" title="Facebook">
                            Facebook
                        </a>
                        <a href="https://wa.me/237689910193" target="_blank" title="WhatsApp">
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <form action="<?= base_url('contact/send') ?>" method="POST">
                    <div class="form-group">
                        <label>Nom complet *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </form>
            </div>
        </div>
    </div>
</section>
