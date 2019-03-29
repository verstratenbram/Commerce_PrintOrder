<?php
namespace RogueClarity\PrintOrder\Modules;
use modmore\Commerce\Modules\BaseModule;
use modmore\Commerce\Admin\Util\Action;
use modmore\Commerce\Events\Admin\OrderActions;
use modmore\Commerce\Events\Admin\GeneratorEvent;
use RogueClarity\PrintOrder\Admin\PrintOrderPage;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use modmore\Commerce\Admin\Widgets\Form\TextField;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class PrintOrder extends BaseModule {

    public function getName()
    {
        $this->adapter->loadLexicon('commerce_printorder:default');
        return $this->adapter->lexicon('commerce_printorder');
    }

    public function getAuthor()
    {
        return 'Tony Klapatch - Rogue Clarity Studios';
    }

    public function getDescription()
    {
        return $this->adapter->lexicon('commerce_printorder.description');
    }

    public function initialize(EventDispatcher $dispatcher)
    {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_printorder:default');

        // Add template path to twig
        /** @var ChainLoader $loader */
        $root = dirname(dirname(__DIR__));
        $loader = $this->commerce->twig->getLoader();
        $loader->addLoader(new FilesystemLoader($root . '/templates/'));

        $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_ORDER_ACTIONS, [$this, 'addPrintButton']);
        $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_INIT_GENERATOR, [$this, 'initGenerator']);
    }

    public function addPrintButton(OrderActions $event)
    {
        $order = $event->getOrder();

        $event->addAction((new Action)
            ->setTitle($this->adapter->lexicon('commerce_printorder'))
            ->setUrl($this->adapter->makeAdminUrl('printorder/print', ['order' => $order->get('id')]))
            ->setNewWindow(true)
            ->setIcon('icon print')
            ->setModal(false)
        );
    }

    public function initGenerator(GeneratorEvent $event)
    {
        $generator = $event->getGenerator();
        $generator->addPage('printorder/print', PrintOrderPage::class);
    }

    public function getModuleConfiguration(\comModule $module)
    {
        $fields[] = new TextField($this->commerce, [
            'name'        => 'properties[system_settings]',
            'label'       => $this->adapter->lexicon('commerce.printorder.property.system_settings'),
            'description' => $this->adapter->lexicon('commerce.printorder.property.system_settings_desc'),
            'value'       => $module->getProperty('system_settings')
        ]);

        return $fields;
    }
}
