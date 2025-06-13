-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Jun-2025 às 22:47
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sisven_5`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairros`
--

CREATE TABLE `bairros` (
  `cod` int(11) NOT NULL,
  `descricao_bairro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `bairros`
--

INSERT INTO `bairros` (`cod`, `descricao_bairro`) VALUES
(1, 'CHAGAS AGUIAR'),
(2, 'CENTRO'),
(3, 'URUCU'),
(4, 'SANTA HELENA'),
(5, 'PERA I'),
(6, 'SANTA EFIGENIA'),
(7, 'DUQUE DE CAXIAS'),
(8, 'UNIÃO'),
(9, 'CIGANOPOLIS'),
(10, 'ITAMARATY'),
(11, 'TAUÁ-MIRIM'),
(12, 'ESPIRITO SANTO '),
(13, 'NAZARÉ PINHEIRO'),
(14, 'PERA II'),
(15, 'PERA III'),
(16, 'PERA IIII'),
(17, 'PERA - FAVELINHA'),
(18, 'PERA CITY'),
(19, 'RENASCER'),
(20, 'VALE DA BENÇÃO'),
(21, 'CAMPO DO ORIENTE'),
(22, 'NAID LINS'),
(23, 'MONTE SINAI'),
(24, 'GRANDE VITORIA'),
(25, 'LIBERDADE'),
(26, 'ZONA RURAL'),
(27, 'VILA LORENÇO '),
(28, 'SÃO SEBASTIÃO'),
(29, 'CONJUNTO CARACOL ');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoriaserfin`
--

CREATE TABLE `categoriaserfin` (
  `cod` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `categoriaserfin`
--

INSERT INTO `categoriaserfin` (`cod`, `nome`, `img`) VALUES
(1, 'TINTAS', ''),
(3, 'MASSAS', ''),
(4, 'ARGAMASSAS', ''),
(5, 'CIMENTO ', ''),
(6, 'FERRAGEM', ''),
(7, 'ELETRICA', ''),
(8, 'FERRAMENTAS', ''),
(9, 'MAQUINAS ELETRICAS E PNEUMATICA', ''),
(10, 'LUBRIFICANTES E ÓLEO', ''),
(11, 'COLAS DE MADEIRA DE TUBOS PVC', ''),
(12, 'FITAS', ''),
(13, 'UTILIDADES', ''),
(14, 'cordas', ''),
(15, 'BANHEIRO ', ''),
(16, 'REPAROS', ''),
(17, 'CONEXÕES', ''),
(18, 'JANELAS E PORTAS', ''),
(19, 'TANQUES E PIAS', ''),
(20, 'IMPERMEABIZANTE', ''),
(21, 'areia e seixo', ''),
(22, 'TUBOS E MANGUEIRAS', ''),
(23, 'MATERIAL BASICOS', ''),
(24, 'NOVOS ITENS', ''),
(25, 'CAIXA DE AR E OUTROS', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `nascimento` varchar(20) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `residencial` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `indicacao` varchar(200) DEFAULT NULL,
  `data` varchar(20) DEFAULT NULL,
  `ultimovisita` varchar(30) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `dr` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `nascimento`, `rg`, `cpf`, `endereco`, `bairro`, `numero`, `complemento`, `celular`, `residencial`, `email`, `indicacao`, `data`, `ultimovisita`, `status`, `dr`) VALUES
(1, 'LUCAS GABRIEL DA SILVA LIMA', '00/00/0000', NULL, '000.000.000-00', 'RUA ANTONIO MOREIRA ROCHA', 'LIBERDADE', '228', '', '(97)99114-4666', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(2, 'JURANDY', '00/00/0000', NULL, '000.000.000-00', 'RUA DA FEIRA ', 'CENTRO', 'S/N', 'PROXIMO A FLORICULTURA', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(3, 'julio sales', '00/00/0000', NULL, '000.000.000-00', 'estrada do guarabira', 'saubinha', '', 'no sitio ', '(97)99146-6760', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(4, 'maik cliente', '00/00/0000', NULL, '000.000.00', 'teceira rua ao lado do ceti', 'sao sebastiao', 'sn', '', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(5, 'reinado', '00/00/0000', NULL, '000.000.000-00', 'abiorana ', 'renascer', '103', 'numero 103 e do vizinho, ao lado desse endereço e a entrega', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(6, 'risanilde', '00/00/0000', NULL, '000.000.000-00', 'rua italo edem', 'ciganopolis', '579', 'ao lado da oficina do pequeno ( uma rua antes da rua dos ind', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(7, 'joao martins ', '00/00/0000', NULL, '000.000.000-00', 'rua 01', ' campo do oriente', '99', 'proximo a praça do oriente na 03 casa', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(8, ' geraldo', '00/00/0000', NULL, '000.000.000-00', 'amazonas', 'itamarati', '101', 'passando do cafe das morena', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(9, 'lucas', '00/00/0000', NULL, '000.000.000-00', 'na casa que tem um buraco pra pesina', 'sao sebastiao', '', 'entregar pro pedreiro lika', '(00)00000-0000', NULL, NULL, NULL, '18/11/2024', NULL, '1', '1'),
(10, 'kennio', '00/00/0000', NULL, '000.000.000-00', 'no mercadinho pinto', 'esprito santo', '', '', '(00)00000-0000', NULL, NULL, NULL, '19/11/2024', NULL, '1', '1'),
(11, 'francy', '00/00/0000', NULL, '000.000.000-00', 'jutai', 'sao sebastiao', 'lote 385', 'atras do cety', '(00)00000-0000', NULL, NULL, NULL, '19/11/2024', NULL, '1', '1'),
(12, 'manuel de oliveira costa', '00/00/0000', NULL, '000.000.000-00', 'antiga garagem', 'duque', '75', 'atras da escola gm3', '(00)00000-0000', NULL, NULL, NULL, '19/11/2024', NULL, '1', '1'),
(13, 'edilson policial', '00/00/0000', NULL, '000.000.000-00', 'rua girasol', 'grande vitoria', 's/n', 'final da rua casa azul prx ao uma igreja evangelica e o poss', '(00)00000-0000', NULL, NULL, NULL, '19/11/2024', NULL, '1', '1'),
(14, 'jean felipe', '00/00/0000', NULL, '000.000.000-00', 'vila lorenço no sitio do professor jean ', 'vila lorenço', '0000000000', '', '00000000000000', NULL, NULL, NULL, '20/11/2024', NULL, '1', '1'),
(15, 'JERUSA DE OLIVEIRA', '0000000000', NULL, '00000000000000', 'RUA ´PRAÇA DA BANDEIRA', 'TAUA MIRIM', '870', 'BEM DO LADO  DA ANTG SERRARIA GOES(DE TRAS DA PRAÇA)', '(0000000000000', NULL, NULL, NULL, '20/11/2024', NULL, '1', '1'),
(16, 'VETE MAE DA RAIANE', '00/0000000', NULL, '00000000000000', 'RUA PADRE MARIO', 'CHAGAS', '1049', 'EM FRENTE AO GINASIO', '(00)0000000000', NULL, NULL, NULL, '20/11/2024', NULL, '1', '1'),
(17, 'JUSILEI DA SILVA DE AMORIM', '00/00/0000', NULL, '00000000000000', 'RUA IPIRANGA', 'PERA 1', '48', 'PRX A FERA RUA DA FRENTE', '(97)98105-1062', NULL, NULL, NULL, '20/11/2024', NULL, '1', '1'),
(18, 'francineide', '0000000000', NULL, '00000000000000', 'rua das graças', 'nazare pinheiro', '301', '', '(00)00000-0000', NULL, NULL, NULL, '21/11/2024', NULL, '1', '1'),
(19, 'edinilsa', '00/00/0000', NULL, '000.000.000-00', 'ipiranga', 'sao sebastiao', 'lote 38', 'proximo a ', '(00)00000-0000', NULL, NULL, NULL, '21/11/2024', NULL, '1', '1'),
(20, 'francisco', '00/00/0000', NULL, '000.000.000-00', 'nilo de souza', 'uniao/ vila progreso', '65', 'em frete do mercadinho ziparam', '(00)00000-0000', NULL, NULL, NULL, '21/11/2024', NULL, '1', '1'),
(21, 'jade cliente', '00/00/0000', NULL, '000.000.000-00', 'dois de dezembro', 'taua mirim', '1052', 'na baixa da egua casa branca', '(97)98442-1361', NULL, NULL, NULL, '21/11/2024', NULL, '1', '1'),
(22, 'natalia maciel', '0000000000', NULL, '000.000.000-00', 'rua ', 'vila do itapeua', '64', '', '00000000000000', NULL, NULL, NULL, '22/11/2024', NULL, '1', '1'),
(23, 'severino (cabeça)', '0000000000', NULL, '00000000000000', 'rua ajurimaua', 'urucu', '238', 'rua do siqueira ', '(0000000000000', NULL, NULL, NULL, '22/11/2024', NULL, '1', '1'),
(24, 'maria de fatima', '00/00/0000', NULL, '00000000000000', 'beco agamenor ', 'urucu', '0000000000', 'beco de frente ao labolatorio (ao lado da escola francisco e', '(00)00000-0000', NULL, NULL, NULL, '22/11/2024', NULL, '1', '1'),
(25, 'ADRIANO DA SILVA', '00/00/0000', NULL, '000.000.000-00', 'COPAIBA', ' GRANDE VITORIA', '95', 'PERTO DO POSTO DE AGUA', '(00)00000-0000', NULL, NULL, NULL, '22/11/2024', NULL, '1', '1'),
(26, 'ROSANGELA', '00/00/0000', NULL, '000.000.000-00', 'SANTOS DO MOM', ' DUQUE DE CAXIA', '81', 'PROXIMO A PONTE QUE VAI PRO CENTRO', '(97)98116-4160', NULL, NULL, NULL, '22/11/2024', NULL, '1', '1'),
(27, 'antonio', '0000000000', NULL, '00000000000000', 'rua do siqueira (entregar no cemiterio)', 'urucu', 's/n', 'rua do siqueira', '(00)00000-0000', NULL, NULL, NULL, '23/11/2024', NULL, '1', '1'),
(28, 'eudo jose', '00/00/0000', NULL, '000.000.000-00', 'maria goes', 'urucu', '52', 'proximo a escola amelha ares', '(00)00000-0000', NULL, NULL, NULL, '23/11/2024', NULL, '1', '1'),
(29, 'benilda batista', '00/00/0000', NULL, '000.000.000-00', 'paraiso', 'naazare pinheiro', '60', '', '(00)00000-0000', NULL, NULL, NULL, '23/11/2024', NULL, '1', '1'),
(30, 'raimundo', '00/00/0000', NULL, '000.000.000-00', 'rua d', 'vila progresso', '206', 'na decida do naide lins', '(00)00000-0000', NULL, NULL, NULL, '24/11/2024', NULL, '1', '1'),
(31, 'JELISON', '00/00/0000', NULL, '000.000.000-00', 'BOCA DO MATIAS', 'NAZARE PINHEIRO', 'S/N', 'RUA DO VANI', '(00)00000-0000', NULL, NULL, NULL, '26/11/2024', NULL, '1', '1'),
(32, 'LUCAS NOVO TESTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '(12) 22222-2222', NULL, NULL, NULL, '24/01/2025', NULL, '1', NULL),
(33, 'LUCAS NOVO TESTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '(12) 22222-2222', NULL, NULL, NULL, '24/01/2025', NULL, '1', NULL),
(34, 'LUCAS GABRIEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '(97) 98182-8282', NULL, NULL, NULL, '10/06/2025', NULL, '1', NULL),
(35, 'LUCAS GABRIEL', NULL, NULL, NULL, 'novo endereço para cadastrar', '1', '228', 'PROXIMA VENDA', '(97) 98182-8282', NULL, NULL, NULL, '10/06/2025', NULL, '1', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `entradas`
--

CREATE TABLE `entradas` (
  `cod` int(11) NOT NULL,
  `ata_pregao` int(11) DEFAULT NULL,
  `cod_solicitacao` int(11) DEFAULT NULL,
  `fornecedor` int(11) DEFAULT NULL,
  `n_notafiscal` varchar(100) DEFAULT NULL,
  `cod_produto` int(11) DEFAULT NULL,
  `cod_funcionario` int(11) DEFAULT NULL,
  `dia` int(3) DEFAULT NULL,
  `mes` int(3) DEFAULT NULL,
  `ano` int(5) DEFAULT NULL,
  `cod_orgao` int(11) DEFAULT NULL,
  `img` varchar(64) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `conferidor1` varchar(60) DEFAULT NULL,
  `conferidor2` varchar(60) DEFAULT NULL,
  `conferidor3` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `entradas`
--

INSERT INTO `entradas` (`cod`, `ata_pregao`, `cod_solicitacao`, `fornecedor`, `n_notafiscal`, `cod_produto`, `cod_funcionario`, `dia`, `mes`, `ano`, `cod_orgao`, `img`, `status`, `conferidor1`, `conferidor2`, `conferidor3`) VALUES
(1, 1, 1, 1, 'NOTA FISCAL', 1, 1, 10, 6, 2025, 1, NULL, 3, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fechar_caixa`
--

CREATE TABLE `fechar_caixa` (
  `cod` int(11) NOT NULL,
  `cod_funcionario` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `hora_inicio` varchar(40) NOT NULL,
  `hora_fechamento` varchar(40) DEFAULT NULL,
  `caixa_inicial` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fechar_caixa`
--

INSERT INTO `fechar_caixa` (`cod`, `cod_funcionario`, `status`, `dia`, `mes`, `ano`, `hora_inicio`, `hora_fechamento`, `caixa_inicial`) VALUES
(1, 1, 2, 26, 1, 2025, '26/01/2025 15:02', '09/06/2025 11:16', '100'),
(2, 1, 2, 9, 6, 2025, '09/06/2025 14:52', '13/06/2025 10:23', '500'),
(3, 1, 1, 13, 6, 2025, '13/06/2025 10:23', NULL, '230');

-- --------------------------------------------------------

--
-- Estrutura da tabela `financeiro_clientes`
--

CREATE TABLE `financeiro_clientes` (
  `cod` int(11) NOT NULL,
  `cod_orcamento` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `subtotal` varchar(20) NOT NULL,
  `total` varchar(20) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `numparcelas` int(11) DEFAULT NULL,
  `tipopag` varchar(20) NOT NULL,
  `categoria` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `gorjeta` varchar(20) DEFAULT NULL,
  `dinheiro` varchar(30) DEFAULT NULL,
  `debito` varchar(30) DEFAULT NULL,
  `credito` varchar(30) DEFAULT NULL,
  `pix` varchar(30) DEFAULT NULL,
  `desconto` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `financeiro_clientes`
--

INSERT INTO `financeiro_clientes` (`cod`, `cod_orcamento`, `tipo`, `subtotal`, `total`, `descricao`, `numparcelas`, `tipopag`, `categoria`, `dia`, `mes`, `ano`, `gorjeta`, `dinheiro`, `debito`, `credito`, `pix`, `desconto`) VALUES
(1, 1, 1, '300', '300 ', '', 1, '1', 0, 26, 1, 2025, '0', '300', '0', '0', '0', '0'),
(2, 2, 1, '900', '900 ', '', 1, '1', 0, 26, 1, 2025, '0', '300', '300', '0', '300', '0'),
(3, 3, 1, '130', '130 ', '', 1, '1', 0, 26, 1, 2025, '0', '100', '0', '0', '30', '0'),
(4, 4, 1, '30', '30 ', '', 1, '1', 0, 26, 1, 2025, '20', '30', '0', '0', '0', '0'),
(5, 5, 2, '180', '180.00 ', '', 4, '2', 0, 26, 1, 2025, '', NULL, NULL, NULL, NULL, NULL),
(6, 6, 1, '30', '30 ', '', 1, '1', 0, 26, 1, 2025, '20', '30', '0', '0', '0', '0'),
(7, 7, 1, '45', '40 ', '', 1, '1', 0, 26, 1, 2025, '10', '40', '0', '0', '0', '5'),
(8, 8, 1, '150', '150 ', '', 1, '1', 0, 26, 1, 2025, '50', '150', '0', '0', '0', '0'),
(9, 9, 1, '160', '160 ', '', 1, '1', 0, 18, 2, 2025, '40', '160', '0', '0', '0', '0'),
(10, 10, 1, '360', '360 ', '', 1, '1', 0, 18, 2, 2025, '0', '0', '260', '0', '100', '0'),
(11, 11, 1, '30', '30 ', '', 1, '1', 0, 9, 6, 2025, '0', '30', '0', '0', '0', '0'),
(12, 12, 1, '15', '15 ', '', 1, '1', 0, 10, 6, 2025, '0', '15', '0', '0', '0', '0'),
(13, 13, 1, '30', '30 ', '', 1, '1', 0, 10, 6, 2025, '0', '0', '0', '0', '30', '0'),
(14, 14, 1, '211', '211 ', '', 1, '1', 0, 11, 6, 2025, '89', '211', '0', '0', '0', '0'),
(15, 15, 1, '5500', '5500 ', '', 1, '1', 0, 11, 6, 2025, '0', '0', '0', '0', '5500', '0'),
(16, 16, 2, '', '41.00 ', '', 3, '2', 0, 12, 6, 2025, '', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `financeiro_empresa`
--

CREATE TABLE `financeiro_empresa` (
  `id` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `cat` int(11) DEFAULT NULL,
  `descricao` longtext DEFAULT NULL,
  `valor` varchar(30) DEFAULT NULL,
  `cod_usu` int(11) NOT NULL,
  `cod_caixa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `financeiro_empresa`
--

INSERT INTO `financeiro_empresa` (`id`, `tipo`, `dia`, `mes`, `ano`, `cat`, `descricao`, `valor`, `cod_usu`, `cod_caixa`) VALUES
(1, 1, 9, 6, 2025, 1, 'DESPESA PARA TESTAR', '150.50', 1, 2),
(2, 1, 13, 6, 2025, 1, 'UMA DESPESA HOJE PARA TESTE', '1000.00', 1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `cod` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `cnpj` varchar(50) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cod_orgao` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=2048 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `lc_cat`
--

CREATE TABLE `lc_cat` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cod_usu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `lc_cat`
--

INSERT INTO `lc_cat` (`id`, `nome`, `cod_usu`) VALUES
(1, 'DESPESA DIÁRIA', 1),
(2, 'DESPESA MENSAL', 1),
(3, 'MATERIAIS E LIMPEZA', 1),
(4, 'FUNCIONÁRIOS ', 1),
(5, 'DESPESA CORRENTE', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `lista_entradas`
--

CREATE TABLE `lista_entradas` (
  `cod` int(11) NOT NULL,
  `cod_entrada` int(11) NOT NULL,
  `ata_pregao` int(11) DEFAULT NULL,
  `cod_solicitacao` int(11) DEFAULT NULL,
  `cod_produto` int(11) NOT NULL,
  `lote` varchar(500) DEFAULT NULL,
  `mes_validade` varchar(100) DEFAULT NULL,
  `ano_validade` varchar(100) DEFAULT NULL,
  `qtd` int(11) NOT NULL,
  `valor_total` varchar(20) NOT NULL,
  `dia` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `lista_entradas`
--

INSERT INTO `lista_entradas` (`cod`, `cod_entrada`, `ata_pregao`, `cod_solicitacao`, `cod_produto`, `lote`, `mes_validade`, `ano_validade`, `qtd`, `valor_total`, `dia`, `mes`, `ano`) VALUES
(1, 1, 1, 1, 1, 'LOTEDEENTRADA2', '06', '2026', 100, '1000', 10, 6, 2025);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notas`
--

CREATE TABLE `notas` (
  `cod` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `nomeCli` varchar(60) DEFAULT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `func` int(11) NOT NULL,
  `som` int(11) NOT NULL DEFAULT 1,
  `ordem` int(11) DEFAULT NULL,
  `hora` varchar(20) NOT NULL,
  `tipo_pedido` int(11) DEFAULT NULL,
  `cod_caixa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `notas`
--

INSERT INTO `notas` (`cod`, `status`, `usuario`, `nomeCli`, `dia`, `mes`, `ano`, `func`, `som`, `ordem`, `hora`, `tipo_pedido`, `cod_caixa`) VALUES
(1, 3, 2, NULL, 26, 1, 2025, 1, 1, 0, '15:03:05', 2, 1),
(2, 3, 0, NULL, 26, 1, 2025, 1, 1, 0, '15:03:32', 1, 1),
(3, 3, 0, NULL, 26, 1, 2025, 1, 1, 1, '15:03:56', 1, 1),
(4, 3, 0, NULL, 26, 1, 2025, 1, 1, 2, '15:04:57', 1, 1),
(5, 3, 1, NULL, 26, 1, 2025, 1, 1, 3, '15:05:08', 1, 1),
(6, 3, 0, NULL, 26, 1, 2025, 1, 1, 4, '15:44:02', 1, 1),
(7, 3, 0, NULL, 26, 1, 2025, 1, 1, 5, '17:35:49', 1, 1),
(8, 3, 0, NULL, 26, 1, 2025, 1, 1, 6, '20:29:39', 1, 1),
(9, 3, 0, NULL, 18, 2, 2025, 1, 1, 7, '11:10:37', 1, 1),
(10, 3, 1, NULL, 18, 2, 2025, 1, 1, 0, '11:18:07', 3, 1),
(11, 3, 0, NULL, 9, 6, 2025, 1, 1, 8, '11:15:41', 1, 1),
(12, 3, 0, NULL, 10, 6, 2025, 1, 1, 9, '16:48:24', 1, 2),
(13, 3, 35, NULL, 10, 6, 2025, 1, 1, 0, '16:48:36', 3, 0),
(14, 3, 0, NULL, 11, 6, 2025, 1, 1, 10, '17:31:34', 1, 2),
(15, 3, 0, NULL, 11, 6, 2025, 1, 1, 11, '17:32:17', 1, 2),
(16, 3, 1, NULL, 12, 6, 2025, 1, 1, 0, '15:55:55', 3, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pag_par_pro`
--

CREATE TABLE `pag_par_pro` (
  `cod` int(11) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `valor` varchar(20) NOT NULL,
  `financeiro_pac` int(11) NOT NULL,
  `tipopag` varchar(20) NOT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `esp_proc` int(11) NOT NULL,
  `data_vencimento` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `cod` int(11) NOT NULL,
  `dente` int(11) DEFAULT NULL,
  `servico` int(11) NOT NULL,
  `usuario` int(10) NOT NULL,
  `qtd` varchar(20) NOT NULL,
  `valor` varchar(20) NOT NULL,
  `status` int(20) NOT NULL,
  `nivel` varchar(11) DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`cod`, `dente`, `servico`, `usuario`, `qtd`, `valor`, `status`, `nivel`, `obs`, `tipo`, `dia`, `mes`, `ano`, `categoria`) VALUES
(1, NULL, 11, 1, '10', '300', 3, NULL, '', NULL, 26, 1, 2025, 3),
(2, NULL, 36, 2, '6', '900', 3, NULL, '', NULL, 26, 1, 2025, 6),
(3, NULL, 11, 3, '1', '30.00', 3, NULL, '', NULL, 26, 1, 2025, 3),
(4, NULL, 16, 3, '1', '20.00', 3, NULL, '', NULL, 26, 1, 2025, 1),
(5, NULL, 16, 3, '1', '20.00', 3, NULL, '', NULL, 26, 1, 2025, 1),
(6, NULL, 17, 3, '1', '20.00', 3, NULL, '', NULL, 26, 1, 2025, 1),
(7, NULL, 18, 3, '1', '20.00', 3, NULL, '', NULL, 26, 1, 2025, 1),
(8, NULL, 18, 3, '1', '20.00', 3, NULL, '', NULL, 26, 1, 2025, 1),
(9, NULL, 11, 4, '1', '30.00', 3, NULL, '', NULL, 26, 1, 2025, 3),
(10, NULL, 11, 5, '6', '180', 3, NULL, '', NULL, 26, 1, 2025, 3),
(11, NULL, 11, 6, '1', '30.00', 3, NULL, '', NULL, 26, 1, 2025, 3),
(12, NULL, 155, 7, '1', '45.00', 3, NULL, '', NULL, 26, 1, 2025, 13),
(13, NULL, 11, 8, '5', '150', 3, NULL, '', NULL, 26, 1, 2025, 3),
(14, NULL, 32, 9, '10', '160', 3, NULL, '', NULL, 18, 2, 2025, 4),
(15, NULL, 14, 10, '20', '360', 3, NULL, '', NULL, 18, 2, 2025, 1),
(16, NULL, 11, 11, '1', '30.00', 3, NULL, '', NULL, 9, 6, 2025, 3),
(17, NULL, 70, 12, '1', '15.00', 3, NULL, '', NULL, 10, 6, 2025, 7),
(18, NULL, 70, 13, '1', '15.00', 3, NULL, '', NULL, 10, 6, 2025, 7),
(19, NULL, 70, 13, '1', '15.00', 3, NULL, '', NULL, 10, 6, 2025, 7),
(20, NULL, 11, 14, '1', '30.00', 3, NULL, '', NULL, 11, 6, 2025, 3),
(21, NULL, 29, 14, '1', '13.00', 3, NULL, '', NULL, 11, 6, 2025, 4),
(22, NULL, 20, 14, '1', '90.00', 3, NULL, '', NULL, 11, 6, 2025, 3),
(23, NULL, 11, 14, '1', '30.00', 3, NULL, '', NULL, 11, 6, 2025, 3),
(24, NULL, 11, 14, '1', '30.00', 3, NULL, '', NULL, 11, 6, 2025, 3),
(25, NULL, 19, 14, '1', '18.00', 3, NULL, '', NULL, 11, 6, 2025, 1),
(26, NULL, 34, 15, '100', '5500', 3, NULL, '', NULL, 11, 6, 2025, 5),
(27, NULL, 179, 16, '1', '19.00', 3, NULL, '', NULL, 12, 6, 2025, 6),
(28, NULL, 180, 16, '1', '22.00', 3, NULL, '', NULL, 12, 6, 2025, 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ponto`
--

CREATE TABLE `ponto` (
  `cod` int(11) NOT NULL,
  `codcli` int(11) NOT NULL,
  `dia` varchar(3) NOT NULL,
  `mes` varchar(3) NOT NULL,
  `ano` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `cod` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `categoria` int(11) NOT NULL,
  `valor` varchar(20) NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `codbarra` varchar(100) DEFAULT NULL,
  `apresentacao` int(11) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  `fornecedor` int(11) DEFAULT NULL,
  `est_max` int(11) DEFAULT NULL,
  `est_mim` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `codbusca` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`cod`, `nome`, `descricao`, `categoria`, `valor`, `img`, `codbarra`, `apresentacao`, `qtd`, `fornecedor`, `est_max`, `est_mim`, `tipo`, `codbusca`) VALUES
(1, 'TINTA ACR. COR PALHA FOSCO 3,6L HIPERCOR', '', 1, '35.00', NULL, '0', NULL, 0, NULL, 4, 2, 1, 1000),
(2, 'TINTA SEMI AREIA 3L LUX', '', 1, '75.00', NULL, '0', NULL, 0, NULL, 3, 2, 1, 1001),
(3, 'TINTA SEMI BRANCA 3L LUX', '', 1, '75.00', NULL, '0', NULL, 0, NULL, 3, 2, 1, NULL),
(4, 'TINTA ESMALTE VERMELHO FERRARI 750ML LUX                    ', '', 1, '24.00', NULL, '0', NULL, 0, NULL, 3, 3, 1, NULL),
(5, ' TINTA ACR. COR PALHA FOSCO  15LT HIPERCOR', '', 1, '95.00', NULL, '0', NULL, 0, NULL, 1, 1, 1, NULL),
(6, 'TINTA SEMI VERDE FORTUNA 15LT LUX', '', 1, '240.00', NULL, '0', NULL, 0, NULL, 0, 1, 1, NULL),
(7, 'TINTA SEMI AZUL CLARO 15LT LUX', '', 1, '240.00', NULL, '0', NULL, 0, NULL, 1, 1, 1, NULL),
(8, 'TINTA SEMI BRANCO NEVE 15LT LUX', '', 1, '240.00', NULL, '0', NULL, 0, NULL, 3, 2, 1, NULL),
(9, 'TINTA SEMI CINZA 15LT LUX', '', 1, '240.00', NULL, '0', NULL, 0, NULL, 1, 1, 1, NULL),
(10, ' TINTA ACR. COR CINZA REAL 15LT HIPERCOR', '', 1, '110.00', NULL, '0', NULL, 0, NULL, 1, 1, 1, NULL),
(11, ' MASSA CORRIDA 5KG HIPERCOR', '', 3, '30.00', NULL, '0', NULL, 3, NULL, 8, 4, 0, NULL),
(12, ' MASSA CORRIDA 20KG HIPERCOR', '', 3, '62.00', NULL, '123123123123', NULL, 0, NULL, 10, 4, 1, 1312),
(14, 'TINTA SPRAY PAINT-COLOR   PRETO FOSCO ', '', 1, '18.00', NULL, NULL, NULL, -17, NULL, 3, 5, 1, NULL),
(15, 'TINTA SPRAY PRETO BRILHANTE', '', 1, '18.00', NULL, NULL, NULL, 0, NULL, 0, 5, 1, NULL),
(16, 'TINTA SPRAY VERMELHO TEK BOND', '', 1, '20.00', NULL, NULL, NULL, 5, NULL, 7, 6, 1, NULL),
(17, 'TINTA SPRAY DOURADO TEK BOND', '', 1, '20.00', NULL, NULL, NULL, 4, NULL, 5, 2, 1, NULL),
(18, 'TINTA SPRAY CINZA TEK BOND', '', 1, '20.00', NULL, NULL, NULL, 3, NULL, 5, 2, 1, NULL),
(19, 'TINTA SPRAY VERNIZ PEG&UZE', '', 1, '18.00', NULL, NULL, NULL, 2, NULL, 3, 3, 1, NULL),
(20, 'MASSA ACRILICA 20KG HIPERCOR', '', 3, '90.00', NULL, '0', NULL, 3, NULL, 8, 3, 1, NULL),
(21, ' MASSA ACRILICA 5KG HIPERCOR', '', 3, '40.00', NULL, '0', NULL, 8, NULL, 8, 4, 1, NULL),
(22, 'CORANTE LIQD. TEK BOND PRETO  50ML', '', 1, '5.00', NULL, NULL, NULL, 18, NULL, 18, 8, 1, NULL),
(23, 'CORANTE LIQD. OCRE TEK BOND 50ML', '', 1, '5.00', NULL, NULL, NULL, 6, NULL, 6, 6, 1, NULL),
(24, 'CORANTE LIQD. MARROM TEK BOND 50ML', '', 1, '5.00', NULL, NULL, NULL, 1, NULL, 1, 8, 1, NULL),
(25, 'CORANTE LIQD. LILAS TEK BOND 50ML', '', 1, '5.00', NULL, NULL, NULL, 6, NULL, 6, 8, 1, NULL),
(26, 'CORANTE LIQD. FLEX AZUL 50ML', '', 1, '5.00', NULL, NULL, NULL, 11, NULL, 11, 6, 1, NULL),
(27, 'CORANTE LIQD. XADRES VERMELHO 50ML', '', 1, '5.00', NULL, NULL, NULL, -1, NULL, -1, 8, 1, NULL),
(28, 'CORANTE LIQD. XADRES VERDE 50ML', '', 1, '5.00', NULL, NULL, NULL, 7, NULL, 7, 5, 1, NULL),
(29, 'ARGAMASSA AC-1 AMAZOMIX 15KG', '', 4, '13.00', NULL, NULL, NULL, 113, NULL, 150, 30, 1, NULL),
(30, 'ARGAMASSA AC-2 AMAZOMIX', '', 4, '20.00', NULL, NULL, NULL, 73, NULL, 150, 30, 1, NULL),
(31, 'ARGAMASSA AC-3 AMAZOMIX 15KG', '', 4, '30.00', NULL, NULL, NULL, 54, NULL, 100, 20, 1, NULL),
(32, 'SUPERCALL 5KG', '', 4, '16.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(33, 'REAJUNTE AMAZOMIX BRANCO 1KG', '', 4, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(34, 'CIMENTO POTY 42,5 KG', '', 5, '55.00', NULL, '', NULL, -84, NULL, 100, 5, 1, NULL),
(36, 'CARRINHO DE MÃO METALOSA ', '', 6, '150.00', NULL, NULL, NULL, 1, NULL, 7, 4, 1, NULL),
(37, 'QUADRO DE DISTRIBUIÇÃO BRUM 18 MODULOS DIN ', '', 7, '110.00', NULL, NULL, NULL, 3, NULL, 3, 1, 1, NULL),
(38, 'FITA ISOLANTE 10MTS - AVELINO', '', 7, '3.00', NULL, '', NULL, 13, NULL, 46, 10, 1, 1002),
(39, 'FITA ISOLANTE 5MTS-AVELINO', '', 7, '2.00', NULL, '', NULL, 19, NULL, 50, 10, 1, 1003),
(40, 'INTERRUPTOR TRIPLO DE ALVENARIA- ROMAZI', '', 7, '16.00', NULL, '', NULL, 100, NULL, 100, 50, 1, 1004),
(41, 'INTERRUPTOR SIMPLES ALVENARIA - ROMAZI', '', 7, '10.00', NULL, '', NULL, 15, NULL, 15, 8, 1, 1005),
(42, 'INTERRUPTOR ALVENARIA DUPLO TOMADA 10A', '', 7, '12.00', NULL, '', NULL, 26, NULL, 28, 10, 1, 1006),
(43, 'TAMPA CEGA 4X2 -ILUMI', '', 7, '4.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1007),
(44, 'TOMADA DUPLA 20A - TRAMONTINA', '', 7, '13.00', NULL, '', NULL, 11, NULL, 24, 10, 1, 1008),
(45, 'TOMADA SIMPLES 20A - TRAMONTINA', '', 7, '10.00', NULL, '', NULL, 8, NULL, 24, 10, 1, 1009),
(46, 'BOCAL PLASTICO PRETO SIMPLES', '', 7, '4.00', NULL, '', NULL, 8, NULL, 30, 15, 1, 1010),
(47, 'TOMADA SOBREPOR 10A - ROMAZI', '', 7, '7.00', NULL, '', NULL, 17, NULL, 18, 10, 1, 1011),
(48, 'PLUG FEMEA 10A - MECTRONIC', '', 7, '5.00', NULL, '', NULL, 18, NULL, 20, 7, 1, 1012),
(49, 'PLUG MACHO 10A - MECTRONIC', '', 7, '5.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1013),
(50, 'PLUG FEMEA 20A - MECTRONIC', '', 7, '7.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1014),
(51, 'PLUG MACHO 20A - MECTRONIC', '', 7, '7.00', NULL, '', NULL, 10, NULL, 20, 7, 1, 1015),
(52, 'PLUG ADAPTADORES -DIVERSOS', '', 7, '6.00', NULL, '', NULL, 20, NULL, 20, 6, 1, 1016),
(53, 'INTERRUPTOR TRIPLO SOBREPOR', '', 7, '12.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1018),
(54, 'INTERRUPTOR DUPLO SOBREPOR', '', 7, '12.00', NULL, '', NULL, 40, NULL, 50, 20, 1, 1019),
(55, 'INTERRUPTOR PRETO TRIPLO ALVENARIA', '', 7, '12.00', NULL, '', NULL, 16, NULL, 16, 7, 1, 1020),
(56, 'INTERRUPTOR PRETO SIMPLES ALVENARIA', '', 7, '12.00', NULL, '', NULL, 36, NULL, 36, 20, 1, 1021),
(57, 'LUMINARIA LED QUADRADA EMBUTIR 24W - ELGIN', '', 7, '48.00', NULL, '0', NULL, 9, NULL, 9, 5, 1, 1022),
(58, 'LUMINARIA LED EMBUTIR 18W - ELGIN', '', 7, '45.00', NULL, '', NULL, 9, NULL, 9, 5, 1, 1023),
(59, 'LUMINARIA LED REDONDA SOBREPOR 18W - TRIAL', '', 7, '40.00', NULL, '', NULL, 9, NULL, 9, 5, 1, 1024),
(60, 'LUMINARIA LED EMBUTIR QUADRADA   6W - TRIAL', '', 7, '25.00', NULL, '', NULL, 19, NULL, 19, 5, 1, 1025),
(61, 'LAMPADA LED 9W - TRAMONTINA', '', 7, '4.00', NULL, '', NULL, 20, NULL, 26, 15, 1, 1026),
(62, 'LAMPADA LED 12W - TRAMONTINA', '', 7, '6.00', NULL, '', NULL, 48, NULL, 50, 20, 1, 1027),
(63, 'EXTENSÃO COMPOLUX 10MTS ', '', 7, '40.00', NULL, '', NULL, 4, NULL, 4, 4, 1, 1028),
(64, 'EXTENSÃO COMPOLUX 5MTS', '', 7, '26.00', NULL, NULL, NULL, 8, NULL, 8, 4, 1, 1029),
(65, 'EXTENSÃO 10MTS - ROMAZI(CABO PP)', '', 7, '40.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1030),
(66, 'LAMPADA ARTEK-15W 3U', '', 7, '10.00', NULL, '', NULL, 13, NULL, 13, 0, 1, 1031),
(67, 'LAMPADA ARTEK-15W ESPIRAL', '', 7, '10.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1032),
(68, 'LAMPADA BOLINHA 7W ', '', 7, '2.00', NULL, '', NULL, 20, NULL, 20, 5, 1, 1033),
(69, 'LAMPADA AMARELA (SIMPLES)', '', 7, '4.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1034),
(70, 'CAIXA  ARSTOP TOMADA 20A - ROMAZI', '', 7, '15.00', NULL, '0', NULL, 8, NULL, 12, 8, 1, 1035),
(71, 'REFLETOR COM SENSOR 100/150W', '', 7, '60.00', NULL, '', NULL, 2, NULL, 2, 1, 1, 1036),
(72, 'RODANA DE PORCELANA', '', 7, '10.00', NULL, '', NULL, 4, NULL, 20, 6, 1, 1037),
(73, 'LUMINARIA SPOT EMBUTIR 1XE/27 BRANCA REDONDA ', '', 7, '28.00', NULL, '', NULL, 2, NULL, 2, 0, 1, 1038),
(74, 'REFLETOR EMBUTIR AÇO BRANCO - LLUM', '', 7, '32.00', NULL, '', NULL, 1, NULL, 1, 0, 1, 1039),
(75, 'GARRA DE BATERIA PRETA (CHUPETA) ', '', 7, '12.00', NULL, '', NULL, 2, NULL, 2, 1, 1, 1040),
(77, 'RESISTENCIA DE CHUVEIRO-LORENZETI', '', 7, '6.00', NULL, '', NULL, 40, NULL, 40, 0, 1, 1042),
(78, 'CAIXA 4X2 AMARELA - TRAMONTINA', '', 7, '2.00', NULL, '', NULL, 0, NULL, 80, 20, 1, 1043),
(79, 'ENERBRAS FERRO DE SOLDA 40W', '', 8, '39.00', NULL, '0', NULL, 3, NULL, 3, 3, 1, 1044),
(80, 'JAGUAR OCULOS DE SEGURANÇA', '', 8, '10.00', NULL, '', NULL, 9, NULL, 10, 5, 1, 1124),
(81, 'OK BRASIL LIMA PARA SERROTE', '', 8, '10.00', NULL, NULL, NULL, 1, NULL, 4, 5, 1, NULL),
(82, 'TRAMOTINA MASTER MARTELO 25MM', '', 8, '35.00', NULL, NULL, NULL, 6, NULL, 7, 5, 1, NULL),
(83, 'CORTAG PRUMO DE PAREDE 500G', '', 8, '26.00', NULL, NULL, NULL, 7, NULL, 8, 5, 1, NULL),
(84, 'TRAMONTINA MASTER CHAVE COMBINADA 10MM', '', 8, '13.00', NULL, NULL, NULL, 15, NULL, 15, 10, 1, NULL),
(86, 'TRAMONTINA MASTER ALICATE CORTE DIAGONAL', '', 8, '42.00', NULL, NULL, NULL, 3, NULL, 3, 3, 1, NULL),
(87, 'TRAMONTINA MASTER ALICATE UNIVERSAL', '', 8, '42.00', NULL, NULL, NULL, 0, NULL, 6, 3, 1, NULL),
(88, 'TRAMONTINA MASTER ALICATE BICO MEIA CANA', '', 8, '42.00', NULL, NULL, NULL, 4, NULL, 4, 3, 1, NULL),
(89, 'SAO ROMAO TORQUES ARMADOR 12&#39;&#39;', '', 8, '55.00', NULL, NULL, NULL, 3, NULL, 6, 6, 1, NULL),
(90, 'TRAMONTINA  PA DE BICO COM CABO DE MADEIRA 71CM', '', 8, '50.00', NULL, NULL, NULL, 7, NULL, 10, 5, 1, NULL),
(91, 'MAX FERRAMENTAS  LAMINA PARA ROÇADEIRA 2 PONTAS', '', 8, '28.00', NULL, NULL, NULL, 4, NULL, 4, 3, 1, NULL),
(92, 'SOPRANO FECHADURA', '', 8, '45.00', NULL, NULL, NULL, 2, NULL, 13, 5, 1, NULL),
(93, 'EF FECHADURA DE PORTAO DE FERRO SOBREPOR', '', 8, '65.00', NULL, '0', NULL, 4, NULL, 4, 2, 1, NULL),
(94, 'FERRO VERGALHÃO 3/8  C-10MM ', '', 6, '52.00', NULL, '', NULL, 4, NULL, 23, 20, 1, NULL),
(95, 'FERRO VERGALHÃO 5/16 C-8MM ', '', 6, '40.00', NULL, '', NULL, 32, NULL, 42, 25, 1, 1045),
(96, 'ESTRIBO 7X17 CX-200 UND', '', 6, '120.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1046),
(97, 'ESTRIBO 7X27 CX 200 UND', '', 6, '135.00', NULL, '', NULL, -30, NULL, 1, 1, 1, 1047),
(98, 'ARAME RECOZIDO 1KG', '', 6, '16.00', NULL, '', NULL, 0, NULL, 10, 4, 1, 1048),
(99, 'INTECH PISTOLA DE PINTURA DE ALTA PRODUÇÃO  P990 ', '', 9, '155.00', NULL, '', NULL, 2, NULL, 2, 1, 1, 1049),
(100, 'TRAMONTINA PARAFUSADEIRA  KIT 16PCS', '', 9, '325.00', NULL, '', NULL, 1, NULL, 2, 1, 1, 1050),
(101, 'ESMILHADEIRA ANGULAR .SKILL 115MM (4.1/2). 700W', '', 9, '295.00', NULL, '', NULL, 0, NULL, 1, 1, 1, 1051),
(102, 'SERRA MARMORI.BOSCH GDC 1500W 127', '', 9, '450.00', NULL, '', NULL, 0, NULL, 2, 1, 1, 1052),
(103, ' KING ÓLEO MULTIUSO 100ML', '', 10, '7.00', NULL, '', NULL, 23, NULL, 24, 6, 1, 1053),
(104, 'STARRETT ANTIFERRUGEM LUBRIFICANTE MULTIUSO 300ML', '', 10, '14.00', NULL, '', NULL, 1, NULL, 14, 4, 1, 1054),
(105, 'ALMAFLEX COLA MADEIRA 290G', '', 11, '11.00', NULL, '', NULL, 5, NULL, 12, 5, 1, 1055),
(106, 'AMANCO COLA TUBO PVC ADESIVO PLASTICO 17G', '', 11, '3.00', NULL, '', NULL, 14, NULL, 25, 15, 1, 1056),
(107, 'UNIUM  COLA TUBO PVC ADESIVO PLASTICO 75G', '', 11, '6.00', NULL, '', NULL, 10, NULL, 30, 10, 1, 1057),
(108, 'TEK BOND COLA INSTANTANEA - 793  20G', '', 11, '11.00', NULL, '', NULL, 9, NULL, 10, 4, 1, 1058),
(111, 'TRAMONTINA FITA ISOLANTE PRETA 0,13MMX19MMX5MT', '', 12, '2.00', NULL, '', NULL, 6, NULL, 30, 10, 1, 1061),
(112, '3M FITA DUPLAFACE MT', '', 12, '4.50', NULL, '', NULL, 1, NULL, 2, 1, 1, 1062),
(113, 'CONDOR FITA CREPE 18MMX50MT', '', 12, '6.50', NULL, '', NULL, 9, NULL, 12, 5, 1, 1063),
(114, 'CONDOR FITA CREPE 24MMX50MT', '', 12, '8.00', NULL, '', NULL, 10, NULL, 11, 5, 1, 1064),
(115, 'CONDOR FITA CREPE 48MMX50MT', '', 12, '13.00', NULL, '', NULL, 15, NULL, 16, 6, 1, 1065),
(116, 'FITA KIMANTA ALUMINIZADA (MANTA) 5CMX10MT ROLO', 'MT 2,00', 12, '17.00', NULL, '0', NULL, 2, NULL, 5, 2, 1, NULL),
(117, 'ARTEK PILHA COMUM C2 ', '', 8, '6.00', NULL, '', NULL, 5, NULL, 5, 5, 1, 1066),
(118, 'EHC -CHAVE DE FORÇA ESTRELA 28-25', '', 8, '25.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1067),
(119, 'EHC-CHAVE COMBINADA 35MM', '', 8, '48.00', NULL, '', NULL, 4, NULL, 4, 2, 1, 1068),
(120, 'KALA-FECHADURA PEGA LADRÃO', '', 8, '25.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1069),
(121, 'RISCADOR PARA PISOS E AZULEIJOS ', '', 8, '17.00', NULL, '', NULL, 11, NULL, 11, 6, 1, 1070),
(122, 'ALICADE CORTA ARAME 8\"', '', 8, '33.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1071),
(123, 'ALICATE DE PRESSÃO RCA, BNC, F', '', 8, '30.00', NULL, '', NULL, 2, NULL, 2, 3, 1, 1072),
(124, 'IDEA-CORTADOR DE TUBOS METAL 3\"-32MM', '', 8, '25.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1073),
(125, 'BOM CORTE DISCO PARA MADEIRA -24DENTES', '', 8, '14.00', NULL, '', NULL, 11, NULL, 14, 10, 1, 1074),
(126, 'BOM CORTE- DISCO DE CORTE ADIAMANTADO ALVENARIA ', '', 8, '15.00', NULL, '', NULL, 7, NULL, 8, 10, 1, 1075),
(127, 'STARRETT DISCO INOX/AÇO 4\"', '', 8, '4.00', NULL, '', NULL, 3, NULL, 16, 7, 1, 1076),
(128, 'STARRETT DISCO INOX/AÇO 7\"', '', 8, '8.00', NULL, '', NULL, 6, NULL, 23, 7, 1, 1077),
(129, 'DISCO DE FIODE AÇO REGULAR 8\"', '', 8, '52.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1078),
(130, 'BOCA DE LOBO TRAMONTINA CAVADEIRA ARTICULADA 110CM ', '', 8, '72.00', NULL, '', NULL, 4, NULL, 6, 4, 1, 1079),
(131, 'TRAMONTINA PÁ DE BICO C/CABO', '', 8, '45.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1080),
(132, 'TRAMONTINA ENXADA LARGA C/CABO 2.5', '', 8, '72.00', NULL, '', NULL, 2, NULL, 6, 3, 1, 1081),
(133, 'TRAMONTINA MACHADO', '', 8, '85.00', NULL, '', NULL, 5, NULL, 6, 3, 1, 1082),
(135, 'K', '', 8, '20.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1084),
(137, 'K', '', 8, '9.50', NULL, '0', NULL, NULL, NULL, 0, 0, 0, NULL),
(138, 'LIMA PARA MOTOSERRA 8\"X3/16\"', '', 8, '9.50', NULL, '', NULL, 9, NULL, 9, 5, 1, 1085),
(139, 'DISMA-CHAVE COMBINADA 6\"', '', 8, '7.00', NULL, '', NULL, 6, NULL, 6, 5, 1, 1086),
(140, 'TRAMONTINA CHAVE COMBINADA 9MM', '', 8, '7.00', NULL, NULL, NULL, 10, NULL, 10, 5, 1, NULL),
(141, 'EHC CHAVE COMBINADA 9MM', '', 8, '7.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1087),
(142, 'OK BRASIL CHAVE FENDA 5/16X6\"', '', 8, '5.00', NULL, '', NULL, 20, NULL, 20, 15, 1, 1088),
(143, 'OK BRASIL CHAVE PHILLIPES 3/8X8\"', '', 8, '5.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1089),
(144, 'GEDORE CHAVE L 18MM', '', 8, '10.00', NULL, '', NULL, 6, NULL, 6, 3, 1, 1090),
(145, 'FERTAK CHAVE L 9MM', '', 8, '7.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1091),
(146, 'FERTAK CHAVE L 16MM', '', 8, '10.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1092),
(147, 'DISMA CHAVE COMBINADA 20MM', '', 8, '15.00', NULL, '', NULL, 10, NULL, 10, 5, 1, 1093),
(148, 'FAMASTIL TRENA DE 5MTS ', '', 8, '12.00', NULL, '', NULL, 2, NULL, 10, 5, 1, 1094),
(149, 'ALIANÇA REGULADOR PARA GÁS S/MANGUEIRA', '', 13, '28.00', NULL, '', NULL, 5, NULL, 10, 4, 1, 1095),
(150, 'AJAX BRAÇADEIRA DE NAYLON (ENFORCA GATO) 250X4,8-- UNIDADE', '', 8, '0.50', NULL, '', NULL, 2, NULL, 2, 50, 1, 1096),
(151, 'ARMADOR DE CHUMBAR (PAR)', '', 13, '13.50', NULL, '', NULL, 12, NULL, 12, 4, 1, 1097),
(152, 'ARMADOR DE PARAFUSAR (PAR)', '', 13, '13.50', NULL, '', NULL, 0, NULL, 12, 4, 1, 1098),
(153, 'TALHADEIRA CHATA 1/4\"X.1\"X1/2\"', '', 8, '20.00', NULL, '', NULL, 3, NULL, 10, 5, 1, 1099),
(154, 'SOPRANO-FECHADURA PARA PORTAO DE ENRROLAR ', '', 13, '45.00', NULL, '', NULL, 5, NULL, 5, 2, 1, 1100),
(155, 'STAM-FECHADURA INOX PARA PORTA DE CORRER S/TRINCO', '', 13, '45.00', NULL, '', NULL, 5, NULL, 6, 2, 1, 1101),
(156, 'DURIM- GRAMPO SARGENTO 4\"', '', 8, '40.00', NULL, '', NULL, 5, NULL, 5, 5, 1, 1102),
(157, 'LOTUS PLUS- JOGO DE 5 PONTAS MONTADAS 1/8(3MM)', '', 8, '20.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1103),
(158, 'IRWIN-COMPONETE DE REPOSIÇAO DA LINHA DE CORTES DE PISOS E A', '', 8, '20.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1104),
(159, 'WORKWR-CONTADOR VCM-4 ', '', 13, '47.50', NULL, '', NULL, 1, NULL, 1, 2, 1, 1105),
(160, 'HEAVY DUTY PONTEIRA CANHÃO 5/16X45MM', '', 13, '25.00', NULL, NULL, NULL, 5, NULL, 5, 4, 1, NULL),
(161, 'ENERBRAS-CHAVE TESTE DIGITAL TENSÃO MAX12V-220V', '', 7, '12.00', NULL, '0', NULL, 4, NULL, 4, 2, 1, 1106),
(162, 'IRWIN-SERRA COPO P/MADEIRA 1-1/4\"   32MM', '', 8, '20.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1107),
(163, 'BOMCORTE-DISCO REBOLO ABASIVO PARA METAIS 6\"X3/4  X  1 1', '', 8, '28.00', NULL, '', NULL, 2, NULL, 2, 3, 1, 1108),
(164, 'DISCO DE FERRO DE 10\" ', '', 8, '18.00', NULL, '', NULL, 4, NULL, 4, 5, 1, 1109),
(165, 'MAX-MARTELO DE BORRACHA 40MM ', '', 8, '15.00', NULL, '', NULL, 7, NULL, 7, 5, 1, 1110),
(166, 'MAX-ESQUADRO 10\"', '', 8, '22.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1111),
(167, 'MAX-ESQUADRO 12\"', '', 8, '23.00', NULL, '', NULL, 3, NULL, 3, 3, 1, 1112),
(168, 'KALIPSO-LUVA DE LATEX AMARELA MULTIUSO 9G', '', 13, '6.00', NULL, '0', NULL, 0, NULL, 12, 4, 1, 1113),
(169, 'KALIPSO-LUVA DE PROTEÇÃO PU TAM.9G', '', 13, '6.00', NULL, '', NULL, 8, NULL, 24, 8, 1, 1114),
(170, 'CONDOR-PINCEL 1&#34;', '', 1, '4.00', NULL, NULL, NULL, 1, NULL, 1, 5, 1, NULL),
(171, 'CONDOR-PINCEL 3&#34;', '', 1, '10.00', NULL, NULL, NULL, 6, NULL, 9, 5, 1, NULL),
(172, 'CONDOR-ROLO DE PINTURA C/CABO 5CM', '', 1, '5.00', NULL, '0', NULL, 65, NULL, 65, 20, 1, 1115),
(173, 'CONDOR-ROLO C/CABO 9CM', '', 1, '8.00', NULL, '', NULL, 21, NULL, 21, 10, 1, 1116),
(174, 'CONDOR-ROLO PINTURA 23CM', '', 1, '20.00', NULL, NULL, NULL, 8, NULL, 9, 6, 1, NULL),
(175, 'CONDOR-ROLO TEXTURA S/CABO 23CM', '', 1, '25.00', NULL, '', NULL, 3, NULL, 3, 2, 1, 1117),
(176, 'MAX-ESPATULA DE SILICONE BRANCA', '', 1, '5.00', NULL, NULL, NULL, 11, NULL, 11, 10, 1, NULL),
(177, 'LUMINARIA TIPO TARTARUGA DE VIDRO', '', 7, '20.00', NULL, '', NULL, 5, NULL, 5, 4, 1, 1118),
(178, 'LUMINARIA TIPO TARTARUGA DE PLASTICO', '', 7, '20.00', NULL, NULL, NULL, 5, NULL, 5, 3, 1, NULL),
(179, 'GERDAU PREGO CCAB 19X39 3.1/2X 9', '100G=2,00', 6, '19.00', NULL, '0', NULL, 10, NULL, 15, 5, 1, 1119),
(180, 'GERDAU PREGO CCAB 15X18- 1.1/2X13\"', '100G=3,00', 6, '22.00', NULL, '0', NULL, 15, NULL, 16, 5, 1, 1120),
(181, 'THINNER FARBEN ', '', 1, '17.00', NULL, NULL, NULL, 7, NULL, 9, 5, 1, NULL),
(182, 'CAIXA P/MEDIDOR MONOFASICO', '', 7, '42.00', NULL, '', NULL, 2, NULL, 2, 1, 1, 1121),
(183, 'CAIXA P/MEDIDOR BIFASICO', '', 7, '77.00', NULL, '', NULL, 2, NULL, 2, 1, 1, 1122),
(184, 'CAIXA P/MEDIDOR TRIFASICO ', '', 7, '90.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1123),
(185, 'topcables - fio 10mm pç100mt', 'mt-9,00', 7, '850.00', NULL, '0', NULL, 1, NULL, 1, 1, 1, 1125),
(186, 'topcables - fio 2,5mm pç100mt', 'mt-3,00', 7, '250.00', NULL, '0', NULL, 0, NULL, 1, 1, 1, 1126),
(187, ' fita drykofita (manta)10cmx10mt ', 'mt-4,00', 12, '30.00', NULL, '0', NULL, 0, NULL, 4, 1, 1, 1127),
(188, ' fita drykofita (manta) 30cmx10mt ', 'mt-10,00', 12, '90.00', NULL, '0', NULL, 1, NULL, 11, 1, 1, 1128),
(189, 'tramontina furadeira de impacto 3/8-10mm 500w 127v', '', 9, '210.00', NULL, '', NULL, 1, NULL, 2, 1, 1, 1129),
(190, 'rayma sapeca-bomba sapo 280whats', '', 9, '316.00', NULL, '', NULL, 1, NULL, 1, 0, 1, 1130),
(191, 'PLASMÓDIA CORDA 2,0MM BRANCA -455MTS', '', 14, '50.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1131),
(192, 'ASTRA-PORTA TOALHA ARGOLA', '', 15, '5.00', NULL, '', NULL, 41, NULL, 40, 20, 1, 1132),
(193, 'ASTRA-PORTA TOALHA RETO', '', 15, '10.00', NULL, '', NULL, 39, NULL, 39, 20, 1, 1133),
(194, 'BOCAL DE PORCELANA GRANDE', '', 7, '7.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1134),
(195, 'MARFIX-PORTA TOALHA INOX REDONDO', '', 15, '10.00', NULL, '', NULL, 31, NULL, 30, 10, 1, 1135),
(196, 'MARFIX-PORTA TOALHA DE INOX RETO', '', 15, '10.00', NULL, '0', NULL, 10, NULL, 10, 4, 1, 1136),
(197, 'MARFIX-REPAROS DE TORNEIRA INOX CURTO', '', 16, '10.00', NULL, '', NULL, 15, NULL, 15, 5, 1, 1137),
(198, 'ASTRA REPARO DE ACINAMENTO P/CAIXA DE DESCARGA ACLOPADA LATA', '', 16, '6.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1138),
(199, 'CORTAG- ESPAÇADOR- 2,0MM/3,0MM/4,0MM/5,0MM/8,0MM', '', 8, '4.00', NULL, '', NULL, 9, NULL, 9, 5, 1, 1139),
(200, 'TAMBOR DE FECHADURA HELA/SILVANA', '', 16, '20.00', NULL, '', NULL, 10, NULL, 20, 10, 1, 1140),
(201, 'PAR PARAFUSO C/BUCHA - P/VASO E PIAS  ', '', 13, '7.00', NULL, '', NULL, 30, NULL, 30, 20, 1, 1141),
(202, 'HERC-KIT REPARO REGISTRO DE PLASTCO 01PÇS', '', 16, '4.00', NULL, '0', NULL, 22, NULL, 20, 7, 1, 1142),
(203, 'HERC-REFIL PARA PURIFICADOR', '', 16, '12.00', NULL, '', NULL, 8, NULL, 8, 3, 1, 1143),
(204, 'POLY-ACABAMENTO PARA REGISTROESTRIA  INOX  1/2 E 3/4', '', 16, '32.00', NULL, '0', NULL, 4, NULL, 4, 3, 1, NULL),
(205, 'LUBREFER- SACA FILTRO DE OLEO MEDIA ', '', 8, '35.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1144),
(206, 'LUBREFER-BICO D AR PARA LIMPEZA C/BICO DE NYLON', '', 8, '24.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1145),
(207, 'MARFIX-FERROLHO CHATO 5\"', '', 6, '7.00', NULL, '', NULL, 18, NULL, 20, 5, 1, 1146),
(208, 'MARKEL-DOBRADIÇA 2.1/2   3UND CARTELADA', '', 6, '13.00', NULL, '0', NULL, 4, NULL, 5, 3, 1, 1147),
(209, 'FIBRA-FERROLHO ROLIÇO 5\"', '', 6, '8.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1148),
(210, 'PARAFUSO PHLIPS C/20UND P/VOLANTE ', '', 13, '18.00', NULL, '', NULL, 5, NULL, 5, 0, 1, 1149),
(211, 'KRONA-TORNEIRA BOIA P CX D`AGUA 1/2 E 3/4', '', 17, '13.00', NULL, '', NULL, 19, NULL, 20, 10, 1, 1150),
(212, 'MARFIX-GANCHO DUPLO (CABIDE) INOX', '', 13, '8.00', NULL, '', NULL, 6, NULL, 6, 3, 1, 1151),
(213, 'DUDA-SIFÃO COPOFLEX BRANCO C/ANEL PLASTICO', '', 17, '10.00', NULL, '', NULL, 6, NULL, 6, 2, 1, 1152),
(214, 'VALEPLAST-ANEL DE VEDAÇÃO PARA VASO SANITARIO', '', 16, '7.00', NULL, '', NULL, 19, NULL, 20, 10, 1, 1153),
(215, 'COMPEL-ESTOPA DE POLIMENTO CRUA 200G', '', 13, '10.00', NULL, '', NULL, 10, NULL, 10, 7, 1, 1154),
(216, 'CORTAG-DESEMPENADEIRA DENTADA 10X10MM', '', 8, '15.00', NULL, '', NULL, 6, NULL, 10, 5, 1, 1155),
(217, 'KAEL-DESEMPENADEIRA LISA DE AÇO CABO PLASTICO/MADEIRA', '', 8, '15.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1156),
(218, 'OK BRASIL-DESEMPENADEIRA PLASTICA PRETA LISA', '', 8, '15.00', NULL, '', NULL, 3, NULL, 10, 5, 1, 1157),
(219, 'LUZARTE-VASO ACLOPADO S/CAIXA', '', 15, '180.00', NULL, '', NULL, 1, NULL, 1, 0, 1, 1158),
(220, 'LUZARTE-PIA C/COLUNA  PARA BANHEIRO', '', 15, '220.00', NULL, '', NULL, 1, NULL, 2, 1, 1, 1159),
(221, 'PIA DE VIDRO VERMELHA DE LUXO SUPORTE INOX', '', 15, '450.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1160),
(222, 'CANELA DE PIA MARROM-UNIVERSAL', '', 15, '45.00', NULL, '0', NULL, 5, NULL, 5, 0, 1, 1161),
(223, 'TAMPA DE VASO SANITARIO BRANCO HERC', '', 15, '35.00', NULL, '0', NULL, 8, NULL, 8, 3, 1, 1162),
(224, 'GOOL-FITA VEDA ROSCA 18MMX5MTS', '', 12, '3.00', NULL, '', NULL, 43, NULL, 60, 15, 1, 1163),
(225, 'MARKEL-DOBRADIÇA 3.1/2   3UND CARTELADA', '', 6, '17.00', NULL, '', NULL, 1, NULL, 14, 3, 1, 1164),
(226, 'MARKEL-DOBRADIÇA 3\"   3UND CARTELADA', '', 6, '14.00', NULL, '', NULL, 7, NULL, 19, 3, 1, 1165),
(227, 'ASTRA-GRELHA P/CAIXA SIFONADA REDONDA -DIANMETRO 15CM', '', 15, '10.00', NULL, '0', NULL, 3, NULL, 3, 2, 1, 1166),
(228, 'REAL- REGISTRO DE PRESSÃO 1/2 ', '', 16, '45.00', NULL, '', NULL, 0, NULL, 10, 3, 1, 1167),
(229, 'VIQUA-RESGISTRO DE PRESÃO SOLD 20MM', '', 16, '15.00', NULL, '0', NULL, 3, NULL, 3, 3, 1, 1168),
(230, 'POLY-REGISTRO PARA FILTRO 1/2 AGUA FRIA', '', 16, '20.00', NULL, '', NULL, 2, NULL, 2, 2, 1, 1169),
(231, 'AJAX-REGISTRO DE ESFERA 1/2 ', '', 16, '20.00', NULL, '', NULL, 16, NULL, 17, 10, 1, 1170),
(233, 'ASTRA-REPARO PARA MECANISMO DE CAIXA ACLOPADA', '', 16, '10.00', NULL, '', NULL, 19, NULL, 19, 5, 1, 1171),
(234, 'RIP-CHUVEIRO PLASTICO CROMADO 7\"', '', 15, '32.00', NULL, '', NULL, 4, NULL, 4, 4, 1, 1172),
(235, 'RIP-CHUVEIRO PLASTICO CROMADO 4\"', '', 15, '24.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1173),
(236, 'RIP-CHUVEIRO PLASTICO S/REGISTRO BRANCO 7\"', '', 15, '17.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1174),
(238, 'PLASBOHN-SIFÃO DUPLO ', '', 17, '16.00', NULL, '', NULL, 12, NULL, 12, 10, 1, 1175),
(239, 'PLASBOHN-SIFÃO TRIPLO', '', 17, '35.00', NULL, '0', NULL, 12, NULL, 10, 5, 1, 1176),
(240, 'PLASBOHN-SIFÃO SIMPLES', '', 17, '8.00', NULL, '', NULL, 18, NULL, 20, 7, 1, 1177),
(241, 'LUCONI-SIFÃO COPINHO S/VALVULA 7/8 X 1. 1/2', '', 17, '12.00', NULL, NULL, NULL, 2, NULL, 2, 1, 1, NULL),
(242, 'BOGNAR-TORNEIRA NOX DE MESA 1/2 ', '', 15, '74.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1178),
(243, 'BOGNAR-TORNEIRA NOX DE PAREDE 1/2 ', '', 15, '68.00', NULL, '', NULL, 1, NULL, 6, 3, 1, 1179),
(244, 'ASTRA-MECANISMO DE DESCARGA COMPLETO UNIVERSAL', '', 16, '100.00', NULL, '', NULL, 1, NULL, 3, 1, 1, 1180),
(245, 'HERC- TORNEIRA PAREDE PLASTICO BRANCA 1/2 ', '', 15, '28.00', NULL, '', NULL, 20, NULL, 40, 15, 1, 1181),
(246, 'ASTRA-MECANISMO DE DESCARGA SAÍDA DE ÁGUA', '', 16, '80.00', NULL, '', NULL, 2, NULL, 2, 1, 1, 1182),
(247, 'ASTRA-MECANISMO DE DESCARGA ENTRADA DE ÁGUA', '', 16, '65.00', NULL, '', NULL, 1, NULL, 3, 1, 1, 1183),
(248, 'OK BRASIL CANTONEIRA MÃO FRANCESA 30CM', '', 13, '10.00', NULL, '', NULL, 4, NULL, 12, 4, 1, 1184),
(249, 'OK BRASIL CANTONEIRA MÃO FRANCESA 40CM', '', 13, '16.00', NULL, '', NULL, 9, NULL, 9, 4, 1, 1185),
(250, 'DESEMPENADEIRA 14X24 PVC COM ESPUMA', '', 13, '10.00', NULL, '', NULL, 7, NULL, 7, 4, 1, 1187),
(251, 'TRAMONTINA MANGUEIRA FLEXIVEL PARA JARDINAGEM 10MT', '', 13, '45.00', NULL, '', NULL, 3, NULL, 4, 2, 1, 1188),
(252, 'FAMASTIL KIT MANGUEIRA 20MT', '', 13, '75.00', NULL, '', NULL, 2, NULL, 4, 2, 1, 1189),
(253, 'KAEF COLHER P\\ PEDREIRO 9\'\' ', '', 8, '16.00', NULL, '', NULL, 1, NULL, 1, 10, 1, 1190),
(254, 'KAEF COLHER P\\ PEDREIRO 8\'\'', '', 8, '14.00', NULL, '', NULL, 15, NULL, 15, 10, 1, 1191),
(255, 'FORD BROCA EXTRA LONGA-VÍDEA 12X 400MM', '', 8, '15.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1192),
(256, 'KAEF DESEMPENADEIRA DE AÇO LISA COM CABO DE PLASTICO 256MMX1', '', 8, '15.00', NULL, '', NULL, 20, NULL, 15, 10, 1, 1193),
(257, 'MAX FERRAMENTA DESEMPENADEIRA DE AÇO LISA', '', 8, '15.00', NULL, '', NULL, 15, NULL, 15, 10, 1, 1195),
(258, 'IRWIN BROCA 16MM', '', 8, '12.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1196),
(259, 'MAKITA BROCA PROLINE 16MM', '', 8, '30.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1197),
(260, 'HE AVY DUTY BROCA HD PLUS MARTELETES 8,0MMX260MM', '', 8, '25.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1198),
(261, 'BROCA CHATA DE AÇO CARBONO 1\\4', '', 8, '10.00', NULL, '', NULL, 3, NULL, 4, 3, 1, 1199),
(262, 'BROCA CHATA PRA MEDEIRA 5\\16\'\'', '', 8, '11.00', NULL, '', NULL, 2, NULL, 2, 3, 1, 1200),
(263, 'SILVANA NUMERO DE CASA ', '', 8, '15.00', NULL, '', NULL, 15, NULL, 15, 6, 1, 1201),
(264, 'REATOR ELETRÕNICO', '', 7, '15.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1203),
(265, 'PHILIPS REATOR 20W', '', 7, '20.00', NULL, '', NULL, 20, NULL, 20, 6, 1, 1205),
(266, 'TARRAXAS TORMEP 3/4', '', 8, '20.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1206),
(267, 'TARRAXAS PARA TUBOS 2 1/2', '', 8, '30.00', NULL, '', NULL, 5, NULL, 5, 3, 1, 1207),
(268, 'DELTA PLUS PROTECÃO AUDITIVA', '', 13, '20.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1208),
(269, 'BOCAL PLAFON', '', 7, '5.00', NULL, '', NULL, 89, NULL, 90, 30, 1, 1209),
(270, 'RAMADA SERRA DE AÇO DENTADA', '', 8, '29.00', NULL, '', NULL, 6, NULL, 7, 5, 1, 1210),
(271, 'RAMADA  SERRA DE AÇO DENTADA 559MM', '', 8, '33.00', NULL, '', NULL, 11, NULL, 13, 6, 1, 1211),
(272, 'TEÇADO CABO DE MADEIRA', '', 8, '40.00', NULL, '', NULL, 16, NULL, 16, 10, 1, 1212),
(273, 'TEÇADO CABO DE PLASTICO', '', 8, '25.00', NULL, '', NULL, 6, NULL, 6, 10, 1, 1213),
(274, 'PARABONI ENXADÃO SEM CABO', '', 8, '40.00', NULL, '', NULL, 2, NULL, 6, 3, 1, 1214),
(275, 'TRAMONTINA FOICE ROÇADEIRA SEM CABO', '', 8, '25.00', NULL, '', NULL, 10, NULL, 10, 5, 1, 1216),
(276, 'DINSER SERRA COPO DIAMANTADA 25MM', '', 8, '25.00', NULL, '', NULL, 5, NULL, 6, 5, 1, 1217),
(277, 'TERALF GUIA FIO CINZA 20MT', '', 7, '12.00', NULL, '', NULL, 6, NULL, 6, 3, 1, 1218),
(278, 'MANGUEIRA PARA MAQUINA DE LAVAR 1,20MT', '', 15, '12.00', NULL, '', NULL, 1, NULL, 6, 2, 1, 1219),
(279, 'PLASTIL SAIDA DE ÁGUA SUJA DE MAQUINA DE LAVAR', '', 15, '12.00', NULL, '', NULL, 4, NULL, 10, 4, 1, 1220),
(280, 'TRAMONTINA PLUG MACHO 20A', '', 7, '6.00', NULL, '', NULL, 10, NULL, 20, 10, 1, 1221),
(281, 'TRAMONTINA PLUG FÊMEA 20A', '', 7, '6.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1222),
(282, 'PLUG FÊMEA 10A', '', 7, '5.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1223),
(284, ' CLM BASCULHANTE 60X60 PREMIUM', '', 18, '113.00', NULL, '0', NULL, 1, NULL, 8, 1, 1, 1225),
(285, ' CLM BASCULHANTE 60X4O PREMIUM', '', 18, '102.00', NULL, '', NULL, 1, NULL, 6, 1, 1, 1226),
(286, 'CLM BASCULHANTE 50X50 PREMIUM ', '', 18, '88.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1227),
(287, 'CLM BASCULHANTE 40X40 PREMIUM ', '', 18, '72.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1228),
(288, 'CLM BASCULHANTE 30X30 PREMIUM ', '', 18, '54.00', NULL, '', NULL, 0, NULL, 1, 1, 1, 1229),
(289, 'PARABONO ESCADA DE 4 DEGRAL ', '', 13, '280.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1230),
(290, 'PARABONE ESCADA DE 5 DEGRAL', '', 13, '340.00', NULL, '', NULL, 1, NULL, 1, 1, 1, 1231),
(291, 'LIXEIRA DE PLASTICO', '', 13, '10.00', NULL, '', NULL, 3, NULL, 3, 2, 1, 1232),
(292, 'SUPORTE DE BOTIJA DE GÁS ', '', 13, '7.00', NULL, '', NULL, 13, NULL, 15, 4, 1, 1233),
(293, 'MACHADINHA DE AÇO COM CABO DE BORRACHA', '', 8, '25.00', NULL, '', NULL, 2, NULL, 4, 3, 1, 1234),
(294, 'BEBEDORO DE PLASTICO ', '', 13, '10.00', NULL, '', NULL, 3, NULL, 3, 2, 1, 1235),
(295, 'AVELINO TANQUE DE FIBRA DUPLO 1,0MT X 0,50', '', 19, '195.00', NULL, '', NULL, 1, NULL, 2, 1, 1, 1236),
(296, 'DRIKOLAJE MANTA LÍQUIDA', '', 20, '95.00', NULL, '', NULL, 4, NULL, 4, 2, 1, 1238),
(297, 'AREIA FINA LATA S/SACA', '', 21, '3.00', NULL, '0', NULL, NULL, NULL, 0, 0, 0, NULL),
(298, 'AREIA FINA CARRADA 5MTS', '', 21, '400.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(299, 'AREIA FINA MEIA CARRADA 2,5MTS', '', 21, '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(300, 'SEIXO LATA S/SACA ', '', 21, '9.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(301, 'SEIXO EM LATA C/SACA', '', 21, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(302, 'AREIA FINA LATA C/SACA', '', 21, '4.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(303, 'TANQUE DE 500LT GREEN', '', 19, '260.00', NULL, NULL, NULL, 14, NULL, 16, 10, 1, NULL),
(304, 'ENGATE 30CM', '', 17, '3.00', NULL, '', NULL, 50, NULL, 50, 15, 1, 1239),
(305, 'ENGATE 40CM', '', 17, '5.00', NULL, '', NULL, 50, NULL, 50, 15, 1, 1240),
(306, 'UNIÃO ROSCAVEL 1/2 ', '', 17, '6.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1241),
(307, 'UNIÃO ROSCAVEL 3/4', '', 17, '10.00', NULL, '', NULL, 20, NULL, 40, 20, 1, 1242),
(308, 'UNIÃO ROSCAVEL 1\'\'', '', 17, '15.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1243),
(309, 'FRANJA ROSCAVEL 1.1/4', '', 17, '18.00', NULL, '', NULL, 50, NULL, 50, 15, 1, 1244),
(310, 'JOELHO ROSCAVEL 3/4', '', 17, '3.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1245),
(311, 'JOELHO ROSCAVEL 1/2', '', 17, '2.00', NULL, '', NULL, 40, NULL, 40, 10, 1, 1246),
(312, 'JOELHO ROSCAVEL 3/4 - 1/2', '', 17, '3.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1247),
(313, 'JOELHO ROSCAVEL1\'\'', '', 17, '5.00', NULL, '', NULL, 20, NULL, 20, 8, 1, 1248),
(314, 'JOELHO ROSCAVEL 1.1/2', '', 17, '11.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1249),
(315, 'JOELHO ROSCAVEL 1.1/4 ', '', 17, '8.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(316, 'JOELHO ROSCAVEL 2\'\'', '', 17, '20.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1251),
(317, 'TEE ROSCAVEL 3/4X1/2', '', 17, '3.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1252),
(318, 'JOELHO ROSCAVEL 1. 1/2', '', 17, '11.00', NULL, '', NULL, 20, NULL, 20, 8, 1, 1253),
(319, 'JOELHO ROSCAVEL 1. 1/4', '', 17, '8.00', NULL, '', NULL, 20, NULL, 20, 8, 1, 1254),
(321, 'TEE ROSCAVEL 3/4', '', 17, '3.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1256),
(322, 'TEE ROSCAVEL 1/2', '', 17, '2.00', NULL, NULL, NULL, 20, NULL, 20, 10, 1, NULL),
(323, 'TEE ROSCAVEL 1.1/2', '', 17, '5.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1257),
(324, 'TEE ROSCAVEL 1.1/4', '', 17, '10.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1258),
(325, 'TEE ROSCAVEL 1.1/2', '', 17, '11.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1259),
(326, 'TEE ROSCAVEL 2\'\'', '', 17, '5.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1260),
(327, 'NIPE ROSCAVEL 1. 1/2', '', 17, '5.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1261),
(328, 'NIPE ROSCAVEL 1\'\'', '', 17, '4.00', NULL, '', NULL, 20, NULL, 30, 10, 1, 1262),
(329, 'NIPE ROSCAVEL 3/4', '', 17, '3.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1263),
(330, 'NIPE ROSCAVEL 1/2', '', 17, '2.00', NULL, '', NULL, 19, NULL, 40, 20, 1, 1264),
(331, 'ESTUPE PARA VASO SANITARIO', '', 17, '4.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1265),
(332, 'LUVA ROSCAVEL 1/2 ', '', 17, '2.00', NULL, '', NULL, 20, NULL, 50, 10, 1, 1266),
(333, 'LUVA ROSCAVEL 3/4', '', 17, '3.00', NULL, '', NULL, 20, NULL, 50, 10, 1, 1267),
(334, 'BUJÃO ROSCAVEL 1/2', '', 17, '2.00', NULL, '', NULL, 25, NULL, 50, 15, 1, 1268),
(335, 'BUJÃO ROSCAVEL 1.1/4 ', '', 17, '5.00', NULL, '', NULL, 50, NULL, 50, 15, 1, 1269),
(336, 'BUJÃO ROSCAVEL 1\'\'', '', 17, '3.00', NULL, '', NULL, 50, NULL, 50, 20, 1, 1270),
(337, 'REDUCÃO ROSCAVEL 3/4 X 1/2 ', '', 17, '3.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1271),
(338, 'REDUÇÃO ROSCAVEL 1.1/2 X 3/4', '', 17, '4.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1273),
(339, 'TEE 40MM PARA ESGOTO', '', 17, '3.00', NULL, '', NULL, 20, NULL, 30, 15, 1, 1275),
(340, 'Y DE 40MM ESGOTO', '', 17, '3.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1276),
(341, 'Y DE 50MM ESGOTO', '', 17, '5.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1277),
(342, 'TEE 75X50 ESGOTO', '', 17, '8.00', NULL, '', NULL, 15, NULL, 15, 5, 1, 1278),
(343, 'TEE 75 ESGOTO', '', 17, '7.00', NULL, '', NULL, 20, NULL, 30, 15, 1, 1279),
(344, 'CURVA DE100MM PARA ESGOTO', '', 17, '8.00', NULL, '', NULL, 30, NULL, 30, 10, 1, 1281),
(345, 'TEE 50MM PARA ESGOTO', '', 17, '6.00', NULL, '', NULL, 20, NULL, 30, 15, 1, 1282),
(346, 'JOELHO ESGOTO 40MM ', '', 17, '3.00', NULL, '', NULL, 26, NULL, 36, 15, 1, 1283),
(347, 'TEE ESGOTO 100MM ', '', 17, '15.00', NULL, '', NULL, 20, NULL, 20, 7, 1, 1285),
(348, 'Y PARA ESGOTO 75 X 50 ', '', 17, '8.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1286),
(349, 'JOELHO  ESGOTO 75MM', '', 17, '7.00', NULL, '', NULL, NULL, NULL, 0, 0, 0, 1288),
(350, 'REDUÇÃO 50 X 40 ', '', 17, '3.00', NULL, '', NULL, 20, NULL, 30, 10, 1, 1289),
(351, 'JOELHO ESGOTO 100MM ', '', 17, '10.00', NULL, '', NULL, 15, NULL, 15, 6, 1, 1290),
(353, 'REDUÇÃO ESGOTO 100X50', '', 17, '7.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1292),
(354, 'Y ESGOTO 100X75', '', 17, '8.00', NULL, '', NULL, 15, NULL, 15, 7, 1, 1293),
(356, 'JOELHO ESGOTO 50MM ', '', 17, '5.00', NULL, '', NULL, 3, NULL, 23, 8, 1, 1295),
(357, 'LUVA ESGOTO 75MM', '', 17, '6.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1297),
(358, 'LUVA ESGOTO DE 50MM ', '', 17, '5.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1298),
(359, 'CAP ESGOTO 50MM', '', 17, '5.00', NULL, '', NULL, 50, NULL, 50, 15, 1, 1299),
(360, 'CAP 40MM', '', 17, '2.00', NULL, '', NULL, 80, NULL, 80, 20, 1, 1300),
(361, 'LUVA CORRER ESGOTO 40MM', '', 17, '8.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1301),
(362, 'LUVA CORRER 50MM', '', 17, '3.00', NULL, '', NULL, 20, NULL, 20, 10, 1, 1302),
(363, 'LUVA ESGOTO 40MM', '', 17, '4.00', NULL, '0', NULL, 20, NULL, 20, 10, 1, 1303),
(365, 'TEE ESGOTO 150MM', '', 17, '55.00', NULL, '', NULL, 4, NULL, 4, 2, 1, 1306),
(366, 'JOELHO ESGOTO 150MM ', '', 17, '50.00', NULL, '', NULL, 2, NULL, 2, 2, 1, 1308),
(367, 'CAP 150MM ESGOTO ', '', 17, '28.00', NULL, '', NULL, 4, NULL, 4, 3, 1, 1309),
(368, 'CAP DE 100MM ESGOTO ', '', 17, '7.00', NULL, '', NULL, 12, NULL, 12, 5, 1, 1310),
(369, 'PONTALETE MONOFASICO ', '', 7, '95.00', NULL, NULL, NULL, 5, NULL, 5, 2, 1, NULL),
(370, 'PONTALETE BIFASICO', '', 7, '115.00', NULL, NULL, NULL, 5, NULL, 5, 2, 1, NULL),
(371, 'PONTALETE TRIFASICO', '', 7, '125.00', NULL, NULL, NULL, 5, NULL, 5, 2, 1, NULL),
(372, 'CONDUITE CONRRUGADO AMARELO 3/4-25M', '', 7, '65.00', NULL, NULL, NULL, 1, NULL, 4, 1, 1, NULL),
(373, 'CONDUITE CONRRUGADO PRETO 3/4-25M', '', 7, '60.00', NULL, NULL, NULL, 2, NULL, 4, 1, 1, NULL),
(374, 'MANGUEIRA POLITILENO PRETA 1/2 C/100MTS', '', 22, '180.00', NULL, NULL, NULL, 2, NULL, 2, 1, 1, NULL),
(375, 'MANGUEIRA POLITILENO PRETA 1/2 -- P/METRO', '', 22, '2.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(376, 'HIPERCOR TINTA ACRILICA BRANCO NEVE 3,6L', '', 1, '35.00', NULL, NULL, NULL, 1, NULL, 6, 2, 1, NULL),
(377, 'HIPERCOR TINTA ACRILICA BRANCO NEVE 15L', '', 1, '95.00', NULL, NULL, NULL, 0, NULL, 0, 1, 1, NULL),
(378, 'HIPERCOR TINTA ECONOMICA ACRILICA AREIA 15L', '', 1, '95.00', NULL, '', NULL, 0, NULL, 1, 1, 1, 1311),
(379, 'TINTA ECONOMICA ACRILICA AZUL PAVÃO 15LT HIPERCOR', '', 1, '95.00', NULL, NULL, NULL, 1, NULL, 1, 1, 1, NULL),
(380, 'VARAL DE CORTINA DE 4MTS (COMPLETO)', '', 13, '55.00', NULL, NULL, NULL, 15, NULL, 15, 6, 1, NULL),
(381, 'VARAL DE CORTINA DE 3MTS (COMPLETO)', '', 13, '45.00', NULL, NULL, NULL, 15, NULL, 15, 6, 1, NULL),
(382, 'VARAL DE CORTINA 2MTS (COMPLETO)', '', 13, '35.00', NULL, NULL, NULL, 15, NULL, 15, 6, 1, NULL),
(383, 'FIO P/ROÇADEIRA LARANJA- MT', '', 13, '2.00', NULL, NULL, NULL, 195, NULL, 200, 40, 1, NULL),
(384, 'MILHEIRO DE TIJOLO', '', 23, '1025.00', NULL, NULL, NULL, 1, NULL, 1, 0, 1, NULL),
(385, 'TIJOLOS MEIO MILHEIRO', '', 23, '525.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(386, 'TIJOLO P/UNIDADE', '', 23, '1.10', NULL, NULL, NULL, -679, NULL, 1000, 100, 1, NULL),
(387, 'TINTA ESMALTE PRETO LUX 750ML', '', 1, '24.00', NULL, NULL, NULL, 0, NULL, 7, 5, 1, NULL),
(388, 'TINTA SEMI AMARELO REAL 15LT LUX', '', 1, '240.00', NULL, NULL, NULL, 1, NULL, 1, 1, 1, NULL),
(389, 'TINTA SEMI AREIA 15LT LUX', '', 1, '240.00', NULL, NULL, NULL, 1, NULL, 1, 1, 1, NULL),
(390, 'TINTA SEMI PALHA 15LT LUX', '', 1, '240.00', NULL, NULL, NULL, 1, NULL, 1, 1, 1, NULL),
(391, 'MANGUEIRA TRANÇADA 1/2     P/METRO', '', 22, '7.00', NULL, NULL, NULL, 1, NULL, 3, 1, 1, NULL),
(392, 'MANGUEIRA CRISTAL 3/4     P/METRO', '', 22, '5.00', NULL, NULL, NULL, 1, NULL, 3, 1, 1, NULL),
(393, 'TAMPA DE VASO SANITARIO CINZA HERC', '', 15, '35.00', NULL, NULL, NULL, 9, NULL, 9, 4, 1, NULL),
(394, 'TAMPA DE VASO SANITARIO PRETO- HERC', '', 15, '35.00', NULL, NULL, NULL, 8, NULL, 8, 3, 1, NULL),
(395, 'torneira branca 1/2', '', 17, '5.00', NULL, NULL, NULL, 20, NULL, 20, 10, 1, NULL),
(396, 'REGISTRO SOLD 20MM', '', 17, '8.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(397, 'CAIXA DE AR DE 7.500BTUS', '', 25, '85.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(398, 'topcables- fio 4mm (metro)', '', 7, '4.00', NULL, NULL, NULL, 0, NULL, 1, 1, 1, NULL),
(399, 'TOPCABLES- FIO 6MM (METRO)', '', 7, '6.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(400, 'ESTRIBO 7X17 (UND)', '', 6, '0.80', NULL, '', NULL, 25, NULL, 50, 30, 1, NULL),
(401, 'ESTRIBO 7X27 (UND)', '', 6, '0.80', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(402, 'PREGO 1 .1/2  -100G', '', 6, '2.00', NULL, NULL, NULL, 1000, NULL, 1000, 200, 1, NULL),
(403, 'PREGO 3.1/2  -100G', '', 6, '2.00', NULL, NULL, NULL, 1000, NULL, 1000, 200, 1, NULL),
(404, 'TOMADA E INTERRUPTORES PROMO', '', 7, '2.00', NULL, NULL, NULL, 73, NULL, 100, 30, 1, NULL),
(405, 'PLAFON  PROMOÇÃO', '', 7, '2.00', NULL, NULL, NULL, 96, NULL, 96, 30, 1, NULL),
(406, 'JOELHO ESGOTO 75MM', '', 17, '6.00', NULL, NULL, NULL, 30, NULL, 30, 10, 1, NULL),
(407, 'JANELA DE ALUMINIU 1,0X1,0MT', '', 18, '248.00', NULL, NULL, NULL, 0, NULL, 2, 1, 1, NULL),
(409, 'PIA DE INOX CONCRETADA 1,0X0,50 MT ', '', 19, '215.00', NULL, NULL, NULL, 0, NULL, 2, 1, 1, NULL),
(410, 'PIA DE INOX CONCRETADA 1,20X0,50 MT', '', 19, '230.00', NULL, NULL, NULL, 0, NULL, 2, 1, 1, NULL),
(411, 'PORTA S/PORTAL ', '', 18, '0.00', NULL, '0', NULL, 0, NULL, 2, 1, 1, NULL),
(412, 'TUBO SOLD 20MM ', '', 22, '15.00', NULL, NULL, NULL, 0, NULL, 50, 10, 1, NULL),
(413, 'TUBO SOLD 25MM', '', 22, '20.00', NULL, NULL, NULL, 0, NULL, 30, 10, 1, NULL),
(414, 'TUBO ESGOTO 100MM', '', 22, '55.00', NULL, NULL, NULL, 2, NULL, 20, 5, 1, NULL),
(415, 'TUBO ESGOTO 50MM', '', 22, '35.00', NULL, NULL, NULL, 0, NULL, 10, 5, 1, NULL),
(416, 'CONDOR-PINCEL 2&#34;', '', 1, '7.00', NULL, NULL, NULL, 0, NULL, 0, 5, 1, NULL),
(417, 'CIMENTO DE KL', '', 5, '3.00', NULL, NULL, NULL, -18, NULL, 42, 0, 1, NULL),
(418, 'esponja pedreiro', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 4, 1, NULL),
(419, 'chave de fenda fina', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 5, 1, NULL),
(420, 'SPRAY BRANCO', '', 24, '00.00', NULL, NULL, NULL, 0, NULL, 6, 3, 1, NULL),
(421, 'DUREPOX PEQUENO ', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 5, 1, NULL),
(422, 'DUREPOX GRANDE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 5, 1, NULL),
(423, 'BOMBA PERIFERICA', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 1, 0, 1, NULL),
(424, 'TORNEIRA PRETA P JARDIM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 6, 1, NULL),
(425, 'JOELHO 50 ESGOTO', '', 24, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(426, 'PLASTICO P MESA TRANSPARENTE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 1, 0, 1, NULL),
(427, 'REGISTRO DE 50 SOLD', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 4, 1, NULL),
(428, 'TELA PARA GALINHEIRO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 1, 0, 1, NULL),
(429, 'TUBO ELETRODUTO DE 1&#34;', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 4, 1, NULL),
(430, 'LUVA DE 20 SOLD', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 50, 10, 1, NULL),
(431, 'CAP DE 20 SOLD', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 50, 10, 1, NULL),
(432, 'TEE DE 20 SOLD', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 50, 10, 1, NULL),
(433, 'LUVA DE 32X3/4   R/S', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 30, 10, 1, NULL),
(434, 'SUPORTE P TV', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(435, 'REJUNTE COR  AREIA', '', 24, '0.00', NULL, NULL, NULL, -11, NULL, 20, 6, 1, NULL),
(436, 'BUCHA DE REDUÇÃO 25X1/2 SOLD', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 50, 10, 1, NULL),
(437, 'DUCHA HIGIENICA ', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(438, 'FECHADURA -MODELOS DIFERENTE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(439, 'CHAVE DE BOCA COMBINADA 24MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(440, 'CHAVE DE BOCA COMBINADA 19MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(441, 'BEIJAME', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 6, 1, NULL),
(442, 'REFLETOR ', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(443, 'TELA VERDE PARA MOSQUITO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 2, 1, 1, NULL),
(444, 'BARRA DE ATERRAMENTO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(445, 'JOGO DE CHAVE ALEM C/8', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(446, 'JOGO DE CHAVE COMBINADA DE 16 PÇS', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(447, 'DISCO DE DESBASTE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(448, 'CANTONEIRA PEQUENA 4PÇS', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(449, 'KIT ADPTADOR PARA SOQUETE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(450, 'KIT REMENDO PARA PNEU', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(451, 'FIO METAIS PARA SOLDA', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(452, 'FERROLHO CHATO GRANDE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(453, 'PENEIRA DE AREIA', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(454, 'ESPATUA DE AÇO C/CABO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 12, 3, 1, NULL),
(455, 'NUMERO DE CASA 1', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(456, 'NUMERO DE CASA 2', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(457, 'FITA TRANSPARENTE', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(458, 'DISJUNTOR MONO 16A', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 5, 1, NULL),
(459, 'DISJUNTOR MONO 20A', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 5, 1, NULL),
(460, 'DISJUNTOR MONO 25A', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 5, 1, NULL),
(461, 'PREGA FIO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 20, 5, 1, NULL),
(462, 'FITA ZEBRADA', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 15, 5, 1, NULL),
(463, 'BROCA ALVENARIA 8MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 12, 4, 1, NULL),
(464, 'BROCA ALVENARIA 6MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 12, 4, 1, NULL),
(465, 'BROCA ALVENARIA 7MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 12, 4, 1, NULL),
(466, 'BROCA ALVENARIA 10MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 12, 4, 1, NULL),
(467, 'BROCA DE AÇO 5MM', '', 24, '0.00', NULL, NULL, NULL, -1, NULL, 10, 4, 1, NULL),
(468, 'BROCA DE AÇO 6MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 4, 1, NULL),
(469, 'BROCA AÇO 8MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 4, 1, NULL),
(470, 'BROCA DE AÇO 10MM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(471, 'GRAMPO DE BATERIA 2&#34; C/8', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(472, 'ESCOVA DE AÇO C/3', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 8, 2, 1, NULL),
(473, 'ENGRAXADEIRA DE ALUMINIO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(474, 'ADAPTADOR P/IRRIGAÇÃO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(475, 'JOGO DE SERRA 12&#34;', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 4, 1, NULL),
(476, 'JOGO DE PONTAS C/10', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 5, 2, 1, NULL),
(477, 'JOGO DE BROCA P MEDEIRA C/5', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 4, 1, 1, NULL),
(478, 'ESPATULA P JARDINAGEM', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(479, 'TELHA BRASILT CANO CURTO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 100, 20, 1, NULL),
(480, 'FORRO PVC PCT', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(481, 'RIBITADEIRA MANOEL', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 6, 2, 1, NULL),
(482, 'ABRAÇADEIRA AMERICANA C/BORBOLETA 3/4 PCT', '', 24, '0.00', NULL, '', NULL, 0, NULL, 2, 1, 1, NULL),
(483, 'FUNIL MULTIUSO', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 7, 2, 1, NULL),
(484, 'MULTIMETRO DE DIGITAL', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 4, 1, 1, NULL),
(485, 'TERMINAL P/BETERIA', '', 24, '0.00', NULL, NULL, NULL, 0, NULL, 10, 3, 1, NULL),
(486, 'PLAFON BRANCO', '', 7, '8.00', NULL, NULL, NULL, 0, NULL, 20, 6, 1, NULL),
(487, 'MÃO FRANCESA 20CM', '', 13, '10.00', NULL, NULL, NULL, 0, NULL, 12, 2, 1, NULL),
(488, 'FLANGE DE 20MM SOLD', '', 17, '12.00', NULL, NULL, NULL, 0, NULL, 20, 5, 1, NULL),
(489, 'LIXA 100', '', 1, '2.50', NULL, NULL, NULL, 36, NULL, 38, 15, 1, NULL),
(490, 'LIXA 120', '', 1, '2.50', NULL, NULL, NULL, 45, NULL, 45, 15, 1, NULL),
(491, 'LIXA 220', '', 1, '2.50', NULL, NULL, NULL, 43, NULL, 43, 15, 1, NULL),
(492, 'MANTA 10CM -MT', '', 12, '5.00', NULL, '', NULL, 10, NULL, 10, 2, 1, NULL),
(493, 'MANTA 05CM -MT', '', 12, '2.00', NULL, NULL, NULL, 8, NULL, 10, 2, 1, NULL),
(494, 'MANTA 30CM -MT', '', 12, '10.00', NULL, NULL, NULL, 9, NULL, 10, 2, 1, NULL),
(495, 'linha de pedreiro 50mts', '', 8, '6.00', NULL, NULL, NULL, 10, NULL, 12, 5, 1, NULL),
(496, 'linha de pedreiro 100mts', '', 8, '7.00', NULL, NULL, NULL, 1, NULL, 12, 5, 1, NULL),
(497, 'SERRIMHA DE FERRO AMARELA', '', 8, '6.00', NULL, NULL, NULL, 6, NULL, 20, 5, 1, NULL),
(498, 'KIT BANHEIRO DE 5PÇS', '', 15, '25.00', NULL, NULL, NULL, 0, NULL, 3, 1, 1, NULL),
(499, 'CHUVEIRO S/RESGISTRO 4&#34; BRANCO', '', 17, '15.00', NULL, NULL, NULL, 8, NULL, 15, 5, 1, NULL),
(500, 'FIO 10MM TOPCABLES -METRO', '', 7, '9.00', NULL, NULL, NULL, 60, NULL, 10, 20, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `cod` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `permissao` int(11) NOT NULL,
  `rua` varchar(40) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `celular` varchar(20) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `comissao` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `tipoempresa` int(11) DEFAULT NULL,
  `entrega` int(11) DEFAULT NULL,
  `opcaopreco` int(11) DEFAULT NULL,
  `etapas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`cod`, `nome`, `usuario`, `rg`, `cpf`, `email`, `foto`, `permissao`, `rua`, `bairro`, `numero`, `celular`, `senha`, `comissao`, `status`, `tipoempresa`, `entrega`, `opcaopreco`, `etapas`) VALUES
(1, 'P.G.A MATERIAL DE CONSTRUÇÃO', 'admin', NULL, '54.228.369/0001-85', '2222', '4ba84ccff860341ca19a3c07c8d491a3.jpeg', 1, 'AVENIDA DO FUTURO', 'ESPIRITO SANTO', '140', '(97)98105-9246', 'f5bb0c8de146c67b44babbf4e6584cc0', NULL, NULL, 3, 1, 1, 1),
(6, 'RAIANE SOUZA DE ALMEIDA2', 'raiane2', NULL, '123123123123', '123123123123', '3113be05d036202ca0c44d2c5c92d45e.png', 1, NULL, NULL, NULL, '(97)98102-2206', 'f5bb0c8de146c67b44babbf4e6584cc0', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'NOME DO USUARIO', 'usuario123', NULL, '021.057.712-62', 'LUCAS_COARI@HOTMAIL.COM', NULL, 1, NULL, NULL, NULL, '(12) 31231-2312', 'f5bb0c8de146c67b44babbf4e6584cc0', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'NOVO CADASTRO2', '1231231232', NULL, '021.057.712-62', 'LUCAS_COARI@HOTMAIL.COM', NULL, 1, NULL, NULL, NULL, '(11) 11111-1111', 'f5bb0c8de146c67b44babbf4e6584cc0', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `categoriaserfin`
--
ALTER TABLE `categoriaserfin`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `fechar_caixa`
--
ALTER TABLE `fechar_caixa`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `financeiro_clientes`
--
ALTER TABLE `financeiro_clientes`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `financeiro_empresa`
--
ALTER TABLE `financeiro_empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `lc_cat`
--
ALTER TABLE `lc_cat`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `lista_entradas`
--
ALTER TABLE `lista_entradas`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `pag_par_pro`
--
ALTER TABLE `pag_par_pro`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `ponto`
--
ALTER TABLE `ponto`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cod`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bairros`
--
ALTER TABLE `bairros`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `categoriaserfin`
--
ALTER TABLE `categoriaserfin`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `entradas`
--
ALTER TABLE `entradas`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `fechar_caixa`
--
ALTER TABLE `fechar_caixa`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `financeiro_clientes`
--
ALTER TABLE `financeiro_clientes`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `financeiro_empresa`
--
ALTER TABLE `financeiro_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `lc_cat`
--
ALTER TABLE `lc_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `lista_entradas`
--
ALTER TABLE `lista_entradas`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `notas`
--
ALTER TABLE `notas`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `pag_par_pro`
--
ALTER TABLE `pag_par_pro`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `ponto`
--
ALTER TABLE `ponto`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
