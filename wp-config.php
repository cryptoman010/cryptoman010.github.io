<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'b44535_db');

/** Имя пользователя MySQL */
define('DB_USER', 'b44535_lev');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'X9!zk<jt*RCA@!#B');

/** Имя сервера MySQL */
define('DB_HOST', 'b44535.hostde15.fornex.org');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'COiT0V)UQaV?j~QbG>,IRc!HQ]YXmsUo#0%W0)RZodgA^@vJPHABNXFg*F^1S_]j');
define('SECURE_AUTH_KEY',  'XW[l7@VBZ_C~%cG8YlI(;[_m8P Dg>N6m~m*vG)x[*<6b=bsZ1b:-J94Z/!yJAiY');
define('LOGGED_IN_KEY',    'UCA-(6B//m-;<Z$0C]vdgeG yU*KNPT]pt2X0b7[gY^Jxk!P(4xkOkY[ =%DUl_J');
define('NONCE_KEY',        'ydNYWW.Ys`$DGf9G)$kj$H[Udh[~X!(B}7Xenl5eW(%XC#/DDk}o~-7~A8r*wCQ!');
define('AUTH_SALT',        'E9$~O9Vsxm9?l4]MG0n:>ONmur3G%/URO0Y!>t 8p]6efsTj{^:4_$VwO.V7vbJB');
define('SECURE_AUTH_SALT', '3/|hEB tL^[V6&pz/@#3Y#VL6}Vf4/1K0:OnyXq1D(zey&iU2MH7k7R?W,rZx=9i');
define('LOGGED_IN_SALT',   'XC;8MHwKL_U_|wM%OR>,826jfI $}^f~vcg40;Top}aZ,58JTEuTxkfx;mQhJyV(');
define('NONCE_SALT',       ')+{<@LLnW0z.JHHCLOj~?]zWqbh?dEu$FODE8 3qA9 5@I?n46s8nNVAGTgT7Fkb');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
