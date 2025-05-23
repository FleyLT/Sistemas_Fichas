-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 01:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ficha_sistema`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fichas`
--

CREATE TABLE `fichas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `classe_id` int(11) NOT NULL,
  `nome_personagem` varchar(100) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT 1,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ficha_atributos`
--

CREATE TABLE `ficha_atributos` (
  `id` int(11) NOT NULL,
  `ficha_id` int(11) NOT NULL,
  `atributo` varchar(50) NOT NULL,
  `valor` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ficha_habilidades`
--

CREATE TABLE `ficha_habilidades` (
  `id` int(11) NOT NULL,
  `ficha_id` int(11) NOT NULL,
  `habilidade_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ficha_inventarios`
--

CREATE TABLE `ficha_inventarios` (
  `id` int(11) NOT NULL,
  `ficha_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `rolagem_dano` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `custo_xp` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT 0.00,
  `valor` int(11) NOT NULL DEFAULT 0,
  `raridade` enum('comum','incomum','raro','épico','lendário') NOT NULL DEFAULT 'comum',
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `papel` enum('jogador','mestre','admin') NOT NULL DEFAULT 'jogador'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xp_gastos`
--

CREATE TABLE `xp_gastos` (
  `id` int(11) NOT NULL,
  `ficha_id` int(11) NOT NULL,
  `xp_gasto` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `data_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xp_historico`
--

CREATE TABLE `xp_historico` (
  `id` int(11) NOT NULL,
  `ficha_id` int(11) NOT NULL,
  `xp_ganho` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `data_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `classe_id` (`classe_id`);

--
-- Indexes for table `ficha_atributos`
--
ALTER TABLE `ficha_atributos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ficha_id` (`ficha_id`);

--
-- Indexes for table `ficha_habilidades`
--
ALTER TABLE `ficha_habilidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ficha_id` (`ficha_id`),
  ADD KEY `habilidade_id` (`habilidade_id`);

--
-- Indexes for table `ficha_inventarios`
--
ALTER TABLE `ficha_inventarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ficha_id` (`ficha_id`),
  ADD UNIQUE KEY `item_id` (`item_id`),
  ADD UNIQUE KEY `unique_ficha_item` (`ficha_id`,`item_id`);

--
-- Indexes for table `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `xp_gastos`
--
ALTER TABLE `xp_gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ficha_id` (`ficha_id`);

--
-- Indexes for table `xp_historico`
--
ALTER TABLE `xp_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ficha_id` (`ficha_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ficha_atributos`
--
ALTER TABLE `ficha_atributos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ficha_habilidades`
--
ALTER TABLE `ficha_habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ficha_inventarios`
--
ALTER TABLE `ficha_inventarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xp_gastos`
--
ALTER TABLE `xp_gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xp_historico`
--
ALTER TABLE `xp_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fichas`
--
ALTER TABLE `fichas`
  ADD CONSTRAINT `fk_fichas_classe` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `fk_fichas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `ficha_atributos`
--
ALTER TABLE `ficha_atributos`
  ADD CONSTRAINT `fk_fichas_atributo` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`);

--
-- Constraints for table `ficha_habilidades`
--
ALTER TABLE `ficha_habilidades`
  ADD CONSTRAINT `fk_ficha_habilidades` FOREIGN KEY (`habilidade_id`) REFERENCES `habilidades` (`id`),
  ADD CONSTRAINT `fk_ficha_usuarios` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`);

--
-- Constraints for table `ficha_inventarios`
--
ALTER TABLE `ficha_inventarios`
  ADD CONSTRAINT `fk_inventario_ficha` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`),
  ADD CONSTRAINT `fk_inventario_item` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`);

--
-- Constraints for table `xp_historico`
--
ALTER TABLE `xp_historico`
  ADD CONSTRAINT `fk_xp_historico_ficha` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
