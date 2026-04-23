</main>
<footer class="py-4 mt-auto" style="background: #2c3e50; color: white;">
    <div class="container text-center">
        <small>&copy; <?= date('Y') ?> Kvitter. <a href="#" data-bs-toggle="modal" data-bs-target="#gdprModal" style="color: #FEFFAF;">GDPR</a></small>
    </div>
</footer>

<div class="modal fade" id="gdprModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#78A2D2;color:white;">
                <h5 class="modal-title"><i class="fas fa-shield-alt"></i> GDPR Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Vi samlar endast nödvändig data (användarnamn, email, inlägg). Dina uppgifter är krypterade. Du kan radera ditt konto när som helst.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="canvas-clock.js"></script>
</body>
</html>