-- Demo accounts (password for all: demo1234). Fictitious data only.
INSERT INTO `administrador` (`nombre`, `usuario_admin`, `clave_admin`, `fecha_ingreso`, `genero`) VALUES
('Admin Demo', 'admin', '$2y$10$8aB867.7sw3DTWixss9t8.MrsZLmwHKVT2ZJUFJGq6elqPbfW/VUu', '2025-01-01', 'N/A');
INSERT INTO `usuario` (`email`, `password`, `nombrec`, `genero`) VALUES
('medico@demo.com', '$2y$10$8aB867.7sw3DTWixss9t8.MrsZLmwHKVT2ZJUFJGq6elqPbfW/VUu', 'Médico Demo', 'N/A');
INSERT INTO `servicio` (`usuario_servicio`, `clave_servicio`) VALUES
('soporte', '$2y$10$8aB867.7sw3DTWixss9t8.MrsZLmwHKVT2ZJUFJGq6elqPbfW/VUu');
