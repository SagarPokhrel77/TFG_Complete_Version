-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 11:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `futbol`
--

-- --------------------------------------------------------

--
-- Table structure for table `clubes`
--

CREATE TABLE `clubes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `estadio` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clubes`
--

INSERT INTO `clubes` (`id`, `nombre`, `pais`, `estadio`, `logo`) VALUES
(1, 'Real Madrid', 'España', 'Santiago Bernabéu', 'realmadrid.jpg'),
(2, 'FC Barcelona', 'España', 'Montjuic', 'barcelona.png'),
(3, 'Manchester City', 'Inglaterra', 'Etihad Stadium', 'ManchasterCity.png'),
(4, 'Liverpool', 'Inglaterra', 'Anfield', 'liverpool.png'),
(5, 'PSG', 'Francia', 'Parc des Princes', 'psg.png'),
(6, 'Bayern Munich', 'Alemania', 'Allianz Arena', 'bayermunich.jpg'),
(7, 'Juventus', 'Italia', 'Allianz Stadium', 'juventus.png'),
(8, 'AC Milan', 'Italia', 'San Siro', 'acmilan.png'),
(9, 'Arsenal', 'Inglaterra', 'Emirates Stadium', 'arsenal.png'),
(10, 'Chelsea', 'Inglaterra', 'Stamford Bridge', 'chelsea.png'),
(11, 'Manchester United', 'Inglaterra', 'Old Trafford', 'manchesterunited.png'),
(12, 'Atletico Madrid', 'España', 'Metropolitano', 'AtleticoMadrid.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favoritos`
--

INSERT INTO `favoritos` (`id`, `user_id`, `jugador_id`, `fecha`) VALUES
(31, 2, 1, '2026-05-17 20:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `jugadores`
--

CREATE TABLE `jugadores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `posicion` varchar(50) DEFAULT NULL,
  `equipo` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT 0,
  `nacionalidad` varchar(50) DEFAULT NULL,
  `altura` varchar(20) DEFAULT NULL,
  `peso` varchar(20) DEFAULT NULL,
  `pierna` varchar(20) DEFAULT NULL,
  `dorsal` int(11) DEFAULT NULL,
  `valor_mercado` varchar(50) DEFAULT NULL,
  `partidos` int(11) DEFAULT 0,
  `goles` int(11) DEFAULT 0,
  `asistencias` int(11) DEFAULT 0,
  `club_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jugadores`
--

INSERT INTO `jugadores` (`id`, `nombre`, `edad`, `posicion`, `equipo`, `foto`, `observaciones`, `creado_por`, `fecha_registro`, `usuario_id`, `rating`, `nacionalidad`, `altura`, `peso`, `pierna`, `dorsal`, `valor_mercado`, `partidos`, `goles`, `asistencias`, `club_id`) VALUES
(1, 'Cristiano Ronaldo', 39, 'Delantero', 'Real Madrid', 'uploads/Cristiano Ronaldo.jpg', 'Cristiano Ronaldo es una leyenda del fútbol mundial. Destaca por su físico, velocidad y capacidad goleadora. Ganó múltiples Champions League y Balones de Oro. Es considerado uno de los mejores jugadores de la historia.', NULL, '2026-04-18 18:03:10', NULL, 99, 'Portugal', '1.87 m', '83 kg', 'Derecha', 7, '15M€', 1200, 900, 250, 1),
(2, 'Karim Benzema', 36, 'Delantero', 'Real Madrid', 'uploads/Karim Benzema.jpg', 'Karim Benzema es un delantero muy completo con gran visión de juego. Fue clave en el Real Madrid durante años. Ganó Champions League y un Balón de Oro. Destaca por su inteligencia y definición.', NULL, '2026-04-18 18:03:10', NULL, 80, 'Francia', '1.85 m', '81 kg', 'Derecha', 9, '10M€', 850, 430, 210, 1),
(3, 'Sergio Ramos', 38, 'Defensa', 'Real Madrid', 'uploads/Sergio Ramos.jpg', 'Sergio Ramos es un defensa histórico conocido por su liderazgo y agresividad. Marcó goles importantes en finales. Capitán del Real Madrid durante años. Campeón del mundo con España.', NULL, '2026-04-18 18:03:10', NULL, 70, 'España', '1.84 m', '82 kg', 'Derecha', 4, '3M€', 950, 120, 40, 1),
(4, 'Luka Modric', 39, 'Centrocampista', 'Real Madrid', 'uploads/Luka Modric.jpg', 'Luka Modric es un centrocampista elegante con gran control del balón. Ganador del Balón de Oro. Destaca por su visión de juego y pase preciso. Pieza clave del Real Madrid y Croacia.', NULL, '2026-04-18 18:03:10', NULL, 85, 'Croacia', '1.72 m', '66 kg', 'Derecha', 10, '8M€', 1000, 130, 180, 1),
(5, 'Toni Kroos', 34, 'Centrocampista', 'Real Madrid', 'uploads/Toni Kroos.jpg', 'Toni Kroos es un mediocampista alemán con precisión extrema en el pase. Controla el ritmo del partido con inteligencia. Ganador de múltiples Champions League. Uno de los mejores pasadores del mundo.', NULL, '2026-04-18 18:03:10', NULL, 84, 'Alemania', '1.83 m', '76 kg', 'Derecha', 8, '5M€', 850, 90, 170, 1),
(6, 'Iker Casillas', 43, 'Portero', 'Real Madrid', 'uploads/Iker Casillas.jpg', 'Iker Casillas es una leyenda del Real Madrid y España. Portero con reflejos increíbles y gran liderazgo. Campeón del mundo y de Europa. Considerado uno de los mejores porteros de la historia.', NULL, '2026-04-18 18:03:10', NULL, 79, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(7, 'Roberto Carlos', 51, 'Defensa', 'Real Madrid', 'uploads/Roberto Carlos.jpg', 'Roberto Carlos fue un lateral izquierdo ofensivo con un disparo potente. Famoso por sus tiros libres imposibles. Ganó múltiples títulos con el Real Madrid. Icono del fútbol brasileño.', NULL, '2026-04-18 18:03:10', NULL, 95, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(8, 'Raúl González', 47, 'Delantero', 'Real Madrid', 'uploads/Raúl González.jpg', 'Raúl González es una leyenda del Real Madrid. Delantero inteligente con gran olfato goleador. Capitán histórico del club. Símbolo de entrega y profesionalismo.', NULL, '2026-04-18 18:03:10', NULL, 92, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(9, 'Vinícius Jr', 24, 'Delantero', 'Real Madrid', 'uploads/Vinícius Jr.jpg', 'Vinícius Jr es un extremo muy rápido y habilidoso. Destaca por su regate y desequilibrio. Pieza clave del Real Madrid actual. Ha evolucionado como una estrella mundial.', NULL, '2026-04-18 18:03:10', NULL, 89, 'Brasil', '1.76 m', '73 kg', 'Derecha', 7, '180M€', 320, 110, 70, 1),
(10, 'Rodrygo Goes', 23, 'Delantero', 'Real Madrid', 'uploads/Rodrygo Goes.jpg', 'Rodrygo Goes es un atacante joven con gran talento. Destaca por su velocidad y definición en partidos importantes. Ha marcado goles decisivos en Champions League. Futuro del Real Madrid.', NULL, '2026-04-18 18:03:10', NULL, 96, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(11, 'Dani Carvajal', 32, 'Defensa', 'Real Madrid', 'uploads/Dani Carvajal.png', 'Dani Carvajal es un lateral derecho sólido y muy competitivo. Destaca por su intensidad defensiva. Ha ganado múltiples Champions League con el Real Madrid. Jugador clave en defensa.', NULL, '2026-04-18 18:03:10', NULL, 94, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(12, 'Thibaut Courtois', 32, 'Portero', 'Real Madrid', 'uploads/Thibaut Courtois.jpg', 'Thibaut Courtois es un portero belga de gran altura y reflejos. Decisivo en finales de Champions League. Considerado uno de los mejores porteros del mundo. Seguridad bajo palos.', NULL, '2026-04-18 18:03:10', NULL, 84, 'Bélgica', '2.00 m', '96 kg', 'Izquierda', 1, '25M€', 600, 0, 2, 1),
(13, 'Federico Valverde', 25, 'Centrocampista', 'Real Madrid', 'uploads/Federico Valverde.jpg', 'Federico Valverde es un centrocampista uruguayo muy completo. Destaca por su energía, velocidad y disparo potente. Puede jugar en varias posiciones. Fundamental en el Real Madrid moderno.', NULL, '2026-04-18 18:03:10', NULL, 97, 'Uruguay', '1.82 m', '78 kg', 'Derecha', 15, '100M€', 420, 55, 40, 1),
(14, 'Éder Militão', 26, 'Defensa', 'Real Madrid', 'uploads/Éder Militão.jpg', 'Éder Militão es un defensa central fuerte y rápido. Destaca por su físico y capacidad defensiva. Importante en la zaga del Real Madrid. Gran proyección internacional.', NULL, '2026-04-18 18:03:10', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(15, 'David Alaba', 32, 'Defensa', 'Real Madrid', 'uploads/David Alaba.jpg', 'David Alaba es un defensa polivalente con gran calidad técnica. Puede jugar en varias posiciones defensivas. Experiencia en grandes clubes europeos. Campeón de Champions League.', NULL, '2026-04-18 18:03:10', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(16, 'Antonio Rüdiger', 31, 'Defensa', 'Real Madrid', 'uploads/Antonio Rüdiger.jpg', 'Antonio Rüdiger es un defensa físico y agresivo. Destaca en duelos individuales y juego aéreo. Fundamental en defensa. Experiencia en grandes competiciones.', NULL, '2026-04-18 18:03:10', NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(17, 'Aurélien Tchouaméni', 24, 'Centrocampista', 'Real Madrid', 'uploads/Aurélien Tchouaméni.jpg', 'Aurélien Tchouaméni es un mediocentro defensivo moderno. Recupera balones y distribuye con inteligencia. Gran futuro en el Real Madrid. Jugador muy completo.', NULL, '2026-04-18 18:03:10', NULL, 92, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(18, 'Eduardo Camavinga', 22, 'Centrocampista', 'Real Madrid', 'uploads/camavinga.jpg', 'Eduardo Camavinga es un joven centrocampista con mucho talento. Destaca por su versatilidad y control del balón. Puede jugar en varias posiciones. Futuro estrella del fútbol mundial.', NULL, '2026-04-18 18:03:10', NULL, 0, 'Francia', '1.78 m', '68 kg', 'Derecha', 12, '90M€', 260, 25, 18, 1),
(19, 'Joselu', 34, 'Delantero', 'Real Madrid', 'uploads/joselu.jpg', 'Joselu es un delantero fuerte en el juego aéreo. Destaca por su capacidad de remate. Aporta experiencia y goles importantes. Jugador de rol ofensivo.', NULL, '2026-04-18 18:03:10', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(20, 'Brahim Díaz', 25, 'Delantero', 'Real Madrid', 'uploads/brahim.jpg', 'Brahim Díaz es un atacante técnico y creativo. Destaca por su regate y habilidad ofensiva. Puede jugar en varias posiciones de ataque. Jugador con mucho potencial.', NULL, '2026-04-18 18:03:10', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(21, 'Arda Güler', 19, 'Centrocampista', 'Real Madrid', 'uploads/guler.jpg', 'Arda Güler es una joven promesa turca con gran talento. Destaca por su visión de juego y técnica. Futuro estrella del Real Madrid. Gran potencial ofensivo.', NULL, '2026-04-18 18:03:10', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(22, 'Andriy Lunin', 25, 'Portero', 'Real Madrid', 'uploads/lunin.jpg', 'Andriy Lunin es un portero con buenos reflejos. Ha demostrado seguridad cuando ha sido titular. Forma parte del Real Madrid. Portero en crecimiento.', NULL, '2026-04-18 18:03:10', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1),
(23, 'Erling Haaland', 24, 'Delantero', 'Manchester City', 'uploads/haaland.jpg', 'Erling Haaland es un delantero letal con enorme potencia física. Destaca por su velocidad y definición. Rompe récords goleadores en Europa. Uno de los mejores delanteros del mundo.', NULL, '2026-05-14 23:16:02', NULL, 95, 'Noruega', '1.94 m', '88 kg', 'Izquierda', 9, '200M€', 320, 280, 45, 3),
(24, 'Kevin De Bruyne', 33, 'Centrocampista', 'Manchester City', 'uploads/debruyne.jpg', 'Kevin De Bruyne es un centrocampista ofensivo de élite. Destaca por su visión de juego y pases decisivos. Líder del Manchester City. Uno de los mejores mediocampistas del mundo.', NULL, '2026-05-14 23:16:02', NULL, 94, 'Bélgica', '1.81 m', '70 kg', 'Derecha', 17, '70M€', 700, 150, 260, 3),
(25, 'Phil Foden', 24, 'Extremo', 'Manchester City', 'uploads/foden.jpg', 'Phil Foden es una joven estrella inglesa muy técnica. Destaca por su creatividad y velocidad. Jugador clave del Manchester City. Gran futuro internacional.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 3),
(26, 'Rodri', 28, 'Centrocampista', 'Manchester City', 'uploads/rodri.jpg', 'Rodri es un mediocentro defensivo inteligente y sólido. Controla el ritmo del juego. Fundamental en el Manchester City. Uno de los mejores en su posición.', NULL, '2026-05-14 23:16:02', NULL, 93, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 3),
(27, 'Ederson', 31, 'Portero', 'Manchester City', 'uploads/ederson.jpg', 'Ederson es un portero moderno con gran juego de pies. Destaca por sus pases largos y reflejos. Clave en el sistema del Manchester City. Portero de élite.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 3),
(28, 'Kylian Mbappe', 26, 'Delantero', 'PSG', 'uploads/kylianmbappe.jpg', 'Kylian Mbappé es una superestrella mundial. Destaca por su velocidad y capacidad goleadora. Campeón del mundo con Francia. Uno de los mejores jugadores del planeta.', NULL, '2026-05-14 23:16:02', NULL, 97, 'Francia', '1.78 m', '73 kg', 'Derecha', 7, '180M€', 420, 310, 120, 5),
(29, 'Ousmane Dembele', 27, 'Extremo', 'PSG', 'uploads/dembele.jpg', 'Ousmane Dembélé es un extremo rápido y habilidoso. Destaca por su regate en ambas bandas. Jugador impredecible y peligroso. Gran talento ofensivo.', NULL, '2026-05-14 23:16:02', NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 5),
(30, 'Achraf Hakimi', 26, 'Defensa', 'PSG', 'uploads/hakimi.jpg', 'Achraf Hakimi es un lateral ofensivo muy rápido. Destaca por sus subidas constantes al ataque. Jugador clave en PSG y Marruecos. Uno de los mejores laterales del mundo.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 5),
(31, 'Marquinhos', 30, 'Defensa', 'PSG', 'uploads/marquinhos.jpg', 'Marquinhos es un defensa central líder y muy sólido. Capitán del PSG durante años. Destaca por su inteligencia táctica. Referente defensivo.', NULL, '2026-05-14 23:16:02', NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 5),
(32, 'Gianluigi Donnarumma', 26, 'Portero', 'PSG', 'uploads/donnarumma.jpg', 'Donnarumma es un portero italiano con gran envergadura. Destaca por sus reflejos y seguridad. Campeón de la Eurocopa. Uno de los mejores porteros jóvenes.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 5),
(33, 'Mohamed Salah', 32, 'Extremo', 'Liverpool', 'uploads/salah.jpg', 'Mohamed Salah es una estrella egipcia reconocida por su velocidad y capacidad goleadora. Fue fundamental en el regreso del Liverpool a la élite europea. Ha ganado Champions League y Premier League con actuaciones memorables.', NULL, '2026-05-14 23:16:02', NULL, 92, 'Egipto', '1.75 m', '71 kg', 'Izquierda', 11, '60M€', 720, 350, 160, 4),
(34, 'Virgil van Dijk', 33, 'Defensa', 'Liverpool', 'uploads/vandijk.jpg', 'Virgil van Dijk es considerado uno de los mejores defensas centrales del mundo. Su fortaleza física y liderazgo defensivo son impresionantes. Fue clave en la conquista de la Champions League del Liverpool.', NULL, '2026-05-14 23:16:02', NULL, 91, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 4),
(35, 'Alisson Becker', 32, 'Portero', 'Liverpool', 'uploads/alisson.jpg', 'Alisson Becker es un portero brasileño reconocido por sus reflejos y seguridad bajo presión. Ha sido decisivo en títulos importantes para Liverpool y Brasil. También destaca por su juego con los pies.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 4),
(36, 'Trent Alexander-Arnold', 26, 'Defensa', 'Liverpool', 'uploads/trent.jpg', 'Trent Alexander-Arnold es uno de los laterales más ofensivos del fútbol moderno. Sus asistencias y precisión en los centros son extraordinarias. Ha revolucionado la posición de lateral derecho en Liverpool.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 4),
(37, 'Darwin Nunez', 25, 'Delantero', 'Liverpool', 'uploads/darwin.jpg', 'Darwin Núñez es un delantero uruguayo con gran potencia física y velocidad. Destaca por sus movimientos ofensivos y capacidad de finalización. Continúa creciendo como referencia ofensiva en Liverpool.', NULL, '2026-05-14 23:16:02', NULL, 85, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 4),
(38, 'Bukayo Saka', 23, 'Extremo', 'Arsenal', 'uploads/saka.jpg', 'Bukayo Saka es una de las mayores promesas del fútbol inglés. Su velocidad, regate y capacidad ofensiva lo hacen determinante para Arsenal e Inglaterra. A pesar de su juventud ya es una estrella internacional.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 9),
(39, 'Martin Odegaard', 26, 'Centrocampista', 'Arsenal', 'uploads/odegaard.jpg', 'Martin Odegaard es un centrocampista creativo con una visión de juego excepcional. Capitán del Arsenal, lidera el ataque con inteligencia y técnica. Destaca por su capacidad para asistir y controlar el ritmo del partido.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 9),
(40, 'Declan Rice', 26, 'Centrocampista', 'Arsenal', 'uploads/rice.jpg', 'Declan Rice es uno de los mediocampistas defensivos más completos de Europa. Sobresale por su fuerza física y recuperación de balón. Es pieza fundamental tanto en Arsenal como en la selección inglesa.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 9),
(41, 'William Saliba', 23, 'Defensa', 'Arsenal', 'uploads/saliba.jpg', 'William Saliba es un defensa francés reconocido por su elegancia y seguridad defensiva. Tiene gran capacidad física y salida de balón. Está considerado uno de los mejores centrales jóvenes del mundo.', NULL, '2026-05-14 23:16:02', NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 9),
(42, 'Gabriel Jesus', 27, 'Delantero', 'Arsenal', 'uploads/jesus.jpg', 'Gabriel Jesus es un delantero brasileño conocido por su movilidad y presión ofensiva. Destaca por su trabajo en equipo y capacidad técnica. Ha ganado títulos importantes tanto en Inglaterra como con Brasil.', NULL, '2026-05-14 23:16:02', NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 9),
(43, 'Harry Kane', 31, 'Delantero', 'Bayern Munich', 'uploads/kane.jpg', 'Harry Kane es uno de los delanteros más completos del fútbol mundial. Destaca por su capacidad goleadora, visión de juego y liderazgo. Ha sido capitán de Inglaterra y estrella del Bayern Munich.', NULL, '2026-05-14 23:16:02', NULL, 93, 'Inglaterra', '1.88 m', '86 kg', 'Derecha', 9, '100M€', 600, 390, 95, 6),
(44, 'Jamal Musiala', 21, 'Centrocampista', 'Bayern Munich', 'uploads/musiala.jpg', 'Jamal Musiala es uno de los talentos jóvenes más prometedores del fútbol europeo. Su regate y creatividad ofensiva impresionan a nivel mundial. Es una pieza fundamental para Bayern Munich.', NULL, '2026-05-14 23:16:02', NULL, 91, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 6),
(45, 'Manuel Neuer', 38, 'Portero', 'Bayern Munich', 'uploads/neuer.jpg', 'Manuel Neuer revolucionó la posición de portero moderno con su estilo de juego adelantado. Destaca por sus reflejos y liderazgo. Es considerado uno de los mejores porteros de la historia.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 6),
(46, 'Joshua Kimmich', 29, 'Centrocampista', 'Bayern Munich', 'uploads/kimmich.jpg', 'Joshua Kimmich es un jugador alemán extremadamente versátil y táctico. Puede jugar como mediocampista o lateral derecho con gran nivel. Su liderazgo y calidad técnica son esenciales para Bayern Munich.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 6),
(47, 'Alphonso Davies', 24, 'Defensa', 'Bayern Munich', 'uploads/davies.jpg', 'Alphonso Davies es uno de los laterales más rápidos del fútbol mundial. Su explosividad y capacidad ofensiva destacan en cada partido. Es una de las figuras principales del Bayern Munich.', NULL, '2026-05-14 23:16:02', NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 6),
(48, 'Antoine Griezmann', 33, 'Delantero', 'Atletico Madrid', 'uploads/griezmann.jpg', 'Antoine Griezmann es un delantero francés con gran inteligencia táctica y calidad ofensiva. Fue campeón del mundo con Francia y leyenda del Atlético de Madrid. Destaca por su movilidad y visión de juego.', NULL, '2026-05-14 23:16:02', NULL, 91, 'Francia', '1.76 m', '73 kg', 'Izquierda', 7, '25M€', 780, 290, 140, 12),
(49, 'Julian Alvarez', 25, 'Delantero', 'Atletico Madrid', 'uploads/alvarez.jpg', 'Julián Álvarez es un delantero argentino campeón del mundo con gran capacidad goleadora. Destaca por su movilidad, presión y definición. Es una de las jóvenes estrellas del Atlético de Madrid.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 12),
(50, 'Jan Oblak', 32, 'Portero', 'Atletico Madrid', 'uploads/oblak.jpg', 'Jan Oblak es uno de los porteros más seguros y consistentes del fútbol europeo. Sus reflejos y posicionamiento son extraordinarios. Ha sido clave en el éxito defensivo del Atlético de Madrid.', NULL, '2026-05-14 23:16:02', NULL, 90, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 12),
(51, 'Koke', 33, 'Centrocampista', 'Atletico Madrid', 'uploads/koke.jpg', 'Koke es un centrocampista histórico del Atlético de Madrid. Destaca por su liderazgo, entrega y visión de juego. Ha sido capitán y símbolo del club durante muchos años.', NULL, '2026-05-14 23:16:02', NULL, 85, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 12),
(52, 'Jose Gimenez', 30, 'Defensa', 'Atletico Madrid', 'uploads/gimenez.jpg', 'José Giménez es un defensa uruguayo reconocido por su agresividad y fortaleza física. Ha formado parte de una de las defensas más sólidas de Europa. Su juego aéreo es uno de sus puntos fuertes.', NULL, '2026-05-14 23:16:02', NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 12),
(53, 'Cole Palmer', 22, 'Centrocampista', 'Chelsea', 'uploads/palmer.jpg', 'Cole Palmer es una joven estrella inglesa con gran talento ofensivo. Destaca por su creatividad, regate y definición. Está creciendo rápidamente como figura importante del Chelsea.', NULL, '2026-05-14 23:16:02', NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 10),
(54, 'Enzo Fernandez', 24, 'Centrocampista', 'Chelsea', 'uploads/enzo.jpg', 'Enzo Fernández es un centrocampista argentino campeón del mundo. Sobresale por su visión de juego y precisión en los pases. Es uno de los líderes del nuevo proyecto del Chelsea.', NULL, '2026-05-14 23:16:02', NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 10),
(55, 'Reece James', 25, 'Defensa', 'Chelsea', 'uploads/reece.jpg', 'Reece James es un lateral inglés fuerte físicamente y muy ofensivo. Destaca por sus centros y capacidad defensiva. Cuando está sano es uno de los mejores laterales de la Premier League.', NULL, '2026-05-14 23:16:02', NULL, 86, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 10),
(56, 'Christopher Nkunku', 27, 'Delantero', 'Chelsea', 'uploads/nkunku.jpg', 'Christopher Nkunku es un delantero francés con gran movilidad y técnica. Puede jugar en varias posiciones ofensivas y generar peligro constantemente. Es una de las principales armas ofensivas del Chelsea.', NULL, '2026-05-14 23:16:02', NULL, 86, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 10),
(57, 'Robert Sanchez', 27, 'Portero', 'Chelsea', 'uploads/sanchez.jpg', 'Robert Sánchez es un portero español con gran capacidad física y buenos reflejos. Destaca por su seguridad en el juego aéreo. Forma parte del proyecto competitivo del Chelsea.', NULL, '2026-05-14 23:16:02', NULL, 82, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 10),
(58, 'Bruno Fernandes', 30, 'Centrocampista', 'Manchester United', 'uploads/bruno.jpg', 'Bruno Fernandes es el líder creativo del Manchester United. Destaca por sus asistencias, disparos lejanos y visión ofensiva. Es uno de los centrocampistas más productivos de la Premier League.', NULL, '2026-05-14 23:16:02', NULL, 90, 'Portugal', '1.79 m', '69 kg', 'Derecha', 8, '55M€', 520, 130, 160, 11),
(59, 'Marcus Rashford', 27, 'Delantero', 'Manchester United', 'uploads/rashford.jpg', 'Marcus Rashford es un delantero inglés conocido por su velocidad y capacidad de definición. Ha sido una figura clave del Manchester United durante varias temporadas. También destaca fuera del campo por su labor social.', NULL, '2026-05-14 23:16:02', NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 11),
(60, 'Alejandro Garnacho', 20, 'Extremo', 'Manchester United', 'uploads/garnacho.jpg', 'Alejandro Garnacho es una de las jóvenes promesas más emocionantes del Manchester United. Destaca por su regate, velocidad y valentía ofensiva. Tiene un enorme potencial para el futuro.', NULL, '2026-05-14 23:16:02', NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 11),
(61, 'Lisandro Martinez', 27, 'Defensa', 'Manchester United', 'uploads/lisandro.jpg', 'Lisandro Martínez es un defensa argentino campeón del mundo. Sobresale por su intensidad, liderazgo y salida de balón. Es uno de los pilares defensivos del Manchester United.', NULL, '2026-05-14 23:16:02', NULL, 86, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 11),
(62, 'Andre Onana', 29, 'Portero', 'Manchester United', 'uploads/onana.jpg', 'Andre Onana es un portero camerunés reconocido por su gran juego con los pies. Participa activamente en la construcción ofensiva del equipo. Tiene experiencia en competiciones europeas de máximo nivel.', NULL, '2026-05-14 23:16:02', NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 11),
(63, 'Dusan Vlahovic', 25, 'Delantero', 'Juventus', 'uploads/vlahovic.jpg', 'Dusan Vlahovic es un delantero serbio con gran capacidad goleadora y potencia física. Destaca por sus disparos y presencia dentro del área. Es una de las referencias ofensivas de Juventus.', NULL, '2026-05-14 23:16:02', NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 7),
(64, 'Federico Chiesa', 27, 'Extremo', 'Juventus', 'uploads/chiesa.jpg', 'Federico Chiesa es un extremo italiano muy explosivo y habilidoso. Su velocidad y capacidad de desequilibrio lo convierten en un jugador diferencial. Fue importante en la Eurocopa ganada por Italia.', NULL, '2026-05-14 23:16:02', NULL, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 7),
(65, 'Paul Pogba', 31, 'Centrocampista', 'Juventus', 'uploads/pogba.jpg', 'Paul Pogba es un centrocampista francés reconocido por su físico y calidad técnica. Fue campeón del mundo con Francia y protagonista en grandes clubes europeos. Destaca por sus pases y disparos de larga distancia.', NULL, '2026-05-14 23:16:02', NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 7),
(66, 'Bremer', 28, 'Defensa', 'Juventus', 'uploads/bremer.jpg', 'Bremer es un defensa brasileño fuerte físicamente y muy sólido en defensa. Destaca en el juego aéreo y los duelos individuales. Es una pieza clave de la Juventus.', NULL, '2026-05-14 23:16:02', NULL, 86, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 7),
(67, 'Wojciech Szczesny', 35, 'Portero', 'Juventus', 'uploads/szczesny.jpg', 'Wojciech Szczesny es un portero polaco con gran experiencia internacional. Sus reflejos y liderazgo lo convierten en una garantía bajo palos. Ha defendido la portería de Juventus durante varias temporadas.', NULL, '2026-05-14 23:16:02', NULL, 85, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 7),
(68, 'Rafael Leao', 25, 'Extremo', 'AC Milan', 'uploads/leao.jpg', 'Rafael Leao es uno de los extremos más peligrosos de Europa. Destaca por su velocidad, potencia y habilidad en el uno contra uno. Ha sido clave en el éxito reciente del AC Milan.', NULL, '2026-05-14 23:16:02', NULL, 90, 'Portugal', '1.88 m', '81 kg', 'Derecha', 10, '90M€', 350, 95, 70, 8),
(69, 'Theo Hernandez', 27, 'Defensa', 'AC Milan', 'uploads/theo.jpg', 'Theo Hernández es un lateral francés muy ofensivo y rápido. Sus incorporaciones al ataque generan constantemente peligro. Es uno de los mejores laterales izquierdos del mundo.', NULL, '2026-05-14 23:16:02', NULL, 88, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 8),
(70, 'Mike Maignan', 29, 'Portero', 'AC Milan', 'uploads/maignan.jpg', 'Mike Maignan es un portero francés reconocido por sus reflejos y liderazgo. Ha sido fundamental en los éxitos defensivos del AC Milan. Está considerado entre los mejores porteros europeos.', NULL, '2026-05-14 23:16:02', NULL, 89, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 8),
(71, 'Christian Pulisic', 26, 'Extremo', 'AC Milan', 'uploads/pulisic.jpg', 'Christian Pulisic es un extremo estadounidense con gran velocidad y técnica. Puede jugar por ambas bandas y generar peligro constante. Es una de las figuras ofensivas del AC Milan.', NULL, '2026-05-14 23:16:02', NULL, 85, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 8),
(72, 'Tijjani Reijnders', 26, 'Centrocampista', 'AC Milan', 'uploads/reijnders.jpg', 'Tijjani Reijnders es un centrocampista neerlandés elegante y técnico. Destaca por su visión de juego y capacidad para controlar el ritmo del partido. Se ha convertido en una pieza importante del AC Milan.', NULL, '2026-05-14 23:16:02', NULL, 84, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mensajes`
--

INSERT INTO `mensajes` (`id`, `usuario_id`, `mensaje`, `fecha`) VALUES
(1, 1, 'Partido importante - Juan', '2026-04-15 05:56:02'),
(4, 2, 'Segundo entrenamiento ', '2026-04-15 05:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `partidos`
--

CREATE TABLE `partidos` (
  `id` int(11) NOT NULL,
  `equipo_local` varchar(100) DEFAULT NULL,
  `equipo_visitante` varchar(100) DEFAULT NULL,
  `goles_local` int(11) DEFAULT 0,
  `goles_visitante` int(11) DEFAULT 0,
  `fecha` datetime DEFAULT NULL,
  `estadio` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente',
  `logo_local` varchar(255) DEFAULT NULL,
  `logo_visitante` varchar(255) DEFAULT NULL,
  `resultado` varchar(50) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partidos`
--

INSERT INTO `partidos` (`id`, `equipo_local`, `equipo_visitante`, `goles_local`, `goles_visitante`, `fecha`, `estadio`, `estado`, `logo_local`, `logo_visitante`, `resultado`) VALUES
(1, 'Real Madrid', 'Barcelona', 9, 0, '2026-05-20 21:00:00', 'Santiago Bernabéu', 'Finalizado', 'realmadrid.jpg', 'barcelona.png', 'Pendiente'),
(2, 'Manchester City', 'Liverpool', 0, 0, '2026-05-22 18:00:00', 'Etihad Stadium', 'Pendiente', 'ManchasterCity.png', 'liverpool.png', 'Pendiente'),
(3, 'PSG', 'Bayern Munich', 3, 2, '2026-05-25 20:00:00', 'Parc des Princes', 'Finalizado', 'psg.png', 'bayermunich.jpg', 'Pendiente'),
(6, 'PSG', 'Bayern Munich', 2, 0, '2026-05-22 21:00:00', 'Parc des Princes', 'Finalizado', 'psg.png', 'bayermunich.jpg', 'Pendiente'),
(7, 'Juventus', 'AC Milan', 0, 0, '2026-05-23 18:00:00', 'Allianz Stadium', 'Pendiente', 'juventus.png', 'acmilan.png', 'Pendiente'),
(8, 'Arsenal', 'Chelsea', 4, 1, '2026-05-24 17:30:00', 'Emirates Stadium', 'Finalizado', 'arsenal.png', 'chelsea.png', 'Pendiente'),
(9, 'Inter Miami', 'LA Galaxy', 2, 3, '2026-05-24 03:00:00', 'DRV PNK Stadium', 'Finalizado', 'intermiami.png', 'lagalexy.png', 'Pendiente'),
(10, 'Borussia Dortmund', 'RB Leipzig', 1, 2, '2026-05-25 19:00:00', 'Signal Iduna Park', 'Finalizado', 'dormund.png', 'leipzig.png', 'Pendiente'),
(11, 'Atlético Madrid', 'Sevilla', 2, 2, '2026-05-25 22:00:00', 'Metropolitano', 'Finalizado', NULL, NULL, 'Pendiente'),
(12, 'Ajax', 'PSV Eindhoven', 3, 1, '2026-05-26 18:00:00', 'Johan Cruyff Arena', 'Finalizado', NULL, NULL, 'Pendiente'),
(13, 'Benfica', 'Porto', 1, 0, '2026-05-26 21:00:00', 'Estádio da Luz', 'Finalizado', NULL, NULL, 'Pendiente'),
(14, 'Napoli', 'Roma', 2, 1, '2026-05-27 20:45:00', 'Diego Armando Maradona', 'Finalizado', NULL, NULL, 'Pendiente'),
(15, 'Tottenham', 'Manchester United', 1, 3, '2026-05-28 21:00:00', 'Tottenham Stadium', 'Finalizado', NULL, NULL, 'Pendiente'),
(16, 'Al Nassr', 'Al Hilal', 2, 2, '2026-05-29 19:00:00', 'King Saud University Stadium', 'En vivo', NULL, NULL, 'Pendiente'),
(17, 'Galatasaray', 'Fenerbahçe', 1, 0, '2026-05-30 20:00:00', 'RAMS Park', 'Pendiente', NULL, NULL, 'Pendiente'),
(18, 'River Plate', 'Boca Juniors', 2, 2, '2026-05-31 22:00:00', 'Monumental', 'Finalizado', NULL, NULL, 'Pendiente');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transferencias`
--

CREATE TABLE `transferencias` (
  `id` int(11) NOT NULL,
  `jugador` varchar(100) DEFAULT NULL,
  `posicion` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `foto_jugador` varchar(255) DEFAULT NULL,
  `club_origen` varchar(100) DEFAULT NULL,
  `club_destino` varchar(100) DEFAULT NULL,
  `logo_origen` varchar(255) DEFAULT NULL,
  `logo_destino` varchar(255) DEFAULT NULL,
  `precio` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transferencias`
--

INSERT INTO `transferencias` (`id`, `jugador`, `posicion`, `rating`, `foto_jugador`, `club_origen`, `club_destino`, `logo_origen`, `logo_destino`, `precio`, `fecha`, `estado`) VALUES
(1, 'Kylian Mbappé', 'Delantero', 95, 'uploads/kylianmbappe.jpg', 'PSG', 'Real Madrid', 'uploads/psg.png', 'uploads/realmadrid.jpg', '180M€', '2026-06-01', 'Confirmado'),
(5, 'Sergio Ramos', 'Defensa', 70, 'uploads/Sergio Ramos.jpg', 'Real Madrid', 'Libre', 'uploads/Sergio Ramos.jpg', 'uploads/default.png', '3M€', '2026-05-17', 'En venta');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `user` varchar(50) DEFAULT NULL,
  `pass` varchar(50) DEFAULT NULL,
  `rol` int(11) NOT NULL DEFAULT 3 COMMENT '1:Admin, 2:Adminis, 3:Consulta',
  `ultima_conexion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `pass`, `rol`, `ultima_conexion`, `estado`, `fecha_registro`) VALUES
(1, 'admin', '1234', 1, '2026-04-15 08:32:55', 1, '2026-04-18 13:35:27'),
(2, 'madrid', '1234', 2, '2026-04-15 09:06:39', 1, '2026-04-18 13:35:27'),
(3, 'barcelona', '1234', 2, '2026-04-15 05:48:01', 1, '2026-04-18 13:35:27'),
(4, 'valencia', '1234', 2, '2026-04-15 05:48:01', 1, '2026-04-18 13:35:27'),
(5, 'sevilla', '1234', 2, '2026-04-15 05:48:01', 1, '2026-04-18 13:35:27'),
(6, 'zaragoza', '1234', 2, '2026-04-15 05:48:01', 1, '2026-04-18 13:35:27'),
(7, 'visitante', '1234', 3, '2026-04-15 06:22:49', 1, '2026-04-18 13:35:27'),
(8, 'Juanito', '1234', 3, '2026-04-15 06:37:38', 1, '2026-04-18 13:35:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clubes`
--
ALTER TABLE `clubes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `jugador_id` (`jugador_id`);

--
-- Indexes for table `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partidos`
--
ALTER TABLE `partidos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clubes`
--
ALTER TABLE `clubes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `partidos`
--
ALTER TABLE `partidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `fk_fav_jugador` FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jugadores`
--
ALTER TABLE `jugadores`
  ADD CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
