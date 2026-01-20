CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    area_id INT NOT NULL,
    position VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (area_id) REFERENCES areas(id)
);

CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internal_folio VARCHAR(50) UNIQUE,
    external_folio VARCHAR(50),
    subject VARCHAR(200) NOT NULL,
    description TEXT,
    doc_type VARCHAR(50) DEFAULT 'OFICIO',
    sender_dependency VARCHAR(100),
    sender_name VARCHAR(100),
    priority VARCHAR(20) DEFAULT 'NORMAL',
    status VARCHAR(20) DEFAULT 'RECIBIDO',
    current_area_id INT,
    current_user_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (current_area_id) REFERENCES areas(id),
    FOREIGN KEY (current_user_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS document_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id)
);

-- SEEDS
INSERT INTO roles (id, name, slug) VALUES
(1, 'Administrador TI', 'admin'),
(2, 'Mesa de Control', 'mesa_control'),
(3, 'Director General', 'director'),
(4, 'Jefe de Área', 'jefe_area'),
(5, 'Operador', 'operador');

INSERT INTO areas (id, name, code) VALUES
(1, 'Dirección General', 'DG'),
(2, 'Tecnologías de la Información', 'DTI'),
(3, 'Jurídico', 'JUR'),
(4, 'Normatividad y Seguimiento', 'NYS'),
(5, 'Dirección de Prestaciones', 'DP'),
(6, 'Prestaciones Económicas', 'PE'),
(7, 'Prestaciones Sociales', 'PS'),
(8, 'Prestaciones Policiales', 'PP'),
(9, 'Administración y Finanzas', 'UAF'),
(10, 'Contabilidad y Finanzas', 'CYF');

-- USERS (Password: 123456 -> e10adc3949ba59abbe56e057f20f883e)
INSERT INTO users (name, email, password, role_id, area_id, position) VALUES
('José Elpidio Altamirano López', 'direccion.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 3, 1, 'Director General'),
('Faustino Benjamín Rivera López', 'tecnologias.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 1, 2, 'Jefe DTI'),
('Aldo Pimentel López', 'juridico.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 3, 'Jefe Jurídico'),
('Fredy Martínez García', 'normatividad.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 2, 4, 'Jefe Normatividad'),
('Por designar', 'direccionprestaciones.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 5, 'Director de Prestaciones'),
('Eduardo Reyes Merlín', 'peconomicas.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 6, 'Jefa Prestaciones Económicas'),
('Sujey Dehesa Fuentes', 'psociales.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 7, 'Jefa Prestaciones Sociales'),
('Diego Fernando Urbieta Villavicencio', 'ppoliciales.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 8, 'Jefe Prestaciones Policiales'),
('Lucero Vásquez Ramírez', 'administracion.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 9, 'Jefa UAF Fondos de Pensiones'),
('Karina Mireya Sierra Hernández', 'contabilidadyfinanzas.pensiones@oaxaca.gob.mx', 'e10adc3949ba59abbe56e057f20f883e', 4, 10, 'Jefa Contabilidad y Finanzas');
ALTER TABLE documents ADD COLUMN deadline_date DATE DEFAULT NULL;
ALTER TABLE documents ADD COLUMN days_limit INT DEFAULT 0;
ALTER TABLE documents ADD COLUMN alert_level VARCHAR(20) DEFAULT 'VERDE';

-- Update existing documents to have a default deadline (e.g. 5 days from creation)
UPDATE documents SET days_limit = 5, deadline_date = DATE_ADD(created_at, INTERVAL 5 DAY) WHERE deadline_date IS NULL;
CREATE TABLE IF NOT EXISTS closing_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO closing_types (name) VALUES
('Trámite Concluido'),
('Informativo / Conocimiento'),
('Improcedente / No Aplica'),
('Turnado a otra Instancia Externa'),
('Atendido Parcialmente');

ALTER TABLE documents ADD COLUMN closing_type_id INT DEFAULT NULL;
ALTER TABLE documents ADD KEY (closing_type_id);
CREATE TABLE IF NOT EXISTS outgoing_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folio VARCHAR(50) UNIQUE,
    recipient_name VARCHAR(100) NOT NULL,
    recipient_dependency VARCHAR(100),
    subject VARCHAR(200) NOT NULL,
    description TEXT,
    related_incoming_id INT DEFAULT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    filepath VARCHAR(255),
    FOREIGN KEY (related_incoming_id) REFERENCES documents(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(50) UNIQUE,
    value VARCHAR(255)
);

-- Seed initial sequence for 2025
INSERT INTO settings (key_name, value) VALUES ('outgoing_sequence_2025', 1);
