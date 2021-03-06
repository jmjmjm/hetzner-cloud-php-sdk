<?php
/**
 * Created by PhpStorm.
 * User: lukaskammerling
 * Date: 28.01.18
 * Time: 21:02
 */

namespace LKDev\HetznerCloud\Models\ISOs;

use LKDev\HetznerCloud\HetznerAPIClient;
use LKDev\HetznerCloud\Models\Model;

class ISOs extends Model
{
    /**
     * @var array
     */
    public $isos;

    /**
     * Returns all iso objects.
     *
     * @see https://docs.hetzner.cloud/#resources-isos-get
     * @return array
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function all(): array
    {
        $response = $this->httpClient->get('isos');
        if (! HetznerAPIClient::hasError($response)) {
            return self::parse(json_decode((string) $response->getBody()))->isos;
        }
    }

    /**
     * Returns a specific iso object.
     *
     * @see https://docs.hetzner.cloud/#resources-iso-get-1
     * @param int $isoId
     * @return \LKDev\HetznerCloud\Models\ISOs\ISO
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function get(int $isoId): ISO
    {
        $response = $this->httpClient->get('isos/'.$isoId);
        if (! HetznerAPIClient::hasError($response)) {
            return ISO::parse(json_decode((string) $response->getBody())->iso);
        }
    }

    /**
     * @param  $input
     * @return $this
     */
    public function setAdditionalData( $input)
    {
        $this->locations = collect($input->isos)->map(function ($iso, $key) {
            return ISO::parse($iso);
        })->toArray();

        return $this;
    }

    /**
     * @param $input
     * @return $this|static
     */
    public static function parse($input)
    {
        return (new self())->setAdditionalData($input);
    }
}