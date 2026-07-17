<?php

/*
 * Point d'entrée serverless pour Vercel (runtime vercel-php).
 * Toutes les requêtes sont réécrites ici par vercel.json (équivalent du .htaccess Apache).
 * Les require_once() sont relatifs à la racine du repo, d'où le chdir.
 */

chdir(dirname(__DIR__));

/* Fallback si le paramètre url de la route vercel.json est inexistant */
if (!isset($_GET['url'])) {
	$path = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH);
	$_GET['url'] = ltrim((string) $path, '/');
}

require dirname(__DIR__) . '/index.php';
