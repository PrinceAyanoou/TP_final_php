// facelone/assets/js/auth.js

document.addEventListener('DOMContentLoaded', () => {
  const reg = document.getElementById('register-form'),
        log = document.getElementById('login-form'),
        adminLog = document.getElementById('admin-login-form');

  // ➤ Inscription
  if (reg) {
    reg.addEventListener('submit', async e => {
      e.preventDefault();
      const fd = new FormData(reg);
      const pass = fd.get('password'),
            conf = fd.get('confirm');
      if (pass !== conf) {
        return alert("Les mots de passe ne correspondent pas.");
      }
      try {
        const res = await fetch('/facelone/api/register.php', {
          method: 'POST',
          body: fd,
          credentials: 'include'
        });
        const d = await res.json();
        if (d.success) {
          alert(d.message || "Inscription réussie");
          window.location.href = '/facelone/vues/clients/login.php';
        } else {
          alert(d.message || "Erreur à l'inscription.");
        }
      } catch (err) {
        console.error('Register error:', err);
        alert("Erreur lors de la requête.");
      }
    });
  }

  // ➤ Connexion utilisateur
  if (log) {
    log.addEventListener('submit', async e => {
      e.preventDefault();
      const fd = new FormData(log);
      try {
        const res = await fetch('/facelone/api/login.php', {
          method: 'POST',
          body: fd,
          credentials: 'include'
        });
        const d = await res.json();
        if (d.success) {
          alert(d.message || "Connexion réussie");
          window.location.href = '/facelone/vues/clients/accueil.php';
        } else {
          alert(d.message || "Identifiants invalides");
        }
      } catch (err) {
        console.error('Login error:', err);
        alert("Erreur de connexion");
      }
    });
  }

  // ➤ Connexion admin/modo
  if (adminLog) {
    adminLog.addEventListener('submit', async e => {
      e.preventDefault();
      const fd = new FormData(adminLog);
      try {
        const res = await fetch('/facelone/api/login.php', {
          method: 'POST',
          body: fd,
          credentials: 'include'
        });
        const d = await res.json();
        if (d.success && ['admin', 'moderateur'].includes(d.user.role)) {
          alert("Connexion administrateur réussie");
          window.location.href = '/facelone/vues/back-office/dashboard.php';
        } else {
          alert("Accès refusé");
        }
      } catch (err) {
        console.error('Admin login error:', err);
        alert("Erreur lors de la connexion admin");
      }
    });
  }
});
