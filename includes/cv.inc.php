<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Common stuff
 *
 * PHP version 5
 *
 * Copyright © 2007-2014 Johan Cwiklinski
 *
 * This file is part of my curriculum vitae (http://cv.ulysses.fr).
 *
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Main
 * @package   CV
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2007-2014 Johan Cwiklinski
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://cv.ulysses.fr
 */
ini_set("session.use_trans_sid", "0");
session_start();

$session = &$_SESSION['cv_johan'];
if ( isset($_GET['lang'])
    && ($_GET['lang'] === 'fr'
    || $_GET['lang'] === 'en')
) {
    $session['lang'] = $_GET['lang'];
} else {
    if ( !isset($session['lang']) ) {
        $session['lang'] = 'fr';
    }
}

require_once 'lang/' . $session['lang'] . '.php';

