-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/03/2026 às 01:16
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `facefoodbd`
--
CREATE DATABASE IF NOT EXISTS `facefoodbd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `facefoodbd`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curtidas`
--

INSERT INTO `curtidas` (`id`, `usuario_id`, `post_id`) VALUES
(45, 36, 23),
(46, 37, 23),
(47, 37, 24),
(48, 38, 23),
(49, 38, 24),
(50, 38, 25),
(51, 39, 23),
(52, 39, 24),
(53, 39, 25),
(55, 39, 26),
(62, 41, 23),
(57, 41, 24),
(58, 41, 25),
(59, 41, 26),
(60, 41, 27),
(63, 42, 23),
(64, 42, 24),
(65, 42, 25),
(66, 42, 26),
(67, 42, 27),
(68, 42, 28),
(69, 43, 23),
(70, 43, 24),
(71, 43, 25),
(72, 43, 26),
(73, 43, 27),
(74, 43, 28),
(75, 43, 29),
(76, 44, 23),
(77, 44, 24),
(78, 44, 25),
(79, 44, 26),
(80, 44, 27),
(81, 44, 28),
(82, 44, 29),
(83, 44, 30),
(84, 45, 23),
(85, 45, 24),
(86, 45, 25),
(87, 45, 26),
(88, 45, 27),
(89, 45, 28),
(90, 45, 29),
(91, 45, 30),
(92, 45, 31),
(93, 46, 23),
(94, 46, 24),
(95, 46, 25),
(96, 46, 26),
(97, 46, 27),
(98, 46, 28),
(99, 46, 29),
(100, 46, 30),
(101, 46, 31),
(102, 46, 32);

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_publicacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id`, `usuario_id`, `titulo`, `descricao`, `imagem`, `data_publicacao`) VALUES
(23, 36, 'Coxinha de Frango', 'Ingredientes:\r\n\r\n500g de peito de frango cozido e desfiado\r\n\r\n2 xícaras de caldo do cozimento do frango\r\n\r\n2 xícaras de farinha de trigo\r\n\r\n1 cebola picada\r\n\r\n2 dentes de alho picados\r\n\r\nSalsinha, sal, pimenta a gosto\r\n\r\n1 ovo batido\r\n\r\nFarinha de rosca para empanar\r\n\r\nÓleo para fritar\r\n\r\nModo de preparo:\r\n\r\nRefogue a cebola e o alho, acrescente o frango desfiado, tempere com sal, pimenta e salsinha. Reserve.\r\n\r\nEm outra panela, ferva o caldo do frango, adicione a farinha de trigo de uma vez, mexendo até desgrudar do fundo.\r\n\r\nDeixe esfriar, modele as coxinhas, recheie com o frango, feche bem.\r\n\r\nPasse no ovo batido e na farinha de rosca, frite em óleo quente até dourar.', '69a37a77e25f6_Coxinha.jpg', '2026-02-28 20:29:59'),
(24, 37, 'Brigadeiro', 'Ingredientes:\r\n\r\n1 lata de leite condensado\r\n\r\n2 colheres (sopa) de margarina\r\n\r\n4 colheres (sopa) de chocolate em pó\r\n\r\nChocolate granulado para decorar\r\n\r\nModo de preparo:\r\n\r\nEm uma panela, misture o leite condensado, a margarina e o chocolate em pó.\r\n\r\nCozinhe em fogo baixo, mexendo sempre, até desgrudar do fundo.\r\n\r\nDeixe esfriar, enrole em bolinhas e passe no granulado.', '69a37ab273d41_brigadeiro.jpg', '2026-02-28 20:30:58'),
(25, 38, 'Feijoada', 'Ingredientes:\r\n\r\n500g de feijão preto\r\n\r\n200g de costelinha de porco salgada\r\n\r\n200g de lombo de porco salgado\r\n\r\n150g de paio\r\n\r\n150g de linguiça calabresa\r\n\r\n2 cebolas picadas\r\n\r\n4 dentes de alho picados\r\n\r\n2 folhas de louro\r\n\r\nSal, pimenta-do-reino a gosto\r\n\r\nCouve, laranja e farinha para acompanhar\r\n\r\nModo de preparo:\r\n\r\nDeixe o feijão de molho por 12 horas. Dessalgue as carnes conforme necessário.\r\n\r\nCozinhe o feijão com as folhas de louro até ficar macio.\r\n\r\nEm outra panela, refogue a cebola e o alho, acrescente as carnes cortadas e refogue.\r\n\r\nJunte o feijão cozido com um pouco do caldo, deixe apurar.\r\n\r\nSirva com couve refogada, laranja fatiada e farinha.', '69a37b029dc87_feijoada.jpg', '2026-02-28 20:32:18'),
(26, 39, 'Pão de Queijo', 'Ingredientes:\r\n\r\n500g de polvilho azedo\r\n\r\n250ml de leite\r\n\r\n100ml de óleo\r\n\r\n2 ovos\r\n\r\n200g de queijo minas meia cura ralado\r\n\r\nSal a gosto\r\n\r\nModo de preparo:\r\n\r\nFerva o leite com o óleo e o sal.\r\n\r\nDespeje sobre o polvilho em uma tigela, misture bem e deixe esfriar.\r\n\r\nAdicione os ovos e o queijo, sovando até obter uma massa homogênea.\r\n\r\nModele bolinhas e coloque em assadeira untada.\r\n\r\nAsse em forno preaquecido a 180°C por cerca de 25 minutos ou até dourarem.', '69a37b644d2a0_pao_de_queijo.jpg', '2026-02-28 20:33:56'),
(27, 41, 'Lasanha à Bolonhesa', 'Ingredientes:\r\n\r\n500g de massa para lasanha\r\n\r\n500g de carne moída\r\n\r\n1 cebola picada\r\n\r\n2 dentes de alho picados\r\n\r\n2 latas de molho de tomate\r\n\r\n300g de mussarela fatiada\r\n\r\n200g de presunto fatiado\r\n\r\n200g de queijo ralado para gratinar\r\n\r\nSal, pimenta, orégano a gosto\r\n\r\nAzeite\r\n\r\nModo de preparo:\r\n\r\nRefogue a cebola e o alho no azeite, acrescente a carne moída e cozinhe até dourar.\r\n\r\nAdicione o molho de tomate, tempere e deixe apurar.\r\n\r\nEm um refratário, alterne camadas de massa, molho, presunto e mussarela.\r\n\r\nFinalize com molho e queijo ralado. Leve ao forno preaquecido a 200°C por 30 minutos.', '69a37bca97a40_lasanha-bolonhesa-na-pressao.jpg', '2026-02-28 20:35:38'),
(28, 42, 'Hambúrguer Artesanal', 'Ingredientes:\r\n\r\n500g de carne moída (acém ou fraldinha)\r\n\r\n1 cebola pequena ralada\r\n\r\n1 dente de alho picado\r\n\r\n1 ovo\r\n\r\nSal, pimenta-do-reino a gosto\r\n\r\nPão de hambúrguer, alface, tomate, queijo cheddar, maionese\r\n\r\nModo de preparo:\r\n\r\nMisture a carne com a cebola, alho, ovo, sal e pimenta.\r\n\r\nModele os hambúrgueres e leve à geladeira por 30 minutos.\r\n\r\nGrelhe em frigideira ou churrasqueira até o ponto desejado.\r\n\r\nMonte o sanduíche com os acompanhamentos.', '69a37c55990a0_hamburguer.jpg', '2026-02-28 20:37:57'),
(29, 43, 'Torta de Limão', 'Ingredientes:\r\n\r\nMassa: 200g de biscoito maisena, 100g de margarina derretida\r\n\r\nRecheio: 1 lata de leite condensado, 1 caixa de creme de leite, 1/2 xícara de suco de limão\r\n\r\nCobertura: 3 claras, 6 colheres (sopa) de açúcar, raspas de limão\r\n\r\nModo de preparo:\r\n\r\nTriture o biscoito, misture com a margarina e forre o fundo de uma forma. Leve ao forno por 10 minutos.\r\n\r\nMisture o leite condensado, creme de leite e suco de limão até engrossar. Despeje sobre a massa.\r\n\r\nBata as claras em neve, adicione o açúcar aos poucos até formar um merengue. Cubra a torta e decore com raspas.\r\n\r\nLeve ao forno para dourar levemente. Sirva gelado.', '69a37cdf1ad0d_torta-de-limao-facil-capa.jpeg', '2026-02-28 20:40:15'),
(30, 44, 'Sushi de Salmão (Uramaki)', 'Ingredientes:\r\n\r\n2 xícaras de arroz para sushi\r\n\r\n2 1/2 xícaras de água\r\n\r\n1/4 xícara de vinagre de arroz\r\n\r\n2 colheres (sopa) de açúcar\r\n\r\n1 colher (chá) de sal\r\n\r\nFolhas de nori\r\n\r\n200g de salmão fresco em tiras\r\n\r\nCream cheese, pepino, gergelim\r\n\r\nModo de preparo:\r\n\r\nCozinhe o arroz, tempere com a mistura de vinagre, açúcar e sal. Deixe esfriar.\r\n\r\nColoque uma folha de nori sobre uma esteira de bambu, espalhe o arroz, vire a folha (arroz para baixo).\r\n\r\nColoque o salmão, cream cheese e pepino, enrole apertando.\r\n\r\nPasse no gergelim, corte em fatias.', '69a37d9325b0c_uramaki.jpg', '2026-02-28 20:43:15'),
(31, 45, 'Moqueca de Peixe', 'Ingredientes:\r\n\r\n500g de filé de peixe (cação, robalo)\r\n\r\n1 cebola em rodelas\r\n\r\n2 tomates em rodelas\r\n\r\n1 pimentão verde em rodelas\r\n\r\n200ml de leite de coco\r\n\r\n2 colheres (sopa) de azeite de dendê\r\n\r\nCoentro, salsinha, sal, suco de limão\r\n\r\nPimenta a gosto\r\n\r\nModo de preparo:\r\n\r\nTempere o peixe com limão, sal e pimenta.\r\n\r\nEm uma panela, arrume camadas de cebola, tomate, pimentão e peixe.\r\n\r\nRegue com leite de coco e dendê. Cozinhe em fogo baixo com a panela tampada por 20 minutos.\r\n\r\nFinalize com coentro e sirva com arroz branco.', '69a37e1eac1fd_moqueca_de_peixe_baiana_9980_600_square.jpg', '2026-02-28 20:45:34'),
(32, 46, 'Empadão de Frango', 'Ingredientes:\r\n\r\nMassa: 500g de farinha de trigo, 200g de manteiga, 1 ovo, sal\r\n\r\nRecheio: 500g de peito de frango cozido e desfiado, 1 cebola, 2 tomates, 1 lata de milho, azeitona, 1 copo de requeijão, salsinha, sal\r\n\r\nModo de preparo:\r\n\r\nMisture a farinha, manteiga, ovo e sal até formar uma massa homogênea. Deixe descansar.\r\n\r\nRefogue a cebola, junte o frango, tomate, milho, azeitona, temperos. Acrescente o requeijão.\r\n\r\nForre uma forma com metade da massa, coloque o recheio, cubra com o restante da massa.\r\n\r\nPincele com gema e leve ao forno a 180°C por 40 minutos.', '69a37ebf71c99_empadao-de-frango-rapido.jpg', '2026-02-28 20:48:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `senha`, `foto_perfil`, `data_cadastro`) VALUES
(35, 'edugold123456', '$2y$10$bT3Fdul2wD4ozR/yWfnhT.hN5FZRaLIK5jbHEe4qkyWFXN/9LsazO', 'perfil_699cca0e09c203.12167742.jpg', '2026-02-22 23:39:54'),
(36, 'usuario1', '$2y$10$a4RQxqvXmPchv030USjXoO9qW6cVJxOh287dks1ycp9VoCGzPsOXe', 'perfil_69a371f3962160.02536425.jpg', '2026-02-28 19:51:19'),
(37, 'usuario2', '$2y$10$7EpSfsDJK44g72B8v2HQCe3jsLxo9MsLpqIYQ96AS59DfiA3Y74Ui', 'perfil_69a3739ec26e96.60121760.jpg', '2026-02-28 19:51:38'),
(38, 'usuario3', '$2y$10$A9lH0kN58IGfHT9QbdK3S.xEfUW8SZtfCn8xXua9Kk/A21afku1BO', 'perfil_69a37b27710c05.46638485.gif', '2026-02-28 19:51:54'),
(39, 'usuario4', '$2y$10$anfMu4C3JPj8gIDBSlK4wuc8/wF0uTSNPfm8Stim/LrLaHPW3urLm', 'perfil_69a37b86352383.62375882.jpg', '2026-02-28 19:52:23'),
(41, 'usuário5', '$2y$10$f24.An9dQOLtAR2AS8k2ke9Te3vyG7j3WP1C1QLCgGP6xjBrSHclG', 'perfil_69a37bf6c2be25.06247971.jpg', '2026-02-28 20:21:16'),
(42, 'usuario6', '$2y$10$Qh0/FN4USBPCBWwt47/exeMDHIDL2x4mrKfh5GrMsotd4mGGfuJzy', 'perfil_69a37c7c874904.31122800.jpeg', '2026-02-28 20:37:31'),
(43, 'usuario7', '$2y$10$KdMOADdojN.qTDu76VeDYe/PUjotu5HLHUgF9aJmmHhrn7sOC8QHK', 'perfil_69a37d05355306.04926106.jpg', '2026-02-28 20:39:50'),
(44, 'usuario8', '$2y$10$U7J6Edc3OEA/TmOc9sNjh.XpPddCrk2VDgs1FR72nUWrJPlKFA/NK', 'perfil_69a37db2841919.58651775.jpg', '2026-02-28 20:42:14'),
(45, 'usuario9', '$2y$10$4HLvkA1zmDWRTWoG0SclteXVDTOWvM2uJhCCw9R2eNcAxsltrN1M2', 'perfil_69a37dfe999806.54285937.jpg', '2026-02-28 20:44:39'),
(46, 'usuario10', '$2y$10$tBTLon3JflmQVT2JuP8brueYl/pGT1.cy11mPrF.lXgZ2oQIUR8/K', 'perfil_69a37ea1ad4db7.93055997.jpg', '2026-02-28 20:46:41');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
