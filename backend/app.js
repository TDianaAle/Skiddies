const express = require('express');
const app = express();
const PORT = 3000;

// Importa le rotte di autenticazione e registrazione
const authRoutes = require('./routes/auth');
const userRoutes = require('./routes/user');

// Middleware per gestire il body delle richieste (in formato JSON)
app.use(express.json()); // Permette di leggere il corpo delle richieste in formato JSON

// Usa le rotte per login e registrazione
app.use('/api/auth', authRoutes);  // Ad esempio, POST /api/auth/login per il login
app.use('/api/user', userRoutes);  // Ad esempio, POST /api/user/register per la registrazione

// Rotta di test
app.get('/', (req, res) => {
    res.send('Hello from backend!');
});

// Avvia il server
app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});
