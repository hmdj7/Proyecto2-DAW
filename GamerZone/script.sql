/* Crear y usar la base de datos */
CREATE DATABASE IF NOT EXISTS Juegos;
USE Juegos;

/* Crear la tabla usuarios */
CREATE TABLE IF NOT EXISTS USUARIOS (
  id INT NOT NULL AUTO_INCREMENT,
  usuario VARCHAR(20) NOT NULL,
  contraseña VARCHAR(20) NOT NULL,
  email VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

/* Crear la tabla USUARIOTOKEN */
CREATE TABLE IF NOT EXISTS USUARIOTOKEN (
  id INT NOT NULL AUTO_INCREMENT,  
  token VARCHAR(255) NOT NULL,
  fechainicio DATETIME NOT NULL,
  fechavencimiento DATETIME NOT NULL,
  id_usuario INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_id_usuario_token_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES USUARIOS (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/* Crear la tabla Videojuegos */
CREATE TABLE IF NOT EXISTS Videojuegos (
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  portada VARCHAR(255) NOT NULL,
  precio INT NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
 /* Crear la tabla Carritos */
CREATE TABLE IF NOT EXISTS Carritos (
  id INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_id_usuario_carrito_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES USUARIOS (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/* Crear la tabla Pedidos */
CREATE TABLE IF NOT EXISTS Pedidos (
  id INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  fecha_pedido DATE NOT NULL,
  total INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_id_usuario_pedido_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES USUARIOS (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/* Crear la tabla Videojuegos_Carritos */
CREATE TABLE IF NOT EXISTS Videojuegos_Carritos (
    id INT NOT NULL AUTO_INCREMENT,
    id_carrito INT NOT NULL,
    id_videojuego INT NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_carrito_videojuego_carrito
        FOREIGN KEY (id_carrito)
        REFERENCES Carritos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_carrito_videojuego_videojuego
        FOREIGN KEY (id_videojuego)
        REFERENCES Videojuegos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 /* Crear la tabla Videojuegos_Pedidos */ 
CREATE TABLE IF NOT EXISTS Videojuegos_Pedidos (
    id INT NOT NULL AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_videojuego INT NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_pedido_videojuego_pedido
        FOREIGN KEY (id_pedido)
        REFERENCES Pedidos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_pedido_videojuego_videojuego
        FOREIGN KEY (id_videojuego)
        REFERENCES Videojuegos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);