</main>

<footer class="py-4 mt-auto" style="background: #2c3e50; color: white;">
    <div class="container text-center">
        <small>
            &copy; <?= date('Y') ?> Kvitter. 
            <a href="#" data-bs-toggle="modal" data-bs-target="#gdprModal" style="color: #FEFFAF;">
                <i class="fas fa-shield-alt"></i> GDPR
            </a>
        </small>
    </div>
</footer>

<div class="modal fade" id="gdprModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #78A2D2; color: white;">
                <h5 class="modal-title"><i class="fas fa-shield-alt me-2"></i>GDPR Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Vi värnar om din integritet:</strong></p>
                <ul>
                    <li>Vi samlar endast nödvändig data (användarnamn, email, inlägg)</li>
                    <li>Dina uppgifter är krypterade med bcrypt</li>
                    <li>Du kan begära ut dina data</li>
                    <li>Du kan radera ditt konto när som helst (GDPR rätt att bli bortglömd)</li>
                    <li>Dina inlägg raderas automatiskt vid kontoborttagning</li>
                </ul>
                <hr>
                <p class="small text-muted mb-0">Kontakta oss för frågor om dina personuppgifter.</p>
            </div>
            <div class="modal-footer" style="background: #f8f9fa;">
                <button type="button" class="btn btn-sm" style="background: #78A2D2; color: white;" data-bs-dismiss="modal">Stäng</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>