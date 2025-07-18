ISTRUZIONI PER AVVIARE IL PROGETTO

1. Aprire XAMPP e avviare Apache e MySQL

2. Aprire phpMyAdmin (http://localhost/phpmyadmin)

3. Creare un nuovo database, ad esempio:
   nome: skiddies_db

4. Importare il file:
   database/skiddies_db.sql

   !!!ogni volta che si modifica il db, il file .sql va esportato ed importato nel progetto manualmente per poter caricare le modifiche su github!!!

   IMPLEMENTAZIONI:

   1. Controlla sessione e autenticazione

      Quando si effettua login, il server crea una sessione (es. $_SESSION['user'] = [...] in PHP).

      Il browser salva un cookie di sessione (es. PHPSESSID).

      Ad ogni richiesta successiva, il browser invia il cookie al server.

      Il server recupera la sessione associata e sa chi è l’utente.

      Se il cookie o la sessione mancano, il server non riconosce l’utente (e dice "non autorizzato").

   2.Se la sessione non rimane attiva significa che:
      Il cookie di sessione non viene inviato (ad esempio, mancano credentials: 'include' nelle fetch).

      Il server non salva correttamente la sessione.

      La sessione scade subito o viene distrutta.

      C’è un problema di configurazione CORS o cookie (es. cookie non accettati in cross-origin).

3. SANIFICAZIONE INPUT DA IMPLEMENTARE
  - per evitare path trasversal nel caricamento del video
  $safeName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($videoFile['name']));
  $filename = uniqid() . '_' . $safeName;