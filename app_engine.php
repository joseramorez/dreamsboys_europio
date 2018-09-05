<?php
/**
* EuropioEngine
*
* Motor principal de la aplicación.
* Importa todos los archivos necesarios, del núcleo de la aplicación e
* inicializa el controlador del motor MVC
*
* This file is part of EuropioEngine.
* EuropioEngine is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* EuropioEngine is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    EuropioEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
* @version    3.2.5
*/

require_once 'settings.php';

import('core.helpers.patterns');
import('core.helpers.http');
import('core.helpers.template');
import('core.helpers.files');

import('core.orm_engine.mysqlilayer');

import('core.orm_engine.objects.standardobject');
import('core.orm_engine.objects.serializedobject');
import('core.orm_engine.objects.collectorobject');
import('core.orm_engine.objects.multiplierobject');
import('core.orm_engine.objects.logicalconnector');
import('core.orm_engine.objects.composerobject');

import('core.sessions.handler');

import('core.mvc_engine.controller');
import('core.mvc_engine.front_controller');

#importacion de funciones internas
import('appmodules.FuncionHelper');

# Importación de aplicaciones habilitadas
foreach($enabled_apps as $plugin) import("common.plugins.$plugin.__init__");

# Archivos del usuario son cargados desde user_imports.php
# Si este archivo no existe en la raíz de la app, debe ser creado
// import('user_imports');

# Arrancar el motor de la aplicación
FrontController::start();
?>
