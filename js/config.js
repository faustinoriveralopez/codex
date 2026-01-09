// Configuración Global
const CONFIG = {
    // Detectar si estamos en local o producción para facilitar desarrollo
    // Si estás usando una estructura diferente, ajusta estas URLs
    API_URL: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
        ? 'http://localhost/api.php'  // Ajusta esto a tu ruta local si es necesario
        : 'https://secretariadeauditoriaoaxaca.rf.gd/api.php',

    AUTH_URL: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
        ? 'http://localhost/auth.php'
        : 'https://secretariadeauditoriaoaxaca.rf.gd/auth.php'
};
