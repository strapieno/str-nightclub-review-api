<?php
namespace Strapieno\NightClubReview\Api\V1\Listener;

use Matryoshka\Model\Object\ActiveRecord\ActiveRecordInterface;
use Matryoshka\Model\Wrapper\Mongo\Criteria\ActiveRecord\ActiveRecordCriteria;
use Strapieno\NightClub\Model\NightClubModelAwareInterface;
use Strapieno\NightClub\Model\NightClubModelAwareTrait;
use Strapieno\NightClubReview\Model\Entity\ReviewEntity;
use Strapieno\Utils\Model\Object\AggregateRating\AggregateRatingAwareInterface;
use Strapieno\Utils\Model\Object\Place\PlaceInterface;
use Zend\EventManager\EventInterface;
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
        $this->listeners[] = $events->attach('create.post', [$this, 'onPostSave']);
    }

    /**
     * @param EventInterface $event
     */
    public function onPostSave(EventInterface $event)
    {
        // TODO add interface
        /** @var $review ReviewEntity */
        $review = $event->getParam('entity')->entity;

        $nightClub = $this->getNightClubFromId($review->getNightClubReference()->getId());

        if ($nightClub instanceof AggregateRatingAwareInterface && $nightClub instanceof ActiveRecordInterface) {

            $ratingCount = $nightClub->getAggregateRating()->getRatingCount() + $review->getRating()->getRatingValue();
            $nightClub->getAggregateRating()->setRatingCount($ratingCount);

            $reviewCount =  $nightClub->getAggregateRating()->getReviewCount() + 1;
            $nightClub->getAggregateRating()->setReviewCount($reviewCount);

            $partial = $nightClub->getAggregateRating()->getPartial();
            if (isset($partial[$review->getRating()->getRatingValue()])) {
                $partial[$review->getRating()->getRatingValue()] = $partial[$review->getRating()->getRatingValue()] + 1;
            } else {
                $partial[$review->getRating()->getRatingValue()] =   1;
            }
            $nightClub->getAggregateRating()->setPartial($partial);

            $nightClub->save();

        }
    }

    /**
     * @param $id
     * @return PlaceInterface|null
     */
    protected function getNightClubFromId($id)
    {
        return $this->getNightClubModelService()->find((new ActiveRecordCriteria())->setId($id))->current();

    }
}