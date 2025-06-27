const express = require('express');
const router = express.Router();
const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');
const db = require('../config/db');

// API per login
router.post('/login', (req, res) => {
    const { email, password } = req.body;
    
    db.query('SELECT * FROM users WHERE email = ?', [email], (err, results) => {
        if (err) return res.status(500).json({ error: err });

        if (results.length === 0) return res.status(400).json({ message: 'Utente non trovato' });

        const user = results[0];
        // Confronta la password
        bcrypt.compare(password, user.password, (err, isMatch) => {
        if (!isMatch) return res.status(400).json({ message: 'Password errata' });

        // Genera un token JWT
        const token = jwt.sign({ user_id: user.id }, 'segreto_jwt', { expiresIn: '1h' });
        res.json({ message: 'Login riuscito', token });
        });
    });
});

module.exports = router;
