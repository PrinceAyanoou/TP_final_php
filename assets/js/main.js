// facelone/assets/js/main.js

document.addEventListener('DOMContentLoaded', () => {
  const mainEl = document.getElementById('app-content');
  if (!mainEl) {
    console.error('⚠️ #app-content introuvable !');
    return;
  }
  console.log('main.js chargé');

  // ── ROUTER SPA ──
  async function loadPage(url, push = true) {
    console.log('loadPage:', url);
    try {
      const sep = url.includes('?') ? '&' : '?';
      const res = await fetch(url + sep + 'ajax=1', { credentials: 'include' });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const html = await res.text();
      mainEl.innerHTML = html;
      initPage();
      if (push) history.pushState({ url }, '', url);
    } catch (e) {
      console.error('SPA load error', e);
      window.location.href = url;
    }
  }

  document.body.addEventListener('click', e => {
    const a = e.target.closest('a.spa-link');
    if (!a) return;
    e.preventDefault();
    loadPage(a.href);
  });

  window.addEventListener('popstate', e => {
    if (e.state?.url) loadPage(e.state.url, false);
  });

  // ── INITIALISER CHAQUE PAGE ──
  function initPage() {
    console.log('initPage:', window.location.pathname);

    // 1) Publication de posts
    const postForm = document.getElementById('form-post');
    if (postForm) {
      postForm.onsubmit = async e => {
        e.preventDefault();
        const fd = new FormData(postForm);
        try {
          const res = await fetch('/facelone/api/post.php', {
            method: 'POST',
            body: fd,
            credentials: 'include'
          });
          const j = await res.json();
          if (j.success) loadPage(window.location.pathname, false);
          else alert(j.error || 'Erreur publication');
        } catch {
          alert('Erreur réseau');
        }
      };
    }

    // 2) Chargement du feed
    const postsContainer = document.getElementById('posts-container');
    if (postsContainer) {
      let url = '/facelone/api/get_feed.php';
      if (window.Facelone?.userId) url += `?user_id=${window.Facelone.userId}`;
      fetch(url, { credentials: 'include' })
        .then(r => r.json())
        .then(d => {
          postsContainer.innerHTML = '';
          (d.feed || []).forEach(post => {
            const div = document.createElement('div');
            div.className = 'post';
            div.dataset.id = post.id;
            div.innerHTML = `
              <div class="post-header">
                <img src="/facelone/assets/images/${post.avatar}" class="avatar">
                <strong>${post.prenom} ${post.nom}</strong>
              </div>
              <div class="post-content">${post.contenu}</div>
              ${post.image ? `<img src="/facelone/assets/images/${post.image}" class="post-img">` : ''}
              <div class="post-actions">
                <button class="like" onclick="react(${post.id},'like')">👍 ${post.likes}</button>
                <button class="dislike" onclick="react(${post.id},'dislike')">👎 ${post.dislikes}</button>
              </div>
            `;
            postsContainer.appendChild(div);
            loadComments(post.id, div);
          });
        })
        .catch(console.error);
    }

    // 3) Like/Dislike
    window.react = async (postId, action) => {
      try {
        const res = await fetch('/facelone/api/like_post.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include',
          body: JSON.stringify({ id: postId, action })
        });
        const j = await res.json();
        if (!j.success) return alert(j.error || 'Erreur réaction');
        const p = document.querySelector(`.post[data-id="${postId}"]`);
        if (p) {
          p.querySelector('.like').textContent    = `👍 ${j.likes}`;
          p.querySelector('.dislike').textContent = `👎 ${j.dislikes}`;
        }
      } catch {
        alert('Erreur réseau');
      }
    };

    // 4) Commentaires
    async function loadComments(postId, postEl) {
      try {
        const r = await fetch(`/facelone/api/comments.php?post_id=${postId}`, { credentials: 'include' });
        const d = await r.json();
        if (!d.success) return;
        const sec = document.createElement('div');
        sec.className = 'comment-section';
        d.comments.forEach(c => {
          const cd = document.createElement('div');
          cd.className = 'comment';
          cd.innerHTML = `
            <img src="/facelone/assets/images/${c.avatar}" class="avatar">
            <div class="texte"><strong>${c.prenom} ${c.nom}</strong><br>${c.contenu}</div>
          `;
          sec.appendChild(cd);
        });
        const inp = document.createElement('input');
        inp.placeholder = 'Écrire un commentaire…';
        const btn = document.createElement('button');
        btn.textContent = 'Envoyer';
        btn.onclick = async () => {
          const txt = inp.value.trim();
          if (!txt) return;
          try {
            const r2 = await fetch('/facelone/api/comments.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              credentials: 'include',
              body: JSON.stringify({ post_id: postId, contenu: txt })
            });
            const d2 = await r2.json();
            if (d2.success) {
              sec.innerHTML = '';
              await loadComments(postId, postEl);
            } else {
              alert(d2.error || 'Erreur ajout commentaire');
            }
          } catch {
            alert('Erreur réseau');
          }
        };
        sec.appendChild(inp);
        sec.appendChild(btn);
        postEl.appendChild(sec);
      } catch (e) {
        console.error(e);
      }
    }

    // 5) Amis: trouver, demandes, liste
    const map = {
      liste_users: 'utilisateurs-container',
      pending:     'pending-container',
      liste:       'amis-container'
    };
    Object.entries(map).forEach(([action, id]) => {
      const container = document.getElementById(id);
      if (!container) return;
      fetch(`/facelone/api/amis.php?action=${action}`, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
          container.innerHTML = '';
          (Array.isArray(data) ? data : []).forEach(u => {
            const card = document.createElement('div');
            card.className = 'card user-card';
            if (action === 'liste_users') {
              card.innerHTML = `
                <img src="/facelone/assets/images/${u.avatar}" class="avatar">
                <strong>${u.prenom} ${u.nom}</strong>
                <button class="btn">Ajouter</button>`;
              card.querySelector('button').onclick = async () => {
                const fd = new FormData(); fd.append('to_id', u.id);
                const res = await fetch('/facelone/api/amis.php?action=add', {
                  method: 'POST', body: fd, credentials: 'include'
                });
                const j = await res.json();
                if (j.success) {
                  const b = card.querySelector('button');
                  b.textContent = 'En attente';
                  b.disabled = true;
                } else alert(j.message || 'Erreur');
              };
            } else if (action === 'pending') {
              card.innerHTML = `
                <img src="/facelone/assets/images/${u.avatar}" class="avatar">
                <strong>${u.prenom} ${u.nom}</strong>
                <button class="btn accept-btn">Accepter</button>
                <button class="btn reject-btn">Refuser</button>`;
              card.querySelector('.accept-btn').onclick = async () => {
                const fd = new FormData(); fd.append('relation_id', u.relation_id);
                await fetch('/facelone/api/amis.php?action=accepter', {
                  method: 'POST', body: fd, credentials: 'include'
                });
                card.remove();
              };
              card.querySelector('.reject-btn').onclick = async () => {
                const fd = new FormData(); fd.append('relation_id', u.relation_id);
                await fetch('/facelone/api/amis.php?action=refuser', {
                  method: 'POST', body: fd, credentials: 'include'
                });
                card.remove();
              };
            } else {
              card.innerHTML = `
                <img src="/facelone/assets/images/${u.avatar}" class="avatar">
                <strong>${u.prenom} ${u.nom}</strong>`;
            }
            container.appendChild(card);
          });
        })
        .catch(console.error);
    });

    // 6) Chat: contacts & conversation
    (function() {
      const contactsList = document.getElementById('contacts-list'),
            messagesDiv  = document.getElementById('messages'),
            convTitle    = document.getElementById('conv-title'),
            chatForm     = document.getElementById('chat-form'),
            msgInput     = document.getElementById('message-input');
      let currentToId = null;
      if (!contactsList || !messagesDiv || !chatForm) return;

      // Charger contacts
      fetch('/facelone/api/amis.php?action=liste', { credentials: 'include' })
        .then(r => r.json())
        .then(friends => {
          friends.forEach(f => {
            const d = document.createElement('div');
            d.className = 'contact';
            d.dataset.to = f.id;
            d.innerHTML = `
              <img src="/facelone/assets/images/${f.avatar}" class="avatar">
              <span>${f.prenom} ${f.nom}</span>`;
            d.onclick = () => {
              contactsList.querySelectorAll('.contact').forEach(c => c.classList.remove('active'));
              d.classList.add('active');
              currentToId = f.id;
              convTitle.textContent = `Conversation avec ${f.prenom} ${f.nom}`;
              messagesDiv.innerHTML = '<p style="padding:16px;">Chargement…</p>';
              msgInput.disabled = false;
              loadConv();
            };
            contactsList.appendChild(d);
          });
        })
        .catch(e => {
          console.error('Chat contacts error', e);
          contactsList.innerHTML = '<p style="padding:16px;">Erreur contacts.</p>';
        });

      // Charger conversation
      async function loadConv() {
        if (!currentToId) return;
        try {
          const res = await fetch(`/facelone/api/chat.php?to=${currentToId}`, { credentials: 'include' });
          if (!res.ok) throw new Error(res.status);
          const msgs = await res.json();
          messagesDiv.innerHTML = '';
          msgs.forEach(m => {
            const dv = document.createElement('div');
            dv.className = 'message' + (m.from_id === window.Facelone.userId ? ' you' : '');
            dv.innerHTML = `
              <img src="/facelone/assets/images/${m.avatar}" class="avatar">
              <div class="bubble">${m.message}</div>`;
            messagesDiv.appendChild(dv);
          });
          messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } catch (e) {
          console.error('Chat load error', e);
          messagesDiv.innerHTML = '<p style="padding:16px;">Erreur messages.</p>';
        }
      }

      // Envoyer message
      chatForm.onsubmit = async e => {
        e.preventDefault();
        const txt = msgInput.value.trim();
        if (!txt) return alert('Message vide');
        if (!currentToId) return alert('Sélectionnez un contact');
        try {
          const res = await fetch('/facelone/api/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ to: currentToId, message: txt })
          });
          if (!res.ok) throw new Error(res.status);
          const j = await res.json();
          if (j.success) {
            msgInput.value = '';
            loadConv();
          } else {
            alert(j.message || 'Erreur serveur');
          }
        } catch (e) {
          console.error('Chat send error', e);
          alert('Erreur réseau');
        }
      };

      msgInput.disabled = true;
    })();

    // 7) Admin & stats
    const statsContainer = document.getElementById('stats');
    if (statsContainer) {
      fetch('/facelone/api/admin.php?action=stats', { credentials: 'include' })
        .then(r => r.json())
        .then(d => {
          Object.entries(d.stats).forEach(([k, v]) => {
            const c = document.createElement('div');
            c.className = 'stat-card';
            c.innerHTML = `<h3>${k.replace('nb_','')}</h3><p>${v}</p>`;
            statsContainer.appendChild(c);
          });
        })
        .catch(console.error);
    }
    const usersTableBody = document.querySelector('#users-table tbody');
    if (usersTableBody) {
      fetch('/facelone/api/admin.php?action=list_users', { credentials: 'include' })
        .then(r => r.json())
        .then(d => {
          (d.users || []).forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${u.id}</td><td>${u.nom}</td><td>${u.email}</td><td>${u.role}</td>
              <td>
                <button onclick="changeRole(${u.id},'${u.role==='admin'?'utilisateur':'admin'}')">
                  Changer rôle
                </button>
                <button onclick="deleteUser(${u.id})">Supprimer</button>
              </td>`;
            usersTableBody.appendChild(tr);
          });
        })
        .catch(console.error);
    }
    window.changeRole = async (id, role) => {
      await fetch('/facelone/api/admin.php?action=changer_role', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ user_id: id, role })
      });
      location.reload();
    };
    window.deleteUser = async id => {
      await fetch('/facelone/api/admin.php?action=supprimer', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ user_id: id })
      });
      location.reload();
    };
  }

  // Démarrage initial
  loadPage(window.location.pathname, false);
});
