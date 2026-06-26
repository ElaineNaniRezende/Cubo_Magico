-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/06/2026 às 01:50
-- Versão do servidor: 8.0.40
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `shoptb`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `anuncios`
--

CREATE TABLE `anuncios` (
  `idAnuncio` int NOT NULL,
  `Usuarios_idUsuario` int NOT NULL,
  `fotoAnuncio` varchar(100) NOT NULL,
  `tituloAnuncio` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `categoriaAnuncio` varchar(20) NOT NULL,
  `descricaoAnuncio` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `valorAnuncio` decimal(10,2) NOT NULL,
  `dataAnuncio` date NOT NULL,
  `horaAnuncio` time NOT NULL,
  `statusAnuncio` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `anuncios`
--

INSERT INTO `anuncios` (`idAnuncio`, `Usuarios_idUsuario`, `fotoAnuncio`, `tituloAnuncio`, `categoriaAnuncio`, `descricaoAnuncio`, `valorAnuncio`, `dataAnuncio`, `horaAnuncio`, `statusAnuncio`) VALUES
(1, 4, 'assets/img/PS5_PRO.jpg', 'console', 'Eletrônicos', 'copoo', 80.00, '2026-04-27', '20:57:12', 'disponivel'),
(2, 5, 'assets/img/iPhone17.jpg', 'iPhone', 'Eletrônicos', 'utilidade', 100.00, '2026-04-27', '20:59:45', 'disponivel');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `idCompra` int NOT NULL,
  `Usuarios_idUsuario` int NOT NULL,
  `Anuncios_idAnuncio` int NOT NULL,
  `dataCompra` date NOT NULL,
  `novaCompra` time NOT NULL,
  `valor_Compra` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int NOT NULL,
  `fotoUsuario` varchar(100) NOT NULL,
  `nomeUsuario` varchar(50) NOT NULL,
  `dataNascimentoUsuario` date NOT NULL,
  `cidadeUsuario` varchar(30) NOT NULL,
  `emailUsuario` varchar(50) NOT NULL,
  `senhaUsuario` varchar(100) NOT NULL,
  `nivelUsuario` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `fotoUsuario`, `nomeUsuario`, `dataNascimentoUsuario`, `cidadeUsuario`, `emailUsuario`, `senhaUsuario`, `nivelUsuario`) VALUES
(1, 'assets/img/20260312_142704.jpg', 'Elaine Rezende', '2026-03-09', 'Curiúva', 'elaine@gmail.com', 'fd3a5e3e168d5bea9757b2ce6c656eba', ''),
(2, 'assets/img/Logo_Unimarket_Branco.png', 'Nani Admin', '2026-03-29', 'Telêmaco Borba', 'f@g', '86871b9b1ab33b0834d455c540d82e89', ''),
(3, 'assets/img/Logo_UniMarket_Preto.png', 'Julinha', '2026-03-11', 'Reserva', 'g@j', '0cb74a0c216befd6cdb7745b1a4186ff', ''),
(4, 'assets/img/people04.jpg', 'nani', '2026-03-30', 'Telêmaco Borba', 'nani@gmail', '202cb962ac59075b964b07152d234b70', 'administrador'),
(5, 'assets/img/stanley.png', 'halenn', '2026-04-13', 'Telêmaco Borba', 'helenn@gmail', '202cb962ac59075b964b07152d234b70', 'usuario');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`idAnuncio`),
  ADD KEY `fk_anuncios_usuarios` (`Usuarios_idUsuario`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`idCompra`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `idAnuncio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `idCompra` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `anuncios`
--
ALTER TABLE `anuncios`
  ADD CONSTRAINT `fk_anuncios_usuarios` FOREIGN KEY (`Usuarios_idUsuario`) REFERENCES `usuarios` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
