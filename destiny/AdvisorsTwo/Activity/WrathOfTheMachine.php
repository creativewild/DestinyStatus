<?php

namespace Destiny\AdvisorsTwo\Activity;

use Destiny\AdvisorsTwo\Activity;
use Destiny\AdvisorsTwo\Collections\ActivityTierCollection;
use Destiny\Definitions\SkullModifier;

/**
 * @property string $progressionHash
 * @property ActivityTierCollection $activityTiers
 * @property SkullModifier[] $skulls
 * @property \Destiny\Definitions\Activity $definition
 */
class WrathOfTheMachine extends Activity implements ActivityInterface
{
    const FeaturedWoTM = 3356249023;
    const NonFeaturedWoTM = 430160982;

    public function __construct(array $items, array $properties)
    {
        // HOTFIX - Add in T3 (390LL)
        if (count($properties['activityTiers']) === 2) {

            // WoTM is the weekly raid. Lets add the skulls
            if ($items['weeklyfeaturedraid']['activityTiers'][0]['activityHash'] === self::FeaturedWoTM) {
                $skullCategory = $items['weeklyfeaturedraid']['activityTiers'][0]['skullCategories'];
                if (isset($items['weeklyfeaturedraid']['activityTiers'][0]['completion'])) {
                    $completion = $items['weeklyfeaturedraid']['activityTiers'][0]['completion'];
                } else {
                    $completion = [
                        'complete' => false,
                        'success'  => false,
                    ];
                }
                $identifier = self::FeaturedWoTM;
            } else {
                $skullCategory = [
                    0 => [
                        'title'  => 'Modifiers',
                        'skulls' => [
                            0 => [
                                'displayName' => 'Heroic',
                                'description' => 'Enemies appear in greater numbers and are more aggressive.',
                                'icon'        => asset('/img/heroic.png'),
                            ],
                        ],
                    ],
                ];
                $completion = [
                    'complete' => false,
                    'success'  => false,
                ];
                $identifier = self::NonFeaturedWoTM;
            }
            $properties['activityTiers'][] = [
                'activityHash'    => $identifier,
                'hidden'          => true,
                'tierDisplayName' => 'Hard',
                'completion'      => $completion,
                'steps'           => [
                    ['complete' => false],
                    ['complete' => false],
                    ['complete' => false],
                    ['complete' => false],
                    ['complete' => false],
                ],
                'skullCategories' => $skullCategory,
                'rewards'         => [],
                'activityData'    => [
                    'activityHash'     => 430160982,
                    'isNew'            => false,
                    'canLead'          => true,
                    'canJoin'          => true,
                    'isCompleted'      => true,
                    'isVisible'        => true,
                    'displayLevel'     => 42,
                    'recommendedLight' => 390,
                    'difficultyTier'   => 2,
                ],
            ];
        }

        $properties['activityTiers'] = (new ActivityTierCollection($this, $properties['activityTiers']));
        $skullsCategories = $properties['activityTiers']->first()['skullCategories'];
        $properties['definition'] = $properties['activityTiers']->first()['definition'];

        if (is_array($skullsCategories)) {
            $skulls = [];
            foreach ($skullsCategories as $skullCategory) {
                foreach ($skullCategory['skulls'] as $skull) {
                    $skull = new SkullModifier($skull);
                    $skull->isModifier = $skullCategory['title'] === 'Modifiers';
                    $skulls[] = $skull;
                }
            }
            $properties['skulls'] = $skulls;
        }

        parent::__construct($properties);
    }

    /**
     * @return string
     */
    public static function getIdentifier()
    {
        return 'wrathofthemachine';
    }
}