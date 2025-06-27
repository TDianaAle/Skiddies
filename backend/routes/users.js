const express = require('express');
const router = express.Router();
const db = require('../config/db');
const jwt = require('jsonwebtoken');

// Middleware per autenticare il token JWT
function authenticateToken(req, res, next) {
    const token = req.header('Authorization');
    if (!token) return res.status(401).json({ message: 'Token mancante' });

    jwt.verify(token, 'segreto_jwt', (err, user) => {
        if (err) return res.status(403).json({ message: 'Token non valido' });
        req.user = user;
        next();
    });
}

// API per recuperare il profilo utente
router.get('/profile', authenticateToken, (req, res) => {
    const user_id = req.user.user_id;
    db.query('SELECT * FROM users WHERE id = ?', [user_id], (err, results) => {
        if (err) return res.status(500).json({ error: err });
        res.json(results[0]);
    });
});

module.exports = router;
