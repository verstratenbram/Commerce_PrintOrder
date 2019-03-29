<?php
/* @var modX $modx */

if ($object->xpdo) {
	$modx =& $object->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:

            $modx->log(modX::LOG_LEVEL_INFO, 'Loading/updating available modules...');

            $modx->log(modX::LOG_LEVEL_INFO, 'Checking for old namespace...');
            $oldModule = $modx->getObject('comModule', ['class_name' => 'RogueClarity\PrintOrder\Modules\PrintOrder']);

        	// Modify old module information to avoid errors
            if ($oldModule) {
                $oldModule->set('class_name', 'PoconoSewVac\PrintOrder\Modules\PrintOrder');
                $oldModule->set('author', 'Tony Klapatch');
                $oldModule->save();
                $modx->log(modX::LOG_LEVEL_INFO, 'Old namespace updated');
            } else {
                $modx->log(modX::LOG_LEVEL_INFO, 'Namespace is up to date!');
            }

            $corePath = $modx->getOption('commerce.core_path', null, $modx->getOption('core_path') . 'components/commerce/');
            $commerce = $modx->getService('commerce', 'Commerce', $corePath . 'model/commerce/' , ['isSetup' => true]);
            if ($commerce instanceof Commerce) {
                // Grab the path to our namespaced files
                $basePath = $modx->getOption('core_path') . 'components/commerce_printorder/';
                include $basePath . 'vendor/autoload.php';
                $modulePath = $basePath . 'src/Modules/';
                // Instruct Commerce to load modules from our directory, providing the base namespace and module path twice
                $commerce->loadModulesFromDirectory($modulePath, 'PoconoSewVac\\PrintOrder\\Modules\\', $modulePath);
                $modx->log(modX::LOG_LEVEL_INFO, 'Synchronised modules.');
            }
            else {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load Commerce service to load module');
            }

        break;
    }

}
return true;

