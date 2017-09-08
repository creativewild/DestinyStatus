<?php

namespace Destiny;

use App\Account;

/**
 * Class DestinyPlatform.
 */
class DestinyPlatform
{
    /**
     * @param $uri
     * @param array $params
     * @param null  $cacheMinutes
     * @param bool  $salvageable
     *
     * @return DestinyRequest
     */
    protected function destinyRequest($uri, $params = [], $cacheMinutes = null, $salvageable = true) : DestinyRequest
    {
        return new DestinyRequest($uri, $params, $cacheMinutes, $salvageable);
    }

    /**
     * @return DestinyRequest
     */
    public function manifest() : DestinyRequest
    {
        return $this->destinyRequest('destiny2/manifest/', false);
    }

    /**
     * @param $gamertag
     *
     * @return DestinyRequest
     */
    public function searchDestinyPlayer(string $gamertag) : DestinyRequest
    {
        $gamertag = rawurlencode(trim($gamertag));

        return $this->destinyRequest("Destiny2/SearchDestinyPlayer/all/$gamertag/", CACHE_PLAYER, false);
    }

    /**
     * @param Account $account
     *
     * @return DestinyRequest
     */
    public function getDestinyProfile(Account $account) : DestinyRequest
    {
        $profileBuilder = (new DestinyComponentUrlBuilder("Destiny2/$account->membership_type/Profile/$account->membership_id/"))
            ->addProfiles()
            ->addProfileCurrencies()
            ->addCharacters()
            ->addCharacterProgressions()
            ->addCharacterEquipment()
            ->addItemInstances();

        return $this->destinyRequest($profileBuilder->buildUrl(), $profileBuilder->getComponentArray(), CACHE_DEFAULT, false);
    }

    /**
     * @return DestinyRequest
     */
    public function getMilestones() : DestinyRequest
    {
        return $this->destinyRequest("Destiny2/Milestones/", null,CACHE_INDEX, false);
    }
}
