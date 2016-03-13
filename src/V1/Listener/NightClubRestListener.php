<?php
namespace Strapieno\NightClubReview\Api\V1\Listener;

use Strapieno\NightClub\Model\NightClubModelAwareInterface;
use Strapieno\NightClub\Model\NightClubModelAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Class NightClubRestListener
 */
class NightClubRestListener implements ListenerAggregateInterface, NightClubModelAwareInterface
{
    use ListenerAggregateTrait;
    use NightClubModelAwareTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('create.pre', [$this, 'onPostSave']);
    }

    public function onPostSave()
    {
        var_dump(get_class($this->getNightClubModelService()));
        die();
    }
}