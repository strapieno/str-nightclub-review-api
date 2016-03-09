<?php
namespace Strapieno\NightClubReview\Api\V1\Hydrator;

use Matryoshka\Model\Hydrator\Strategy\HasOneStrategy;
use Strapieno\NightClubReview\Model\Entity\Object\RatingObject;
use Strapieno\Utils\Hydrator\DateHystoryHydrator;


/**
 * Class ReviewHydrator
 */
class ReviewHydrator extends DateHystoryHydrator
{
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);

        $this->addStrategy(
            'rating',
            new HasOneStrategy(new RatingObject(), false)
        );

    }
}